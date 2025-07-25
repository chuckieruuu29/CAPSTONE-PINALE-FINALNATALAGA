import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const Login = () => {
  const navigate = useNavigate();
  const { login } = useAuth();
  const [formData, setFormData] = useState({ email: "", password: "" });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      const result = await login(formData.email, formData.password);
      
      if (result.success) {
        const { user } = result;
        
        // Redirect based on role
        if (user.role === "admin") {
          navigate("/admin/dashboard");
        } else {
          navigate("/customer/dashboard");
        }
      } else {
        setError(result.message || "Invalid credentials. Please try again.");
      }
    } catch (err) {
      console.error(err);
      setError("Login failed. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  const handleDemoLogin = () => {
    setFormData({
      email: "demo@example.com",
      password: "password",
    });
  };

  return (
    <div 
      className="container-fluid d-flex justify-content-center align-items-center vh-100"
      style={{
        background: 'linear-gradient(135deg, #8B4513 0%, #D2691E 50%, #CD853F 100%)',
        backgroundImage: `url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23654321' fill-opacity='0.1'%3E%3Cpath d='M20 20c0 11.046-8.954 20-20 20v-40c11.046 0 20 8.954 20 20z'/%3E%3C/g%3E%3C/svg%3E")`,
        fontFamily: "'Georgia', 'Times New Roman', serif"
      }}
    >
      <div 
        className="card shadow-lg p-4" 
        style={{ 
          width: "100%", 
          maxWidth: "420px",
          backgroundColor: "#F5F5DC",
          border: "3px solid #8B4513",
          borderRadius: "15px",
          boxShadow: "0 8px 32px rgba(139, 69, 19, 0.3)"
        }}
      >
        <div className="text-center mb-4">
          <div 
            style={{
              fontSize: "3rem",
              color: "#8B4513",
              marginBottom: "10px"
            }}
          >
            🪵
          </div>
          <h2 
            className="text-center mb-4" 
            style={{ 
              color: "#654321", 
              fontWeight: "bold",
              textShadow: "1px 1px 2px rgba(139, 69, 19, 0.3)",
              fontFamily: "'Georgia', serif"
            }}
          >
            WoodCraft Login
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
          <div className="mb-3">
            <label style={{ color: "#654321", fontWeight: "bold" }}>Email</label>
            <input
              type="email"
              name="email"
              className="form-control"
              value={formData.email}
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
          <div className="mb-3">
            <label style={{ color: "#654321", fontWeight: "bold" }}>Password</label>
            <input
              type="password"
              name="password"
              className="form-control"
              value={formData.password}
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
            className="btn w-100" 
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
            {loading ? "Crafting Access..." : "Enter Workshop"}
          </button>
        </form>

        {/* Demo Login and Register */}
        <div className="text-center mt-4">
          <hr style={{ border: "1px solid #CD853F" }} />
          <p className="text-muted mb-3" style={{ color: "#8B4513" }}>
            For demonstration purposes:
          </p>
          <button
            onClick={handleDemoLogin}
            className="btn btn-outline-success btn-lg w-100 mb-3"
            disabled={loading}
            style={{
              border: "2px solid #228B22",
              color: "#228B22",
              backgroundColor: "transparent",
              borderRadius: "8px"
            }}
          >
            <i className="fas fa-play me-2"></i>
            Try Demo Workshop
          </button>

          <p className="text-muted" style={{ color: "#8B4513" }}>
            New to the craft?
          </p>
          <button
            onClick={() => navigate("/register")}
            className="btn btn-lg w-100"
            style={{
              backgroundColor: "#CD853F",
              border: "2px solid #8B4513",
              color: "#F5F5DC",
              fontWeight: "bold",
              borderRadius: "8px"
            }}
            onMouseOver={(e) => e.target.style.backgroundColor = "#DAA520"}
            onMouseOut={(e) => e.target.style.backgroundColor = "#CD853F"}
          >
            <i className="fas fa-user-plus me-2"></i>
            Join the Guild
          </button>
        </div>
      </div>
    </div>
  );
};

export default Login;
