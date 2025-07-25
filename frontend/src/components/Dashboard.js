import React, { useState, useEffect } from 'react';
import { Bar, Pie } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from 'chart.js';
import axios from 'axios';

// Register ChartJS components
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
);

const Dashboard = () => {
  const [stats, setStats] = useState({});
  const [recentOrders, setRecentOrders] = useState([]);
  const [productionOverview, setProductionOverview] = useState([]);
  const [inventoryAlerts, setInventoryAlerts] = useState({
    low_stock_products: [],
    materials_to_reorder: []
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      setLoading(true);
      const [statsRes, ordersRes, productionRes, alertsRes] = await Promise.all([
        axios.get('/api/dashboard/stats'),
        axios.get('/api/dashboard/recent-orders'),
        axios.get('/api/dashboard/production-overview'),
        axios.get('/api/dashboard/inventory-alerts')
      ]);

      setStats(statsRes.data);
      setRecentOrders(ordersRes.data);
      setProductionOverview(productionRes.data);
      setInventoryAlerts(alertsRes.data);
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
      // Set mock data for development
      setStats({
        total_customers: 156,
        total_products: 89,
        total_orders: 234,
        monthly_revenue: 45780,
        orders_this_month: 42,
        production_efficiency: 87.5
      });
      setRecentOrders([
        { id: 1, customer_name: 'John Smith', total: 850, status: 'In Progress', created_at: '2025-01-25' },
        { id: 2, customer_name: 'Sarah Johnson', total: 1200, status: 'Completed', created_at: '2025-01-24' },
        { id: 3, customer_name: 'Mike Wilson', total: 675, status: 'Pending', created_at: '2025-01-23' }
      ]);
      setProductionOverview([
        { product_name: 'Oak Table', quantity_produced: 15, target_quantity: 20 },
        { product_name: 'Pine Chair', quantity_produced: 45, target_quantity: 50 },
        { product_name: 'Walnut Cabinet', quantity_produced: 8, target_quantity: 10 }
      ]);
      setInventoryAlerts({
        low_stock_products: ['Oak Planks', 'Wood Stain', 'Screws'],
        materials_to_reorder: ['Pine Boards', 'Sandpaper', 'Wood Glue']
      });
    } finally {
      setLoading(false);
    }
  };

  // Chart configurations with woodcraft colors
  const chartColors = {
    primary: '#8B4513',
    secondary: '#D2691E', 
    tertiary: '#CD853F',
    accent: '#DAA520',
    success: '#228B22',
    warning: '#FF8C00',
    danger: '#DC143C'
  };

  const salesChartData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [
      {
        label: 'Monthly Revenue',
        data: [32000, 28000, 35000, 41000, 38000, 45000],
        backgroundColor: `${chartColors.primary}CC`,
        borderColor: chartColors.primary,
        borderWidth: 2,
        borderRadius: 4,
      },
    ],
  };

  const productionChartData = {
    labels: productionOverview.map(item => item.product_name),
    datasets: [
      {
        label: 'Produced',
        data: productionOverview.map(item => item.quantity_produced),
        backgroundColor: chartColors.secondary,
        borderColor: chartColors.primary,
        borderWidth: 2,
      },
      {
        label: 'Target',
        data: productionOverview.map(item => item.target_quantity),
        backgroundColor: `${chartColors.tertiary}80`,
        borderColor: chartColors.tertiary,
        borderWidth: 2,
      },
    ],
  };

  const orderStatusData = {
    labels: ['Completed', 'In Progress', 'Pending', 'Cancelled'],
    datasets: [
      {
        data: [65, 25, 8, 2],
        backgroundColor: [
          chartColors.success,
          chartColors.accent,
          chartColors.warning,
          chartColors.danger,
        ],
        borderWidth: 2,
        borderColor: '#fff',
      },
    ],
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'top',
        labels: {
          font: {
            family: 'Merriweather',
            size: 12,
          },
          color: chartColors.primary,
        },
      },
      title: {
        display: false,
      },
    },
    scales: {
      x: {
        ticks: {
          color: chartColors.primary,
          font: {
            family: 'Merriweather',
          },
        },
        grid: {
          color: `${chartColors.tertiary}40`,
        },
      },
      y: {
        ticks: {
          color: chartColors.primary,
          font: {
            family: 'Merriweather',
          },
        },
        grid: {
          color: `${chartColors.tertiary}40`,
        },
      },
    },
  };

  const pieChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          font: {
            family: 'Merriweather',
            size: 12,
          },
          color: chartColors.primary,
          padding: 20,
        },
      },
    },
  };

  if (loading) {
    return (
      <div className="woodcraft-loading">
        <div className="loading-spinner">
          <div className="spinner-ring"></div>
          <p>Loading Dashboard...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="dashboard-container">
      {/* Header */}
      <div className="page-header-wood">
        <h1>🪵 WoodCraft Dashboard</h1>
        <p>Master your craft, manage your business</p>
      </div>

      {/* Stats Cards */}
      <div className="row mb-4">
        <div className="col-md-3 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-body text-center">
              <div className="stat-icon">👥</div>
              <h3 className="stat-number">{stats.total_customers || 0}</h3>
              <p className="stat-label">Total Customers</p>
            </div>
          </div>
        </div>
        
        <div className="col-md-3 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-body text-center">
              <div className="stat-icon">🪑</div>
              <h3 className="stat-number">{stats.total_products || 0}</h3>
              <p className="stat-label">Products</p>
            </div>
          </div>
        </div>
        
        <div className="col-md-3 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-body text-center">
              <div className="stat-icon">📦</div>
              <h3 className="stat-number">{stats.total_orders || 0}</h3>
              <p className="stat-label">Total Orders</p>
            </div>
          </div>
        </div>
        
        <div className="col-md-3 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-body text-center">
              <div className="stat-icon">💰</div>
              <h3 className="stat-number">${(stats.monthly_revenue || 0).toLocaleString()}</h3>
              <p className="stat-label">Monthly Revenue</p>
            </div>
          </div>
        </div>
      </div>

      {/* Charts Row */}
      <div className="row mb-4">
        <div className="col-lg-8 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-header-wood">
              <h5 className="mb-0">📈 Revenue Trends</h5>
            </div>
            <div className="card-body">
              <div style={{ height: '300px' }}>
                <Bar data={salesChartData} options={chartOptions} />
              </div>
            </div>
          </div>
        </div>
        
        <div className="col-lg-4 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-header-wood">
              <h5 className="mb-0">📊 Order Status</h5>
            </div>
            <div className="card-body">
              <div style={{ height: '300px' }}>
                <Pie data={orderStatusData} options={pieChartOptions} />
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Production Overview */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="woodcraft-card">
            <div className="card-header-wood">
              <h5 className="mb-0">🏭 Production Overview</h5>
            </div>
            <div className="card-body">
              <div style={{ height: '350px' }}>
                <Bar data={productionChartData} options={chartOptions} />
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Recent Orders and Alerts */}
      <div className="row">
        <div className="col-lg-8 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-header-wood">
              <h5 className="mb-0">📋 Recent Orders</h5>
            </div>
            <div className="card-body">
              <div className="table-responsive">
                <table className="table table-wood">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    {recentOrders.map((order) => (
                      <tr key={order.id}>
                        <td>{order.customer_name}</td>
                        <td>${order.total.toLocaleString()}</td>
                        <td>
                          <span className={`badge status-${order.status.toLowerCase().replace(' ', '-')}`}>
                            {order.status}
                          </span>
                        </td>
                        <td>{new Date(order.created_at).toLocaleDateString()}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        
        <div className="col-lg-4 mb-3">
          <div className="woodcraft-card h-100">
            <div className="card-header-wood">
              <h5 className="mb-0">⚠️ Inventory Alerts</h5>
            </div>
            <div className="card-body">
              <div className="mb-3">
                <h6 className="text-warning mb-2">Low Stock Items:</h6>
                {inventoryAlerts.low_stock_products.length > 0 ? (
                  <ul className="list-unstyled">
                    {inventoryAlerts.low_stock_products.map((item, index) => (
                      <li key={index} className="mb-1">
                        <span className="badge bg-warning text-dark">{item}</span>
                      </li>
                    ))}
                  </ul>
                ) : (
                  <p className="text-muted">No low stock items</p>
                )}
              </div>
              
              <div>
                <h6 className="text-danger mb-2">Items to Reorder:</h6>
                {inventoryAlerts.materials_to_reorder.length > 0 ? (
                  <ul className="list-unstyled">
                    {inventoryAlerts.materials_to_reorder.map((item, index) => (
                      <li key={index} className="mb-1">
                        <span className="badge bg-danger">{item}</span>
                      </li>
                    ))}
                  </ul>
                ) : (
                  <p className="text-muted">No items to reorder</p>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;