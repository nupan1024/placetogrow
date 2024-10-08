<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import SearchForm from '@/Components/SearchForm.vue';

const message = usePage().props.$t.roles.tooltip;
const searchTerm = ref('');
const props = defineProps({ roles: Array });
const roles = ref([]);
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.roles.list];
const roleId = ref('');
const roleName = ref('');
const isOpenModal = ref(false);
const rolesProtect = [usePage().props.auth.roles.SUPER_ADMIN, usePage().props.auth.roles.GUEST]
const closeModal = () => {
    isOpenModal.value = false;

    form.reset();
};
const openModal = (e) => {
    roleId.value = e.target.dataset.id ?? "";
    roleName.value = e.target.dataset.name ?? "";
    isOpenModal.value = true;
};
const searchRoles = (text) => {
    searchTerm.value = text;

    loadRoles(`${route('api.admin.roles')}/?filter=${text}`);
}
const loadRoles = (url = null) => {
    axios.get(url || route('api.admin.roles')).then((response) => {
        roles.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
const form = useForm({});
const deleteRole = () => {
    form.delete(route('role.delete', roleId.value), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => loadRoles()
    });
}
loadRoles();

</script>

<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.roles.list }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_ROLE)"
                               :href="route('role.create')"
                               class="btn btn-link">
                                {{ $page.props.$t.roles.create }}
                            </a>
                        </div>
                        <SearchForm @search="searchRoles" :message="message"/>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="role in roles.data" :key="role.id" class="hover">
                                <td>{{ role.name }}</td>
                                <td>
                                    <div v-if="!rolesProtect.includes(role.name)">
                                        <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.UPDATE_ROLE)"
                                           :href="route('role.edit', role.id)" class="text-indigo-500 hover:underline">
                                            {{ $page.props.$t.labels.edit }}
                                        </a>&nbsp;
                                        <button v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.DELETE_ROLE)"
                                                :data-id="role.id" :data-name="role.name" @click="openModal"
                                                class="text-indigo-500 hover:underline">
                                            {{ $page.props.$t.labels.delete }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="roles && roles.data?.length > 0" class="mt-6 mb-6" :links="roles.links"
                            :filter="`&filter=${searchTerm}`" :click="loadRoles"/>

            </div>
        </div>
        <Modal :show="isOpenModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.roles.delete }}</h2>
                    <p class="mt-4">{{ $page.props.$t.roles.msj_delete }} {{ roleName }}?</p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteRole"
                        >
                            {{ $page.props.$t.labels.delete }}
                        </DangerButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
