import React, { createContext, useContext, useEffect, useState, useCallback } from 'react';
import axios from 'axios';

// Global axios defaults
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

const AuthContext = createContext(null);

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  const getCsrfCookie = useCallback(() => axios.get('/sanctum/csrf-cookie'), []);

  const fetchUser = useCallback(async () => {
    try {
      const res = await axios.get('/api/user');
      setUser(res.data);
    } catch (e) {
      setUser(null);
    }
  }, []);

  const login = useCallback(async (email, password) => {
    try {
      await getCsrfCookie();
      await axios.post('/login', { email, password });
      const res = await axios.get('/api/user');
      setUser(res.data);
      return { success: true, user: res.data };
    } catch (error) {
      const message = error?.response?.data?.message || 'Invalid credentials';
      return { success: false, message };
    }
  }, [getCsrfCookie]);

  const register = useCallback(async (name, email, password, password_confirmation) => {
    try {
      await getCsrfCookie();
      await axios.post('/register', { name, email, password, password_confirmation });
      await fetchUser();
      return { success: true };
    } catch (error) {
      const data = error?.response?.data;
      return { success: false, message: data?.message || 'Registration failed', errors: data?.errors };
    }
  }, [fetchUser, getCsrfCookie]);

  const logout = useCallback(async () => {
    try {
      await axios.post('/logout');
    } finally {
      setUser(null);
    }
  }, []);

  useEffect(() => {
    (async () => {
      try {
        await fetchUser();
      } finally {
        setLoading(false);
      }
    })();
  }, [fetchUser]);

  const value = { user, loading, login, register, logout };
  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export default axios;
