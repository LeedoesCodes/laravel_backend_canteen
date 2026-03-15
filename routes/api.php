<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

// ==================== TEST ROUTES ====================
Route::post('/test-simple', function(Request $request) {
    return response()->json([
        'method' => $request->method(),
        'headers' => [
            'content-type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept')
        ],
        'raw_content' => $request->getContent(),
        'json_decoded' => json_decode($request->getContent(), true),
        'input_all' => $request->all(),
        'request_input' => $request->input('name'),
        'request_json' => $request->json('name')
    ]);
})->middleware('auth:sanctum');

Route::post('/debug-post', function(Request $request) {
    Log::info('DEBUG POST ENDPOINT', [
        'all' => $request->all(),
        'json' => $request->json()->all(),
        'content' => $request->getContent(),
        'headers' => $request->headers->all()
    ]);
    
    return response()->json([
        'received' => $request->all(),
        'json_parsed' => $request->json()->all(),
        'raw' => $request->getContent(),
        'content_type' => $request->header('Content-Type')
    ]);
})->middleware('auth:sanctum');

Route::post('/test-category-debug', function(Request $request) {
    try {
        $rawContent = $request->getContent();
        $data = json_decode($rawContent, true);
        
        return response()->json([
            'success' => true,
            'debug' => [
                'raw_content' => $rawContent,
                'decoded_data' => $data,
                'headers' => [
                    'content-type' => $request->header('Content-Type'),
                    'accept' => $request->header('Accept')
                ],
                'input_all' => $request->all(),
                'json_all' => $request->json()->all()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->middleware('auth:sanctum');

Route::get('/test-auth', function() {
    return response()->json([
        'message' => 'Authentication test endpoint',
        'authenticated' => auth()->check(),
        'user' => auth()->user()
    ]);
})->middleware('auth:sanctum');

Route::get('/role-test', function() {
    return response()->json([
        'message' => 'Role middleware works!',
        'user' => auth()->user()
    ]);
})->middleware(['auth:sanctum', 'role:admin']);

Route::get('/test-category-list', function() {
    try {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->middleware('auth:sanctum');

Route::get('/orders/today-stats', function() {
    // Returns today's stats specifically for cashier dashboard
});

// ==================== PUBLIC ROUTES ====================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ==================== PROTECTED ROUTES ====================
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Public menu (accessible to all authenticated users)
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/menu/{menuItem}', [MenuController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Customer routes
    Route::prefix('customer')->group(function () {
        Route::get('/orders', [OrderController::class, 'history']);
    });

// ==================== CASHIER AND ADMIN ROUTES ====================
Route::middleware('role:cashier,admin')->group(function () {
    // Orders - Cashier accessible
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/queue', [OrderController::class, 'queue']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    
    // ===== ADD THESE CUSTOMER ROUTES HERE =====
    // Get all customers for POS selection
    Route::get('/customers', [App\Http\Controllers\UserController::class, 'getCustomers']);
    
    // Search customers (optional - for better performance)
    Route::get('/customers/search', [App\Http\Controllers\UserController::class, 'searchCustomers']);
    
    // Get single customer by ID
    Route::get('/customers/{id}', [App\Http\Controllers\UserController::class, 'getCustomer']);
    // ==========================================
    
    // Cashier Dashboard Stats
    Route::get('/orders/today-stats', function() {
        try {
            $today = now()->format('Y-m-d');
            $orders = App\Models\Order::whereDate('created_at', $today)
                           ->whereIn('status', ['pending', 'preparing', 'ready', 'completed'])
                           ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_orders' => $orders->count(),
                    'total_revenue' => $orders->sum('total_amount'),
                    'pending' => $orders->where('status', 'pending')->count(),
                    'preparing' => $orders->where('status', 'preparing')->count(),
                    'ready' => $orders->where('status', 'ready')->count(),
                    'completed' => $orders->where('status', 'completed')->count(),
                    'recent_orders' => $orders->sortByDesc('created_at')->take(5)->values()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    });
    
    // Inventory - Cashier accessible
    Route::get('/inventory/low-stock', [MenuController::class, 'lowStock']);
    Route::post('/inventory/adjust', [InventoryController::class, 'adjustStock']);
});

    // ==================== ADMIN ONLY ROUTES ====================
    Route::middleware('role:admin')->group(function () {
        // Categories management
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Menu management
        Route::post('/menu', [MenuController::class, 'store']);
        Route::put('/menu/{menuItem}', [MenuController::class, 'update']);
        Route::delete('/menu/{menuItem}', [MenuController::class, 'destroy']);
        Route::patch('/menu/{menuItem}/toggle', [MenuController::class, 'toggleAvailability']);

        // Full inventory management
        Route::get('/inventory/logs', [InventoryController::class, 'logs']);
        Route::post('/inventory/bulk-restock', [InventoryController::class, 'bulkRestock']);

        // Reports
        Route::get('/reports/sales', [ReportController::class, 'sales']);
        Route::get('/reports/best-selling', [ReportController::class, 'bestSelling']);
        Route::get('/reports/category-breakdown', [ReportController::class, 'categoryBreakdown']);
        Route::get('/reports/order-trend', [ReportController::class, 'orderTrend']);
        Route::get('/reports/summary', [ReportController::class, 'summary']);

        // Full orders access
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);

        // Get all users (admin only)
    // Get all users (admin only)
    Route::get('/users', function() {
        try {
            $users = App\Models\User::select('id', 'name', 'email', 'role')
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users'
            ], 500);
        }
    });

        // Admin User Management CRUD
        Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'adminIndex']);
        Route::post('/admin/users', [App\Http\Controllers\UserController::class, 'adminStore']);
        Route::put('/admin/users/{id}', [App\Http\Controllers\UserController::class, 'adminUpdate']);
        Route::delete('/admin/users/{id}', [App\Http\Controllers\UserController::class, 'adminDestroy']);
    });
});