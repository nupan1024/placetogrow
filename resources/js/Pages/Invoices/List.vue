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
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({ user: Object });

const message = usePage().props.$t.invoices.tooltip;
const searchTerm = ref('');
const user = usePage().props.auth.user ?? '';
const invoices = ref([]);

const searchFields = (text) => {
    searchTerm.value = text;

    loadInvoices(`${route('api.user.invoices', user.id)}/?filter=${text}`);
}
const loadInvoices = (url = null) => {
    axios.get(url || route('api.user.invoices', user.id)).then((response) => {
        invoices.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadInvoices();
</script>

<template>
    <Head><title>{{ $page.props.$t.invoices.title }}</title></Head>
    <GuestLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.invoices.list }}</h2>
        </template>
        <div class="py-12">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ $page.props.$t.invoices.list }}</h2>
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
                                <td>{{ invoice.microsite }}</td>
                                <td>{{ invoice.description.slice(0,100) }}</td>
                                <td>{{ invoice.value }}</td>
                                <td>{{ invoice.status }}</td>
                                <td>
                                    <a v-if="invoice.status === $page.props.$t.invoices.pending" :href="route('micrositio.form', invoice.microsite_id)"
                                       class="btn btn-link">{{ $page.props.$t.invoices.pay }}</a>
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
    </GuestLayout>
</template>



