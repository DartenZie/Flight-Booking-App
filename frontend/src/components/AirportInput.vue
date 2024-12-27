<script setup lang="ts">
import {AirportModel, AirportSearchResponse} from "@/models/airport.model";
import {ref} from "vue";
import {throttle} from "lodash";
import {useFetch} from "@vueuse/core";
import FloatingUiSearchFlightsList from "@/components/floating-ui/FloatingUiSearchFlightsList.vue";

const props = defineProps<{
    id: string,
    placeholder: string,
    class: string
}>();

const model = defineModel<AirportModel>();

const searchQuery = ref('');
const suggestions = ref<AirportModel[]>([]);
const isSearching = ref(false);

const fetchSuggestions = throttle(async () => {
    if (!searchQuery.value) {
        suggestions.value = [];
        return;
    }

    isSearching.value = true;
    try {
        const { data } = await useFetch<AirportSearchResponse>(`http://localhost:8080/airport/search?q=${searchQuery.value}`)
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

const resetSearchQuery = () => {
    searchQuery.value = model.value?.name;
};
</script>

<template>
    <input :id="id" type="text" v-model="searchQuery" @input="fetchSuggestions()" @blur="resetSearchQuery()"
           v-floating-ui-trigger="{ componentId: `search-input-${id}`, triggerEvent: 'input' }"
           autocomplete="off" :placeholder="placeholder" :class="props.class" />

    <floating-ui-search-flights-list
        :component-id="`search-input-${id}`"
        :suggestion-items="suggestions"
        @select="selectAirport" />
</template>
