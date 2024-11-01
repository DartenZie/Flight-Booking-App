<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendarDays } from '@fortawesome/free-solid-svg-icons';

defineProps<{
    id: string
}>();
</script>

<template>
    <div class="border border-slate-200 rounded-lg px-6 h-24 flex gap-x-10">
        <div class="h-full w-1/2 relative">
            <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">Departure</label>
            <input :id="id" type="text"  value="Fri, 22 Mar"
                   class="absolute block outline-none border-none focus:ring-0 font-bold text-2xl bottom-1/2 translate-y-1/2 w-full pe-7 ps-0" />
            <div class="flex gap-x-6 absolute bottom-1.5">
                <button class="btn-text text-xs">Prev</button>
                <button class="btn-text text-xs">Next</button>
            </div>

            <font-awesome-icon :icon="faCalendarDays" class="absolute bottom-1/2 translate-y-1/2 text-2xl right-0" />
        </div>
        <div class="h-full w-1/2 relative">
            <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">Return</label>
            <input :id="id" type="text" value="Mon, 2 Apr"
                   class="absolute block outline-none border-none focus:ring-0 font-bold text-2xl bottom-1/2 translate-y-1/2 w-full pe-7 ps-0" />
            <div class="flex gap-x-6 absolute bottom-1.5">
                <button class="btn-text pb-0.5 text-xs">Prev</button>
                <button class="btn-text pb-0.5 text-xs">Next</button>
            </div>

            <font-awesome-icon :icon="faCalendarDays" class="absolute bottom-1/2 translate-y-1/2 text-2xl right-0" />
        </div>
    </div>
</template>
