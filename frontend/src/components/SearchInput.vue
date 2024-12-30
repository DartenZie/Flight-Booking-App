<script setup lang="ts">
import {computed, ref} from "vue";
import { throttle } from "lodash";

import FloatingUiSearchFlightsList from "@/components/floating-ui/FloatingUiSearchFlightsList.vue";
import {useFetch} from "@vueuse/core";
import {AirportModel, AirportSearchResponse} from "@/models/airport.model";

const API_URL = process.env.VITE_API_URL;

defineProps<{
    id: string,
    label: string,
    placeholder: string,
}>();

const model = defineModel<AirportModel>();

const searchQuery = ref('');
const suggestions = ref<AirportModel[]>([]);
const isSearching = ref(false);

const location = computed(() => model.value.id ? `${model.value.iata}, ${model.value.city}, ${model.value.country}` : '');

const fetchSuggestions = throttle(async () => {
    if (!searchQuery.value) {
        suggestions.value = [];
        return;
    }

    isSearching.value = true;
    try {
        const { data } = await useFetch<AirportSearchResponse>(`${API_URL}/airport/search?q=${searchQuery.value}`)
            .get().json();

        suggestions.value = data.value.airports
            .filter((_, index) => index < 10);
    } catch (error) {
        console.error('Error fetching suggestions:', error);
    } finally {
        isSearching.value = false;
    }
}, 200);

const selectAirport = (id: number) => {
    const airport = suggestions.value.find((i) => i.id === id);
    if (!airport) {
        return;
    }

    model.value = airport;
    searchQuery.value = airport.name;
};
</script>

<template>
    <div class="relative">
        <div class="border border-slate-200 rounded-lg px-6 h-24">
            <div class="h-full relative">
                <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">{{ label }}</label>
                <input :id="id" type="text" v-model="searchQuery" @input="fetchSuggestions()"
                       v-floating-ui-trigger="{ componentId: `search-input-${id}`, triggerEvent: 'input' }"
                       autocomplete="off" :placeholder="placeholder"
                       class="absolute block outline-none border-none focus:ring-0 font-bold text-2xl p-0 bottom-1/2 translate-y-1/2 w-full" />
                <p class="absolute font-normal text-xs border-b-2 border-transparent bottom-2">{{ location }}</p>
            </div>
        </div>

        <floating-ui-search-flights-list
            :component-id="`search-input-${id}`"
            :suggestion-items="suggestions"
            @select="selectAirport" />
    </div>
</template>
