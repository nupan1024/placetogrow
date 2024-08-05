<script setup>

import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import HeadMicrosites from '@/Components/HeadMicrosites.vue';

defineProps({
    microsite: Object,
})

const microsite = usePage().props.microsite;
const form = useForm({
    name: '',
    email: '',
    description: '',
    value: '',
});
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
            </div>
            <form>
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

                <div class="mt-3">
                    <InputLabel :value="$page.props.$t.labels.subscription_type"/>
                    <Select
                        id="status"
                        class="input mt-1 block w-full select"
                        required
                        autofocus
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $page.props.$t.labels.subscribe }}
                    </PrimaryButton>
                </div>
            </form>

        </FormLayout>

    </GuestLayout>

</template>
