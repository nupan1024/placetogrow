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

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.fields.list];

const props = defineProps({ users: Object });

const message = usePage().props.$t.users.tooltip;
const searchTerm = ref('');
const fields = ref([]);
const isOpenModal = ref(false);
const rolesProtect = [usePage().props.auth.roles.SUPER_ADMIN, usePage().props.auth.roles.GUEST]

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
        onFinish: () => loadFields(),
    });
}
const searchUsers = (text) => {
    searchTerm.value = text;

    loadFields(`${route('api.fields.list')}/?filter=${text}`);
}
const loadFields = (url = null) => {
    axios.get(url || route('api.fields.list')).then((response) => {
        fields.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadFields();
</script>

<template>
    <Head><title>{{ $page.props.$t.fields.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.fields.list }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_USER)"
                               :href="route('fields.create')" class="btn btn-link">{{ $page.props.$t.fields.create }}</a>
                        </div>
                        <SearchForm @search="searchUsers" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.fields.label }}</th>
                                <th scope="col">{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.type }}</th>
                                <th scope="col">{{ $page.props.$t.fields.attributes }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="field in fields.data" :key="field.id" class="hover">
                                <td>{{ field.label }}</td>
                                <td>{{ field.name }}</td>
                                <td>{{ field.type }}</td>
                                <td>{{ field.attributes }}</td>
                                <td>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="users && users.data?.length > 0" class="mt-6 mb-6" :links="users.links"
                            :filter="`&filter=${searchTerm}`" :click="loadFields"/>
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



