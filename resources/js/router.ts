import { createRouter, createWebHistory } from 'vue-router';

// Layouts
import DashboardLayout from './layouts/DashboardLayout.vue';
import SiteLayout from './layouts/SiteLayout.vue';

// Backoffice Pages
import Login from './pages/painel/Login.vue';
import Dashboard from './pages/painel/Dashboard.vue';

// Banners
import BannersIndex from './pages/painel/banners/Index.vue';
import BannerForm from './pages/painel/banners/Form.vue';

// Events
import EventsIndex from './pages/painel/events/Index.vue';
import EventForm from './pages/painel/events/Form.vue';

// Partners
import PartnersIndex from './pages/painel/partners/Index.vue';
import PartnerForm from './pages/painel/partners/Form.vue';

// Artists
import ArtistsIndex from './pages/painel/artists/Index.vue';
import ArtistForm from './pages/painel/artists/Form.vue';

// Users
import UsersIndex from './pages/painel/users/Index.vue';
import UserForm from './pages/painel/users/Form.vue';

// Roles
import RolesIndex from './pages/painel/roles/Index.vue';
import RoleForm from './pages/painel/roles/Form.vue';

// Permissions
import PermissionsIndex from './pages/painel/permissions/Index.vue';
import PermissionForm from './pages/painel/permissions/Form.vue';

const routes = [
    // ─── WEBSITE (PUBLIC) ───
    {
        path: '/',
        component: SiteLayout,
        children: [
            { path: '', name: 'Home', component: () => import('./pages/site/HomeView.vue') },
            { path: 'eventos', name: 'SiteEventos', component: () => import('./pages/site/EventsView.vue') },
            { path: 'eventos/:id', name: 'SiteEventoDetalhes', component: () => import('./pages/site/EventDetailsView.vue') },
            { path: 'portfolio', name: 'SitePortfolio', component: () => import('./pages/site/PortfolioView.vue') },
            { path: 'portfolio/:id', name: 'SitePortfolioEvento', component: () => import('./pages/site/PortfolioEventView.vue') },
        ]
    },

    // ─── BACKOFFICE / CRM ───
    {
        path: '/painel/login',
        name: 'Login',
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/painel/forgot-password',
        name: 'ForgotPassword',
        component: () => import('./pages/painel/auth/ForgotPassword.vue'),
        meta: { guestOnly: true }
    },
    {
        path: '/painel/reset-password/:token',
        name: 'PasswordReset',
        component: () => import('./pages/painel/auth/ResetPassword.vue'),
        meta: { guestOnly: true }
    },
    {
        path: '/painel',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'Dashboard', component: Dashboard, },
            { path: 'atividades', name: 'Auditoria', component: () => import('./pages/painel/Activities.vue'), },

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
            { path: 'settings', name: 'Configurações Globais', component: () => import('./pages/painel/settings/Form.vue'), },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, _from, savedPosition) {
        if (to.hash) {
            return {
                el: to.hash,
                behavior: 'smooth',
            }
        }
        if (savedPosition) {
            return savedPosition
        }
        return { top: 0 }
    }
});

import { useAuth } from './composables/useAuth';

let hasFetched = false;

export function resetAuthFetch() {
    hasFetched = false;
}

router.beforeEach(async (to, from, next) => {
    const { isAuthenticated, fetchUser } = useAuth();

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const guestOnly = to.matched.some(record => record.meta.guestOnly);

    // On the very first navigation to a protected or guest-only route,
    // always check with the server whether the session is still valid
    if (!hasFetched && (requiresAuth || guestOnly)) {
        await fetchUser();
        hasFetched = true;
    }

    if (requiresAuth && !isAuthenticated.value) {
        next({ name: 'Login' });
    } else if (guestOnly && isAuthenticated.value) {
        next({ name: 'Dashboard' });
    } else {
        next();
    }
});

export default router;
