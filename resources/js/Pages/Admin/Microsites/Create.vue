<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/Components/Select.vue';
import FileInput from '@/Components/FileInput.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import TextArea from '@/Components/TextArea.vue';
import { ref } from 'vue'
defineProps({
    categories: Array,
    types: Array,
    currencies: Array,
})

const crumbs = ["Dashboard", "Listado de micrositios", "Crear micrositio"];
const categories = usePage().props.categories;
const types = usePage().props.microsites_types;
const currencies = usePage().props.currencies;

const form = useForm({
    name: '',
    category_id: '',
    microsites_type_id: '',
    currency_id: '',
    date_expire_pay: ref([]),
    logo_path: '',
    status: '',
    description: ''
});
const submit = () => {
    form.post(route('microsite.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Micrositios" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear micrositio</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>

        <div class="mx-auto w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg justify-center">
            <form  @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mt-3">
                    <InputLabel for="name" value="Nombre" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="category_id" value="Categoría"/>
                    <Select
                        id="category_id"
                        class="input mt-1 block w-full select"
                        v-model="form.category_id"
                        :options="categories"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.category_id"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="microsites_type_id" value="Tipo"/>
                    <Select
                        id="microsites_type_id"
                        class="input mt-1 block w-full select"
                        v-model="form.microsites_type_id"
                        :options="types"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.microsites_type_id"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="currency_id" value="Moneda"/>
                    <Select
                        id="currency_id"
                        class="input mt-1 block w-full select"
                        v-model="form.currency_id"
                        :options="currencies"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.currency_id"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="date_expire_pay" value="Fecha límite de pago" />
                    <vue-tailwind-datepicker id="date_expire_pay" name="date_expire_pay" v-model="form.date_expire_pay" class="mt-1 block w-full" as-single required autofocus />
                    <InputError class="mt-2" :message="form.errors.date_expire_pay"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="description" value="Descripción" />
                    <TextArea
                        id="description"
                        class="mt-1 block w-full"
                        v-model="form.description"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.description"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="logo_path" value="Logo"/>
                    <FileInput
                        id="logo_path"
                        class="input mt-1 block w-full"
                        v-model="form.logo_path"
                        required
                        autofocus
                        autocomplete="logo_path"
                    />
                    <InputError class="mt-2" :message="form.errors.logo_path"/>
                </div>
                <div class="mt-3">
                    <InputLabel for="status" value="Estado"/>
                    <input type="checkbox"
                           name="status" id="status"
                           v-model="form.status"
                           class="toggle"/>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button class="btn" :disabled="form.processing">
                       Crear
                    </button>
                </div>
            </form>
        </div>


    </AuthenticatedLayout>
</template>
