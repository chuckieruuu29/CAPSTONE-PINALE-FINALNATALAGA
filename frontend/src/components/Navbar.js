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

  const sidebarStyle = {
    width: '250px',
    height: '100vh',
    zIndex: 1000,
    background: 'linear-gradient(180deg, #8B4513 0%, #654321 100%)',
    backgroundImage: `url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23CD853F' fill-opacity='0.1'%3E%3Cpath d='M15 15c0 8.284-6.716 15-15 15v-30c8.284 0 15 6.716 15 15z'/%3E%3C/g%3E%3C/svg%3E")`,
    borderRight: '3px solid #CD853F',
    boxShadow: '4px 0 12px rgba(139, 69, 19, 0.3)',
    fontFamily: "'Merriweather', serif"
  };

  const linkStyle = {
    color: '#F5F5DC',
    textDecoration: 'none',
    padding: '12px 20px',
    display: 'flex',
    alignItems: 'center',
    borderRadius: '8px',
    margin: '4px 12px',
    transition: 'all 0.3s ease',
    fontFamily: "'Crimson Text', serif",
    fontWeight: '500'
  };

  const activeLinkStyle = {
    ...linkStyle,
    backgroundColor: 'rgba(205, 133, 63, 0.3)',
    borderLeft: '4px solid #DAA520',
    color: '#DAA520'
  };

  const headingStyle = {
    color: '#CD853F',
    fontSize: '0.8rem',
    fontWeight: 'bold',
    textTransform: 'uppercase',
    letterSpacing: '1px',
    padding: '12px 20px 8px',
    fontFamily: "'Crimson Text', serif"
  };

  return (
    <nav className="navbar-nav sidebar sidebar-dark accordion position-fixed" style={sidebarStyle}>
      
      {/* Sidebar Brand */}
      <Link 
        className="sidebar-brand d-flex align-items-center justify-content-center" 
        to="/"
        style={{
          padding: '20px',
          textDecoration: 'none',
          borderBottom: '2px solid #CD853F',
          marginBottom: '10px'
        }}
      >
        <div className="sidebar-brand-icon" style={{ marginRight: '12px', fontSize: '1.8rem' }}>
          🪵
        </div>
        <div 
          className="sidebar-brand-text"
          style={{
            color: '#F5F5DC',
            fontFamily: "'Crimson Text', serif",
            fontWeight: 'bold',
            fontSize: '1.3rem',
            textShadow: '1px 1px 2px rgba(0,0,0,0.3)'
          }}
        >
          WoodCraft Guild
        </div>
      </Link>

      {/* Nav Item - Dashboard */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/"
          style={isActive('/') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-hammer" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Workshop Overview</span>
        </Link>
      </li>

      {/* Divider */}
      <hr style={{ border: '1px solid #CD853F', margin: '15px 20px' }} />

      {/* Heading */}
      <div style={headingStyle}>
        🛒 Order Management
      </div>

      {/* Nav Item - Customers */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/customers"
          style={isActive('/customers') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/customers')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/customers')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-users" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Guild Members</span>
        </Link>
      </li>

      {/* Nav Item - Orders */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/orders"
          style={isActive('/orders') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/orders')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/orders')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-scroll" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Craft Orders</span>
        </Link>
      </li>

      {/* Divider */}
      <hr style={{ border: '1px solid #CD853F', margin: '15px 20px' }} />

      {/* Heading */}
      <div style={headingStyle}>
        🏭 Craft & Materials
      </div>

      {/* Nav Item - Products */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/products"
          style={isActive('/products') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/products')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/products')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-hammer" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Crafted Goods</span>
        </Link>
      </li>

      {/* Nav Item - Inventory */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/inventory"
          style={isActive('/inventory') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/inventory')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/inventory')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-tree" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Wood & Materials</span>
        </Link>
      </li>

      {/* Nav Item - Production */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/production"
          style={isActive('/production') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/production')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/production')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-tools" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Crafting Schedule</span>
        </Link>
      </li>

      {/* Divider */}
      <hr style={{ border: '1px solid #CD853F', margin: '15px 20px' }} />

      {/* Heading */}
      <div style={headingStyle}>
        📊 Guild Analytics
      </div>

      {/* Nav Item - Reports */}
      <li className="nav-item">
        <Link 
          className="nav-link" 
          to="/reports"
          style={isActive('/reports') ? activeLinkStyle : linkStyle}
          onMouseOver={(e) => {
            if (!isActive('/reports')) {
              e.target.style.backgroundColor = 'rgba(205, 133, 63, 0.2)';
              e.target.style.transform = 'translateX(4px)';
            }
          }}
          onMouseOut={(e) => {
            if (!isActive('/reports')) {
              e.target.style.backgroundColor = 'transparent';
              e.target.style.transform = 'translateX(0)';
            }
          }}
        >
          <i className="fas fa-fw fa-chart-line" style={{ marginRight: '12px', fontSize: '1.1rem' }}></i>
          <span>Guild Reports</span>
        </Link>
      </li>

      {/* User Info & Logout */}
      <div 
        className="sidebar-card d-none d-lg-flex mt-auto mb-3"
        style={{
          backgroundColor: 'rgba(205, 133, 63, 0.2)',
          margin: '20px 12px 20px',
          padding: '16px',
          borderRadius: '12px',
          border: '2px solid #CD853F'
        }}
      >
        <div className="text-center text-white small">
          <div style={{ fontSize: '1.2rem', marginBottom: '4px' }}>👨‍🎨</div>
          <div style={{ fontWeight: 'bold', color: '#DAA520', fontSize: '0.9rem' }}>
            {user?.name || 'Craftsman'}
          </div>
          <div style={{ fontSize: '0.8rem', color: '#F5F5DC', marginBottom: '12px' }}>
            {user?.email || 'Master'}
          </div>
          <button 
            className="btn btn-sm"
            onClick={handleLogout}
            style={{
              backgroundColor: '#CD853F',
              border: '2px solid #8B4513',
              color: '#F5F5DC',
              fontWeight: 'bold',
              borderRadius: '6px',
              padding: '6px 12px',
              fontSize: '0.8rem',
              transition: 'all 0.3s ease'
            }}
            onMouseOver={(e) => {
              e.target.style.backgroundColor = '#8B4513';
              e.target.style.transform = 'translateY(-1px)';
            }}
            onMouseOut={(e) => {
              e.target.style.backgroundColor = '#CD853F';
              e.target.style.transform = 'translateY(0)';
            }}
          >
            <i className="fas fa-sign-out-alt fa-sm fa-fw" style={{ marginRight: '6px' }}></i>
            Leave Guild
          </button>
        </div>
      </div>

      {/* Public Store Link */}
      <div className="text-center mb-3">
        <Link 
          to="/store" 
          className="btn btn-sm"
          target="_blank"
          style={{
            backgroundColor: 'transparent',
            border: '2px solid #228B22',
            color: '#228B22',
            fontWeight: 'bold',
            borderRadius: '6px',
            padding: '8px 16px',
            textDecoration: 'none',
            fontSize: '0.85rem',
            transition: 'all 0.3s ease'
          }}
          onMouseOver={(e) => {
            e.target.style.backgroundColor = '#228B22';
            e.target.style.color = '#F5F5DC';
          }}
          onMouseOut={(e) => {
            e.target.style.backgroundColor = 'transparent';
            e.target.style.color = '#228B22';
          }}
        >
          <i className="fas fa-store fa-sm fa-fw" style={{ marginRight: '6px' }}></i>
          View Storefront
        </Link>
      </div>
    </nav>
  );
};

export default Navbar;