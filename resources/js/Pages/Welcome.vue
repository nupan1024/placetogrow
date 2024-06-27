<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
defineProps({
    microsites: Array,
    canLogin: {
        type: Boolean,
    }
});
</script>

<template>
    <Head title="Welcome" />
    <GuestLayout>
        <div class="navbar bg-base-100 flex flex-col sm:flex-row items-center justify-between">
            <div class="flex-1 text-center sm:text-left">
                <a class="btn btn-ghost text-xl" href="/">Place to Grow</a>
            </div>
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1 text-base flex flex-col sm:flex-row">
                    <li v-if="$page.props.auth.user">
                        <a :href="route('dashboard')">Dashboard</a>
                    </li>
                    <li v-else>
                        <a :href="route('login')">Iniciar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="text-center py-12 underline">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Listado de micrositios</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 py-6 ml-12">
            <div v-for="microsite in microsites" :key="microsite.id" class="card card-compact bg-base-100 w-96 shadow-xl">
                <figure>
                    <img
                        src="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                        alt="Micrositio" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{{ microsite.name }}</h2>
                    <p>Categoría: {{ microsite.category.name }}</p>
                    <p> {{ microsite.description }}</p>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">Visitar</button>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
