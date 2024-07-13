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
    states: Array
})

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.microsites.list, usePage().props.$t.microsites.create];
const categories = usePage().props.categories;
const types = usePage().props.microsites_types;
const currencies = usePage().props.currencies;
const states = usePage().props.states;
const hiddenField = ref(true);
const formatter = ref({
    date: 'YYYY-MM-DD',
    month:'MMM',
});
function disableData(date) {
    return date < new Date();
}
const changeType = (e) => {
    hiddenField.value = e.target.value !== '1';
}

const form = useForm({
    name: '',
    category_id: '',
    microsites_type_id: '',
    currency_id: '',
    date_expire_pay: ref(''),
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
                        v-on:change="changeType"
                        id="microsites_type_id"
                        class="input mt-1 block w-full select"
                        v-model="form.microsites_type_id"
                        :options="types"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.microsites_type_id"/>
                </div>
                <div class="mt-3" v-if="!hiddenField">
                    <InputLabel for="date_expire_pay" :value="$page.props.$t.labels.date_pay" />
                    <vue-tailwind-datepicker
                        :formatter="formatter"
                        :disable-date="disableData"
                        id="date_expire_pay"
                        name="date_expire_pay"
                        v-model="form.date_expire_pay"
                        class="mt-1 block w-full"
                        as-single
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.date_expire_pay"/>
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
                        id="logo_path"
                        class="input mt-1 block w-full"
                        v-model="form.logo_path"
                        required
                        autofocus
                        autocomplete="on"
                    />
                    <InputError class="mt-2" :message="form.errors.logo_path"/>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button class="btn" :disabled="form.processing">
                        Crear
                    </button>
                </div>
            </form>
        </FormLayout>
    </AuthenticatedLayout>
</template>
