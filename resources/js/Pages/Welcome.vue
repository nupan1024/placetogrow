<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import SearchForm from '@/Components/SearchForm.vue';
import LogoMicrositio from '@/Components/LogoMicrositio.vue';

defineProps({
    microsites: Object,
    canLogin: {
        type: Boolean,
    }
});

const searchTerm = ref('');
const microsites = ref([]);
const message = usePage().props.$t.microsites.tooltip;
const searchMicrosites = (text, cat) => {
    searchTerm.value = text;

    loadMicrosites(`${route('api.microsites')}/?filter=${text}`);
}
const loadMicrosites = (url = null) => {
    axios.get(url || route('api.microsites')).then((response) => {
        microsites.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadMicrosites();
</script>

<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>
    <GuestLayout>
        <div class="flex items-center">
            <div class="p-4 grow">
                <h2 class="mx-auto text-center font-semibold text-2xl text-gray-800 leading-tight underline">{{ $page.props.$t.microsites.list }}</h2>
            </div>
            <SearchForm class="" @search="searchMicrosites" :message="message" />
        </div>

        <div class="container px-3 mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 py-4">

            <div v-for="microsite in microsites.data" :key="microsite.id" class="card card-compact bg-base-100 shadow-lg mt-6">
                <a :href="route('form.microsite', microsite.id)">
                    <div class="text-center mt-4" v-if="microsite.logo_path">
                        <LogoMicrositio :url="`/storage/${microsite.logo_path}`" />
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">{{ microsite.name }}</h2>
                        <p>{{ $page.props.$t.categories.title }}: {{ microsite.category }}</p>
                        <p> {{ microsite.description }}</p>
                    </div>
                </a>
            </div>
        </div>

        <Pagination v-if="microsites && microsites.data?.length > 0" class="mt-6 mb-6" :links="microsites.links"
                    :filter="`&filter=${searchTerm}`" :click="loadMicrosites"/>
    </GuestLayout>
</template>
