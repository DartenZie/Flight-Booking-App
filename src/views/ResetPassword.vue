<script setup lang="ts">
import FormControl from "@/components/FormControl.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <div class="h-screen flex items-center justify-center">
        <div class="w-full max-w-screen-lg grid grid-cols-1 lg:grid-cols-7 gap-x-36">
            <div class="lg:col-span-3 h-full content-center">
                <h1 class="font-medium text-3xl mb-10">Back to the Skies!</h1>

                <form>
                    <div class="mb-6">
                        <form-control id="login" label="E-mail" type="email" placeholder="johndoe@gmail.com" />
                    </div>

                    <div class="flex justify-between items-center">
                        <router-link class="btn-text h-8" to="/sign-in">Remembered?</router-link>

                        <button class="btn-primary h-12" type="submit">
                            <span class="me-7">Submit</span>
                            <font-awesome-icon :icon="faChevronRight" />
                        </button>
                    </div>
                </form>
            </div>
            <div class="lg:col-span-4 h-full content-center">
                <img src="@/assets/illustrations/forgot_password_illustration.svg" alt="Forgot password illustration" class="w-full" />
            </div>
        </div>
    </div>
</template>
