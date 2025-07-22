<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductRawMaterial;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with(['inventory']);

            // Search functionality
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->get('category'));
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->get('type'));
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sku' => 'required|string|unique:products,sku',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|max:100',
                'type' => 'in:furniture,decor,custom,other',
                'selling_price' => 'required|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'weight' => 'nullable|numeric|min:0',
                'dimensions' => 'nullable|string',
                'wood_type' => 'nullable|string',
                'finish' => 'nullable|string',
                'production_time_hours' => 'nullable|integer|min:0',
                'min_stock_level' => 'nullable|integer|min:0',
                'max_stock_level' => 'nullable|integer|min:0',
                'image_url' => 'nullable|url',
                'status' => 'in:active,inactive,discontinued',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::create($request->all());

            // Create inventory record for the product
            $product->inventory()->create([
                'item_type' => 'product',
                'item_id' => $product->id,
                'current_stock' => 0,
                'available_stock' => 0,
                'reserved_stock' => 0,
                'incoming_stock' => 0,
                'average_cost' => $product->cost_price,
                'last_movement_date' => now(),
                'location' => 'Finished Goods'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('inventory')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        try {
            $product = Product::with(['inventory', 'rawMaterials', 'orderItems', 'productionBatches'])
                             ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'sku' => 'required|string|unique:products,sku,' . $id,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|max:100',
                'type' => 'in:furniture,decor,custom,other',
                'selling_price' => 'required|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'weight' => 'nullable|numeric|min:0',
                'dimensions' => 'nullable|string',
                'wood_type' => 'nullable|string',
                'finish' => 'nullable|string',
                'production_time_hours' => 'nullable|integer|min:0',
                'min_stock_level' => 'nullable|integer|min:0',
                'max_stock_level' => 'nullable|integer|min:0',
                'image_url' => 'nullable|url',
                'status' => 'in:active,inactive,discontinued',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load('inventory')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Check if product has orders
            if ($product->orderItems()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete product with existing orders'
                ], 422);
            }

            // Check if product has production batches
            if ($product->productionBatches()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete product with existing production batches'
                ], 422);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product materials (BOM)
     */
    public function materials($id)
    {
        try {
            $product = Product::with(['rawMaterials' => function($query) {
                $query->withPivot('quantity_required', 'unit_of_measure', 'waste_factor', 'usage_notes', 'criticality');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product->rawMaterials
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product materials',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Attach material to product (BOM)
     */
    public function attachMaterial(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'raw_material_id' => 'required|exists:raw_materials,id',
                'quantity_required' => 'required|numeric|min:0',
                'unit_of_measure' => 'required|string',
                'waste_factor' => 'nullable|numeric|min:0|max:1',
                'usage_notes' => 'nullable|string',
                'criticality' => 'in:low,medium,high,critical'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if material is already attached
            if ($product->rawMaterials()->where('raw_material_id', $request->raw_material_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material already attached to this product'
                ], 422);
            }

            $product->rawMaterials()->attach($request->raw_material_id, [
                'quantity_required' => $request->quantity_required,
                'unit_of_measure' => $request->unit_of_measure,
                'waste_factor' => $request->waste_factor ?? 0,
                'usage_notes' => $request->usage_notes,
                'criticality' => $request->criticality ?? 'medium'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Material attached to product successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to attach material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detach material from product (BOM)
     */
    public function detachMaterial($productId, $materialId)
    {
        try {
            $product = Product::findOrFail($productId);
            $product->rawMaterials()->detach($materialId);

            return response()->json([
                'success' => true,
                'message' => 'Material detached from product successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to detach material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate production cost for quantity
     */
    public function productionCost($id, $quantity)
    {
        try {
            $product = Product::findOrFail($id);
            $cost = $product->getEstimatedProductionCost($quantity);

            return response()->json([
                'success' => true,
                'data' => [
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'estimated_cost' => $cost,
                    'cost_per_unit' => $cost / $quantity,
                    'required_materials' => $product->calculateRequiredMaterials($quantity)
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate production cost',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock products
     */
    public function lowStock()
    {
        try {
            $products = Product::lowStock()->with('inventory')->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve low stock products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Public product listing (for customer portal)
     */
    public function publicIndex(Request $request)
    {
        try {
            $query = Product::active()->with(['inventory']);

            // Search functionality
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
                });
            }

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->get('category'));
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 12);
            $products = $query->paginate($perPage);

            // Hide sensitive information for public API
            $products->getCollection()->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category' => $product->category,
                    'type' => $product->type,
                    'selling_price' => $product->selling_price,
                    'weight' => $product->weight,
                    'dimensions' => $product->dimensions,
                    'wood_type' => $product->wood_type,
                    'finish' => $product->finish,
                    'production_time_hours' => $product->production_time_hours,
                    'image_url' => $product->image_url,
                    'in_stock' => $product->current_stock > 0
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Public product detail (for customer portal)
     */
    public function publicShow($id)
    {
        try {
            $product = Product::active()->findOrFail($id);

            $productData = [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category,
                'type' => $product->type,
                'selling_price' => $product->selling_price,
                'weight' => $product->weight,
                'dimensions' => $product->dimensions,
                'wood_type' => $product->wood_type,
                'finish' => $product->finish,
                'production_time_hours' => $product->production_time_hours,
                'image_url' => $product->image_url,
                'in_stock' => $product->current_stock > 0,
                'estimated_delivery_days' => ceil($product->production_time_hours / 8) + 3 // Production time + 3 days shipping
            ];

            return response()->json([
                'success' => true,
                'data' => $productData
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
