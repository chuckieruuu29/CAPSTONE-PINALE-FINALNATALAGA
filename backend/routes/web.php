<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductionController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// API Routes (with CORS headers for frontend)
Route::prefix('api')->middleware(['api'])->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    });

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Customer Management
        Route::apiResource('customers', CustomerController::class);
        Route::get('customers/{customer}/orders', [CustomerController::class, 'orders']);
        Route::get('customers/{customer}/credit-status', [CustomerController::class, 'creditStatus']);
        
        // Product Management
        Route::apiResource('products', ProductController::class);
        Route::get('products/{product}/materials', [ProductController::class, 'materials']);
        Route::post('products/{product}/materials', [ProductController::class, 'attachMaterial']);
        Route::delete('products/{product}/materials/{material}', [ProductController::class, 'detachMaterial']);
        Route::get('products/{product}/production-cost/{quantity}', [ProductController::class, 'productionCost']);
        Route::get('products/low-stock', [ProductController::class, 'lowStock']);
        
        // Order Management
        Route::apiResource('orders', OrderController::class);
        Route::post('orders/{order}/confirm', [OrderController::class, 'confirm']);
        Route::post('orders/{order}/ship', [OrderController::class, 'ship']);
        Route::post('orders/{order}/deliver', [OrderController::class, 'deliver']);
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
        Route::get('orders/{order}/tracking', [OrderController::class, 'tracking']);
        Route::get('orders/status/{status}', [OrderController::class, 'byStatus']);
        
        // Inventory Management (MRP)
        Route::apiResource('inventory', InventoryController::class);
        Route::get('inventory/raw-materials', [InventoryController::class, 'rawMaterials']);
        Route::get('inventory/products', [InventoryController::class, 'products']);
        Route::get('inventory/low-stock', [InventoryController::class, 'lowStock']);
        Route::get('inventory/reorder-suggestions', [InventoryController::class, 'reorderSuggestions']);
        Route::post('inventory/{item}/adjust', [InventoryController::class, 'adjustStock']);
        Route::get('inventory/movements', [InventoryController::class, 'movements']);
        
        // Production Management
        Route::apiResource('production', ProductionController::class);
        Route::get('production/schedules', [ProductionController::class, 'schedules']);
        Route::post('production/{batch}/start', [ProductionController::class, 'startBatch']);
        Route::post('production/{batch}/complete', [ProductionController::class, 'completeBatch']);
        Route::post('production/{batch}/pause', [ProductionController::class, 'pauseBatch']);
        Route::get('production/capacity', [ProductionController::class, 'capacity']);
        Route::get('production/efficiency', [ProductionController::class, 'efficiency']);
        
        // Dashboard & Reports
        Route::prefix('dashboard')->group(function () {
            Route::get('/stats', function () {
                return response()->json([
                    'total_customers' => \App\Models\Customer::count(),
                    'active_customers' => \App\Models\Customer::active()->count(),
                    'total_products' => \App\Models\Product::count(),
                    'low_stock_products' => \App\Models\Product::lowStock()->count(),
                    'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
                    'in_production_orders' => \App\Models\Order::where('status', 'in_production')->count(),
                    'total_orders_value' => \App\Models\Order::sum('total_amount'),
                    'materials_needing_reorder' => \App\Models\RawMaterial::lowStock()->count(),
                ]);
            });
            
            Route::get('/recent-orders', function () {
                return \App\Models\Order::with(['customer', 'orderItems.product'])
                    ->latest()
                    ->take(10)
                    ->get();
            });
            
            Route::get('/production-overview', function () {
                return \App\Models\ProductionBatch::with(['product'])
                    ->whereIn('status', ['in_progress', 'planned'])
                    ->get();
            });
            
            Route::get('/inventory-alerts', function () {
                $lowStockProducts = \App\Models\Product::lowStock()->with('inventory')->get();
                $materialsToReorder = \App\Models\RawMaterial::lowStock()->get();
                
                return response()->json([
                    'low_stock_products' => $lowStockProducts,
                    'materials_to_reorder' => $materialsToReorder
                ]);
            });
        });
        
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/sales', [OrderController::class, 'salesReport']);
            Route::get('/production', [ProductionController::class, 'productionReport']);
            Route::get('/inventory', [InventoryController::class, 'inventoryReport']);
            Route::get('/customers', [CustomerController::class, 'customerReport']);
        });
    });

    // Public API endpoints (for customer ordering)
    Route::prefix('public')->group(function () {
        Route::get('/products', [ProductController::class, 'publicIndex']);
        Route::get('/products/{product}', [ProductController::class, 'publicShow']);
        Route::post('/orders/quote', [OrderController::class, 'quote']);
        Route::post('/orders/place', [OrderController::class, 'placePublicOrder']);
        Route::get('/orders/{orderNumber}/track', [OrderController::class, 'publicTracking']);
    });
});
