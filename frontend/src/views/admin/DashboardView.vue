<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faSearch} from "@fortawesome/free-solid-svg-icons";
import AdminCard from "@/components/admin/AdminCard.vue";
</script>

<template>
    <admin-card class="col-span-12 h-28">
        <div class="max-w-lg h-14 relative">
            <input placeholder="Search"
                   class="bg-[#F0F3F4] w-full h-full rounded-[1.5rem] border-none focus:ring-0 ps-8 pe-14" />
            <font-awesome-icon :icon="faSearch" class="absolute bottom-1/2 translate-y-1/2 right-8" />
        </div>
    </admin-card>

    <admin-card class="col-span-12">
        <h1>Hello World!</h1>
    </admin-card>
</template>

