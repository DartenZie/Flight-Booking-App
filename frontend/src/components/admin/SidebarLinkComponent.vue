<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {IconDefinition} from "@fortawesome/free-solid-svg-icons";

defineProps<{
    to: string;
    icon: IconDefinition,
    label: string;
}>();
</script>

<template>
    <router-link :to="to" active-class="admin-link-active" class="admin-link">
        <div class="px-8 flex h-full items-center gap-x-4">
            <font-awesome-icon :icon="icon" class="w-4" />
            <div>{{ label }}</div>
        </div>
    </router-link>
</template>
