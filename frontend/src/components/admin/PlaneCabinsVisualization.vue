<script setup lang="ts">
import {faUpRightAndDownLeftFromCenter} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {SeatingModel} from "@/models/plane.model";

defineProps<{
    seatingModel: SeatingModel
}>();
</script>

<template>
    <div class="bg-gray-100 rounded-lg w-fit h-fit max-h-full py-6 overflow-auto">
        <div v-for="cabin in seatingModel.cabins" :key="cabin.id" class="flex flex-col gap-y-2 mb-6">
            <div class="font-semibold text-center text-lg mb-4 text-gray-700">{{ cabin.className }}</div>
            <div class="justify-center flex gap-x-8 px-14">
                <div v-for="isle in cabin.isles" :key="isle.id" class="flex gap-x-2">
                    <div v-for="seat in isle.seats" :key="seat.id">
                        <div class="w-10 text-center font-medium">{{ seat.colCode }}</div>
                    </div>
                </div>
            </div>
            <div v-for="index in cabin.rows" :key="index" class="relative flex justify-center">
                <div class="absolute left-2 bottom-1/2 translate-y-1/2 w-6 font-semibold text-md text-gray-600 text-center"><span>{{ index }}</span></div>
                <div class="flex gap-x-8 px-14">
                    <div v-for="isle in cabin.isles" :key="isle.id" class="flex gap-x-2">
                        <div v-for="seat in isle.seats" :key="seat.id">
                            <div :class="['w-10 h-10 rounded flex items-center justify-center cursor-pointer', cabin.className.toLowerCase().includes('business') ? 'bg-yellow-400 hover:bg-yellow-500' : '', cabin.className.toLowerCase().includes('economy') ? 'bg-blue-400 hover:bg-blue-500' : '']">
                                <font-awesome-icon v-if="cabin.moreLegRoom?.includes(index)" :icon="faUpRightAndDownLeftFromCenter" class="text-white" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
