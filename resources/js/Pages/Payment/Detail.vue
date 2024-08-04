<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    transaction: Object,
    status: Object,
});

const transaction = usePage().props.transaction;
const status = usePage().props.status;
</script>

<template>
    <Head><title>{{ $page.props.$t.payments.detail }}</title></Head>
    <GuestLayout>

        <div class="flex p-4 border-b-2 justify-between items-center text-center mb-6">
            <div class="shrink-0 flex items-center">
                <Link :href="route('home')">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </Link>

            </div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight underline">{{ $page.props.$t.payments.detail }}</h2>
            <div>
                <div class="text-right pr-4">
                    <div v-if="$page.props.auth.user">
                        <Link
                            v-if="$page.props.auth.user_permissions.length === 0"
                            :href="route('logout')" method="post" as="button">
                            {{ $page.props.$t.auth.sign_off }}
                        </Link>
                        <a v-else :href="route('dashboard')">Dashboard</a>
                    </div>
                    <div v-else>
                        <a :href="route('login')">{{ $page.props.$t.auth.login }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="border border-gray-300 max-w-md mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow">
          <span class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 text-center flex shadow"> Tus datos personales</span>
           <div class="flex gap-4">
               {{ $page.props.$t.labels.name }}: {{ transaction.name }}
           </div>
            <div class="flex gap-4">
                {{ $page.props.$t.labels.email }}: {{ transaction.email }}
            </div>
            <div class="flex gap-4">
                {{ $page.props.$t.labels.document }}: {{ transaction.num_document }}
            </div>
            <span class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 text-center flex shadow"> Datos de pago</span>
            <div class="flex gap-4">
                {{ $page.props.$t.payments.code }}: {{ transaction.code }}
            </div>
            <div class="flex gap-4">
                {{ $page.props.$t.payments.value }}: ${{ transaction.value }}
            </div>
            <div class="flex gap-4">
                {{ $page.props.$t.payments.status }}: {{status.status }}
            </div>
        </div>
    </GuestLayout>
</template>
