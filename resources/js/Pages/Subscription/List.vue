<script setup>
import { ref } from 'vue';

import { Head, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Pagination from '@/Components/Pagination.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({ user: Object });

const searchTerm = ref('');
const user = usePage().props.auth.user ?? '';
const subscriptions = ref([]);

const searchFields = (text) => {
    searchTerm.value = text;

    loadSubscriptions(`${route('api.user.subscriptions', user.id)}/?filter=${text}`);
}
const loadSubscriptions = (url = null) => {
    axios.get(url || route('api.user.subscriptions', user.id)).then((response) => {
        subscriptions.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadSubscriptions();
</script>

<template>
    <Head><title>{{ $page.props.$t.subscriptions.list }}</title></Head>
    <GuestLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.subscriptions.list }}</h2>
        </template>
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ $page.props.$t.subscriptions.list }}</h2>

        </div>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <SearchForm @search="searchFields"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.labels.customer_name }}</th>
                                <th scope="col">{{ $page.props.$t.microsites.microsite }}</th>
                                <th scope="col">{{ $page.props.$t.subscriptions.description_subscription }}</th>
                                <th scope="col">{{ $page.props.$t.subscriptions.amount }}</th>
                                <th scope="col">{{ $page.props.$t.subscriptions.billing_frequency }}</th>
                                <th scope="col">{{ $page.props.$t.labels.status }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="subscription in subscriptions.data" :key="subscription.id" class="hover">
                                <td>{{ subscription.name }}</td>
                                <td>{{ subscription.microsite_name }}</td>
                                <td>{{ subscription.description.slice(0,100) }}</td>
                                <td>$ {{ subscription.amount }}</td>
                                <td>{{ subscription.frequency }} {{ $page.props.$t.subscriptions.days }}</td>
                                <td>{{ subscription.status }}</td>
                                <td>
                                    <a :href="route('payment.subscription.detail', subscription.payment_id)"
                                       target="_blank" class="text-indigo-500 hover:underline">
                                        {{ $page.props.$t.labels.see }}
                                    </a>&nbsp;
                                    <a :href="route('payment.subscription.detail', subscription.payment_id)"
                                       target="_blank" class="text-indigo-500 hover:underline">
                                        {{ $page.props.$t.subscriptions.delete }}
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="subscriptions && subscriptions.data?.length > 0" class="mt-6 mb-6" :links="subscriptions.links"
                            :filter="`&filter=${searchTerm}`" :click="loadSubscriptions"/>
            </div>
        </div>
    </GuestLayout>
</template>



