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
import {email, helpers, required, sameAs} from "@vuelidate/validators";
import ReservationFlightCard from "@/components/ReservationFlightCard.vue";

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
    dateOfBirth: { required, date: helpers.regex(/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/) },
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

    await router.push('/book/seat-reservation');
}
</script>

<template>
    <div class="relative bg-copenhagen bg-cover bg-center h-160">
        <div class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-gradient-to-r from-green-100 via-green-50 to-transparent opacity-80"></div>

        <div
            class="container mx-auto h-full flex flex-auto items-center justify-start"
        >
            <p class="font-thin text-5xl leading-snug text-slate-950 z-10">
                Let's book your flight<br />
                to <span class="font-medium" v-if="reservationStore.departureFlight">{{ reservationStore.departureFlight.arrivalAirport.city }}</span>!
            </p>
        </div>
    </div>

    <div class="container mx-auto -mt-32">
        <div
            class="relative h-72 bg-white rounded-lg shadow-md px-10 pt-6 pb-12"
        >
            <h2 class="font-medium text-lg mb-8">
                <font-awesome-icon :icon="faPlane" />
                <span class="ms-3">Flight information</span>
            </h2>

            <reservation-flight-card v-if="reservationStore.departureFlightId > 0" :flight="reservationStore.departureFlight" />
            <reservation-flight-card v-if="reservationStore.returnFlightId > 0" :flight="reservationStore.returnFlight" />

            <router-link
                class="btn-primary absolute bottom-0 right-10 h-16 w-56 translate-y-1/2 text-left"
                to="/"
            >
                <span>Change Flights</span>
                <font-awesome-icon
                    :icon="faArrowLeft"
                    class="absolute right-6 bottom-1/2 translate-y-1/2"
                />
            </router-link>
        </div>
    </div>

    <div class="container mt-24 mb-24 mx-auto">
        <form @submit.prevent="handleSubmit()">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-40 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <h2 class="text-base/7 font-semibold text-gray-900">Passenger</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Please ensure that passengers' information is identical to the way they are written in their passport.</p>
                        </div>
                        <div class="sm:col-span-4">
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
                                <div class="flex gap-x-10">
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

                            <div class="flex gap-x-10">
                                <div class="w-1/2">
                                    <nationality-form-select v-model="state.nationality" />
                                    <p v-if="v$.nationality.$error" class="text-sm/6 text-red-600">Nationality is required.</p>
                                </div>
                                <div class="w-1/2">
                                    <form-control id="dateOfBirth" v-model="state.dateOfBirth" label="Date of Birth" type="text"
                                                  placeholder="YYYY-DD-MM" />
                                    <p v-if="v$.dateOfBirth.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Date of birth is required.</p>
                                    <p v-if="v$.dateOfBirth.$errors.some(v => v.$validator === 'date')" class="text-sm/6 text-red-600">Date of birth is not in YYYY-MM-DD format.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-40 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <h2 class="text-base/7 font-semibold text-gray-900">Contact information</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Contact phone number makes it easier for us to reach you if necessary.</p>
                        </div>
                        <div class="sm:col-span-4">
                            <div class="flex gap-x-10 mb-6">
                                <div class="w-1/2">
                                    <form-control id="email" v-model="state.email" label="Email" type="email"
                                                  placeholder="johndoe@gmail.com" />
                                    <p v-if="v$.email.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Email is required.</p>
                                    <p v-if="v$.email.$errors.some(v => v.$validator === 'email')" class="text-sm/6 text-red-600">Email is not valid.</p>
                                </div>
                                <div class="w-1/2">
                                    <form-control id="confirmEmail" v-model="state.confirmEmail" label="Confirm Email" type="email"
                                                  placeholder="johndoe@gmail.com" />
                                    <p v-if="v$.confirmEmail.$error" class="text-sm/6 text-red-600">Emails are not the same.</p>
                                </div>
                            </div>

                            <div class="w-1/2 pe-5">
                                <form-control id="phone" v-model="state.phone" label="Phone number" type="text"
                                              placeholder="+420774847807" />
                                <p v-if="v$.phone.$error" class="text-sm/6 text-red-600">Phone is required.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="btn-primary h-12">
                        <span class="me-7">Continue</span>
                        <font-awesome-icon :icon="faChevronRight" />
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
