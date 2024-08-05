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

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.microsites.list];

const props = defineProps({ microsites: Object });
const message = usePage().props.$t.microsites.tooltip;
const searchTerm = ref('');
const microsites = ref([]);
const isOpenModal = ref(false);
const closeModal = () => {
    isOpenModal.value = false;

    form.reset();
};

const micrositeId = ref('');
const micrositeName = ref('');
const openModal = (e) => {
    micrositeId.value = e.target.dataset.id ?? "";
    micrositeName.value = e.target.dataset.name ?? "";
    isOpenModal.value = true;
};

const form = useForm({});
const deleteMicrositio = () => {
    form.delete(route('microsite.delete', micrositeId.value), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => loadMicrosites(),
    });
}
const searchMicrosites = (text) => {
    searchTerm.value = text;

    loadMicrosites(`${route('api.admin.microsites.list')}/?filter=${text}`);
}
const loadMicrosites = (url = null) => {
    axios.get(url || route('api.admin.microsites.list')).then((response) => {
        microsites.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadMicrosites();
</script>

<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.microsites.list }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_MICROSITE)"
                               :href="route('microsite.create')" class="btn btn-link">
                                {{ $page.props.$t.microsites.create }}
                            </a>

                        </div>
                        <SearchForm @search="searchMicrosites" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.categories.title }}</th>
                                <th scope="col">{{ $page.props.$t.labels.type }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="microsite in microsites.data" :key="microsite.id" class="hover">
                                <td>{{ microsite.name }}</td>
                                <td>{{ microsite.category.name }}</td>
                                <td>{{ microsite.type.name }}</td>
                                <td>{{ microsite.status }}</td>
                                <td>
                                    <a :href="route('micrositio.form', microsite.id)"
                                       target="_blank" class="text-indigo-500 hover:underline">
                                        {{ $page.props.$t.labels.see }}
                                    </a>&nbsp
                                    <a :href="route('microsite.edit', microsite.id)"
                                       v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.UPDATE_MICROSITE)"
                                       class="text-indigo-500 hover:underline">
                                        {{ $page.props.$t.labels.edit }}
                                    </a>&nbsp;
                                    <button :data-id="microsite.id" :data-name="microsite.name"
                                            v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.DELETE_MICROSITE)"
                                            @click="openModal" class="text-indigo-500 hover:underline">
                                        {{ $page.props.$t.labels.delete }}
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="microsites && microsites.data?.length > 0" class="mt-6 mb-6" :links="microsites.links"
                            :filter="`&filter=${searchTerm}`" :click="loadMicrosites"/>
            </div>
        </div>
        <Modal :show="isOpenModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.microsites.delete }}</h2>
                    <p class="mt-4">{{ $page.props.$t.microsites.msj_delete }} {{ micrositeName }}?</p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteMicrositio"
                        >
                            {{ $page.props.$t.labels.delete }}
                        </DangerButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



