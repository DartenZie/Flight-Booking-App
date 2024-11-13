<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

import AddLuggageModal from "@/components/modals/AddLuggageModal.vue";
import {createConfirmDialog} from "vuejs-confirm-dialog";

const addLuggageModal = createConfirmDialog(AddLuggageModal, {});

addLuggageModal.onConfirm(() => {
    // Todo add luggage
    console.log('Luggage was added');
    onlineCheckIn.close();
});
addLuggageModal.onCancel(addLuggageModal.close);
</script>

<template>
    <admin-card class="col-span-6 overflow-hidden">
        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Passenger Information</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Please ensure that passengers' names are identical to the way they are written in their passports.</p>
            </div>

            <div class="bg-gray-100 h-14 mb-4 w-full flex gap-x-2 items-center px-2 rounded-lg">
                <button class="h-10 btn-filter active">Passenger 1</button>
                <button class="h-10 btn-filter">Passenger 2</button>
            </div>

            <div class="border-b border-gray-900/10 pb-12 px-4">
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

            <div class="flex justify-end">
                <button class="btn-primary h-10">
                    <span class="me-7">Save</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </div>
    </admin-card>

    <admin-card class="col-span-6 overflow-hidden">
        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Contact Information</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Contact phone number makes it easier for us to reach you if necessary.</p>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
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

                <div class="w-full">
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

            <div class="flex justify-end">
                <button class="btn-primary h-10">
                    <span class="me-7">Save</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </div>
    </admin-card>

    <admin-card class="col-span-12">
        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Luggage</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Adding or deleting luggage will result in 20 EUR manipulation fee.</p>
            </div>

            <div class="flex justify-between items-center">
                <div class="bg-gray-100 h-14 mb-4 w-min flex gap-x-2 items-center px-2 rounded-lg">
                    <button class="h-10 btn-filter active">Passenger 1</button>
                    <button class="h-10 btn-filter">Passenger 2</button>
                </div>

                <button class="btn-primary h-12" @click="addLuggageModal.reveal()">Add luggage</button>
            </div>

            <div>
                <div class="text-center text-xl text-gray-700 font-medium">This feature is temporarily disabled.</div>
                <div class="text-center text-md text-gray-700 font-light">This is the problem of future version of Míra.</div>
            </div>
        </div>
    </admin-card>
</template>
