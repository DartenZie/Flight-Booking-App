import { createRouter, createWebHistory } from 'vue-router';
import {loadLayoutMiddleware} from "@/router/middleware/loadLayoutMiddleware";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/HomeView.vue'),
            meta: { layout: 'AppLayout' }
        },
        {
            path: '/sign-in',
            name: 'sign-in',
            component: () => import('../views/SignIn.vue'),
            meta: { layout: 'AuthLayout' }
        },
        {
            path: '/reset-password',
            name: 'reset-password',
            component: () => import('../views/ResetPassword.vue'),
            meta: { layout: 'AuthLayout' }
        },
        {
            path: '/sign-up',
            name: 'sign-up',
            component: () => import('../views/SignUp.vue'),
            meta: { layout: 'AuthLayout' }
        },
        {
            path: '/profile',
            name: 'profile',
            redirect: '/profile/dashboard',
            children: [
                {
                    path: 'dashboard',
                    name: 'profileDashboard',
                    component: () => import('../views/profile/DashboardView.vue')
                },
                {
                    path: 'reservations',
                    name: 'reservations',
                    children: [
                        {
                            path: '',
                            name: 'reservations-list',
                            component: () => import('../views/profile/ReservationsView.vue'),
                        },
                        {
                            path: 'change',
                            name: 'reservations-change',
                            component: () => import('../views/profile/ReservationChangeView.vue')
                        }
                    ]
                },
                {
                    path: 'settings',
                    name: 'settings',
                    component: () => import('../views/profile/ProfileSettingsView.vue')
                },
            ],
            meta: { layout: 'ProfileLayout' }
        },
        {
            path: '/admin',
            name: 'admin',
            children: [
                {
                    path: 'dashboard',
                    name: 'dashboard',
                    component: () => import('../views/admin/DashboardView.vue')
                },
                {
                    path: 'manage-airports',
                    name: 'manage-airports',
                    component: () => import('../views/admin/ManageAirports.vue')
                }
            ],
            meta: { layout: 'AdminLayout' }
        },
        {
            path: '/book',
            name: 'book',
            children: [
                {
                    path: 'passenger-information',
                    name: 'passenger-information',
                    component: () => import('../views/reservation/PassengerInformationView.vue')
                },
                {
                    path: 'seat-reservation',
                    name: 'seat-reservation',
                    component: () => import('../views/reservation/SeatReservationView.vue')
                }
            ],
            meta: { layout: 'AppLayout' }
        }
    ]
});

router.beforeEach(loadLayoutMiddleware);

export default router;
