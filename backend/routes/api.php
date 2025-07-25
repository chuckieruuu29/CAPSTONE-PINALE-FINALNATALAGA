<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;

// Auth Routes (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::get('/auth/check', [AuthController::class, 'check']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Dashboard Routes
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'total_customers' => \App\Models\Customer::count(),
            'total_products' => \App\Models\Product::count(),
            'total_orders' => \App\Models\Order::count(),
            'monthly_revenue' => \App\Models\Order::whereMonth('created_at', date('m'))->sum('total_amount'),
            'orders_this_month' => \App\Models\Order::whereMonth('created_at', date('m'))->count(),
            'production_efficiency' => 87.5 // Mock data for now
        ]);
    });
    
    Route::get('/dashboard/recent-orders', function () {
        return response()->json([
            ['id' => 1, 'customer_name' => 'John Smith', 'total' => 850, 'status' => 'In Progress', 'created_at' => '2025-01-25'],
            ['id' => 2, 'customer_name' => 'Sarah Johnson', 'total' => 1200, 'status' => 'Completed', 'created_at' => '2025-01-24'],
            ['id' => 3, 'customer_name' => 'Mike Wilson', 'total' => 675, 'status' => 'Pending', 'created_at' => '2025-01-23']
        ]);
    });
    
    Route::get('/dashboard/production-overview', function () {
        return response()->json([
            ['product_name' => 'Oak Table', 'quantity_produced' => 15, 'target_quantity' => 20],
            ['product_name' => 'Pine Chair', 'quantity_produced' => 45, 'target_quantity' => 50],
            ['product_name' => 'Walnut Cabinet', 'quantity_produced' => 8, 'target_quantity' => 10]
        ]);
    });
    
    Route::get('/dashboard/inventory-alerts', function () {
        return response()->json([
            'low_stock_products' => ['Oak Planks', 'Wood Stain', 'Screws'],
            'materials_to_reorder' => ['Pine Boards', 'Sandpaper', 'Wood Glue']
        ]);
    });
    
    // Customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    // Products
    Route::apiResource('products', ProductController::class);

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory', [InventoryController::class, 'store']);
    Route::put('/inventory/{id}', [InventoryController::class, 'update']);
    Route::delete('/inventory/{id}', [InventoryController::class, 'destroy']);

    // Orders
    Route::apiResource('orders', OrderController::class);

    // Production
    Route::get('/production', [ProductionController::class, 'index']);
    Route::post('/production', [ProductionController::class, 'store']);
    Route::put('/production/{id}', [ProductionController::class, 'update']);
    Route::delete('/production/{id}', [ProductionController::class, 'destroy']);
});
