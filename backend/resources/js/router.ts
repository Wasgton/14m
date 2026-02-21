import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue';
import DashboardLayout from './layouts/DashboardLayout.vue';
import Dashboard from './pages/Dashboard.vue';

// Banners
import BannersIndex from './pages/banners/Index.vue';
import BannerForm from './pages/banners/Form.vue';

// Events
import EventsIndex from './pages/events/Index.vue';
import EventForm from './pages/events/Form.vue';

// Partners
import PartnersIndex from './pages/partners/Index.vue';
import PartnerForm from './pages/partners/Form.vue';


// Artists
import ArtistsIndex from './pages/artists/Index.vue';
import ArtistForm from './pages/artists/Form.vue';

// Users
import UsersIndex from './pages/users/Index.vue';
import UserForm from './pages/users/Form.vue';

// Roles
import RolesIndex from './pages/roles/Index.vue';
import RoleForm from './pages/roles/Form.vue';

// Permissions
import PermissionsIndex from './pages/permissions/Index.vue';
import PermissionForm from './pages/permissions/Form.vue';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: () => import('./pages/auth/ForgotPassword.vue'),
        meta: { guestOnly: true }
    },
    {
        path: '/reset-password/:token',
        name: 'PasswordReset',
        component: () => import('./pages/auth/ResetPassword.vue'),
        meta: { guestOnly: true }
    },
    {
        path: '/',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'Dashboard', component: Dashboard, },
            { path: 'atividades', name: 'Auditoria', component: () => import('./pages/Activities.vue'), },

            // BANNERS
            { path: 'banners', name: 'Banners', component: BannersIndex, },
            { path: 'banners/create', name: 'Criar Banner', component: BannerForm, },
            { path: 'banners/:id/edit', name: 'Editar Banner', component: BannerForm, },

            // EVENTOS
            { path: 'eventos', name: 'Eventos', component: EventsIndex, },
            { path: 'eventos/create', name: 'Criar Evento', component: EventForm, },
            { path: 'eventos/:id/edit', name: 'Editar Evento', component: EventForm, },

            // PARCEIROS
            { path: 'parceiros', name: 'Parceiros', component: PartnersIndex, },
            { path: 'parceiros/create', name: 'Criar Parceiro', component: PartnerForm, },
            { path: 'parceiros/:id/edit', name: 'Editar Parceiro', component: PartnerForm, },

            // ARTISTAS
            { path: 'artistas', name: 'Artistas', component: ArtistsIndex, },
            { path: 'artistas/create', name: 'Criar Artista', component: ArtistForm, },
            { path: 'artistas/:id/edit', name: 'Editar Artista', component: ArtistForm, },

            // USUÁRIOS
            { path: 'users', name: 'Usuários', component: UsersIndex, },
            { path: 'users/create', name: 'Criar Usuário', component: UserForm, },
            { path: 'users/:id/edit', name: 'Editar Usuário', component: UserForm, },

            // CARGOS
            { path: 'roles', name: 'Cargos', component: RolesIndex, },
            { path: 'roles/create', name: 'Criar Cargo', component: RoleForm, },
            { path: 'roles/:id/edit', name: 'Editar Cargo', component: RoleForm, },

            // PERMISSÕES
            { path: 'permissions', name: 'Permissões', component: PermissionsIndex, },
            { path: 'permissions/create', name: 'Criar Permissão', component: PermissionForm, },
            { path: 'permissions/:id/edit', name: 'Editar Permissão', component: PermissionForm, },

            // CONFIGURAÇÕES GERAIS
            { path: 'settings', name: 'Configurações Globais', component: () => import('./pages/settings/Form.vue'), },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

import { useAuth } from './composables/useAuth';

router.beforeEach(async (to, from, next) => {
    const { isAuthenticated, fetchUser, isLoading } = useAuth();

    // If it's the very first load, we don't know the state yet (isLoading is true by default)
    // Actually we need to explicitly await fetchUser initially
    // Better strategy: we do the fetchUser logic in App.vue or right here if not fetched yet
    // For simplicity, we can fetch the user every time we hit a protected route if we don't have the state yet
    if (!isAuthenticated.value && isLoading.value) {
        await fetchUser();
    }

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const guestOnly = to.matched.some(record => record.meta.guestOnly);

    if (requiresAuth && !isAuthenticated.value) {
        next('/login');
    } else if (guestOnly && isAuthenticated.value) {
        next('/');
    } else {
        next();
    }
});

export default router;
