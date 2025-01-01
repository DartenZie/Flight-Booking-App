<script setup lang="ts">
import BoardingPass from "@/components/BoardingPass.vue";
import {Reservation} from "@/models/reservation.model";

defineProps<{
    reservation: Reservation
}>();

const emit = defineEmits<{
  (e: 'cancel'): void,
}>();

function outsideClose(event: MouseEvent) {
    if (!(event.target as HTMLElement).closest('.boarding-pass')) {
        emit('cancel');
    }
}
</script>

<template>
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto" @click="outsideClose">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <boarding-pass :reservation="reservation" />
                </div>
            </div>
        </div>
    </div>
</template>
