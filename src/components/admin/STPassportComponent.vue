<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPassport} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <div class="relative h-full py-3 px-6 bg-[#081225]">
        <div class="mb-4">
            <div class="text-xl text-white uppercase">My <strong>SkyTrip</strong> Passport</div>
            <div class="text-gray-300 font-light text-xs">
                <font-awesome-icon :icon="faPassport" class="me-3" />
                <span class="uppercase">Passport <strong>&#183;</strong> Pass <strong>&#183;</strong> Pasaporte</span>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-3">
            <div class="col-span-2">
                <div class="text-purple-300 uppercase font-light">Flights</div>
                <div class="text-2xl text-white font-medium">10</div>
            </div>
            <div class="col-span-2">
                <div class="text-purple-300 uppercase font-light">Flight Distance</div>
                <div class="text-2xl text-white font-medium">10 367 <span class="font-light">km</span></div>
            </div>
            <div class="col-span-2">
                <div class="text-purple-300 uppercase font-light">Flight Time</div>
                <div class="text-2xl text-white font-medium">18 <span class="font-light">h</span> 1<span class="font-light">m</span></div>
            </div>
            <div class="col-span-1">
                <div class="text-purple-300 uppercase font-light">Airports</div>
                <div class="text-2xl text-white font-medium">5</div>
            </div>
            <div class="col-span-1">
                <div class="text-purple-300 uppercase font-light">Airlines</div>
                <div class="text-2xl text-white font-medium">4</div>
            </div>
        </div>

        <div class="absolute bottom-4 text-slate-500 text-sm uppercase">Issued 10Nov24</div>
    </div>
</template>
