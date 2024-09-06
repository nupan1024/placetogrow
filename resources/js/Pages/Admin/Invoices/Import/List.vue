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
import InputLabel from '@/Components/InputLabel.vue';
import FileInput from '@/Components/FileInput.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/Components/Select.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.invoices.list, usePage().props.$t.invoices.list_import];

const props = defineProps({ users: Object });

const message = usePage().props.$t.invoices.tooltip;
const searchTerm = ref('');
const invoices = ref([]);
const isOpenModal = ref(false);

const closeModal = () => {
    isOpenModal.value = false;

    form.reset();
};

const invoiceId = ref('');
const invoiceCode = ref('');
const openModal = (e) => {
    invoiceId.value = e.target.dataset.id ?? "";
    invoiceCode.value = e.target.dataset.code ?? "";
    isOpenModal.value = true;
};

const form = useForm({
    file: '',
    microsite_id: '',
});
const deleteInvoice = () => {
    form.post(route('invoices.import'), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => loadInvoices(),
    });
}
const searchFields = (text) => {
    searchTerm.value = text;

    loadInvoices(`${route('api.admin.invoices')}/?filter=${text}`);
}
const loadInvoices = (url = null) => {
    axios.get(url || route('api.admin.invoices')).then((response) => {
        invoices.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadInvoices();
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
                               @click="openModal" class="btn btn-link">{{ $page.props.$t.invoices.import }}</a>
                        </div>
                        <SearchForm @search="searchFields" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.invoices.code }}</th>
                                <th scope="col">{{ $page.props.$t.invoices.name_client }}</th>
                                <th scope="col">{{ $page.props.$t.invoices.microsites }}</th>
                                <th scope="col">{{ $page.props.$t.labels.description }}</th>
                                <th scope="col">{{ $page.props.$t.invoices.value }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="invoice in invoices.data" :key="invoice.id" class="hover">
                                <td>{{ invoice.code }}</td>
                                <td>{{ invoice.user }}</td>
                                <td>{{ invoice.microsite }}</td>
                                <td>{{ invoice.description.slice(0,100) }}</td>
                                <td>{{ invoice.value }}</td>
                                <td>{{ invoice.status }}</td>
                                <td>
                                    <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.UPDATE_INVOICE)"
                                       :href="route('invoice.edit', invoice.id)"
                                       class="btn btn-link">{{ $page.props.$t.labels.edit }}</a>
                                    <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.DELETE_INVOICE)"
                                       :data-id="invoice.id" :data-code="invoice.code" @click="openModal"
                                       class="btn btn-link">{{ $page.props.$t.labels.delete }}</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="invoices && invoices.data?.length > 0" class="mt-6 mb-6" :links="invoices.links"
                            :filter="`&filter=${searchTerm}`" :click="loadInvoices"/>
            <nModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h1 class="text-lg font-semibold">{{ $page.props.$t.invoices.import }}</h1>
                    <form enctype="multipart/form-data">
                        <div class="mt-4">
                            <InputLabel for="microsite_id" :value="$page.props.$t.invoices.microsites" />
                            <Select id="microsite_id"
                                    class="input mt-1 block w-full select"
                                    required
                                    v-model="form.microsite_id"
                                    :options="microsites"
                            />
                            <InputError class="mt-2" :message="form.errors.microsite_id" />
                        </div>
                        <div class="mt-4">
                            <FileInput
                                class="input mt-1 block w-full"
                                v-model="form.file"
                                required
                                autofocus
                                autocomplete="on"
                                accept=".csv, .xlsx"
                            />
                            <InputError class="mt-2" :message="form.errors.file"/>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                            <DangerButton
                                class="ml-3"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click="deleteInvoice"
                            >
                                {{ $page.props.$t.labels.import }}
                            </DangerButton>
                        </div>
                    </form>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



