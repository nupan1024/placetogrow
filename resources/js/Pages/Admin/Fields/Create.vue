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
import { ref } from 'vue';

defineProps({
    roles: Array,
    types: Array,
    attributes: Array
})
const roles = usePage().props.roles;
const types = usePage().props.types;
const attributes = usePage().props.attributes;
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.fields.list, usePage().props.$t.fields.create];
const form = useForm({
    name: '',
    type: '',
    label: '',
    attributes: ref([]),
});

const getName = () => {
    form.name = form.label
        .replace(/[^a-zA-Z\s]/g, '')
        .replace(/\s+/g, '_')
        .toLowerCase();
}
const submit = () => {
    form.post(route('fields.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.fields.create }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.fields.create }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <FormLayout>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel for="label" :value="$page.props.$t.labels.label" />
                    <TextInput
                        id="label"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.label"
                        required
                        v-on:focusout="getName"
                    />
                    <InputError class="mt-2" :message="form.errors.label" />
                </div>

                <div class="mt-4">
                    <InputLabel for="name" :value="$page.props.$t.labels.name" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full text-gray-800"
                        v-model="form.name"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="type" :value="$page.props.$t.labels.type" />
                    <Select id="type"
                            class="input mt-1 block w-full select"
                            required
                            v-model="form.type"
                            :options="types"
                    />
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <div class="mt-4">
                    <InputLabel for="attributes" :value="$page.props.$t.labels.attributes" />
                    <select id="attributes" class="input mt-1 block w-full select" multiple v-model="form.attributes">
                        <option v-for="attribute in attributes" :value="attribute['id']">{{ attribute['name'] }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.attributes" />
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
