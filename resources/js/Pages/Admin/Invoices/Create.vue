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
import TextArea from '@/Components/TextArea.vue';

defineProps({
    roles: Array,
    types: Array,
    attributes: Array
})
const roles = usePage().props.roles;
const microsites = usePage().props.microsites;
const users = usePage().props.users;
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.invoices.list, usePage().props.$t.invoices.create];
const form = useForm({
    microsite_id: '',
    user_id: '',
    value: '',
    description: '',
});


const submit = () => {
    form.post(route('invoice.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.invoices.create }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.invoices.create }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <FormLayout>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel for="user_id" :value="$page.props.$t.invoices.name_client" />
                    <Select id="user_id"
                            class="input mt-1 block w-full select"
                            required
                            v-model="form.user_id"
                            :options="users"
                    />
                    <InputError class="mt-2" :message="form.errors.user_id" />
                </div>

                <div class="mt-4">
                    <InputLabel for="microsite_id" :value="$page.props.$t.invoices.microsites" />
                    <Select id="microsite_id"
                            class="input mt-1 block w-full select"
                            required
                            v-model="form.microsite_id"
                            :options="microsites"
                    />
                    <InputError class="mt-2" :message="form.errors.microsite_id" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" :value="$page.props.$t.labels.description" />
                    <TextArea
                        id="description"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.description"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div class="mt-4">
                    <InputLabel for="value" :value="$page.props.$t.invoices.value" />
                    <TextInput
                        id="value"
                        type="text"
                        class="mt-1 block w-full text-gray-800"
                        v-model="form.value"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.value" />
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
