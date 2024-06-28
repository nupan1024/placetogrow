<script setup>
import { computed, ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Modal from '@/Components/Modal.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';

const  crumbs = ["Dashboard", "Listado de micrositios"];

const searchFilter = ref('');
const props = defineProps({ microsites: Object });

const message = "Puedes buscar micrositios por nombre, tipo o categoría";
const searchTerm = ref('');
const microsites = ref([]);

const searchMicrosites = (text, cat) => {
    searchTerm.value = text;

    loadMicrosites(`${route('api.admin.microsites.list')}/?filter=${text}`);
}
const loadMicrosites = (url = null) => {
    axios.get(url || route('api.admin.microsites.list')).then((response) => {
        microsites.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadMicrosites();
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
                        <SearchForm @search="searchMicrosites" :message="message"/>
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a :href="route('microsite.create')" class="btn btn-link">Crear micrositio</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="microsite in microsites.data" :key="microsite.id" class="hover">
                                <th>{{ microsite.name }}</th>
                                <td>{{ microsite.category }}</td>
                                <td>{{ microsite.type }}</td>
                                <td>{{ microsite.status }}</td>
                                <td>
                                    <a :href="route('microsite.edit', microsite.id)" class="text-indigo-500 hover:underline"> Editar</a>&nbsp;
                                    <button @click="showModal=true" class="text-indigo-500 hover:underline"> Eliminar</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="microsites && microsites.data?.length > 0" class="mt-6 mb-6" :links="microsites.links"
                            :filter="`&filter=${searchTerm}`" :click="loadMicrosites"/>
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



