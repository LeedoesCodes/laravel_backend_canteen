<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'cashier', 'items.menuItem']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'             => 'required|exists:users,id',
            'items'               => 'required|array|min:1',
            'items.*.menu_item_id'=> 'required|exists:menu_items,id',
            'items.*.quantity'    => 'required|integer|min:1|max:999',
            'payment_method'      => 'required|in:cash,card,online',
            'notes'               => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(
                Order::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $totalAmount = 0;
            $orderItems  = [];

            foreach ($validated['items'] as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);

                if ($menuItem->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$menuItem->name}. Available: {$menuItem->stock_quantity}");
                }

                $subtotal     = $menuItem->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $menuItem->price,
                    'subtotal'     => $subtotal,
                ];
            }

            // Use the request user_id (the customer being served), not the cashier's auth ID
            $order = Order::create([
                'order_number'   => $orderNumber,
                'user_id'        => (int) $validated['user_id'],
                'cashier_id'     => auth()->id(),
                'total_amount'   => $totalAmount,
                'status'         => 'pending',
                'payment_method' => $validated['payment_method'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);

                $menuItem = MenuItem::find($item['menu_item_id']);
                $menuItem->updateStock(
                    -$item['quantity'],
                    'sale',
                    'order',
                    $order->id,
                    auth()->id()
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order'   => $order->load(['items.menuItem', 'user', 'cashier']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['user', 'cashier', 'items.menuItem.category']));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
        ]);

        $newStatus = $validated['status'];

        // Prevent modifying already-terminal orders
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return response()->json([
                'message' => "Cannot change status of a {$order->status} order."
            ], 422);
        }

        // Restore stock when cancelling
        if ($newStatus === 'cancelled') {
            DB::beginTransaction();
            try {
                foreach ($order->items as $item) {
                    $menuItem = MenuItem::find($item->menu_item_id);
                    if ($menuItem) {
                        $menuItem->updateStock(
                            $item->quantity,
                            'order_cancelled',
                            'order',
                            $order->id,
                            auth()->id()
                        );
                    }
                }
                $order->updateStatus($newStatus);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to cancel order: ' . $e->getMessage()], 500);
            }
        } else {
            $order->updateStatus($newStatus);
        }

        return response()->json([
            'message' => 'Order status updated successfully',
            'order'   => $order->fresh()->load(['user', 'cashier', 'items.menuItem']),
        ]);
    }

    /**
     * Queue for cashier — includes user (customer) eager-loaded.
     * Also loads items so the order can be eager-loaded for cancel/restore.
     */
    public function queue()
    {
        $orders = Order::with(['user', 'cashier', 'items.menuItem'])
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($orders);
    }

    /**
     * Customer order history — includes all statuses so "active" orders show up.
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $query = Order::with(['items.menuItem'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Only filter if a specific valid status is explicitly requested.
        // The CustomerDashboard requires all orders (including completed) 
        // to correctly calculate lifetime metrics (totalSpent, totalOrders).
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }
}
