<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({ roles: Array });
const roles = usePage().props.roles;
const  crumbs = ['Dashboard', 'Listado de roles'];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Listado de roles</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="role in roles" :key="role.id" class="hover">
                                <th>{{ role.name }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
