import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import PortfolioView from '../views/PortfolioView.vue'
import PortfolioEventView from '../views/PortfolioEventView.vue'
import EventsView from '../views/EventsView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/eventos',
            name: 'events',
            component: EventsView
        },
        {
            path: '/portfolio',
            name: 'portfolio',
            component: PortfolioView
        },
        {
            path: '/portfolio/:id',
            name: 'portfolio-event',
            component: PortfolioEventView
        }
    ],
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
})

export default router
