import { createRouter, createWebHistory } from 'vue-router';
import {loadLayoutMiddleware} from '@/router/middleware/loadLayoutMiddleware';
import {authGuard, createPermissionGuard} from './guards/auth.guard';
import {signGuard} from "./guards/sign.guard";
import ErrorView from "../views/ErrorView.vue";
import {airlineExistsGuard} from "./guards/airline-exists.guard";
import {passengerInformationGuard} from "./guards/passenger-information.guard";

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
                    component: () => import('../views/profile/ReservationsView.vue'),
                },
                {
                    path: 'settings',
                    name: 'settings',
                    component: () => import('../views/profile/ProfileSettingsView.vue')
                },
                {
                    path: 'manage-airlines',
                    name: 'manage-airlines',
                    component: () => import('../views/profile/ManageAirlinesView.vue'),
                    beforeEnter: createPermissionGuard(2)
                },
                {
                    path: 'manage-users',
                    name: 'manage-users',
                    component: () => import('../views/profile/ManageUsersView.vue'),
                    beforeEnter: createPermissionGuard(3)
                }
            ],
            meta: { layout: 'ProfileLayout' },
            beforeEnter: authGuard
        },
        {
            path: '/airline/:airlineId',
            name: 'admin',
            redirect: (to) => {
                return `/airline/${to.params.airlineId}/manage-planes`;
            },
            children: [
                {
                    path: 'manage-planes',
                    name: 'manage-planes',
                    children: [
                        {
                            path: '',
                            name: 'manage-planes-list',
                            component: () => import('@/views/admin/ManagePlanesView.vue')
                        },
                        {
                            path: 'create',
                            name: 'manage-planes-create',
                            component: () => import('@/views/admin/CreatePlaneView.vue')
                        }
                    ]
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
                },
                {
                    path: 'preferences',
                    name: 'airline-preferences',
                    component: () => import('@/views/admin/AirlinePreferencesView.vue')
                }
            ],
            meta: { layout: 'ManageAirlineLayout' },
            beforeEnter: [createPermissionGuard(2), airlineExistsGuard]
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
                }
            ],
            meta: { layout: 'ManageAirlineLayout' }
        },
        {
            path: '/book',
            name: 'book',
            children: [
                {
                    path: 'passenger-information',
                    name: 'passenger-information',
                    component: () => import('../views/reservation/PassengerInformationView.vue'),
                    beforeEnter: passengerInformationGuard
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
            component: ErrorView
        },
        {
            path: '/404',
            name: '404',
            component: ErrorView
        }
    ]
});

router.beforeEach(loadLayoutMiddleware);

export default router;
