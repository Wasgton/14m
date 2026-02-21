import { ref, readonly } from 'vue';
import api from '../services/api';
import { resetAuthFetch } from '../router';

const user = ref<any>(null);
const permissions = ref<string[]>([]);
const isAuthenticated = ref(false);
const isLoading = ref(true);

export function useAuth() {

    // Check if the user is authenticated directly querying the API (useful on full page reloads)
    const fetchUser = async () => {
        isLoading.value = true;
        try {
            const response = await api.get('/me');
            user.value = response.data.user;
            permissions.value = response.data.permissions || [];
            isAuthenticated.value = true;
        } catch (error) {
            user.value = null;
            permissions.value = [];
            isAuthenticated.value = false;
        } finally {
            isLoading.value = false;
        }
    };

    const login = async (credentials: Record<string, string>) => {
        try {
            // Preflight CSRF cookie request required for Sanctum SPA auth
            await api.get('/sanctum/csrf-cookie', { baseURL: '/' }); // Important: getting it from the root, not /api

            const response = await api.post('/login', credentials);

            user.value = response.data.user;
            permissions.value = response.data.permissions || [];
            isAuthenticated.value = true;

            return response.data;
        } catch (error) {
            isAuthenticated.value = false;
            throw error;
        }
    };

    const logout = async () => {
        try {
            await api.post('/logout');
        } catch (error) {
            console.error('Logout failed on server, cleaning local state anyway.', error);
        } finally {
            user.value = null;
            permissions.value = [];
            isAuthenticated.value = false;
            resetAuthFetch();
        }
    };

    const hasPermission = (permission: string) => {
        return permissions.value.includes(permission);
    };

    return {
        user: readonly(user),
        permissions: readonly(permissions),
        isAuthenticated: readonly(isAuthenticated),
        isLoading: readonly(isLoading),
        fetchUser,
        login,
        logout,
        hasPermission
    };
}
