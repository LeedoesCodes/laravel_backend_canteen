<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $customers = User::where('role', 'customer')->pluck('id')->toArray();
        $cashiers = User::where('role', 'cashier')->pluck('id')->toArray();
        $menuItems = MenuItem::all();

        $statuses = ['pending', 'preparing', 'ready', 'completed', 'cancelled'];
        $paymentMethods = ['cash', 'card', 'online'];

        // Create 200 orders over the past 90 days
        for ($i = 0; $i < 200; $i++) {
            $createdAt = now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Determine status based on age of order
            $status = 'completed';
            if ($createdAt->diffInHours(now()) < 2) {
                $status = $statuses[array_rand(['pending', 'preparing', 'ready'])];
            }

            DB::beginTransaction();

            try {
                // Create order
                $order = Order::create([
                    'order_number' => 'ORD-' . $createdAt->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'user_id' => $customers[array_rand($customers)],
                    'cashier_id' => $status !== 'pending' ? $cashiers[array_rand($cashiers)] : null,
                    'total_amount' => 0,
                    'status' => $status,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'notes' => rand(0, 3) === 0 ? 'Special request: less spicy' : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Add random items (1-5 items per order)
                $totalAmount = 0;
                $numItems = rand(1, 5);
                $selectedItems = [];

                for ($j = 0; $j < $numItems; $j++) {
                    $menuItem = $menuItems[array_rand($menuItems->toArray())];
                    $quantity = rand(1, 3);
                    
                    // Avoid duplicate items in same order
                    while (in_array($menuItem->id, $selectedItems)) {
                        $menuItem = $menuItems[array_rand($menuItems->toArray())];
                    }
                    
                    $selectedItems[] = $menuItem->id;
                    $subtotal = $menuItem->price * $quantity;
                    $totalAmount += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $quantity,
                        'unit_price' => $menuItem->price,
                        'subtotal' => $subtotal,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    // Note: we intentionally do NOT call updateStock here.
                    // Seeded orders are historical demo data; stock levels are
                    // set by MenuItemSeeder and should not be drained by fake orders.
                    // Real orders placed through the app will still deduct stock correctly.
                }

                $order->update(['total_amount' => $totalAmount]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}