<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.microsites.list, usePage().props.$t.subscriptions.list];

const props = defineProps({ microsite: Object });
const microsite = usePage().props.microsite;

const message = usePage().props.$t.subscriptions.tooltip;
const searchTerm = ref('');
const subscriptions = ref([]);

const form = useForm({});
const searchFields = (text) => {
    searchTerm.value = text;

    loadSubscriptions(`${route('api.admin.microsite.subscriptions',microsite.id)}/?filter=${text}`);
}
const loadSubscriptions = (url = null) => {
    axios.get(url || route('api.admin.microsite.subscriptions',microsite.id)).then((response) => {
        subscriptions.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadSubscriptions();
</script>

<template>
    <Head><title>{{ $page.props.$t.subscriptions.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.microsites.list_subscriptions }} {{ microsite.name }}</h2>
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
                                <th scope="col">{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.description }}</th>
                                <th scope="col">{{ $page.props.$t.labels.currency }}</th>
                                <th scope="col">{{ $page.props.$t.subscriptions.amount }}</th>
                                <th scope="col">{{ $page.props.$t.invoices.microsites }}</th>
                                <th scope="col">{{ $page.props.$t.subscriptions.time_expire }}</th>
                                <th scope="col">{{ $page.props.$t.subscriptions.billing_frequency }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="subscription in subscriptions.data" :key="subscription.id" class="hover">
                                <td>{{ subscription.name }}</td>
                                <td>{{ subscription.description.slice(0, 100) }}</td>
                                <td>{{ subscription.currency }}</td>
                                <td>${{ subscription.amount }}</td>
                                <td>{{ subscription.microsite }}</td>
                                <td>{{ subscription.time_expire }}</td>
                                <td>{{ subscription.billing_frequency }}</td>
                                <td>{{ subscription.status }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="subscriptions && subscriptions.data?.length > 0" class="mt-6 mb-6"
                            :links="subscriptions.links"
                            :filter="`&filter=${searchTerm}`" :click="loadSubscriptions"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>



