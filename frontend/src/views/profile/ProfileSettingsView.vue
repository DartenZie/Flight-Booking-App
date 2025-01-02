<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {computed, reactive, ref, watch} from "vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {useAuthStore} from "@/store/auth.store";
import FormControl from "@/components/FormControl.vue";
import NationalityFormSelect from "@/components/NationalityFormSelect.vue";
import useVuelidate, {ValidationArgs} from "@vuelidate/core";
import {email, required, sameAs} from "@vuelidate/validators";

const API_URL = process.env.VITE_API_URL;

const authStore = useAuthStore();

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
    email: { required, email },
    confirmEmail: { sameAs: sameAs(computed(() => state.email )) },
};

const v$ = useVuelidate(rules, state);

const emailChanged = ref<boolean>(false);

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

const handleEmailChange = (value: string) => {
    if (value === authStore.user.email) {
        state.confirmEmail = authStore.user.email;
        emailChanged.value = false;
    } else {
        state.confirmEmail = '';
        emailChanged.value = true;
    }
};

async function handleSubmit() {
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
        authStore.invalidateCache();
    }
};
</script>

<template>
    <admin-card v-if="state" class="col-span-12">
        <form class="space-y-8" @submit.prevent="handleSubmit()">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Profile settings</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Fill in the information in your profile, and it will be automatically prefilled in your reservations.</p>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <div class="flex gap-x-6 mb-6">
                        <div class="flex">
                            <input type="radio" name="gender-group" v-model="state.sex" value="male" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" id="gender-group-1">
                            <label for="gender-group-1" class="text-sm text-gray-900 ms-2">Male</label>
                        </div>

                        <div class="flex">
                            <input type="radio" name="gender-group" v-model="state.sex" value="female" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" id="gender-group-2">
                            <label for="gender-group-2" class="text-sm text-gray-900 ms-2">Female</label>
                        </div>
                    </div>

                    <div class="flex gap-x-2 lg:gap-x-6 xl:gap-x-10 mb-6">
                        <div class="w-1/2">
                            <form-control id="firstName" v-model="state.firstName" label="First Name" type="text"
                                          placeholder="John" />
                        </div>
                        <div class="w-1/2">
                            <form-control id="firstName" v-model="state.lastName" label="Last Name" type="text"
                                          placeholder="Doe" />
                            <p v-if="v$.firstName.$error || v$.lastName.$error" class="text-sm/6 text-red-600">Full name is required.</p>
                        </div>
                    </div>

                    <div class="lg:flex lg:gap-x-10">
                        <div class="mb-6 lg:mb-0 lg:w-1/2">
                            <nationality-form-select v-model="state.nationality" />
                        </div>
                        <div class="lg:w-1/2">
                            <form-control id="dateOfBirth" v-model="state.dateOfBirth" label="Date of Birth" type="date" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <div class="lg:flex lg:gap-x-10 mb-6">
                        <div class="mb-6 lg:mb-0 lg:w-1/2">
                            <form-control id="firstName" v-model="state.email" @update:modelValue="handleEmailChange" label="Email" type="email"
                                          placeholder="Email" />
                            <p v-if="v$.email.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Email is required.</p>
                            <p v-if="v$.email.$errors.some(v => v.$validator === 'email')" class="text-sm/6 text-red-600">Email is not valid.</p>
                        </div>
                        <div v-if="emailChanged" class="lg:w-1/2">
                            <form-control id="confirmEmail" v-model="state.confirmEmail" label="Confirm Email" type="email"
                                          placeholder="johndoe@gmail.com" />
                            <p v-if="v$.confirmEmail.$error" class="text-sm/6 text-red-600">Emails are not the same.</p>
                        </div>
                    </div>

                    <div class="lg:w-1/2">
                        <form-control id="phone" v-model="state.phone" label="Phone number" type="text"
                                      placeholder="+420777888999" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="btn-primary h-10" type="submit">
                    <span class="me-7">Save</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </form>
    </admin-card>
</template>
