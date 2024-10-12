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
import { ref } from 'vue';

defineProps({
    currencies: Array,
    states: Array,
    billing_frequency: Array,
    microsites: Array
})

const formatter = ref({
    date: 'YYYY-MM-DD',
    month:'MMM',
});
function disableData(date) {
    return date < new Date();
}

const currencies = usePage().props.currencies;
const microsites = usePage().props.microsites;
const states = usePage().props.states;
const billing_frequency = usePage().props.billing_frequency;
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.subscriptions.list, usePage().props.$t.subscriptions.create];
const form = useForm({
    name: '',
    microsite_id: '',
    currency_id: '',
    amount: '',
    description: '',
    time_expire: '',
    billing_frequency: '',
    status: '',
});


const submit = () => {
    form.post(route('subscription.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.subscriptions.create }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.subscriptions.create }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <FormLayout>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel for="name" :value="$page.props.$t.labels.name" />
                    <TextInput
                        type="text"
                        class="mt-1 block w-full text-gray-800"
                        v-model="form.name"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
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

                <div class="mt-3">
                    <InputLabel for="time_expire" :value="$page.props.$t.subscriptions.time_expire" />
                    <vue-tailwind-datepicker
                        :formatter="formatter"
                        :disable-date="disableData"
                        name="time_expire"
                        v-model="form.time_expire"
                        class="mt-1 block w-full"
                        as-single
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.time_expire"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="billing_frequency" :value="$page.props.$t.subscriptions.billing_frequency" />
                    <Select class="input mt-1 block w-full select"
                            required
                            v-model="form.billing_frequency"
                            :options="billing_frequency"
                    />
                    <InputError class="mt-2" :message="form.errors.billing_frequency" />
                </div>

                <div class="mt-4">
                    <InputLabel for="currency_id" :value="$page.props.$t.labels.currency" />
                    <Select class="input mt-1 block w-full select"
                            required
                            v-model="form.currency_id"
                            :options="currencies"
                    />
                    <InputError class="mt-2" :message="form.errors.currency_id" />
                </div>

                <div class="mt-4">
                    <InputLabel for="amount" :value="$page.props.$t.subscriptions.amount" />
                    <TextInput
                        type="text"
                        class="mt-1 block w-full text-gray-800"
                        v-model="form.amount"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.amount" />
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

                <div class="mt-3">
                    <InputLabel for="status" :value="$page.props.$t.labels.status"/>
                    <Select
                        class="input mt-1 block w-full select"
                        v-model="form.status"
                        :options="states"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.status" />
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
