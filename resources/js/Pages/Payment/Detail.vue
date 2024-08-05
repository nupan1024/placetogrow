<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    payment: Object,
    status: Object,
});

const payment = usePage().props.payment;
const status = usePage().props.status;
</script>

<template>
    <Head><title>{{ $page.props.$t.payments.detail }}</title></Head>
    <GuestLayout>
        <div class="flex items-center">
            <div class="p-4 grow">
                <h2 class="mx-auto text-center font-semibold text-2xl text-gray-800 leading-tight underline">{{ $page.props.$t.payments.detail }}</h2>
            </div>
        </div>


        <div class="border border-gray-300 max-w-md mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow">
          <span class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 text-center flex shadow"> Tus datos personales</span>
           <div class="flex gap-4">
               {{ $page.props.$t.labels.name }}: {{ payment.name }}
           </div>
            <div class="flex gap-4">
                {{ $page.props.$t.labels.email }}: {{ payment.email }}
            </div>
            <div class="flex gap-4">
                {{ $page.props.$t.labels.document }}: {{ payment.num_document }}
            </div>
            <span class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 text-center flex shadow"> Datos de pago</span>
            <div class="flex gap-4">
                {{ $page.props.$t.payments.code }}: {{ payment.reference }}
            </div>
            <div class="flex gap-4">
                {{ $page.props.$t.payments.value }}: ${{ payment.value }}
            </div>
            <div class="flex gap-4">
                {{ $page.props.$t.payments.status }}: {{status.status }}
            </div>
        </div>
    </GuestLayout>
</template>
