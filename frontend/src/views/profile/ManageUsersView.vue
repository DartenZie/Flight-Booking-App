<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {onMounted, ref} from "vue";
import {UserResponse, UsersResponse} from "@/models/user.model";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {createConfirmDialog} from "vuejs-confirm-dialog";
import ChangeUserRoleModal from "@/components/modals/ChangeUserRoleModal.vue";
import {useAuthStore} from "@/store/auth.store";

const API_URL = process.env.VITE_API_URL;

const changeUserDialog = createConfirmDialog(ChangeUserRoleModal, {});

const authStore = useAuthStore();

const users = ref<UsersResponse>(null);

onMounted(async () => {
    await loadUsers();
});

const loadUsers = async () => {
    const { data } = await useAuthenticatedFetch<UsersResponse>(`${API_URL}/users`).get().json();
    users.value = data.value;
};

changeUserDialog.onConfirm(async (res: { id: int, role: string }) => {
    const body = {
        id: res.id,
        role_id: { 'user': 1, 'flightManager': 2, 'admin': 3 }[role],
    };
    const response = await useAuthenticatedFetch<UserResponse>(`${API_URL}/users`).put(body).json();
    if (response.statusCode.value === 200) {
        await loadUsers();
    }
    changeUserDialog.close();
});
changeUserDialog.onCancel(changeUserDialog.close);
</script>

<template>
    <admin-card v-if="users" class="col-span-12">
        <div class="flex justify-between">
            <div>
                <h1 class="text-3xl font-semibold mb-3">Manage Users</h1>
                <div class="mb-10">Found total of <span class="font-bold">{{ users.total }}</span> users</div>
            </div>
        </div>

        <table class="w-full">
            <thead class="border-b border-t border-[#D6DDE1] h-14">
                <tr>
                    <th class="text-left text-gray-500 font-normal">Name</th>
                    <th class="text-left text-gray-500 font-normal">Email</th>
                    <th class="text-left text-gray-500 font-normal">Role</th>
                    <th class="text-left text-gray-500 font-normal w-48">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.users" :key="user.id" class="h-16">
                    <td>
                        <div>{{ user.firstName }} {{ user.lastName }}</div>
                    </td>
                    <td>
                        <div>{{ user.email }}</div>
                    </td>
                    <td>
                        <div>{{ { 'user': 'User', 'flightManager': 'Flight Manager', 'admin': 'Admin' }[user.role] }}</div>
                    </td>
                    <td>
                        <div v-if="authStore.user?.id !== user.id" class="inline-block">
                            <button class="btn-primary h-12" @click="changeUserDialog.reveal({ user: user })">Change Role</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </admin-card>
</template>
