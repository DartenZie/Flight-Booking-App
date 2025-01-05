<script setup lang="ts">
import {computed, reactive, watch} from "vue";
import router from '@/router';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faPlane, faChevronRight } from '@fortawesome/free-solid-svg-icons';
import FormControl from "@/components/FormControl.vue";
import NationalityFormSelect from "@/components/NationalityFormSelect.vue";
import {useAuthStore} from "@/store/auth.store";
import {useReservationStore} from "@/store/reservation.store";
import useVuelidate, {ValidationArgs} from "@vuelidate/core";
import {email, required, sameAs} from "@vuelidate/validators";
import ReservationFlightCard from "@/components/ReservationFlightCard.vue";
import HeroSection from "@/components/HeroSection.vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";

const API_URL = process.env.VITE_API_URL;

const authStore = useAuthStore();
const reservationStore = useReservationStore();

const state = reactive({
    firstName: '',
    lastName: '',
    nationality: '',
    dateOfBirth: '',
    sex: '',
    email: '',
    confirmEmail: '',
    phone: ''
});

const rules: ValidationArgs = {
    firstName: { required },
    lastName: { required },
    nationality: { required },
    dateOfBirth: { required },
    sex: { required },
    email: { required, email },
    confirmEmail: { sameAs: sameAs(computed(() => state.email )) },
    phone: { required }
};

const v$ = useVuelidate(rules, state);

watch(
    () => authStore.user,
    () => {
        state.firstName = authStore.user?.firstName;
        state.lastName = authStore.user?.lastName;
        state.nationality = authStore.user?.nationality;
        state.dateOfBirth = authStore.user?.dateOfBirth;
        state.sex = authStore.user?.sex;
        state.email = authStore.user?.email;
        state.confirmEmail = authStore.user?.email;
        state.phone = authStore.user?.phone;
    },
    { immediate: true }
);

async function handleSubmit(): void {
    const isFormCorrect = await this.v$.$validate();
    if (!isFormCorrect) {
        return;
    }

    const body = {
        firstName: this.state.firstName,
        lastName: this.state.lastName,
        nationality: this.state.nationality,
        dateOfBirth: this.state.dateOfBirth,
        sex: this.state.sex,
        email: this.state.email,
        phone: this.state.phone
    };

    const response = await useAuthenticatedFetch(`${API_URL}/user`).put(body).json();
    if (response.statusCode.value === 200) {
        await authStore.invalidateCache();
        await router.push('/book/seat-reservation');
    }
}
</script>

<template>
    <hero-section v-if="reservationStore.departureFlight" :city="reservationStore.departureFlight.arrivalAirport.city">
        <h2 class="font-medium text-lg mb-8">
            <font-awesome-icon :icon="faPlane" />
            <span class="ms-3">Flight information</span>
        </h2>

        <reservation-flight-card :flight="reservationStore.departureFlight" />
        <reservation-flight-card v-if="reservationStore.returnFlight" :flight="reservationStore.returnFlight" />

        <router-link
            class="btn-primary flex w-min h-12 ms-auto lg:absolute lg:bottom-0 lg:right-10 lg:h-16 lg:w-56 lg:translate-y-1/2 text-left"
            to="/"
        >
            <span class="me-7 lg:me-0">Change Flights</span>
            <font-awesome-icon
                :icon="faArrowLeft"
                class="lg:absolute lg:right-6 lg:bottom-1/2 lg:translate-y-1/2"
            />
        </router-link>
    </hero-section>

    <div class="container px-4 md:px-8 lg:px-20 xl:px-0 my-12 lg:mt-0 xl:mb-24 mx-auto">
        <form @submit.prevent="handleSubmit()">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-40 gap-y-8 lg:grid-cols-6">
                        <div class="lg:col-span-2">
                            <h2 class="text-base/7 font-semibold text-gray-900">Passenger</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Please ensure that passengers' information is identical to the way they are written in their passport.</p>
                        </div>
                        <div class="lg:col-span-4">
                            <div class="mb-6">
                                <div class="flex gap-x-6">
                                    <div class="flex">
                                        <input id="gender-group-1" type="radio" name="gender-group" v-model="state.sex" value="male"
                                               class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                        <label for="gender-group-1" class="text-sm text-gray-900 ms-2">Male</label>
                                    </div>

                                    <div class="flex">
                                        <input id="gender-group-2" type="radio" name="gender-group" v-model="state.sex" value="female"
                                               class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                        <label for="gender-group-2" class="text-sm text-gray-900 ms-2">Female</label>
                                    </div>
                                </div>
                                <p v-if="v$.sex.$error" class="text-sm/6 text-red-600">Sex is required.</p>
                            </div>

                            <div class="mb-6">
                                <div class="flex gap-x-2 md:gap-x-8 lg:gap-x-10">
                                    <div class="w-1/2">
                                        <form-control id="firstName" v-model="state.firstName" label="First Name" type="text"
                                                      placeholder="John" />
                                    </div>
                                    <div class="w-1/2">
                                        <form-control id="lastName" v-model="state.lastName" label="Last Name" type="text"
                                                      placeholder="Doe" />
                                    </div>
                                </div>
                                <p v-if="v$.firstName.$error || v$.lastName.$error" class="text-sm/6 text-red-600">Full name is required.</p>
                            </div>

                            <div class="md:flex md:gap-x-10">
                                <div class="mb-6 md:mb-0 md:w-1/2">
                                    <nationality-form-select v-model="state.nationality" />
                                    <p v-if="v$.nationality.$error" class="text-sm/6 text-red-600">Nationality is required.</p>
                                </div>
                                <div class="md:w-1/2">
                                    <form-control id="dateOfBirth" v-model="state.dateOfBirth" label="Date of Birth" type="date"
                                                  placeholder="YYYY-DD-MM" />
                                    <p v-if="v$.dateOfBirth.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Date of birth is required.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-40 gap-y-8 lg:grid-cols-6">
                        <div class="lg:col-span-2">
                            <h2 class="text-base/7 font-semibold text-gray-900">Contact information</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Contact phone number makes it easier for us to reach you if necessary.</p>
                        </div>
                        <div class="lg:col-span-4">
                            <div class="md:flex md:gap-x-10 mb-6">
                                <div class="mb-6 md:mb-0 md:w-1/2">
                                    <form-control id="email" v-model="state.email" label="Email" type="email"
                                                  placeholder="johndoe@gmail.com" />
                                    <p v-if="v$.email.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Email is required.</p>
                                    <p v-if="v$.email.$errors.some(v => v.$validator === 'email')" class="text-sm/6 text-red-600">Email is not valid.</p>
                                </div>
                                <div class="md:w-1/2">
                                    <form-control id="confirmEmail" v-model="state.confirmEmail" label="Confirm Email" type="email"
                                                  placeholder="johndoe@gmail.com" />
                                    <p v-if="v$.confirmEmail.$error" class="text-sm/6 text-red-600">Emails are not the same.</p>
                                </div>
                            </div>

                            <div class="md:w-1/2">
                                <form-control id="phone" v-model="state.phone" label="Phone number" type="text"
                                              placeholder="+420774847807" />
                                <p v-if="v$.phone.$error" class="text-sm/6 text-red-600">Phone is required.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="btn-primary h-12" type="submit">
                        <span class="me-7">Continue</span>
                        <font-awesome-icon :icon="faChevronRight" />
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
