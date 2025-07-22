# Unick Enterprises Inc. - Order Processing Management System

## System Overview

A comprehensive web-based order processing, inventory management, and production tracking system specifically designed for Unick Enterprises Inc., a woodcraft furniture manufacturer located in Cabuyao City, Laguna, Philippines.

### Key Features

- **Inventory Management System** with MRP-based control and predictive analytics
- **Production Tracking System** with real-time monitoring and scheduling
- **Integrated Order Processing** with customer portal and automated workflows
- **Bill of Materials (BOM)** management for product-material relationships
- **Customer Management** with credit limits and order history
- **Automated Reporting** with dashboards and analytics

## Technical Architecture

### Backend (Laravel 12)
- **Framework**: Laravel 12 with PHP 8.4
- **Database**: SQLite (for development) / MySQL (for production)
- **Authentication**: Laravel Sanctum for API token authentication
- **API**: RESTful JSON API endpoints
- **Models**: Eloquent ORM with comprehensive business logic

### Frontend (React 18)
- **Framework**: React 18 with functional components and hooks
- **UI Library**: Bootstrap 5 for responsive design
- **HTTP Client**: Axios for API communication
- **Routing**: React Router DOM for SPA navigation
- **Charts**: Chart.js with React-Chartjs-2 for data visualization
- **Authentication**: Context API for global auth state management

## Database Schema

### Core Tables

#### 1. Users
- User authentication and system access
- Fields: id, name, email, password, email_verified_at, timestamps

#### 2. Customers
- Customer information and credit management
- Fields: id, name, email, phone, address details, credit_limit, status, notes

#### 3. Products
- Product catalog with specifications and pricing
- Fields: id, sku, name, description, category, type, pricing, dimensions, production_time, stock_levels

#### 4. Raw Materials
- Raw materials inventory with supplier information
- Fields: id, sku, name, category, type, unit_cost, stock_levels, reorder_points, supplier_details

#### 5. Orders
- Customer orders with status tracking
- Fields: id, order_number, customer_id, dates, status, priority, financial_totals, addresses

#### 6. Order Items
- Individual line items within orders
- Fields: id, order_id, product_id, quantity, pricing, production_details, status

#### 7. Inventories (Polymorphic)
- Unified inventory tracking for products and raw materials
- Fields: id, item_type, item_id, stock_levels, costs, location

#### 8. Product Raw Materials (BOM)
- Bill of Materials linking products to required raw materials
- Fields: id, product_id, raw_material_id, quantity_required, waste_factor, criticality

#### 9. Production Batches
- Production planning and tracking
- Fields: id, batch_number, product_id, quantities, dates, efficiency, material_usage

#### 10. Production Schedules
- Detailed production scheduling with worker assignments
- Fields: id, batch_id, schedule_details, worker_assignment, completion_tracking

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `GET /api/auth/user` - Get authenticated user info

### Customer Management
- `GET /api/customers` - List customers (with search, filters, pagination)
- `POST /api/customers` - Create new customer
- `GET /api/customers/{id}` - Get customer details
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer
- `GET /api/customers/{id}/orders` - Get customer orders
- `GET /api/customers/{id}/credit-status` - Get credit information

### Product Management
- `GET /api/products` - List products (with search, filters, pagination)
- `POST /api/products` - Create new product
- `GET /api/products/{id}` - Get product details
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product
- `GET /api/products/{id}/materials` - Get product BOM
- `POST /api/products/{id}/materials` - Attach material to product
- `DELETE /api/products/{id}/materials/{material_id}` - Remove material from product
- `GET /api/products/{id}/production-cost/{quantity}` - Calculate production cost
- `GET /api/products/low-stock` - Get low stock products

### Order Management
- `GET /api/orders` - List orders
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order
- `POST /api/orders/{id}/confirm` - Confirm order
- `POST /api/orders/{id}/ship` - Ship order
- `POST /api/orders/{id}/deliver` - Mark as delivered
- `POST /api/orders/{id}/cancel` - Cancel order

