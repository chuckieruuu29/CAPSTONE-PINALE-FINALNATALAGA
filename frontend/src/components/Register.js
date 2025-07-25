import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  });

  const [error, setError] = useState('');
  const [validationErrors, setValidationErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const { register } = useAuth();
  const navigate = useNavigate();

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
    // Clear specific field error when user starts typing
    if (validationErrors[e.target.name]) {
      setValidationErrors(prev => ({ ...prev, [e.target.name]: undefined }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setValidationErrors({});

    const result = await register(
      formData.name,
      formData.email,
      formData.password,
      formData.password_confirmation
    );

    if (result.success) {
      navigate('/');
    } else {
      setError(result.message);
      if (result.errors) {
        setValidationErrors(result.errors);
      }
    }

    setLoading(false);
  };

  return (
    <div 
      className="container-fluid" 
      style={{ 
        height: '100vh', 
        background: 'linear-gradient(135deg, #8B4513 0%, #D2691E 50%, #CD853F 100%)',
        backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23654321' fill-opacity='0.1'%3E%3Cpath d='M30 30c0 16.569-13.431 30-30 30v-60c16.569 0 30 13.431 30 30z'/%3E%3C/g%3E%3C/svg%3E")`,
        fontFamily: "'Georgia', 'Times New Roman', serif"
      }}
    >
      <div className="row justify-content-center align-items-center h-100">
        <div className="col-xl-4 col-lg-5 col-md-6">
          <div 
            className="card shadow-lg border-0"
            style={{
              backgroundColor: "#F5F5DC",
              border: "3px solid #8B4513",
              borderRadius: "15px",
              boxShadow: "0 8px 32px rgba(139, 69, 19, 0.3)"
            }}
          >
            <div className="card-body p-5">
              <div className="text-center mb-4">
                <div 
                  style={{
                    fontSize: "3rem",
                    color: "#8B4513",
                    marginBottom: "10px"
                  }}
                >
                  🛠️
                </div>
                <h2 
                  className="h4 text-gray-900 mb-2"
                  style={{ 
                    color: "#654321", 
                    fontWeight: "bold",
                    textShadow: "1px 1px 2px rgba(139, 69, 19, 0.3)",
                    fontFamily: "'Georgia', serif"
                  }}
                >
                  Register 
                </h2>
              </div>

              {error && (
                <div 
                  className="alert alert-danger"
                  style={{
                    backgroundColor: "#F5DEB3",
                    border: "2px solid #CD853F",
                    color: "#8B4513"
                  }}
                >
                  {error}
                </div>
              )}

              <form onSubmit={handleSubmit}>
                <div className="form-group mb-3">
                  <label 
                    className="form-label"
                    style={{ color: "#654321", fontWeight: "bold" }}
                  >
                    Name
                  </label>
                  <input
                    type="text"
                    className="form-control"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    required
                    style={{
                      border: validationErrors.name ? "2px solid #DC3545" : "2px solid #CD853F",
                      borderRadius: "8px",
                      backgroundColor: "#FFF8DC",
                      color: "#654321"
                    }}
                  />
                  {validationErrors.name && (
                    <div className="text-danger mt-1" style={{ fontSize: "0.875rem" }}>
                      {validationErrors.name[0]}
                    </div>
                  )}
                </div>

                <div className="form-group mb-3">
                  <label 
                    className="form-label"
                    style={{ color: "#654321", fontWeight: "bold" }}
                  >
                    Email Address
                  </label>
                  <input
                    type="email"
                    className="form-control"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    required
                    style={{
                      border: validationErrors.email ? "2px solid #DC3545" : "2px solid #CD853F",
                      borderRadius: "8px",
                      backgroundColor: "#FFF8DC",
                      color: "#654321"
                    }}
                  />
                  {validationErrors.email && (
                    <div className="text-danger mt-1" style={{ fontSize: "0.875rem" }}>
                      {validationErrors.email[0]}
                    </div>
                  )}
                </div>

                <div className="form-group mb-3">
                  <label 
                    className="form-label"
                    style={{ color: "#654321", fontWeight: "bold" }}
                  >
                    Password
                  </label>
                  <input
                    type="password"
                    className="form-control"
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    required
                    style={{
                      border: validationErrors.password ? "2px solid #DC3545" : "2px solid #CD853F",
                      borderRadius: "8px",
                      backgroundColor: "#FFF8DC",
                      color: "#654321"
                    }}
                  />
                  {validationErrors.password && (
                    <div className="text-danger mt-1" style={{ fontSize: "0.875rem" }}>
                      {validationErrors.password[0]}
                    </div>
                  )}
                </div>

                <div className="form-group mb-4">
                  <label 
                    className="form-label"
                    style={{ color: "#654321", fontWeight: "bold" }}
                  >
                    Confirm Password
                  </label>
                  <input
                    type="password"
                    className="form-control"
                    name="password_confirmation"
                    value={formData.password_confirmation}
                    onChange={handleChange}
                    required
                    style={{
                      border: "2px solid #CD853F",
                      borderRadius: "8px",
                      backgroundColor: "#FFF8DC",
                      color: "#654321"
                    }}
                  />
                </div>

                <button
                  type="submit"
                  className="btn btn-lg w-100"
                  disabled={loading}
                  style={{
                    backgroundColor: "#8B4513",
                    border: "2px solid #654321",
                    color: "#F5F5DC",
                    fontWeight: "bold",
                    borderRadius: "8px",
                    padding: "12px",
                    fontSize: "1.1rem",
                    boxShadow: "0 4px 8px rgba(139, 69, 19, 0.3)"
                  }}
                  onMouseOver={(e) => e.target.style.backgroundColor = "#654321"}
                  onMouseOut={(e) => e.target.style.backgroundColor = "#8B4513"}
                >
                  {loading ? (
                    <>
                      <span className="spinner-border spinner-border-sm me-2" role="status" />
                      Registering...
                    </>
                  ) : (
                    'Register'
                  )}
                </button>
              </form>

              <div className="text-center mt-3">
                <a 
                  href="/login"
                  style={{ 
                    color: "#8B4513",
                    textDecoration: "none",
                    fontWeight: "bold"
                  }}
                  onMouseOver={(e) => e.target.style.color = "#654321"}
                  onMouseOut={(e) => e.target.style.color = "#8B4513"}
                >
                  May account kana? Enter here
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Register;
