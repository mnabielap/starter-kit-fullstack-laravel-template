/**
 * API Client & Auth Handler for Laravel Velzon
 */
const API = {
    // API Base URL (e.g., http://localhost:8000/api/v1)
    baseUrl: (typeof window.APP_URL !== 'undefined') 
             ? window.APP_URL + '/api' 
             : window.location.origin + '/api',

    // Web Base URL (e.g., http://localhost:8000)
    webUrl: (typeof window.APP_URL !== 'undefined')
             ? window.APP_URL
             : window.location.origin,

    async fetch(endpoint, options = {}) {
        const cleanEndpoint = endpoint.startsWith('/') ? endpoint.substring(1) : endpoint;
        const fullUrl = `${this.baseUrl}/${cleanEndpoint}`; 
        
        const token = localStorage.getItem('accessToken');

        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken, 
            ...options.headers
        };

        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const config = {
            ...options,
            headers
        };

        try {
            const response = await fetch(fullUrl, config);
            
            if (response.status === 401) {
                if (!window.location.pathname.includes('/login') && !window.location.pathname.includes('/register')) {
                    this.logout(); 
                }
            }

            return response;
        } catch (error) {
            console.error("API Error:", error);
            throw error;
        }
    },

    saveTokens(data) {
        if (data.tokens && data.tokens.access) {
            localStorage.setItem('accessToken', data.tokens.access.token);
            
            if (data.user) {
                localStorage.setItem('userData', JSON.stringify(data.user));
            }
        }
    },

    logout() {
        localStorage.removeItem('accessToken');
        localStorage.removeItem('userData');
        window.location.href = `${this.webUrl}/login`;
    },

    getUser() {
        const userData = localStorage.getItem('userData');
        if (userData) {
            try {
                return JSON.parse(userData);
            } catch (e) {
                return null;
            }
        }
        return null;
    }
};