<script setup lang="ts">
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
import Dropzone from "dropzone";
import {useAuthStore} from "@/store/auth.store";
import {useAirlineStore} from "@/store/airline.store";

const API_URL = process.env.VITE_API_URL;

const router = useRouter();

const auth = useAuthStore();
const airlineStore = useAirlineStore();

const airlineId = router.currentRoute.value.params.airlineId;

const dropzoneElement = ref<HTMLElement | null>(null);
const uploadStatus = ref<string>('');

onMounted(() => {
    if (dropzoneElement.value) {
        const dropzone = new Dropzone(dropzoneElement.value, {
            url: `${API_URL}/airline/logo?airlineId=${airlineId}`,
            acceptedFiles: 'image/*',
            maxFiles: 1,
            addRemoveLinks: true,
            dictDefaultMessage: 'Drag and drop a logo here, or click to upload',
            headers: {
                Authorization: `Bearer ${auth.accessToken}`
            },
            params: {
                airlineId: airlineId,
            }
        });

        dropzone.on('success', (file, response) => {
            uploadStatus.value = `Upload successful: ${response.path}`;
            airlineStore.invalidateCache();
        });

        dropzone.on('error', (file, errorMessage) => {
            if (errorMessage === 'incomplete_login_credentials') {

            }
            uploadStatus.value = `Failed to upload logo: ${errorMessage}`;
        });
    }
});
</script>

<template>
    <div>
        <div ref="dropzoneElement" class="dropzone"></div>
        <p>{{ uploadStatus }}</p>
    </div>
</template>