### Inventory Management
- `GET /api/inventory` - List inventory items
- `GET /api/inventory/products` - Product inventory
- `GET /api/inventory/raw-materials` - Raw material inventory
- `GET /api/inventory/low-stock` - Low stock items
- `GET /api/inventory/reorder-suggestions` - Reorder recommendations
- `POST /api/inventory/{id}/adjust` - Adjust stock levels

### Production Management
- `GET /api/production` - List production batches
- `POST /api/production` - Create production batch
- `GET /api/production/schedules` - Production schedules
- `POST /api/production/{id}/start` - Start production batch
- `POST /api/production/{id}/complete` - Complete production batch
- `POST /api/production/{id}/pause` - Pause production batch

### Dashboard & Analytics
- `GET /api/dashboard/stats` - Key performance indicators
- `GET /api/dashboard/recent-orders` - Recent order activity
- `GET /api/dashboard/production-overview` - Production status
- `GET /api/dashboard/inventory-alerts` - Inventory alerts

### Public API (Customer Portal)
- `GET /api/public/products` - Public product catalog
- `GET /api/public/products/{id}` - Public product details
- `POST /api/public/orders/quote` - Request order quote
- `POST /api/public/orders/place` - Place public order
- `GET /api/public/orders/{orderNumber}/track` - Track order status

## Business Logic Implementation

### Material Requirements Planning (MRP)
The system implements MRP functionality through:

1. **Bill of Materials (BOM)**: Products are linked to required raw materials with quantities, waste factors, and criticality levels
2. **Inventory Tracking**: Real-time monitoring of both product and raw material stock levels
3. **Automatic Reorder Points**: System calculates when to reorder based on consumption patterns
4. **Production Planning**: Checks material availability before starting production batches

### Production Workflow
1. **Order Creation**: Customer places order through portal or admin creates order
2. **Production Planning**: System creates production batches based on order requirements
3. **Material Reservation**: Raw materials are reserved when production starts
4. **Production Tracking**: Real-time monitoring of production progress and quality
5. **Inventory Updates**: Finished goods inventory is updated upon completion
6. **Order Fulfillment**: Orders are marked as ready when all items are completed

### Inventory Management
- **Polymorphic Design**: Single inventory table handles both products and raw materials
- **Stock Levels**: Current, available, reserved, and incoming stock tracking
- **Cost Tracking**: Average cost calculation with FIFO/LIFO support
- **Location Management**: Physical location tracking within warehouses
- **Movement Logging**: Complete audit trail of all stock movements

## Frontend Architecture

### Component Structure
```
src/
├── components/
│   ├── Dashboard.js          # Main dashboard with KPIs and charts
│   ├── Navbar.js            # Navigation sidebar
│   ├── Login.js             # Authentication form
│   ├── Customers.js         # Customer management
│   ├── Products.js          # Product management
│   ├── Orders.js            # Order management
│   ├── Inventory.js         # Inventory tracking
│   ├── Production.js        # Production management
│   ├── Reports.js           # Analytics and reporting
│   └── PublicStore.js       # Customer-facing product catalog
├── context/
│   └── AuthContext.js       # Global authentication state
├── App.js                   # Main application component
└── index.js                 # Application entry point
```

### State Management
- **Authentication**: React Context API for global auth state
- **Component State**: useState hooks for local component state
- **API Communication**: Axios with interceptors for token management
- **Protected Routes**: Route guards based on authentication status

## Installation and Setup

### Prerequisites
- PHP 8.4+
- Node.js 16+
- SQLite (for development) or MySQL (for production)
- Composer

### Backend Setup
```bash
cd backend

# Install PHP dependencies
php ../composer.phar install

# Configure environment
cp .env.example .env
# Edit .env file with your configuration

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed with sample data
php artisan db:seed

# Start development server
php artisan serve --host=0.0.0.0 --port=8000
```

### Frontend Setup
```bash
cd frontend

# Install Node.js dependencies
npm install

# Start development server
npm start
```

