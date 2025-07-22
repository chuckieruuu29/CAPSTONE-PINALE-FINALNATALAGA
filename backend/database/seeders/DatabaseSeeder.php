<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\ProductRawMaterial;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@unickenterprises.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create sample customers
        $customers = [
            [
                'name' => 'Home Decor Plus',
                'email' => 'orders@homedecorplus.com',
                'phone' => '+63-917-123-4567',
                'address' => '123 Main Street',
                'city' => 'Manila',
                'state' => 'Metro Manila',
                'zip_code' => '1000',
                'contact_person' => 'Maria Santos',
                'status' => 'active',
                'credit_limit' => 50000.00,
                'notes' => 'Regular bulk customer'
            ],
            [
                'name' => 'Furniture World',
                'email' => 'purchasing@furnitureworld.ph',
                'phone' => '+63-917-234-5678',
                'address' => '456 Commerce Ave',
                'city' => 'Quezon City',
                'state' => 'Metro Manila',
                'zip_code' => '1100',
                'contact_person' => 'John Cruz',
                'status' => 'active',
                'credit_limit' => 75000.00,
                'notes' => 'Premium customer'
            ],
            [
                'name' => 'Laguna Interior Design',
                'email' => 'info@lagunainterior.com',
                'phone' => '+63-917-345-6789',
                'address' => '789 Design Street',
                'city' => 'Sta. Rosa',
                'state' => 'Laguna',
                'zip_code' => '4026',
                'contact_person' => 'Anna Reyes',
                'status' => 'active',
                'credit_limit' => 30000.00,
                'notes' => 'Interior design firm'
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create raw materials
        $rawMaterials = [
            [
                'sku' => 'WOOD-NARRA-001',
                'name' => 'Narra Wood Planks',
                'description' => 'Premium quality narra wood planks',
                'category' => 'Wood',
                'type' => 'wood',
                'unit_of_measure' => 'board_feet',
                'unit_cost' => 150.00,
                'current_stock' => 500,
                'min_stock_level' => 50,
                'max_stock_level' => 1000,
                'reorder_point' => 100,
                'reorder_quantity' => 200,
                'supplier_name' => 'Laguna Lumber Co.',
                'supplier_contact' => '+63-949-111-2222',
                'lead_time_days' => 7,
                'storage_cost_per_unit' => 2.50,
                'storage_location' => 'Warehouse A1'
            ],
            [
                'sku' => 'WOOD-MAHOGANY-001',
                'name' => 'Mahogany Wood Planks',
                'description' => 'High-grade mahogany wood planks',
                'category' => 'Wood',
                'type' => 'wood',
                'unit_of_measure' => 'board_feet',
                'unit_cost' => 120.00,
                'current_stock' => 300,
                'min_stock_level' => 40,
                'max_stock_level' => 800,
                'reorder_point' => 80,
                'reorder_quantity' => 150,
                'supplier_name' => 'Philippine Hardwood',
                'supplier_contact' => '+63-949-222-3333',
                'lead_time_days' => 10,
                'storage_cost_per_unit' => 2.00,
                'storage_location' => 'Warehouse A1'
            ],
            [
                'sku' => 'HW-HINGE-001',
                'name' => 'Cabinet Door Hinges',
                'description' => 'Stainless steel cabinet door hinges',
                'category' => 'Hardware',
                'type' => 'hardware',
                'unit_of_measure' => 'pieces',
                'unit_cost' => 25.00,
                'current_stock' => 200,
                'min_stock_level' => 20,
                'max_stock_level' => 500,
                'reorder_point' => 50,
                'reorder_quantity' => 100,
                'supplier_name' => 'Hardware Plus',
                'supplier_contact' => '+63-949-333-4444',
                'lead_time_days' => 3,
                'storage_cost_per_unit' => 0.50,
                'storage_location' => 'Warehouse B2'
            ],
            [
                'sku' => 'FIN-VARNISH-001',
                'name' => 'Wood Varnish',
                'description' => 'Premium wood varnish for finishing',
                'category' => 'Finishing',
                'type' => 'finish',
                'unit_of_measure' => 'liters',
                'unit_cost' => 180.00,
                'current_stock' => 50,
                'min_stock_level' => 10,
                'max_stock_level' => 100,
                'reorder_point' => 15,
                'reorder_quantity' => 30,
                'supplier_name' => 'Chemical Solutions Inc.',
                'supplier_contact' => '+63-949-444-5555',
                'lead_time_days' => 5,
                'storage_cost_per_unit' => 3.00,
                'storage_location' => 'Chemical Storage'
            ]
        ];

        foreach ($rawMaterials as $materialData) {
            $material = RawMaterial::create($materialData);
            
            // Create inventory record for each raw material
            Inventory::create([
                'item_type' => 'raw_material',
                'item_id' => $material->id,
                'current_stock' => $material->current_stock,
                'available_stock' => $material->current_stock,
                'reserved_stock' => 0,
                'incoming_stock' => 0,
                'average_cost' => $material->unit_cost,
                'last_movement_date' => now(),
                'location' => $material->storage_location
            ]);
        }

        // Create products
        $products = [
            [
                'sku' => 'CHAIR-NARRA-001',
                'name' => 'Narra Dining Chair',
                'description' => 'Handcrafted dining chair made from premium narra wood',
                'category' => 'Seating',
                'type' => 'furniture',
                'selling_price' => 2500.00,
                'cost_price' => 1500.00,
                'weight' => 5.50,
                'dimensions' => '45cm x 45cm x 85cm',
                'wood_type' => 'Narra',
                'finish' => 'Natural Varnish',
                'production_time_hours' => 8,
                'min_stock_level' => 5,
                'max_stock_level' => 50,
                'status' => 'active',
                'notes' => 'Popular dining chair design'
            ],
            [
                'sku' => 'TABLE-MAHOGANY-001',
                'name' => 'Mahogany Dining Table',
                'description' => 'Elegant dining table crafted from mahogany wood',
                'category' => 'Tables',
                'type' => 'furniture',
                'selling_price' => 8500.00,
                'cost_price' => 5000.00,
                'weight' => 25.00,
                'dimensions' => '150cm x 90cm x 75cm',
                'wood_type' => 'Mahogany',
                'finish' => 'Natural Varnish',
                'production_time_hours' => 24,
                'min_stock_level' => 2,
                'max_stock_level' => 20,
                'status' => 'active',
                'notes' => 'Six-seater dining table'
            ],
            [
                'sku' => 'CABINET-NARRA-001',
                'name' => 'Narra Storage Cabinet',
                'description' => 'Multi-purpose storage cabinet with doors',
                'category' => 'Storage',
                'type' => 'furniture',
                'selling_price' => 6500.00,
                'cost_price' => 4000.00,
                'weight' => 18.00,
                'dimensions' => '80cm x 40cm x 120cm',
                'wood_type' => 'Narra',
                'finish' => 'Natural Varnish',
                'production_time_hours' => 16,
                'min_stock_level' => 3,
                'max_stock_level' => 25,
                'status' => 'active',
                'notes' => 'Two-door storage cabinet'
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            // Create inventory record for each product
            Inventory::create([
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
        }

        // Create Bill of Materials (Product-Raw Material relationships)
        $products = Product::all();
        $rawMaterials = RawMaterial::all();

        // Chair BOM
        $chair = $products->where('sku', 'CHAIR-NARRA-001')->first();
        $narra = $rawMaterials->where('sku', 'WOOD-NARRA-001')->first();
        $varnish = $rawMaterials->where('sku', 'FIN-VARNISH-001')->first();

        ProductRawMaterial::create([
            'product_id' => $chair->id,
            'raw_material_id' => $narra->id,
            'quantity_required' => 3.5,
            'unit_of_measure' => 'board_feet',
            'waste_factor' => 0.1,
            'usage_notes' => 'Main frame and seat construction',
            'criticality' => 'critical'
        ]);

        ProductRawMaterial::create([
            'product_id' => $chair->id,
            'raw_material_id' => $varnish->id,
            'quantity_required' => 0.2,
            'unit_of_measure' => 'liters',
            'waste_factor' => 0.05,
            'usage_notes' => 'Final finishing',
            'criticality' => 'medium'
        ]);

        // Table BOM
        $table = $products->where('sku', 'TABLE-MAHOGANY-001')->first();
        $mahogany = $rawMaterials->where('sku', 'WOOD-MAHOGANY-001')->first();

        ProductRawMaterial::create([
            'product_id' => $table->id,
            'raw_material_id' => $mahogany->id,
            'quantity_required' => 12.0,
            'unit_of_measure' => 'board_feet',
            'waste_factor' => 0.15,
            'usage_notes' => 'Table top and legs',
            'criticality' => 'critical'
        ]);

        ProductRawMaterial::create([
            'product_id' => $table->id,
            'raw_material_id' => $varnish->id,
            'quantity_required' => 0.5,
            'unit_of_measure' => 'liters',
            'waste_factor' => 0.1,
            'usage_notes' => 'Final finishing',
            'criticality' => 'medium'
        ]);

        // Cabinet BOM
        $cabinet = $products->where('sku', 'CABINET-NARRA-001')->first();
        $hinges = $rawMaterials->where('sku', 'HW-HINGE-001')->first();

        ProductRawMaterial::create([
            'product_id' => $cabinet->id,
            'raw_material_id' => $narra->id,
            'quantity_required' => 8.0,
            'unit_of_measure' => 'board_feet',
            'waste_factor' => 0.12,
            'usage_notes' => 'Cabinet frame, doors, and shelves',
            'criticality' => 'critical'
        ]);

        ProductRawMaterial::create([
            'product_id' => $cabinet->id,
            'raw_material_id' => $hinges->id,
            'quantity_required' => 4,
            'unit_of_measure' => 'pieces',
            'waste_factor' => 0.0,
            'usage_notes' => 'Door hinges',
            'criticality' => 'high'
        ]);

        ProductRawMaterial::create([
            'product_id' => $cabinet->id,
            'raw_material_id' => $varnish->id,
            'quantity_required' => 0.4,
            'unit_of_measure' => 'liters',
            'waste_factor' => 0.08,
            'usage_notes' => 'Final finishing',
            'criticality' => 'medium'
        ]);

        // Create sample orders
        $customer1 = Customer::where('email', 'orders@homedecorplus.com')->first();
        $customer2 = Customer::where('email', 'purchasing@furnitureworld.ph')->first();

        $order1 = Order::create([
            'customer_id' => $customer1->id,
            'order_date' => now()->subDays(5),
            'required_date' => now()->addDays(14),
            'status' => 'confirmed',
            'priority' => 'normal',
            'subtotal' => 21000.00,
            'tax_amount' => 2520.00,
            'shipping_cost' => 500.00,
            'total_amount' => 24020.00,
            'shipping_address' => '123 Main Street, Manila, Metro Manila 1000',
            'billing_address' => '123 Main Street, Manila, Metro Manila 1000',
            'shipping_method' => 'Standard Delivery',
            'special_instructions' => 'Please handle with care',
            'created_by' => $user->id
        ]);

        // Order items for order 1
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $chair->id,
            'quantity' => 6,
            'unit_price' => 2500.00,
            'line_total' => 15000.00,
            'status' => 'pending'
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $table->id,
            'quantity' => 1,
            'unit_price' => 8500.00,
            'line_total' => 8500.00,
            'status' => 'pending'
        ]);

        $order2 = Order::create([
            'customer_id' => $customer2->id,
            'order_date' => now()->subDays(2),
            'required_date' => now()->addDays(21),
            'status' => 'pending',
            'priority' => 'high',
            'subtotal' => 13000.00,
            'tax_amount' => 1560.00,
            'shipping_cost' => 750.00,
            'total_amount' => 15310.00,
            'shipping_address' => '456 Commerce Ave, Quezon City, Metro Manila 1100',
            'billing_address' => '456 Commerce Ave, Quezon City, Metro Manila 1100',
            'shipping_method' => 'Express Delivery',
            'special_instructions' => 'Rush order',
            'created_by' => $user->id
        ]);

        // Order items for order 2
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $cabinet->id,
            'quantity' => 2,
            'unit_price' => 6500.00,
            'line_total' => 13000.00,
            'status' => 'pending'
        ]);

        echo "Database seeded successfully!\n";
        echo "Admin user: admin@unickenterprises.com / password123\n";
    }
}
