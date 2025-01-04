<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {onMounted, ref} from "vue";
import {UserResponse, UsersResponse} from "@/models/user.model";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {createConfirmDialog} from "vuejs-confirm-dialog";
import ChangeUserRoleModal from "@/components/modals/ChangeUserRoleModal.vue";
import {useAuthStore} from "@/store/auth.store";
import {faPen} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const API_URL = process.env.VITE_API_URL;

const changeUserDialog = createConfirmDialog(ChangeUserRoleModal, {});

const authStore = useAuthStore();

const users = ref<UsersResponse>(null);

onMounted(async () => {
    await loadUsers();
});

const loadUsers = async () => {
    const { data } = await useAuthenticatedFetch<UsersResponse>(`${API_URL}/user/list`).get().json();
    users.value = data.value;
};

changeUserDialog.onConfirm(async (res: { id: number, role: string }) => {
    if (!res.id || !res.role) {
        return;
    }

    const body = {
        id: res.id,
        roleId: { 'user': 1, 'flightManager': 2, 'admin': 3 }[res.role],
    };
    const response = await useAuthenticatedFetch<UserResponse>(`${API_URL}/user/update`).put(body).json();
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

        <div class="w-full overflow-y-auto">
            <table class="min-w-full w-max">
                <thead class="border-b border-t border-[#D6DDE1] h-14">
                    <tr>
                        <th class="text-left text-gray-500 font-normal">Name</th>
                        <th class="text-left text-gray-500 font-normal">Email</th>
                        <th class="text-left text-gray-500 font-normal">Role</th>
                        <th class="text-right lg:text-left text-gray-500 font-normal lg:w-48">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.users" :key="user.id" class="h-16">
                        <td class="pe-2">
                            <div>{{ user.firstName }} {{ user.lastName }}</div>
                        </td>
                        <td class="px-2">
                            <div>{{ user.email }}</div>
                        </td>
                        <td class="px-2">
                            <div>{{ { 'user': 'User', 'flightManager': 'Flight Manager', 'admin': 'Admin', 'super_admin': 'Super Admin' }[user.role] }}</div>
                        </td>
                        <td class="ps-2">
                            <div v-if="authStore.user?.id !== user.id" class="flex justify-end lg:inline-block">
                                <button class="hidden lg:flex btn-primary h-12" @click="changeUserDialog.reveal({ user: user })">Change Role</button>
                                <button class="lg:hidden justify-end btn-text h-12" @click="changeUserDialog.reveal({ user: user })">
                                    <font-awesome-icon :icon="faPen" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </admin-card>
</template>
