<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.roles.list, usePage().props.$t.roles.create];
const props = defineProps({ permissions: Array });
const permissions = usePage().props.permissions;
const form = useForm({
    name: '',
    permissions: ref([])
});
const submit = () => {
    form.post(route('roles.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.roles.create }}</h2>
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
                    <InputLabel for="name" :value="$page.props.$t.labels.permissions"/>
                    <label v-for="permission in permissions" :key="permission.id" class="flex items-center">
                        <input type="checkbox" :value="permission.name" v-model="form.permissions" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                        <span class="ml-2 text-sm text-gray-600">{{ permission.name }}</span>
                    </label>
                    <InputError class="mt-2" :message="form.errors.permissions"/>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button class="btn" :disabled="form.processing">
                        {{ $page.props.$t.labels.create }}
                    </button>
                </div>
            </form>
        </FormLayout>
    </AuthenticatedLayout>
</template>
