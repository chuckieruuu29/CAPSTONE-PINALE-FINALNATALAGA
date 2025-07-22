<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        try {
            $query = Customer::query();

            // Search functionality
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('contact_person', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            // Filter by city
            if ($request->has('city')) {
                $query->where('city', $request->get('city'));
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $customers = $query->paginate($perPage);

            // Add computed fields
            $customers->getCollection()->transform(function ($customer) {
                $customer->total_orders_amount = $customer->getTotalOrdersAmount();
                $customer->pending_orders_count = $customer->getPendingOrdersCount();
                $customer->available_credit = $customer->getAvailableCredit();
                return $customer;
            });

            return response()->json([
                'success' => true,
                'data' => $customers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers',
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
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'zip_code' => 'nullable|string|max:20',
                'contact_person' => 'nullable|string|max:255',
                'status' => 'in:active,inactive',
                'credit_limit' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer = Customer::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified customer
     */
    public function show($id)
    {
        try {
            $customer = Customer::with(['orders' => function($query) {
                $query->latest()->take(10);
            }])->findOrFail($id);

            // Add computed fields
            $customer->total_orders_amount = $customer->getTotalOrdersAmount();
            $customer->pending_orders_count = $customer->getPendingOrdersCount();
            $customer->available_credit = $customer->getAvailableCredit();

            return response()->json([
                'success' => true,
                'data' => $customer
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customer',
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
     * Update the specified customer
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'zip_code' => 'nullable|string|max:20',
                'contact_person' => 'nullable|string|max:255',
                'status' => 'in:active,inactive',
                'credit_limit' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Check if customer has orders
            if ($customer->orders()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete customer with existing orders'
                ], 422);
            }

            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer orders
     */
    public function orders($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $orders = $customer->orders()
                              ->with(['orderItems.product'])
                              ->latest()
                              ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customer orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer credit status
     */
    public function creditStatus($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'credit_limit' => $customer->credit_limit,
                    'available_credit' => $customer->getAvailableCredit(),
                    'pending_orders_amount' => $customer->orders()
                        ->whereIn('status', ['pending', 'confirmed', 'in_production'])
                        ->sum('total_amount'),
                    'total_orders_amount' => $customer->getTotalOrdersAmount(),
                    'can_place_order' => $customer->status === 'active'
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve credit status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Customer report
     */
    public function customerReport(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->subDays(30));
            $endDate = $request->get('end_date', now());

            $report = [
                'total_customers' => Customer::count(),
                'active_customers' => Customer::active()->count(),
                'inactive_customers' => Customer::inactive()->count(),
                'new_customers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
                'top_customers' => Customer::with(['orders'])
                    ->get()
                    ->map(function ($customer) {
                        return [
                            'id' => $customer->id,
                            'name' => $customer->name,
                            'total_orders' => $customer->orders->count(),
                            'total_amount' => $customer->getTotalOrdersAmount()
                        ];
                    })
                    ->sortByDesc('total_amount')
                    ->take(10)
                    ->values(),
                'customers_by_city' => Customer::select('city', \DB::raw('count(*) as count'))
                    ->groupBy('city')
                    ->orderByDesc('count')
                    ->get(),
                'credit_utilization' => Customer::select(
                        \DB::raw('SUM(credit_limit) as total_credit_limit'),
                        \DB::raw('COUNT(*) as customers_with_credit')
                    )
                    ->where('credit_limit', '>', 0)
                    ->first()
            ];

            return response()->json([
                'success' => true,
                'data' => $report
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate customer report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
