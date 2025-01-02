<script setup lang="ts">
</script>

<template>
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-700">Select baggage type</h3>
                        <p class="text-sm text-gray-700 mb-6">If your baggage doesn't meet maximum requirements in size or weight additional fee may be charged.</p>

                        <div class="space-y-4">
                            <div class="grid grid-cols-[4rem_1fr_6rem] items-center gap-x-6 px-6 py-3 border border-gray-200 rounded-xl cursor-pointer hover:outline hover:outline-gray-300 hover:outline-2">
                                <div class="col-span-1">
                                    <img class="max-h-full max-w-full" src="../../assets/images/luggages/luggage-small.svg" alt="Small luggage illustration">
                                </div>
                                <div class="col-span-1">
                                    <div class="text-xl font-medium">Small luggage</div>
                                    <div class="text-sm font-light text-gray-900 mb-1">Bag under the seat</div>
                                    <div class="text-sm font-light text-gray-700">Max size: <span class="text-black font-medium">20x25x45 (cm)</span></div>
                                    <div class="text-sm font-light text-gray-700">Max weight: <span class="text-black font-medium">10 kg</span></div>
                                </div>

                                <div class="col-span-1">
                                    <div class="text-xl font-medium text-right">Free</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-[4rem_1fr_6rem] items-center gap-x-6 px-6 py-3 border border-gray-200 rounded-xl cursor-pointer hover:outline hover:outline-gray-300 hover:outline-2">
                                <div class="col-span-1">
                                    <img class="max-h-full max-w-full" src="../../assets/images/luggages/luggage-medium.svg" alt="Medium luggage illustration">
                                </div>
                                <div class="col-span-1">
                                    <div class="text-xl font-medium">Medium luggage</div>
                                    <div class="text-sm font-light text-gray-900 mb-1">Baggage in overhead cabin</div>
                                    <div class="text-sm font-light text-gray-700">Max size: <span class="text-black font-medium">20x40x55 (cm)</span></div>
                                    <div class="text-sm font-light text-gray-700">Max weight: <span class="text-black font-medium">20 kg</span></div>
                                </div>

                                <div class="col-span-1">
                                    <div class="text-xl font-medium text-right">20 <span class="text-gray-700 font-light">EUR</span></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-[4rem_1fr_6rem] items-center gap-x-6 px-6 py-3 border border-gray-200 rounded-xl cursor-pointer hover:outline hover:outline-gray-300 hover:outline-2">
                                <div class="col-span-1">
                                    <img class="max-h-full max-w-full" src="../../assets/images/luggages/luggage-large.svg" alt="Large luggage illustration">
                                </div>
                                <div class="col-span-1">
                                    <div class="text-xl font-medium">Large luggage</div>
                                    <div class="text-sm font-light text-gray-900 mb-1">Checked baggage</div>
                                    <div class="text-sm font-light text-gray-700">Max size: <span class="text-black font-medium">81x119x119 (cm)</span></div>
                                    <div class="text-sm font-light text-gray-700">Max weight: <span class="text-black font-medium">30 kg</span></div>
                                </div>

                                <div class="col-span-1">
                                    <div class="text-xl font-medium text-right">50 <span class="text-gray-700 font-light">EUR</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-100 px-4 py-3 flex flex-row-reverse sm:px-6 gap-x-4">
                        <button type="button" class="btn-primary h-10" @click="$emit('confirm')">Confirm</button>
                        <button type="button" class="btn-default h-10" @click="$emit('cancel')">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

