<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
</script>

<template>
    <admin-card class="col-span-12">
        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Profile settings</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Fill in the information in your profile, and it will be automatically prefilled in your reservations.</p>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <div class="flex gap-x-6 mb-6">
                        <div class="flex">
                            <input type="radio" name="gender-group" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" id="gender-group-1">
                            <label for="gender-group-1" class="text-sm text-gray-900 ms-2">Male</label>
                        </div>

                        <div class="flex">
                            <input type="radio" name="gender-group" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" id="gender-group-2">
                            <label for="gender-group-2" class="text-sm text-gray-900 ms-2">Female</label>
                        </div>
                    </div>

                    <div class="flex gap-x-10 mb-6">
                        <div class="w-1/2">
                            <label for="firstName" class="block text-sm font-medium mb-2">First Name</label>
                            <input type="text" id="firstName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="John">
                        </div>
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Last Name</label>
                            <input type="text" id="lastName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Doe">
                        </div>
                    </div>

                    <div class="flex gap-x-10">
                        <div class="w-1/2">
                            <label for="nationality" class="block text-sm font-medium mb-2">Nationality</label>
                            <select id="nationality" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <option selected value="" disabled>Select nationality</option>
                                <option value="cz">Czech Republic</option>
                                <option value="—————" disabled>—————</option>
                                <option value="ge">German</option>
                                <option value="nor">Norwegian</option>
                                <option value="fr">French</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Date of Birth</label>
                            <input type="text" id="lastName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <div class="flex gap-x-10 mb-6">
                        <div class="w-1/2">
                            <label for="firstName" class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" id="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                        </div>
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Confirm Email</label>
                            <input type="email" id="confirmEmail" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                        </div>
                    </div>

                    <div class="w-1/2 pe-5">
                        <label for="lastName" class="block text-sm font-medium mb-2">Telephone</label>

                        <div class="flex gap-x-2">
                            <div class="w-1/6">
                                <select id="phonePrefix" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                    <option value="cz">Czech Republic (+420)</option>
                                    <option value="—————" disabled>—————</option>
                                    <option value="ge">German (+49)</option>
                                    <option value="nor">Norwegian (+47)</option>
                                    <option value="fr">French (+33)</option>
                                </select>
                            </div>
                            <div class="w-5/6">
                                <input type="text" id="phoneNumber" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="777888999">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="btn-primary h-10">
                    <span class="me-7">Save</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </div>
    </admin-card>

    <admin-card class="col-span-12 h-min">
        <p class="mb-6">At <strong>SkyTrip</strong>, your privacy is our priority. This GDPR Privacy Notice explains how we collect, use, and protect your personal data in compliance with the General Data Protection Regulation (EU) 2016/679 (GDPR).</p>
        <button class="btn-text ms-auto">Learn more</button>
    </admin-card>
</template>
