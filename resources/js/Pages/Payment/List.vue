<script setup>
import { ref } from 'vue';

import { Head, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Pagination from '@/Components/Pagination.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.payments.list];

const props = defineProps({ payments: Object });
const message = usePage().props.$t.payments.tooltip;
const searchTerm = ref('');
const payments = ref([]);
const user = usePage().props.auth.user ?? '';

const searchPayments = (text) => {
    searchTerm.value = text;

    loadPayments(`${route('api.user.payments', user.id)}/?filter=${text}`);
}
const loadPayments = (url = null) => {
    axios.get(url || route('api.user.payments', user.id)).then((response) => {
        payments.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadPayments();
</script>

<template>
    <Head><title>{{ $page.props.$t.payments.list }}</title></Head>
    <GuestLayout>
        <div class="text-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.payments.list }}</h2>
        </div>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-end text-sm font-semibold">
                        <SearchForm @search="searchPayments" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.payments.request }}</th>
                                <th scope="col">{{ $page.props.$t.payments.type }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                                <th scope="col">{{ $page.props.$t.labels.value }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="payment in payments.data" :key="payment.id" class="hover">
                                <td>{{ payment.request_id }}</td>
                                <td>{{ payment.microsite_type_name }}</td>
                                <td>{{ payment.status }}</td>
                                <td>$ {{ payment.value }}</td>
                                <td>
                                    <a :href="route('payment.detail', payment.id)"
                                       target="_blank" class="text-indigo-500 hover:underline">
                                        {{ $page.props.$t.labels.see }}
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="payments && payments.data?.length > 0" class="mt-6 mb-6" :links="payments.links"
                            :filter="`&filter=${searchTerm}`" :click="loadPayments"/>
            </div>
        </div>
    </GuestLayout>
</template>



