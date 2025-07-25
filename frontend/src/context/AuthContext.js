import { createContext, useContext } from 'react';
import axios from 'axios';

// Configure axios defaults
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Add request interceptor to include auth token
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  // Get CSRF cookie before making authenticated requests
  const getCsrfCookie = async () => {
    try {
      await axios.get('/sanctum/csrf-cookie');
    } catch (error) {
      console.error('Failed to get CSRF cookie:', error);
    }
  };

  const login = async (email, password) => {
    try {
      // Get CSRF cookie first
      await getCsrfCookie();
      
      const response = await axios.post('/api/login', {
        email,
        password,
      });

      const { token, user } = response.data;
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));

      return { success: true, user };
    } catch (error) {
      return { success: false, message: error.response?.data?.message || 'Login failed' };
    }
  };

  const register = async (name, email, password, password_confirmation) => {
    try {
      // Get CSRF cookie first
      await getCsrfCookie();
      
      const response = await axios.post('/api/register', {
        name,
        email,
        password,
        password_confirmation
      });

      const { token, user } = response.data;
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));

      return { success: true, user };
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Registration failed',
        errors: error.response?.data?.errors
      };
    }
  };

  const logout = async () => {
    try {
      await axios.post('/api/logout');
    } catch {}
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  };

  return (
    <AuthContext.Provider value={{ login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
