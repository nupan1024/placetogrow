<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import Select from '@/Components/Select.vue';

defineProps({
    roles: Array,
    status: Array
})
const roles = usePage().props.roles;
const status = usePage().props.status;
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.users.list, usePage().props.$t.users.create];
const form = useForm({
    name: '',
    email: '',
    role_id: '',
    status: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('user.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.users.create }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.users.create }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <FormLayout>
            <form @submit.prevent="submit">
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

                <div class="mt-4">
                    <InputLabel for="email" :value="$page.props.$t.labels.email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
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

                <div class="mt-4">
                    <InputLabel for="password" :value="$page.props.$t.labels.password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" :value="$page.props.$t.labels.password_confirmation" />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $page.props.$t.labels.create }}
                    </PrimaryButton>
                </div>
            </form>
        </FormLayout>
    </AuthenticatedLayout>
</template>
