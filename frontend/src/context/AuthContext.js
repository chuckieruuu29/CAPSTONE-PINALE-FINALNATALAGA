import { createContext, useContext, useState, useEffect } from 'react';
import axios from 'axios';

// Configure axios defaults
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.withCredentials = true; // Required for Sanctum SPA cookies

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Check if user is already authenticated on app start
    checkAuthStatus();
  }, []);

  const checkAuthStatus = async () => {
    try {
      const response = await axios.get('/api/user');
      setUser(response.data);
    } catch (error) {
      // Not authenticated
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  const login = async (email, password) => {
    try {
      setLoading(true);

      // Get CSRF cookie first (from backend full URL)
      await axios.get('http://localhost:8000/sanctum/csrf-cookie');

      // Session-based login
      const response = await axios.post('/api/login', {
        email,
        password,
      });

      if (response.data.success) {
        const { user } = response.data;
        setUser(user);
        return { success: true, user, message: 'Login successful' };
      } else {
        return { success: false, message: response.data.message || 'Login failed' };
      }
    } catch (error) {
      let message = 'Login failed. Please try again.';
      if (error.response?.data?.message) message = error.response.data.message;
      else if (error.response?.data?.errors) message = Object.values(error.response.data.errors).flat().join(', ');
      return { success: false, message };
    } finally {
      setLoading(false);
    }
  };

  const register = async (name, email, password, passwordConfirmation) => {
    try {
      setLoading(true);

      // Get CSRF cookie first
      await axios.get('http://localhost:8000/sanctum/csrf-cookie');

      const response = await axios.post('/api/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });

      if (response.data.success) {
        const { user } = response.data;
        setUser(user);
        return { success: true, user, message: 'Registration successful' };
      } else {
        return { success: false, message: response.data.message || 'Registration failed' };
      }
    } catch (error) {
      let message = 'Registration failed. Please try again.';
      if (error.response?.data?.message) message = error.response.data.message;
      else if (error.response?.data?.errors) message = Object.values(error.response.data.errors).flat().join(', ');
      return { success: false, message };
    } finally {
      setLoading(false);
    }
  };

  const logout = async () => {
    try {
      await axios.post('/api/logout');
    } catch (error) {
      // ignore
    } finally {
      setUser(null);
    }
  };

  const value = { user, login, register, logout, loading };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
