<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import SearchForm from '@/Components/SearchForm.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import LogoMicrositio from '@/Components/LogoMicrositio.vue';

defineProps({
    microsites: Object,
    canLogin: {
        type: Boolean,
    }
});

const searchTerm = ref('');
const microsites = ref([]);
const message = "Puedes buscar micrositios por nombre, descripción o categoría";
const searchMicrosites = (text, cat) => {
    searchTerm.value = text;

    loadMicrosites(`${route('api.microsites.list')}/?filter=${text}`);
}
const loadMicrosites = (url = null) => {
    axios.get(url || route('api.microsites.list')).then((response) => {
        microsites.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadMicrosites();
</script>

<template>
    <Head><title>Micrositios</title></Head>
    <GuestLayout>

        <div class="flex p-4 border-b-2 justify-between items-center text-center mb-6">
            <div class="shrink-0 flex items-center">
                <Link :href="route('home')">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </Link>

            </div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight underline">Listado de micrositios</h2>
            <div>
                <div class="text-right pr-4">
                    <div v-if="$page.props.auth.user">
                        <Link v-if="$page.props.auth.user.role_id !== 1" :href="route('logout')" method="post" as="button">Cerrar sesión</Link>
                        <a v-else :href="route('dashboard')">Dashboard</a>
                    </div>
                    <div v-else>
                        <a :href="route('login')">Iniciar sesión</a>
                    </div>
                </div>
                <SearchForm @search="searchMicrosites" :message="message" />
            </div>

        </div>

        <div class="container px-3 mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 py-4">
            <div v-for="microsite in microsites.data" :key="microsite.id" class="card card-compact bg-base-100 shadow-lg mt-6">
                <div class="text-center mt-4" v-if="microsite.logo_path">
                    <LogoMicrositio :url="`/storage/${microsite.logo_path}`" />
                </div>
                <div class="card-body">
                    <h2 class="card-title">{{ microsite.name }}</h2>
                    <p>Categoría: {{ microsite.category }}</p>
                    <p> {{ microsite.description }}</p>
                    <div class="card-actions justify-end">
                        <a class="btn btn-link" :href="route('micrositio.form', microsite.id)">Visitar</a>
                    </div>
                </div>
            </div>
        </div>

        <Pagination v-if="microsites && microsites.data?.length > 0" class="mt-6 mb-6" :links="microsites.links"
                    :filter="`&filter=${searchTerm}`" :click="loadMicrosites"/>
    </GuestLayout>
</template>
