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
    microsites: Array,
    subscription: Object,

})

const formatter = ref({
    date: 'YYYY-MM-DD',
    month:'MMM',
});
function disableData(date) {
    return date < new Date();
}

const subscription = usePage().props.subscription;
const currencies = usePage().props.currencies;
const microsites = usePage().props.microsites;
const states = usePage().props.states;
const billing_frequency = usePage().props.billing_frequency;
const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.subscriptions.list, usePage().props.$t.subscriptions.edit];
const form = useForm({
    name: subscription.name,
    microsite_id: subscription.microsite_id,
    currency_id: subscription.currency_id,
    amount: subscription.amount.toString(),
    description: subscription.description,
    billing_frequency: subscription.billing_frequency,
    status: subscription.status,
    _method: 'patch'
});


const submit = () => {
    form.post(route('subscription.update', subscription.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.subscriptions.edit }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.subscriptions.edit }}</h2>
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

                <div class="mt-4">
                    <InputLabel for="billing_frequency" :value="$page.props.$t.subscriptions.billing_frequency" />
                    <span class="block text-sm font-medium text-gray-500">{{ $page.props.$t.subscriptions.tooltip_billing_frequency }}</span>
                    <TextInput
                        type="number"
                        class="mt-1 block w-full text-gray-800"
                        v-model="form.billing_frequency"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.billing_frequency" />
                </div>

                <div class="mt-4">
                    <InputLabel for="currency_id" :value="$page.props.$t.labels.currency" />
                    <Select class="input mt-1 block w-full select"
                            required
                            v-model="form.currency_id"
                            :options="currencies"
                            disabled
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
                            disabled
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
                        {{ $page.props.$t.labels.edit }}
                    </PrimaryButton>
                </div>
            </form>
        </FormLayout>
    </AuthenticatedLayout>
</template>
