<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router';
import {computed} from "vue";
import {DialogsWrapper} from "vuejs-confirm-dialog";

const route = useRoute();
const currentLayout = computed(() => route.meta.layoutComponent);
</script>

<template>
    <component :is="currentLayout">
        <RouterView />
    </component>

    <DialogsWrapper />
</template>
