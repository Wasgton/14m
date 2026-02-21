import axios from 'axios';

// Create a custom axios instance
const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
});

// Request interceptor to add logic if needed (e.g., auth tokens)
api.interceptors.request.use(
    (config) => {
        // You can read tokens from localStorage here if using Sanctum token auth instead of session auth
        // const token = localStorage.getItem('auth_token');
        // if (token) {
        //     config.headers.Authorization = `Bearer ${token}`;
        // }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor to handle global errors (like 401 or 422)
api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            // Handle specific status codes
            if (error.response.status === 401) {
                // Handle unauthorized (redirect to login or clear auth state)
                if (window.location.pathname !== '/login') {
                    window.location.href = '/login';
                }
            } else if (error.response.status === 419) {
                // CSRF Token mismatch
                window.location.reload();
            }
        }
        return Promise.reject(error);
    }
);

export default api;
