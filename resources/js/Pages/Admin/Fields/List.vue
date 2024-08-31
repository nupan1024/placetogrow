<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Modal from '@/Components/Modal.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.fields.list];

const props = defineProps({ users: Object });

const message = usePage().props.$t.fields.tooltip;
const searchTerm = ref('');
const fields = ref([]);
const isOpenModal = ref(false);

const closeModal = () => {
    isOpenModal.value = false;

    form.reset();
};

const fieldId = ref('');
const fieldName = ref('');
const openModal = (e) => {
    fieldId.value = e.target.dataset.id ?? "";
    fieldName.value = e.target.dataset.name ?? "";
    isOpenModal.value = true;
};

const form = useForm({});
const deleteField = () => {
    form.delete(route('field.delete', fieldId.value), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => loadFields(),
    });
}
const searchFields = (text) => {
    searchTerm.value = text;

    loadFields(`${route('api.admin.fields')}/?filter=${text}`);
}
const loadFields = (url = null) => {
    axios.get(url || route('api.admin.fields')).then((response) => {
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
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_FIELD)"
                               :href="route('field.create')" class="btn btn-link">{{ $page.props.$t.fields.create }}</a>
                        </div>
                        <SearchForm @search="searchFields" :message="message"/>
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
                                    <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.UPDATE_FIELD)"
                                        :href="route('field.edit', field.id)"
                                       class="btn btn-link">{{ $page.props.$t.labels.edit }}</a>
                                    <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.DELETE_FIELD)"
                                        :data-id="field.id" :data-name="field.name" @click="openModal"
                                       class="btn btn-link">{{ $page.props.$t.labels.delete }}</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="fields && fields.data?.length > 0" class="mt-6 mb-6" :links="fields.links"
                            :filter="`&filter=${searchTerm}`" :click="loadFields"/>
            </div>
        </div>
        <Modal :show="isOpenModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.fields.delete }}</h2>
                    <p class="mt-4">{{ $page.props.$t.fields.msj_delete }} {{ fieldName }}?</p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteField"
                        >
                            {{ $page.props.$t.labels.delete }}
                        </DangerButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



