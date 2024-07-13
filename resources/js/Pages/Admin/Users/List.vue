<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Modal from '@/Components/Modal.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.users.list];

const props = defineProps({ users: Object });

const message = usePage().props.$t.users.tooltip;
const searchTerm = ref('');
const users = ref([]);
const isOpenModal = ref(false);
const closeModal = () => {
    isOpenModal.value = false;

    form.reset();
};

const userId = ref('');
const userName = ref('');
const openModal = (e) => {
    userId.value = e.target.dataset.id ?? "";
    userName.value = e.target.dataset.name ?? "";
    isOpenModal.value = true;
};

const form = useForm({});
const deleteUser = () => {
    form.delete(route('user.delete', userId.value), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => router.visit('/users'),
    });
}
const searchUsers = (text) => {
    searchTerm.value = text;

    loadUsers(`${route('api.users.list')}/?filter=${text}`);
}
const loadUsers = (url = null) => {
    axios.get(url || route('api.users.list')).then((response) => {
        users.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadUsers();
</script>

<template>
    <Head><title>{{ $page.props.$t.users.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.users.list }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a :href="route('user.create')" class="btn btn-link">{{ $page.props.$t.users.create }}</a>
                        </div>
                        <SearchForm @search="searchUsers" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.email }}</th>
                                <th scope="col">{{ $page.props.$t.labels.role }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id" class="hover">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.role.name }}</td>
                                <td>{{ user.status }}</td>
                                <td>
                                    <a :href="route('user.edit', user.id)" class="text-indigo-500 hover:underline"> {{ $page.props.$t.labels.edit }}</a>&nbsp;
                                    <button :data-id="user.id" :data-name="user.name" @click="openModal" class="text-indigo-500 hover:underline"> {{ $page.props.$t.labels.delete }}</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="users && users.data?.length > 0" class="mt-6 mb-6" :links="users.links"
                            :filter="`&filter=${searchTerm}`" :click="loadUsers"/>
            </div>
        </div>
        <Modal :show="isOpenModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.users.delete }}</h2>
                    <p class="mt-4">{{ $page.props.$t.users.msj_delete }} {{ userName }}?</p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteUser"
                        >
                            {{ $page.props.$t.labels.delete }}
                        </DangerButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



