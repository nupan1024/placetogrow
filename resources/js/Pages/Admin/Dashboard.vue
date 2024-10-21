<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import DashboardComponent from '@/Components/DashboardComponent.vue';

const  crumbs = [usePage().props.$t.labels.dashboard];
defineProps({
    total_active_users: Number,
    total_microsites: Array,
    paid_pending_invoices: Array,
    expired_pending_invoices: Array,
    invoices_payments_by_microsite_type: Array,
    subscriptions_payments_by_microsite_type: Array,
    donations_payments_by_microsite_type: Array,

});
const total_active_users = usePage().props.total_active_users;
const total_microsites = usePage().props.total_microsites;
const paid_pending_invoices = usePage().props.paid_pending_invoices;
const expired_pending_invoices = usePage().props.expired_pending_invoices;
const invoices_payments_by_microsite_type = usePage().props.invoices_payments_by_microsite_type;
const subscriptions_payments_by_microsite_type = usePage().props.subscriptions_payments_by_microsite_type;
const donations_payments_by_microsite_type = usePage().props.donations_payments_by_microsite_type;

 </script>

<template>
    <Head><title>Dashboard</title></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.dashboard.title }}</h2>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <div class="py-12">
        <DashboardComponent
        :total_active_users="total_active_users"
        :total_microsites="total_microsites"
        :paid_pending_invoices="paid_pending_invoices"
        :expired_pending_invoices="expired_pending_invoices"
        :invoices_payments_by_microsite_type="invoices_payments_by_microsite_type"
        :subscriptions_payments_by_microsite_type="subscriptions_payments_by_microsite_type"
        :donations_payments_by_microsite_type="donations_payments_by_microsite_type"
        />
        </div>
    </AuthenticatedLayout>
</template>
