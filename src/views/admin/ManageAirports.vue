<script setup lang="ts">

import {faSearch} from "@fortawesome/free-solid-svg-icons";
import AdminCard from "@/components/admin/AdminCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const airports = [
    {
        name: 'Zvartnots International Airport',
        nativeName: 'Զվարթնոց միջազգային օդանավակայան',
        iata: 'EVN',
        city: 'Yerevan',
        country: 'Armenia',
        countryFlagCode: 'am'
    },
    {
        name: 'Shirak International Airport',
        nativeName: 'Գյումրի Շիրակ Միջազգային Oդանավակայան',
        iata: 'LWN',
        city: 'Gyumri',
        country: 'Armenia',
        countryFlagCode: 'am'
    },
    {
        name: 'Heydar Aliyev International Airport',
        nativeName: 'Heydar Aliyev adına Beynəlxalq Hava Limanı',
        iata: 'GYD',
        city: 'Baku',
        country: 'Azerbaijan',
        countryFlagCode: 'az'
    },
    {
        name: 'Fuzuli International Airport',
        nativeName: 'Füzuli Beynəlxalq Hava Limanı',
        iata: 'FZL',
        city: 'Fuzuli',
        country: 'Azerbaijan',
        countryFlagCode: 'az'
    },
    {
        name: 'Ganja International Airport',
        nativeName: 'Gəncə Beynəlxalq Hava Limanı',
        iata: 'GNJ',
        city: 'Ganja',
        country: 'Azerbaijan',
        countryFlagCode: 'az'
    },
    {
        name: 'Lankaran International Airport',
        nativeName: 'Lənkəran Beynəlxalq Hava Limanı',
        iata: 'LLK',
        city: 'Lankaran',
        country: 'Azerbaijan',
        countryFlagCode: 'az'
    },
    {
        name: 'Nakhchivan International Airport',
        nativeName: 'Naxçıvan Beynəlxalq Hava Limanı',
        iata: 'NAJ',
        city: 'Nakhchivan',
        country: 'Azerbaijan',
        countryFlagCode: 'az'
    },
    {
        name: 'Qabala International Airport',
        nativeName: 'Qəbələ Beynəlxalq Aeroportu',
        iata: 'GBB',
        city: 'Qabala',
        country: 'Azerbaijan',
        countryFlagCode: 'az'
    },
];
</script>

<template>
    <admin-card class="col-span-12 h-28 flex justify-between">
        <div class="w-full max-w-lg h-14 relative">
            <input placeholder="Search"
                   class="bg-[#F0F3F4] w-full h-full rounded-[1.5rem] border-none focus:ring-0 ps-8 pe-14" />
            <font-awesome-icon :icon="faSearch" class="absolute bottom-1/2 translate-y-1/2 right-8" />
        </div>

        <button class="btn-primary">Add Airport</button>
    </admin-card>

    <admin-card class="col-span-12">
        <h1 class="text-3xl font-semibold mb-3">Manage Airports</h1>
        <div class="font-medium mb-10">117 total</div>

        <table class="w-full">
            <thead class="border-b border-t border-[#D6DDE1] h-14">
                <tr>
                    <th class=""></th>
                    <th class="text-left text-gray-500 font-normal w-1/3">Name</th>
                    <th class="text-left text-gray-500 font-normal">Code</th>
                    <th class="text-left text-gray-500 font-normal">City</th>
                    <th class="text-left text-gray-500 font-normal">Country</th>
                    <th class="text-left text-gray-500 font-normal">Actions</th>
                </tr>
            </thead>
            <tr class="h-4"></tr>
            <tbody>
                <tr v-for="airport in airports" :key="airport.iata" class="h-16">
                    <td></td>
                    <td>
                        <div>{{ airport.name }}</div>
                        <div class="text-sm text-gray-500">{{ airport.nativeName}}</div>
                    </td>
                    <td>{{ airport.iata }}</td>
                    <td>{{ airport.city }}</td>
                    <td>
                        <div class="flex gap-x-2">
                            <div class="w-6"><span :class="['fi', `fi-${airport.countryFlagCode}`]"></span></div>
                            <div>{{ airport.country }}</div>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </admin-card>
</template>