### Production Deployment
1. **Database**: Configure MySQL database and update .env
2. **Web Server**: Configure Apache/Nginx to serve Laravel application
3. **Frontend Build**: Run `npm run build` and serve static files
4. **Environment**: Set appropriate environment variables for production
5. **HTTPS**: Configure SSL certificates for secure communication

## Default Credentials

**Admin User:**
- Email: `admin@unickenterprises.com`
- Password: `password123`

## Sample Data

The system comes pre-loaded with:
- 3 sample customers (Home Decor Plus, Furniture World, Laguna Interior Design)
- 3 products (Narra Dining Chair, Mahogany Dining Table, Narra Storage Cabinet)
- 4 raw materials (Narra Wood, Mahogany Wood, Cabinet Hinges, Wood Varnish)
- Complete Bill of Materials relationships
- 2 sample orders with different statuses
- Inventory records for all items

## Key Business Features

### 1. Customer Credit Management
- Credit limits with automatic checking
- Available credit calculation
- Order blocking when credit exceeded
- Credit utilization reporting

### 2. Production Cost Calculation
- Automatic material cost calculation based on BOM
- Labor cost estimation using production time
- Waste factor consideration
- Real-time cost updates when material prices change

### 3. Stock Level Management
- Minimum and maximum stock levels
- Reorder point calculations
- Low stock alerts
- Automated reorder suggestions

### 4. Order Status Tracking
- Complete order lifecycle management
- Status progression: Pending → Confirmed → In Production → Ready → Shipped → Delivered
- Automatic notifications and updates
- Overdue order identification

### 5. Quality Control
- Production batch quality tracking
- Rejection rate monitoring
- Quality notes and inspection records
- Performance metrics and efficiency calculation

## Reporting and Analytics

### Dashboard KPIs
- Total customers and active count
- Product inventory levels
- Order values and counts
- Production efficiency metrics
- Low stock alerts
- Material reorder requirements

### Available Reports
- Customer activity and sales analysis
- Product performance and profitability
- Inventory turnover and valuation
- Production efficiency and quality metrics
- Order fulfillment performance

## Security Features

### Authentication & Authorization
- Token-based authentication using Laravel Sanctum
- Secure password hashing with bcrypt
- CORS configuration for frontend integration
- Protected API routes requiring authentication

### Data Protection
- Input validation on all endpoints
- SQL injection protection through Eloquent ORM
- XSS prevention through proper output encoding
- CSRF protection for state-changing operations

## Future Enhancements

### Planned Features
1. **Advanced Analytics**: Machine learning for demand forecasting
2. **Mobile Application**: Native mobile app for production floor workers
3. **Supplier Integration**: Direct integration with supplier systems
4. **Quality Management**: Enhanced QC workflows and ISO compliance
5. **Accounting Integration**: Direct integration with accounting systems
6. **Barcode/QR Code**: Product and material tracking with scanning
7. **Multi-location**: Support for multiple warehouses and facilities

### Technical Improvements
1. **Performance Optimization**: Database indexing and query optimization
2. **Caching**: Redis integration for improved response times
3. **Background Jobs**: Queue processing for heavy operations
4. **Real-time Updates**: WebSocket integration for live updates
5. **API Documentation**: Automated API documentation with Swagger
6. **Testing Coverage**: Comprehensive unit and integration tests

## Support and Maintenance

### Monitoring
- Application logs in `storage/logs/`
- Database query monitoring
- Performance metrics tracking
- Error reporting and alerting

### Backup Procedures
- Daily database backups
- File system backups for uploads
- Configuration backup
- Recovery testing procedures

### Maintenance Tasks
- Regular dependency updates
- Database optimization
- Log rotation and cleanup
- Security patch applications

---

**Company Information:**
Unick Enterprises Inc.
Woodcraft Furniture Manufacturer
Cabuyao City, Laguna, Philippines

For technical support or feature requests, please contact the development team.