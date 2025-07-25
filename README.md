# Unick Enterprises Inc. - Order Processing Management System

A comprehensive web-based system for order processing, inventory management, and production tracking specifically designed for Unick Enterprises Inc., a woodcraft furniture manufacturer in Cabuyao City, Laguna.

## System Overview

This system provides:
- **Inventory Management System** with MRP-based control and predictive analytics
- **Production Tracking System** with real-time monitoring
- **Integrated Order Processing** with customer portal
- **Automated Reporting** with customizable reports

## Tech Stack

### Backend
- **Framework:** Laravel 10
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **Export:** Laravel Excel
- **PDF Generation:** Laravel DOMPDF

### Frontend
- **Framework:** React 18
- **UI Library:** Bootstrap 5
- **HTTP Client:** Axios
- **Routing:** React Router DOM

## Project Structure

```
├── backend/                 # Laravel Backend
│   ├── app/
│   │   ├── Models/          # Eloquent Models
│   │   ├── Http/Controllers/ # API Controllers
│   │   └── Exports/         # Excel Export Classes
│   ├── database/migrations/ # Database Migrations
│   ├── routes/             # API Routes
│   └── config/             # Configuration Files
├── frontend/               # React Frontend
│   ├── src/
│   │   ├── components/     # React Components
│   │   ├── services/       # API Services
│   │   └── assets/         # Static Assets
│   └── public/             # Public Files
└── README.md
```

## Features

### Inventory Management
- Real-time stock monitoring with SKU tracking
- Predictive analytics for material forecasting
- Automated reorder point calculations
- Material usage trend analysis

### Production Tracking
- Daily production output monitoring
- Manufacturing process tracking
- Resource allocation optimization
- Performance metrics and KPIs

### Order Processing
- Customer-friendly online ordering platform
- Real-time order status tracking
- Automated fulfillment workflows
- Customer engagement and notifications

### Reporting System
- Customizable inventory reports
- Production performance analytics
- Order fulfillment tracking
- Multi-format export capabilities

## Installation

### Prerequisites
- PHP 8.1+
- Node.js 16+
- MySQL 8.0+
- Composer

### Backend Setup
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend Setup
```bash
cd frontend
npm install
npm install react react-dom react-router-dom axios bootstrap
npm start
```

## Database Schema

### Core Tables
- `users` - User authentication and roles
- `inventories` - Product and material inventory
- `productions` - Daily production tracking
- `orders` - Customer orders and fulfillment
- `order_items` - Individual order line items

## API Endpoints

### Inventory
- `GET /api/inventory` - List all inventory items
- `POST /api/inventory` - Create new inventory item
- `GET /api/inventory/forecast` - Get inventory forecasts
- `GET /api/inventory/export` - Export inventory data

### Production
- `GET /api/productions` - List production records
- `POST /api/productions` - Record new production
- `GET /api/productions/export` - Export production data

### Orders
- `GET /api/orders` - List orders
- `POST /api/orders` - Create new order
- `GET /api/orders/export` - Export order data

## Company Information

**Unick Enterprises Inc.**
Woodcraft Furniture Manufacturer
Cabuyao City, Laguna, Philippines

This system is specifically designed to address the operational challenges of woodcraft manufacturing, including production tracking, inventory management, and customer order fulfillment.
