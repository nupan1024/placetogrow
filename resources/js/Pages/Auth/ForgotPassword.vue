<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormLayout from '@/Layouts/FormLayout.vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head><title>{{ $page.props.$t.auth.forget }}</title></Head>
        <div class="flex p-4 border-b-2 justify-between items-center text-center mb-6">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight underline">{{ $page.props.$t.auth.forget }}</h2>
        </div>
        <FormLayout>
            <div class="mb-4 text-sm text-gray-600">
                {{ $page.props.$t.auth.msj_forget }}
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" :value="$page.props.$t.labels.email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $page.props.$t.auth.btn_forget }}
                    </PrimaryButton>
                </div>
            </form>
        </FormLayout>
    </GuestLayout>
</template>
