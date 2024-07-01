<script setup>

import { Link, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Select from '@/Components/Select.vue';
import TextInput from '@/Components/TextInput.vue';
import LogoMicrositio from '@/Components/LogoMicrositio.vue';

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
    <GuestLayout>
        <div class="flex p-4 border-b-2 justify-between items-center text-center mb-6">
            <div class="shrink-0 flex items-center">
                <Link :href="route('home')">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </Link>

            </div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight underline">{{ microsite.name }}</h2>
            <div>
                <div class="text-right pr-4">
                    <div v-if="$page.props.auth.user">
                        <Link v-if="$page.props.auth.user.role_id !== 1" :href="route('logout')" method="post" as="button">Cerrar sesión</Link>
                        <a v-else :href="route('dashboard')">Dashboard</a>
                    </div>
                    <div v-else>
                        <a :href="route('login')">Iniciar sesión</a>
                    </div>
                </div>
            </div>

        </div>

        <FormLayout>
            <div class="text-center" v-if="microsite.logo_path">
                <LogoMicrositio :url="`/storage/${microsite.logo_path}`" class="w-20 h-20 fill-current text-gray-500" />
            </div>
            <div class="text-align mb-6">
                <p>{{ microsite.description }}</p>
                <h2>Categoría: {{ microsite.category.name }}</h2>
                <h2>Tipo: {{ microsite.type.name }}</h2>
                <h2>Moneda: {{ microsite.currency.name }}</h2>
            </div>
            <form>
                <div>
                    <InputLabel for="name" value="Nombre" />

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
                    <InputLabel for="email" value="Correo electrónico" />

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
                    <InputLabel value="Selecciona tipo de suscripción"/>
                    <Select
                        id="status"
                        class="input mt-1 block w-full select"
                        required
                        autofocus
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Subcribirme!
                    </PrimaryButton>
                </div>
            </form>

        </FormLayout>

    </GuestLayout>

</template>
