import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Login = () => {
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    const result = await login(formData.email, formData.password);
    
    if (result.success) {
      navigate('/');
    } else {
      setError(result.message || 'Login failed');
    }
    
    setLoading(false);
  };

  // Demo login function
  const handleDemoLogin = async () => {
    setLoading(true);
    setError('');
    
    // For demo purposes, simulate successful login
    const demoUser = {
      id: 1,
      name: 'Demo User',
      email: 'demo@unickenterprises.com',
      role: 'admin'
    };
    
    localStorage.setItem('token', 'demo-token-123');
    localStorage.setItem('user', JSON.stringify(demoUser));
    
    // Simulate API delay
    setTimeout(() => {
      window.location.href = '/';
    }, 1000);
  };

  return (
    <div className="container-fluid" style={{ height: '100vh', background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}>
      <div className="row justify-content-center align-items-center h-100">
        <div className="col-xl-4 col-lg-5 col-md-6">
          <div className="card shadow-lg border-0">
            <div className="card-body p-5">
              {/* Logo and Title */}
              <div className="text-center mb-4">
                <div className="sidebar-brand-icon mb-3">
                  <i className="fas fa-hammer fa-3x text-primary"></i>
                </div>
                <h1 className="h4 text-gray-900 mb-2">Unick Enterprises</h1>
                <p className="text-muted">Order Processing Management System</p>
              </div>

              {/* Error Alert */}
              {error && (
                <div className="alert alert-danger" role="alert">
                  {error}
                </div>
              )}

              {/* Login Form */}
              <form onSubmit={handleSubmit}>
                <div className="form-group mb-3">
                  <label className="form-label">Email Address</label>
                  <input
                    type="email"
                    className="form-control form-control-lg"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    placeholder="Enter email address..."
                    required
                  />
                </div>

                <div className="form-group mb-4">
                  <label className="form-label">Password</label>
                  <input
                    type="password"
                    className="form-control form-control-lg"
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    placeholder="Password"
                    required
                  />
                </div>

                <button
                  type="submit"
                  className="btn btn-primary btn-lg btn-block w-100 mb-3"
                  disabled={loading}
                >
                  {loading ? (
                    <>
                      <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                      Signing In...
                    </>
                  ) : (
                    'Sign In'
                  )}
                </button>
              </form>

              {/* Demo Login */}
              <div className="text-center">
                <hr />
                <p className="text-muted mb-3">For demonstration purposes:</p>
                <button
                  onClick={handleDemoLogin}
                  className="btn btn-outline-success btn-lg w-100"
                  disabled={loading}
                >
                  <i className="fas fa-play me-2"></i>
                  Try Demo Login
                </button>
              </div>

              {/* Footer */}
              <div className="text-center mt-4">
                <small className="text-muted">
                  Woodcraft Furniture Manufacturing System<br />
                  Cabuyao City, Laguna
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;