<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.settings.title];
defineProps({
    attempts: Object,
    period_time: Object,
    invoice_penalty: Object,
});
const attempts = usePage().props.attempts;
const period_time = usePage().props.period_time;
const invoice_penalty = usePage().props.invoice_penalty;
const form = useForm({
    attempts: attempts.value,
    period_time: period_time.value,
    invoice_penalty: invoice_penalty.value,
    _method: 'patch'
});
const submit = () => {
    form.post(route('setting.update'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head><title>{{ $page.props.$t.settings.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.settings.title }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="flex items-center justify-center mt-4">
                                <span class="block text-sm font-medium text-gray-700">{{ $page.props.$t.settings.title_payment }}</span>
                            </div>
                            <div class="mt-4 mt-6 px-6 py-4">
                                <InputLabel for="attempts" :value="$page.props.$t.settings.attempts"/>
                                <TextInput
                                    id="attempts"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.attempts"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.attempts"/>
                            </div>
                            <div class="mt-4 mt-6 px-6 py-4">
                                <InputLabel for="period_time" :value="$page.props.$t.settings.period_time"/>
                                <TextInput
                                    id="period_time"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.period_time"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.period_time"/>
                            </div>

                            <div class="flex items-center justify-center mt-4">
                                <span class="block text-sm font-medium text-gray-700">{{ $page.props.$t.settings.title_invoice }}</span>
                            </div>

                            <div class="mt-4 mt-6 px-6 py-4">
                                <InputLabel for="period_time" :value="$page.props.$t.settings.invoice_penalty"/>
                                <TextInput
                                    id="invoice_penalty"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.invoice_penalty"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.invoice_penalty"/>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button class="btn" :disabled="form.processing">
                                    {{ $page.props.$t.labels.save }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
