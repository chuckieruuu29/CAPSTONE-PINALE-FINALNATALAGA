import React from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Navbar = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout } = useAuth();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  const isActive = (path) => {
    return location.pathname === path ? 'active' : '';
  };

  return (
    <nav className="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion position-fixed" 
         style={{ width: '250px', height: '100vh', zIndex: 1000 }}>
      
      {/* Sidebar Brand */}
      <Link className="sidebar-brand d-flex align-items-center justify-content-center" to="/">
        <div className="sidebar-brand-icon rotate-n-15">
          <i className="fas fa-hammer"></i>
        </div>
        <div className="sidebar-brand-text mx-3">Unick Enterprises</div>
      </Link>

      {/* Divider */}
      <hr className="sidebar-divider my-0" />

      {/* Nav Item - Dashboard */}
      <li className={`nav-item ${isActive('/')}`}>
        <Link className="nav-link" to="/">
          <i className="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </Link>
      </li>

      {/* Divider */}
      <hr className="sidebar-divider" />

      {/* Heading */}
      <div className="sidebar-heading">
        Order Management
      </div>

      {/* Nav Item - Customers */}
      <li className={`nav-item ${isActive('/customers')}`}>
        <Link className="nav-link" to="/customers">
          <i className="fas fa-fw fa-users"></i>
          <span>Customers</span>
        </Link>
      </li>

      {/* Nav Item - Orders */}
      <li className={`nav-item ${isActive('/orders')}`}>
        <Link className="nav-link" to="/orders">
          <i className="fas fa-fw fa-shopping-cart"></i>
          <span>Orders</span>
        </Link>
      </li>

      {/* Divider */}
      <hr className="sidebar-divider" />

      {/* Heading */}
      <div className="sidebar-heading">
        Inventory & Production
      </div>

      {/* Nav Item - Products */}
      <li className={`nav-item ${isActive('/products')}`}>
        <Link className="nav-link" to="/products">
          <i className="fas fa-fw fa-box"></i>
          <span>Products</span>
        </Link>
      </li>

      {/* Nav Item - Inventory */}
      <li className={`nav-item ${isActive('/inventory')}`}>
        <Link className="nav-link" to="/inventory">
          <i className="fas fa-fw fa-warehouse"></i>
          <span>Inventory (MRP)</span>
        </Link>
      </li>

      {/* Nav Item - Production */}
      <li className={`nav-item ${isActive('/production')}`}>
        <Link className="nav-link" to="/production">
          <i className="fas fa-fw fa-cogs"></i>
          <span>Production</span>
        </Link>
      </li>

      {/* Divider */}
      <hr className="sidebar-divider" />

      {/* Heading */}
      <div className="sidebar-heading">
        Reports & Analytics
      </div>

      {/* Nav Item - Reports */}
      <li className={`nav-item ${isActive('/reports')}`}>
        <Link className="nav-link" to="/reports">
          <i className="fas fa-fw fa-chart-bar"></i>
          <span>Reports</span>
        </Link>
      </li>

      {/* Divider */}
      <hr className="sidebar-divider d-none d-md-block" />

      {/* User Info & Logout */}
      <div className="sidebar-card d-none d-lg-flex mt-auto mb-3">
        <div className="text-center text-white small">
          <div><strong>{user?.name || 'User'}</strong></div>
          <div className="mb-2">{user?.email || ''}</div>
          <button 
            className="btn btn-warning btn-sm"
            onClick={handleLogout}
          >
            <i className="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
            Logout
          </button>
        </div>
      </div>

      {/* Public Store Link */}
      <div className="text-center mb-3">
        <Link 
          to="/store" 
          className="btn btn-outline-light btn-sm"
          target="_blank"
        >
          <i className="fas fa-store fa-sm fa-fw mr-2"></i>
          View Store
        </Link>
      </div>
    </nav>
  );
};

export default Navbar;