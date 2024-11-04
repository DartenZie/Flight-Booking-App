import { createRouter, createWebHistory } from 'vue-router';
import {loadLayoutMiddleware} from "@/router/middleware/loadLayoutMiddleware";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/HomeView.vue'),
            meta: { layout: 'AppLayout'}
        },
        {
            path: '/book',
            name: 'book',
            component: () => import('../views/BookView.vue'),
            meta: { layout: 'AppLayout'}
        },
        {
            path: '/sign-in',
            name: 'sign-in',
            component: () => import('../views/SignIn.vue')
        },
        {
            path: '/forgot',
            name: 'forgot',
            component: () => import('../views/Forgot.vue')
        },
        {
            path: '/sign-up',
            name: 'sign-up',
            component: () => import('../views/SignUp.vue')
        }
    ]
});

router.beforeEach(loadLayoutMiddleware);

export default router;
