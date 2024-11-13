<script setup lang="ts">
</script>

<template>
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-700">Online check-in</h3>
                        <p class="text-sm text-gray-700 mb-6">Make sure passport IDs are valid for all passengers.</p>

                        <div class="space-y-4">
                            <div class="w-full">
                                <label for="passenger1Passport" class="block text-sm font-medium mb-2">Passenger 1 - Passport ID</label>
                                <input type="text" id="passenger1Passport" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="324123123">
                            </div>

                            <div class="w-full">
                                <label for="passenger2Passport" class="block text-sm font-medium mb-2">Passenger 2 - Passport ID</label>
                                <input type="text" id="passenger2Passport" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="324123123">
                            </div>
                        </div>

                        <p class="text-sm text-red-500 mt-6">After check-in no further changes can be made for flight.</p>
                    </div>
                    <div class="bg-gray-100 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-x-4">
                        <button type="button" class="btn-primary h-10" @click="$emit('confirm')">Confirm</button>
                        <button type="button" class="btn-default h-10" @click="$emit('cancel')">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

