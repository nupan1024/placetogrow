<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Modal from '@/Components/Modal.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import FileInput from '@/Components/FileInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Select from '@/Components/Select.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.invoices.list, usePage().props.$t.invoices.list_import];

const message = usePage().props.$t.invoices.tooltip_import;
const searchTerm = ref('');
const imports = ref([]);
const modalImport = ref(false);
const microsites = usePage().props.microsites;

const openModal = (e) => {
    if (e.target.dataset.import) {
        modalImport.value = true;
    }
};

const form = useForm({});
const formImport = useForm({
    file: ref(''),
    microsite_id: ref(''),
});
const submitImport = () => {
    formImport.post(route('invoices.import'), {
        forceFormData: true,
        onSuccess: () => modalImport.value = false,
        onFinish: () => loadImports(),
    });
}
const searchImports = (text) => {
    searchTerm.value = text;

    loadImports(`${route('api.admin.invoices.imports')}/?filter=${text}`);
}
const loadImports = (url = null) => {
    axios.get(url || route('api.admin.invoices.imports')).then((response) => {
        imports.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadImports();
</script>

<template>
    <Head><title>{{ $page.props.$t.invoices.list_import }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.invoices.list_import }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_INVOICE)"
                               :data-import="true" @click="openModal" class="btn btn-link">{{ $page.props.$t.invoices.import }}</a>
                        </div>
                        <SearchForm @search="searchImports" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.file_name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                                <th scope="col">{{ $page.props.$t.labels.errors }}</th>
                                <th scope="col">{{ $page.props.$t.labels.created_at }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="data in imports.data" :key="data.id" class="hover">
                                <td>{{ data.user }}</td>
                                <td>{{ data.file_name }}</td>
                                <td>{{ data.status }}</td>
                                <td>
                                    <div v-if="data.errors && data.errors.length > 0" tabindex="0" class="collapse bg-base-200">
                                        <div class="collapse-title">{{ $page.props.$t.labels.title_errors }}</div>
                                        <div class="collapse-content">
                                            <p v-for="error in data.errors">{{ error }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ data.created_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="imports && imports.data?.length > 0" class="mt-6 mb-6" :links="imports.links"
                            :filter="`&filter=${searchTerm}`" :click="loadImports"/>
            </div>
        </div>

        <Modal :show="modalImport" :data-import="true">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.invoices.import }}</h2>
                    <p class="mt-4">{{ $page.props.$t.invoices.msj_import }}</p>
                    <form @submit.prevent="submitImport">
                        <div class="mt-4">
                            <InputLabel for="microsite_id" :value="$page.props.$t.invoices.microsites" />
                            <Select id="microsite_id"
                                    class="input mt-1 block w-full select"
                                    required
                                    v-model="formImport.microsite_id"
                                    :options="microsites"
                            />
                            <InputError class="mt-2" :message="formImport.errors.microsite_id" />
                        </div>

                        <div class="mt-3">
                            <InputLabel for="file" :value="$page.props.$t.labels.import"/>
                            <FileInput
                                class="input mt-1 block w-full"
                                v-model="formImport.file"
                                required
                                autofocus
                                autocomplete="on"
                                accept=".csv,.xlsx"
                            />
                            <InputError class="mt-2" :message="formImport.errors.file"/>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="modalImport=false"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                            <div class="ml-3">
                                <button class="btn" :disabled="formImport.processing">
                                    {{ $page.props.$t.labels.import }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </template>
        </Modal>

    </AuthenticatedLayout>
</template>



