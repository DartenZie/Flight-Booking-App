import { createRouter, createWebHistory } from 'vue-router';
import {loadLayoutMiddleware} from '@/router/middleware/loadLayoutMiddleware';
import {authGuard, createPermissionGuard} from './guards/auth.guard';
import {signGuard} from "./guards/sign.guard";
import ErrorView from "../views/ErrorView.vue";

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
            meta: { layout: 'AuthLayout' },
            beforeEnter: signGuard
        },
        {
            path: '/reset-password',
            name: 'reset-password',
            component: () => import('../views/ResetPassword.vue'),
            meta: { layout: 'AuthLayout' },
            beforeEnter: signGuard
        },
        {
            path: '/sign-up',
            name: 'sign-up',
            component: () => import('../views/SignUp.vue'),
            meta: { layout: 'AuthLayout' },
            beforeEnter: signGuard
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
            meta: { layout: 'ProfileLayout' },
            beforeEnter: authGuard
        },
        {
            path: '/admin',
            name: 'admin',
            redirect: '/admin/dashboard',
            children: [
                {
                    path: 'dashboard',
                    name: 'dashboard',
                    component: () => import('@/views/admin/DashboardView.vue')
                },
                {
                    path: 'manage-flights',
                    name: 'manage-flights',
                    children: [
                        {
                            path: '',
                            name: 'manage-flights-list',
                            component: () => import('@/views/admin/ManageFlightsView.vue')
                        },
                        {
                            path: 'schedule',
                            name: 'manage-flights-schedule',
                            component: () => import('@/views/admin/ScheduleFlightView.vue')
                        }
                    ]
                }
            ],
            meta: { layout: 'AdminLayout' },
            beforeEnter: createPermissionGuard(2)
        },
        {
            path: '/super-admin',
            name: 'super-admin',
            redirect: '/super-admin/manage-airports',
            children: [
                {
                    path: 'manage-airports',
                    name: 'manage-airports',
                    component: () => import('@/views/super-admin/ManageAirports.vue')
                },
                {
                    path: 'manage-airlines',
                    name: 'manage-airlines',
                    component: () => import('@/views/super-admin/ManageAirlinesView.vue')
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
        },
        {
            path: '/403',
            name: '403',
            component: ErrorView,
        },
    ]
});

router.beforeEach(loadLayoutMiddleware);

export default router;
