<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Select from '@/Components/Select.vue';
import FormLayout from '@/Layouts/FormLayout.vue';

defineProps({
    status: Array,
    roles: Array,
    user: Object,
});

const roles = usePage().props.roles;
const status = usePage().props.status;
const user = usePage().props.user;
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.users.list, usePage().props.$t.users.edit];
const form = useForm({
    name: user.name,
    email: user.email,
    role_id: user.role_id,
    status: user.status,
    _method: 'patch'
});
const submit = () => {
    form.post(route('user.update', user.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>Usuarios</title></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.users.edit }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>

        <FormLayout>
            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" :value="$page.props.$t.labels.name" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="on"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" :value="$page.props.$t.labels.email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="on"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="role_id" :value="$page.props.$t.labels.role"/>
                    <Select
                        id="role_id"
                        class="input mt-1 block w-full select"
                        v-model="form.role_id"
                        :options="roles"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.role_id"/>
                </div>

                <div class="mt-3">
                    <InputLabel for="status" :value="$page.props.$t.labels.status"/>
                    <Select
                        id="status"
                        class="input mt-1 block w-full select"
                        v-model="form.status"
                        :options="status"
                        required
                        autofocus
                    />
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
