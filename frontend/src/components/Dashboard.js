import React, { useState, useEffect } from 'react';
import { Bar, Line, Pie } from 'react-chartjs-2';
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
    } finally {
      setLoading(false);
    }
  };

  const getStatusBadgeClass = (status) => {
    const statusClasses = {
      pending: 'bg-warning',
      confirmed: 'bg-info',
      in_production: 'bg-primary',
      completed: 'bg-success',
      shipped: 'bg-success',
      delivered: 'bg-success',
      cancelled: 'bg-danger',
      on_hold: 'bg-secondary'
    };
    return statusClasses[status] || 'bg-secondary';
  };

  // Chart configurations
  const salesChartData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [
      {
        label: 'Sales ($)',
        data: [15000, 23000, 18000, 32000, 28000, 35000],
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
      },
    ],
  };

  const productionChartData = {
    labels: ['Tables', 'Chairs', 'Cabinets', 'Shelves', 'Decor'],
    datasets: [
      {
        label: 'Units Produced',
        data: [12, 19, 8, 5, 15],
        backgroundColor: [
          'rgba(255, 99, 132, 0.6)',
          'rgba(54, 162, 235, 0.6)',
          'rgba(255, 205, 86, 0.6)',
          'rgba(75, 192, 192, 0.6)',
          'rgba(153, 102, 255, 0.6)',
        ],
      },
    ],
  };

  const inventoryStatusData = {
    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
    datasets: [
      {
        data: [65, 25, 10],
        backgroundColor: [
          'rgba(75, 192, 192, 0.6)',
          'rgba(255, 205, 86, 0.6)',
          'rgba(255, 99, 132, 0.6)',
        ],
      },
    ],
  };

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ height: '70vh' }}>
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Loading dashboard...</span>
        </div>
      </div>
    );
  }

  return (
    <div className="container-fluid">
      {/* Header */}
      <div className="row mb-4">
        <div className="col-12">
          <h1 className="h3 mb-0 text-gray-800">Unick Enterprises Dashboard</h1>
          <p className="text-muted">Welcome to your woodcraft management system</p>
        </div>
      </div>

      {/* KPI Cards */}
      <div className="row mb-4">
        <div className="col-xl-3 col-md-6 mb-4">
          <div className="card border-left-primary shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Total Customers
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">
                    {stats.total_customers || 0}
                  </div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-users fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-xl-3 col-md-6 mb-4">
          <div className="card border-left-success shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Orders Value
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">
                    ${(stats.total_orders_value || 0).toLocaleString()}
                  </div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-dollar-sign fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-xl-3 col-md-6 mb-4">
          <div className="card border-left-info shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Pending Orders
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">
                    {stats.pending_orders || 0}
                  </div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-xl-3 col-md-6 mb-4">
          <div className="card border-left-warning shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Low Stock Items
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">
                    {stats.low_stock_products || 0}
                  </div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Charts Row */}
      <div className="row mb-4">
        <div className="col-xl-8 col-lg-7">
          <div className="card shadow mb-4">
            <div className="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 className="m-0 font-weight-bold text-primary">Sales Overview</h6>
            </div>
            <div className="card-body">
              <div style={{ height: '300px' }}>
                <Line 
                  data={salesChartData} 
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        position: 'top',
                      },
                      title: {
                        display: true,
                        text: 'Monthly Sales Performance'
                      }
                    }
                  }}
                />
              </div>
            </div>
          </div>
        </div>

        <div className="col-xl-4 col-lg-5">
          <div className="card shadow mb-4">
            <div className="card-header py-3">
              <h6 className="m-0 font-weight-bold text-primary">Inventory Status</h6>
            </div>
            <div className="card-body">
              <div style={{ height: '300px' }}>
                <Pie 
                  data={inventoryStatusData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        position: 'bottom',
                      }
                    }
                  }}
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Recent Orders and Alerts */}
      <div className="row">
        <div className="col-lg-6 mb-4">
          <div className="card shadow">
            <div className="card-header py-3">
              <h6 className="m-0 font-weight-bold text-primary">Recent Orders</h6>
            </div>
            <div className="card-body">
              <div className="table-responsive">
                <table className="table table-sm">
                  <thead>
                    <tr>
                      <th>Order #</th>
                      <th>Customer</th>
                      <th>Status</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    {recentOrders.slice(0, 5).map((order) => (
                      <tr key={order.id}>
                        <td>{order.order_number}</td>
                        <td>{order.customer?.name || 'N/A'}</td>
                        <td>
                          <span className={`badge ${getStatusBadgeClass(order.status)}`}>
                            {order.status?.replace('_', ' ').toUpperCase()}
                          </span>
                        </td>
                        <td>${order.total_amount?.toLocaleString()}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-6 mb-4">
          <div className="card shadow">
            <div className="card-header py-3">
              <h6 className="m-0 font-weight-bold text-warning">Inventory Alerts</h6>
            </div>
            <div className="card-body">
              {inventoryAlerts.low_stock_products.length > 0 && (
                <div className="mb-3">
                  <h6 className="text-warning">Low Stock Products:</h6>
                  {inventoryAlerts.low_stock_products.slice(0, 3).map((product) => (
                    <div key={product.id} className="alert alert-warning py-2 mb-1">
                      <small>
                        <strong>{product.name}</strong> - Only {product.inventory?.current_stock || 0} left
                      </small>
                    </div>
                  ))}
                </div>
              )}
              
              {inventoryAlerts.materials_to_reorder.length > 0 && (
                <div>
                  <h6 className="text-danger">Materials to Reorder:</h6>
                  {inventoryAlerts.materials_to_reorder.slice(0, 3).map((material) => (
                    <div key={material.id} className="alert alert-danger py-2 mb-1">
                      <small>
                        <strong>{material.name}</strong> - Stock: {material.current_stock} {material.unit_of_measure}
                      </small>
                    </div>
                  ))}
                </div>
              )}
              
              {inventoryAlerts.low_stock_products.length === 0 && 
               inventoryAlerts.materials_to_reorder.length === 0 && (
                <div className="text-success">
                  <i className="fas fa-check-circle me-2"></i>
                  All inventory levels are healthy!
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Production Overview */}
      <div className="row">
        <div className="col-12">
          <div className="card shadow">
            <div className="card-header py-3">
              <h6 className="m-0 font-weight-bold text-primary">Production by Category</h6>
            </div>
            <div className="card-body">
              <div style={{ height: '300px' }}>
                <Bar 
                  data={productionChartData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        display: false,
                      },
                      title: {
                        display: true,
                        text: 'Production Output by Product Category'
                      }
                    }
                  }}
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;