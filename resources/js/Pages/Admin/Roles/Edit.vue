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

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.roles.list, usePage().props.$t.roles.update];
const props = defineProps({
    all_permissions: Array,
    role: Object,
    permissions_role: Array,
});

const all_permissions = usePage().props.all_permissions;
const role = usePage().props.role;
const permissions_role = usePage().props.permissions;
const form = useForm({
    name: role.name,
    permissions: ref(permissions_role.slice()),
    _method: 'patch'
});
const submit = () => {
    form.post(route('roles.update', role.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.roles.update }}</h2>
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
                    <InputLabel :for="form.permissions" :value="$page.props.$t.labels.permissions"/>
                    <label v-for="permission in all_permissions" :for="form.permissions" :key="permission.id" class="flex items-center">
                        <input type="checkbox" :value="permission.name" :checked="permissions_role.includes(permission.name)" v-model="form.permissions"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                        <span class="ml-2 text-sm text-gray-600">{{ permission.name }}</span>
                    </label>
                    <InputError class="mt-2" :message="form.errors.permissions"/>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button class="btn" :disabled="form.processing">
                        {{ $page.props.$t.labels.edit }}
                    </button>
                </div>
            </form>
        </FormLayout>
    </AuthenticatedLayout>
</template>
