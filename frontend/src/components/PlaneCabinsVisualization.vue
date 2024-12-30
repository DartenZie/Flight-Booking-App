<script setup lang="ts">
import {SeatingModel} from "@/models/plane.model";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faTimes, faUser} from "@fortawesome/free-solid-svg-icons";
import {ref} from "vue";

const props = defineProps<{
    seatingModel: SeatingModel
}>();

const emit = defineEmits<{
    (e: 'select', id: string): void,
}>();

const selected = ref<string>('');

const getRowIdx = (index: number, cabin: string): number => {
    let startIdx = 0;

    const cabins = props.seatingModel.cabins;
    const cabinIdx = cabins.findIndex((c) => c.className === cabin);

    if (cabinIdx === -1) {
        return 0;
    }

    for (let i = 0; i < cabinIdx; i++) {
        startIdx += cabins[i].rows;
    }

    return startIdx + index;
};

const selectSeat = (colCode: string, index: number) => {
    selected.value = `${index}${colCode}`;
    emit('select', selected.value);
};

const isTaken = (colCode: string, index: number): boolean => {
    return props.seatingModel.takenSeats.includes(`${index}${colCode}`);
};

const isSelected = (colCode: string, index: number): boolean => {
    return selected.value === `${index}${colCode}`;
};
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
                <div class="absolute left-2 bottom-1/2 translate-y-1/2 w-6 font-semibold text-md text-gray-600 text-center"><span>{{ getRowIdx(index, cabin.className) }}</span></div>
                <div class="flex gap-x-8 px-14">
                    <div v-for="isle in cabin.isles" :key="isle.id" class="flex gap-x-2">
                        <div v-for="seat in isle.seats" :key="seat.id">
                            <div v-if="isTaken(seat.colCode, getRowIdx(index, cabin.className))"
                                 class="seat cursor-default bg-gray-400">
                                <font-awesome-icon :icon="faTimes" class="text-white" />
                            </div>
                            <div v-else-if="isSelected(seat.colCode, getRowIdx(index, cabin.className))"
                                 class="seat bg-cyan-400">
                                <font-awesome-icon :icon="faUser" class="text-white" />
                            </div>
                            <div v-else-if="cabin.className.toLowerCase().includes('business')"
                                 class="seat bg-yellow-400 hover:bg-yellow-500"
                                 @click="selectSeat(seat.colCode, getRowIdx(index, cabin.className))"></div>
                            <div v-else-if="cabin.className.toLowerCase().includes('economy')"
                                 class="seat bg-blue-400 hover:bg-blue-500"
                                 @click="selectSeat(seat.colCode, getRowIdx(index, cabin.className))"></div>
                            <div v-else class="seat bg-green-200 hover:bg-green-300"
                                 @click="selectSeat(seat.colCode, getRowIdx(index, cabin.className))"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
