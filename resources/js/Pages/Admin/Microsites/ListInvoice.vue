<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.microsites.list, usePage().props.$t.invoices.list];

const props = defineProps({ microsite: Object });
const microsite = usePage().props.microsite;

const message = usePage().props.$t.invoices.tooltip;
const searchTerm = ref('');
const invoices = ref([]);
const searchFields = (text) => {
    searchTerm.value = text;

    loadInvoices(`${route('api.admin.microsite.invoices', microsite.id)}/?filter=${text}`);
}
const loadInvoices = (url = null) => {
    axios.get(url || route('api.admin.microsite.invoices', microsite.id)).then((response) => {
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.microsites.list_invoices }} {{ microsite.name  }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
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
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="invoices && invoices.data?.length > 0" class="mt-6 mb-6" :links="invoices.links"
                            :filter="`&filter=${searchTerm}`" :click="loadInvoices"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>



