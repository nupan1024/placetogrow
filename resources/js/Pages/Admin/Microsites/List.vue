<script setup>
import { computed, ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Modal from '@/Components/Modal.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const  crumbs = ["Dashboard", "Listado de micrositios"];

const searchFilter = ref('');
const props = defineProps({ microsites: Array });

const filteredItems = computed(() => {
 if(searchFilter.value !== ''){
    return props.microsites.filter(item =>
        item.name.includes(searchFilter.value) ||
        item.type.name.includes(searchFilter.value) ||
        item.category.name.includes(searchFilter.value)
    );
 }
 return props.microsites;
})
const  handleSearch = (search) => {
    searchFilter.value = search;
};
const showModal = ref(false);
</script>

<template>
    <Head title="Micrositios" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Listado de micrositios</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">

                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <SearchForm @search="handleSearch" />
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a :href="route('microsite.create')" class="btn btn-link">Crear micrositio</a>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Categoría</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">
                                <span class="sr-only">Opciones</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="microsite in filteredItems" :key="microsite.id" class="border-b">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ microsite.name }}</td>
                            <td class="px-4 py-3">{{ microsite.category.name }}</td>
                            <td class="px-4 py-3">{{ microsite.type.name }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ microsite.status }}</td>
                            <td class="px-4 py-3 flex items-center justify-end">
                                <a :href="route('microsite.edit', microsite.id)" class="text-indigo-500 hover:underline"> Editar</a>&nbsp;
                                <button @click="showModal=true" class="text-indigo-500 hover:underline"> Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <!-- Contenido del Modal -->
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">Eliminar micrositio</h2>
                    <p class="mt-4">¿Está seguro de eliminar el micrositio ...?</p>
                    <button @click="showModal = false" class="btn mt-4">Cerrar</button>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



