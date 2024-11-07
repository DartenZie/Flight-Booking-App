<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faDashboard} from "@fortawesome/free-solid-svg-icons";
import {faPlaneUp} from "@fortawesome/free-solid-svg-icons";
import {faPlaneDeparture} from "@fortawesome/free-solid-svg-icons";
import {faTruckPlane} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <div class="p-8 h-screen bg-[#B5C2CA]">
        <div class="grid grid-cols-admin gap-x-6 h-full">
            <div class="w-96 p-10 bg-[#F0F3F4] border-[#D6DDE1] border-5 rounded-3xl shadow-md">

                <router-link to="/admin/dashboard" class="block mb-10">
                    <span class="text-xl">
                        Sky<span class="font-semibold">Trip</span>
                    </span>
                </router-link>

                <hr class="mb-10" />

                <router-link to="/admin/dashboard" active-class="bg-slate-950 text-[#fff] hover:text-[#fff]" class="block mb-2 h-14 rounded-3xl text-slate-700 hover:text-slate-950">
                    <div class="px-8 flex h-full items-center gap-x-4">
                        <font-awesome-icon :icon="faDashboard" class="w-4" />
                        <div>Dashboard</div>
                    </div>
                </router-link>

                <router-link to="/admin/manage-flights" active-class="bg-slate-950 text-[#fff] hover:text-[#fff]" class="block mb-2 h-14 rounded-3xl text-slate-700 hover:text-slate-950">
                    <div class="px-8 flex h-full items-center gap-x-4">
                        <font-awesome-icon :icon="faPlaneUp" class="w-4" />
                        <div>Manage Flights</div>
                    </div>
                </router-link>

                <router-link to="/admin/manage-airports" active-class="bg-slate-950 text-[#fff] hover:text-[#fff]" class="block mb-2 h-14 rounded-3xl text-slate-700 hover:text-slate-950">
                    <div class="px-8 flex h-full items-center gap-x-4">
                        <font-awesome-icon :icon="faPlaneDeparture" class="w-4" />
                        <div>Manage Airports</div>
                    </div>
                </router-link>

                <router-link to="/admin/manage-airlines" active-class="bg-slate-950 text-[#fff] hover:text-[#fff]" class="block mb-2 h-14 rounded-3xl text-slate-700 hover:text-slate-950">
                    <div class="px-8 flex h-full items-center gap-x-4">
                        <font-awesome-icon :icon="faTruckPlane" class="w-4" />
                        <div>Manage Airlines</div>
                    </div>
                </router-link>


            </div>

            <div class="grid lg:grid-cols-12 grid-rows-min gap-6">
                <slot />
            </div>
        </div>
    </div>
</template>
