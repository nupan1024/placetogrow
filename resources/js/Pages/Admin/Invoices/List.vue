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

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.invoices.list];

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

const form = useForm({});
const deleteInvoice = () => {
    form.delete(route('invoice.delete', invoiceId.value), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => loadInvoices(),
    });
}
const searchFields = (text) => {
    searchTerm.value = text;

    loadInvoices(`${route('api.invoices.list')}/?filter=${text}`);
}
const loadInvoices = (url = null) => {
    axios.get(url || route('api.invoices.list')).then((response) => {
        invoices.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadInvoices();
</script>

<template>
    <Head><title>{{ $page.props.$t.invoices.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.invoices.list }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_INVOICE)"
                               :href="route('invoice.create')" class="btn btn-link">{{ $page.props.$t.invoices.create }}</a>
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
            </div>
        </div>
        <Modal :show="isOpenModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.invoices.delete }}</h2>
                    <p class="mt-4">{{ $page.props.$t.invoices.msj_delete }} {{ invoiceCode }}?</p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteInvoice"
                        >
                            {{ $page.props.$t.labels.delete }}
                        </DangerButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



