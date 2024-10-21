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
import FormLayout from '@/Layouts/FormLayout.vue';

defineProps({
    categories: Array,
    types: Array,
    currencies: Array,
    states: Array,
    fields: Array
})

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.microsites.list, usePage().props.$t.microsites.create];
const categories = usePage().props.categories;
const types = usePage().props.microsites_types;
const currencies = usePage().props.currencies;
const states = usePage().props.states;
const fields = usePage().props.fields;

const form = useForm({
    name: '',
    category_id: '',
    microsites_type_id: '',
    currency_id: '',
    logo_path: '',
    status: '',
    description: '',
    fields: ref([]),
});
const submit = () => {
    form.post(route('microsite.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.microsites.create }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <FormLayout>
            <form  @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mt-3">
                    <InputLabel for="name" :value="$page.props.$t.labels.name"/>
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
                    <InputLabel for="microsites_type_id" :value="$page.props.$t.labels.type"/>
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
                    <InputLabel for="category_id" :value="$page.props.$t.categories.title"/>
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
                    <InputLabel for="currency_id" :value="$page.props.$t.labels.currency"/>
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
                    <InputLabel for="description" :value="$page.props.$t.labels.description" />
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
                    <InputLabel for="status" :value="$page.props.$t.labels.status"/>
                    <Select
                        id="status"
                        class="input mt-1 block w-full select"
                        v-model="form.status"
                        :options="states"
                        required
                        autofocus
                    />
                </div>
                <div class="mt-3">
                    <InputLabel for="logo_path" value="Logo"/>
                    <FileInput
                        accept="image/*"
                        class="input mt-1 block w-full"
                        v-model="form.logo_path"
                        required
                        autofocus
                        autocomplete="on"
                    />
                    <InputError class="mt-2" :message="form.errors.logo_path"/>
                </div>
                <div class="mt-3 border border-gray-300 max-w-md mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow">
                    <span class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 text-center flex shadow">{{ $page.props.$t.fields.long_title }}</span>
                    <div class="mt-3" v-for="field in fields" :key="field.name">
                        <div class="flex items-center">
                            <input type="checkbox" :value="field.name" v-model="form.fields" class="checkbox mr-2" />
                            <span class="label-text">
                                {{ field.label }} ({{ field.name }})
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button class="btn" :disabled="form.processing">
                        {{ $page.props.$t.labels.save }}
                    </button>
                </div>
            </form>
        </FormLayout>
    </AuthenticatedLayout>
</template>
