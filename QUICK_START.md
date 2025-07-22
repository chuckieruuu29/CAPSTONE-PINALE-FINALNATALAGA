# Quick Start Guide - Unick Enterprises Order Management System

## Prerequisites

Ensure you have the following installed:
- PHP 8.4+
- Node.js 16+
- SQLite extension for PHP
- Git

## 1. Start Backend Server

```bash
# Navigate to backend directory
cd backend

# Install SQLite if not already installed (Linux/Ubuntu)
sudo apt-get update && sudo apt-get install -y php-sqlite3 sqlite3

# Install PHP dependencies
php ../composer.phar install

# Configure environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
touch database/database.sqlite

# Run migrations and seed data
php artisan migrate --seed

# Start Laravel development server
php artisan serve --host=0.0.0.0 --port=8000
```

## 2. Start Frontend Server

Open a new terminal window:

```bash
# Navigate to frontend directory
cd frontend

# Install Node.js dependencies
npm install

# Start React development server
npm start
```

## 3. Access the System

### Admin Panel
- **URL**: http://localhost:3000
- **Email**: admin@unickenterprises.com
- **Password**: password123

### Customer Portal
- **URL**: http://localhost:3000/store
- Browse products without authentication

### API Documentation
- **Base URL**: http://localhost:8000/api
- **Routes**: Run `php artisan route:list` to see all available endpoints

## 4. Test the System

### Quick Tests

1. **Login Test**
   ```bash
   curl -X POST http://localhost:8000/api/auth/login \
   -H "Content-Type: application/json" \
   -d '{"email":"admin@unickenterprises.com","password":"password123"}'
   ```

2. **Get Dashboard Stats**
   ```bash
   # First get token from login response, then:
   curl -X GET http://localhost:8000/api/dashboard/stats \
   -H "Authorization: Bearer YOUR_TOKEN_HERE"
   ```

3. **View Sample Data**
   ```bash
   # Check customers
   sqlite3 backend/database/database.sqlite "SELECT name, email FROM customers;"
   
   # Check products
   sqlite3 backend/database/database.sqlite "SELECT name, sku, selling_price FROM products;"
   
   # Check orders
   sqlite3 backend/database/database.sqlite "SELECT order_number, status, total_amount FROM orders;"
   ```

## 5. Sample Data Overview

The system includes:

### Customers (3)
- Home Decor Plus (Manila)
- Furniture World (Quezon City)
- Laguna Interior Design (Sta. Rosa)

### Products (3)
- Narra Dining Chair (₱2,500)
- Mahogany Dining Table (₱8,500)
- Narra Storage Cabinet (₱6,500)

### Raw Materials (4)
- Narra Wood Planks
- Mahogany Wood Planks
- Cabinet Door Hinges
- Wood Varnish

### Orders (2)
- Home Decor Plus: 6 chairs + 1 table (₱24,020 total)
- Furniture World: 2 cabinets (₱15,310 total)

## 6. Key Features to Explore

### Admin Dashboard
1. **Dashboard**: Overview of KPIs, charts, and alerts
2. **Customers**: Customer management with credit tracking
3. **Products**: Product catalog with BOM management
4. **Orders**: Order processing and status tracking
5. **Inventory**: Stock levels and reorder suggestions
6. **Production**: Production planning and tracking
7. **Reports**: Analytics and performance metrics

### Customer Portal
1. **Product Catalog**: Browse available products
2. **Product Details**: View specifications and pricing
3. **Order Tracking**: Track order status (future feature)

## 7. Common Operations

### Create New Customer
1. Go to Customers → Add Customer
2. Fill in required information
3. Set credit limit if needed

### Add New Product
1. Go to Products → Add Product
2. Fill product details
3. Attach raw materials (BOM) via "Materials" tab

### Process Order
1. Go to Orders → Create Order
2. Select customer and add products
3. Confirm order to start production planning

### Update Inventory
1. Go to Inventory
2. Select item to adjust
3. Use "Adjust Stock" to update quantities

## 8. Troubleshooting

### Backend Issues

**Database Connection Error**
```bash
# Recreate database
rm backend/database/database.sqlite
touch backend/database/database.sqlite
php artisan migrate --seed
```

**Permission Issues**
```bash
# Fix storage permissions
chmod -R 755 backend/storage
chmod -R 755 backend/bootstrap/cache
```

**CORS Issues**
- Check if CORS middleware is enabled in `bootstrap/app.php`
- Verify frontend URL in `config/cors.php`

### Frontend Issues

**Module Not Found**
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

**Port Already in Use**
```bash
# Use different port
PORT=3001 npm start
```

**API Connection Issues**
- Check if backend server is running on port 8000
- Verify API base URL in `src/context/AuthContext.js`

## 9. Development Tips

### Database Management
```bash
# View all tables
sqlite3 backend/database/database.sqlite ".tables"

# Reset database
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_new_table
```

### API Testing
```bash
# List all routes
php artisan route:list

# Test with Laravel Tinker
php artisan tinker
# Then try: App\Models\Customer::count()
```

### Frontend Development
```bash
# Add new dependencies
npm install package-name

# Build for production
npm run build
```

## 10. Next Steps

1. **Explore the Documentation**: Read `SYSTEM_DOCUMENTATION.md` for detailed information
2. **Customize Settings**: Update company information in `.env`
3. **Add Real Data**: Replace sample data with actual products and customers
4. **Configure Production**: Set up MySQL database for production use
5. **Implement Features**: Add custom features based on business requirements

## Support

For technical support:
1. Check the full documentation in `SYSTEM_DOCUMENTATION.md`
2. Review Laravel logs in `backend/storage/logs/`
3. Check browser console for frontend errors
4. Verify all services are running on correct ports

---

**Happy coding!** 🚀

The system is now ready for development and testing. Start with the dashboard to explore all available features.