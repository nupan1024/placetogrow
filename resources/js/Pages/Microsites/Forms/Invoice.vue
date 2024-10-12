<script setup>

import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import GenerateFields from '@/Components/GenerateFields.vue';
import HeadMicrosites from '@/Components/HeadMicrosites.vue';

defineProps({
    microsite: Object,
    documents: Array,
    fields: Array,
})

const microsite = usePage().props.microsite;
const documents = usePage().props.documents;
const fields = usePage().props.fields;
const invoices = usePage().props.invoices;
let set_fields = microsite.fields.reduce((fields, field) => {
    fields[field] = '';
    return fields;
}, {});

const form = useForm({
    name: usePage().props.auth.user ? usePage().props.auth.user.name : '',
    email: usePage().props.auth.user ? usePage().props.auth.user.email : '',
    type_document: '',
    num_document: '',
    value: '',
    microsite_id: microsite.id,
    currency: microsite.currency.name,
    fields: set_fields,
    invoice_id: '',
    gateway: usePage().props.$t.payments.gateway_placetopay,

});
let descriptionInvoice;
let valueInvoice;

const selectInvoice = (e) => {
    const selectedOption = e.target.selectedOptions[0];
    let value = '';
    let description = '';
    if (selectedOption.dataset.description !== undefined &&
        selectedOption.dataset.value !== undefined) {
        description = selectedOption.dataset.description;
        value = selectedOption.dataset.value;
    }

    descriptionInvoice = usePage().props.$t.labels.description +': ' + description;
    valueInvoice = usePage().props.$t.invoices.value + ': ' + value;
    form.invoice_id = e.target.value;
    form.value = value;
};

const submit = () => {
    form.post(route('payment.create'), {
        forceFormData: true,
    });
};
</script>
<template>
    <Head><title>{{ $page.props.$t.microsites.title }}</title></Head>
    <GuestLayout>
        <FormLayout>
            <HeadMicrosites :title="microsite.name" :logo="microsite.logo_path"></HeadMicrosites>
            <div class="text-align mb-6">
                <p>{{ microsite.description }}</p>
                <h2>{{ $page.props.$t.categories.title }}: {{ microsite.category.name }}</h2>
                <h2>{{ $page.props.$t.labels.type }}: {{ microsite.type.name }}</h2>
                <h2>{{ $page.props.$t.labels.currency }}: {{ microsite.currency.name }}</h2>
                <h2>{{ $page.props.$t.labels.date_pay }}: {{ microsite.date_expire_pay }}</h2>
            </div>
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
                        autocomplete="off"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="type_document" :value="$page.props.$t.labels.document" />
                    <div class="flex">
                        <Select
                            id="category_id"
                            class="input mt-1 select"
                            v-model="form.type_document"
                            :options="documents"
                            required
                            autofocus
                        />
                        <TextInput
                            id="num_document"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.num_document"
                            required
                            autofocus
                            autocomplete="on"
                        />
                        <InputError class="mt-2" :message="form.errors.num_document" />
                    </div>
                </div>

                <div v-for="field in fields" :key="field" class="mt-3">
                    <GenerateFields
                        :label="field.label"
                        :type="field.type"
                        :fieldName="field.label"
                        :attributes="field.attributes"
                        v-model="form.fields[field.name]"
                    />
                </div>

                <div class="mt-3">
                    <InputLabel :value="$page.props.$t.labels.select_invoice"/>
                    <select
                        @change="selectInvoice($event)"
                        class="input mt-1 block w-full select border-gray-300 focus:border-primary focus:outline-none p"
                        v-model="form.invoice_id">
                        <option value="">{{ $page.props.$t.labels.select }}</option>
                        <option v-for="invoice in invoices" :key="invoice['id']"
                                :value="invoice['id']"
                                :data-value="invoice['value']"
                                :data-description="invoice['description']">
                            {{invoice['code']}}
                        </option>
                    </select>
                </div>
                <div class="mt-3 border border-gray-300 max-w-md mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow">
                    <span class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 text-center flex shadow">{{ $page.props.$t.payments.detail }}</span>
                    <div class="flex gap-4" v-html="descriptionInvoice"></div>
                    <div class="flex gap-4" v-html="valueInvoice"></div>
                </div>

                <div class="flex items-center justify-end mt-4" v-if="invoices && invoices.length > 0">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $page.props.$t.labels.pay }}
                    </PrimaryButton>
                </div>
            </form>

        </FormLayout>

    </GuestLayout>

</template>
