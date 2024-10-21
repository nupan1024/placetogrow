<template>
    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.active_users }}: {{ total_active_users }}</h2>
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.active_microsites }}: </h2>
                <p v-for="microsite in total_microsites" :key="microsite.id">
                    {{ microsite.type_name }}: {{ microsite.total }}
                </p>
            </div>
            <div class="bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.vs_invoices }}: </h2>
                <canvas id="invoicesChart"></canvas>
            </div>
            <div class="bg-white shadow-md rounded p-6">
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.vs_invoices }}</h2>
                <canvas id="salesPieChart"></canvas>
            </div>
            <div class="bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.collect_invoices }} </h2>
                <canvas id="paymentsChart"></canvas>
            </div>
            <div class="bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.collect_subscriptions }} </h2>
                <canvas id="subscriptionsChart"></canvas>
            </div>
            <div class="bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-semibold mb-4">{{ $page.props.$t.dashboard.collect_donations }} </h2>
                <canvas id="donationsChart"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted } from "vue";
import Chart from "chart.js/auto";
import { usePage } from '@inertiajs/vue3';

export default {
    name: "DashboardComponent",
    props: {
        total_active_users: {
            type: Number,
            required: true,
        },
        total_microsites: {
            type: Array,
            required: true,
        },
        paid_pending_invoices: {
            type: Array,
            required: true,
        },
        expired_pending_invoices: {
            type: Array,
            required: true,
        },
        invoices_payments_by_microsite_type: {
            type: Array,
            required: true,
        },
        subscriptions_payments_by_microsite_type: {
            type: Array,
            required: true,
        },
        donations_payments_by_microsite_type: {
            type: Array,
            required: true,
        },
    },
    setup() {
        onMounted(() => {
            const ctxInvoice = document.getElementById("invoicesChart").getContext("2d");
            const dataInvoice = usePage().props.expired_pending_invoices.map((invoice) => invoice.total_value);
            new Chart(ctxInvoice, {
                type: "pie",
                data: {
                    labels: [usePage().props.$t.dashboard.pending, usePage().props.$t.dashboard.expired],
                    datasets: [
                        {
                            label: usePage().props.$t.invoices.title,
                            data: dataInvoice,
                            backgroundColor: [
                                "rgba(75, 192, 192, 0.6)",
                                "rgba(153, 102, 255, 0.6)",
                            ],
                            borderColor: [
                                "rgba(75, 192, 192, 1)",
                                "rgba(153, 102, 255, 1)",
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return ` ${tooltipItem.label}: $${value} ${usePage().props.$t.dashboard.total}`;
                                },
                            },
                        },
                    },
                },
            });

            const ctxPayments = document.getElementById("paymentsChart").getContext("2d");
            const payments = usePage().props.invoices_payments_by_microsite_type.map((payment) => payment.total_value);
            new Chart(ctxPayments, {
                type: "line",
                data: {
                    labels: [
                        usePage().props.$t.dashboard.sunday,
                        usePage().props.$t.dashboard.monday,
                        usePage().props.$t.dashboard.tuesday,
                        usePage().props.$t.dashboard.wednesday,
                        usePage().props.$t.dashboard.thursday,
                        usePage().props.$t.dashboard.friday,
                        usePage().props.$t.dashboard.saturday
                    ],
                    datasets: [
                        {
                            label: usePage().props.$t.dashboard.money_raised,
                            data: payments,
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 2,
                            fill: false,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            const ctxSubscriptions = document.getElementById("subscriptionsChart").getContext("2d");
            const subscriptions = usePage().props.subscriptions_payments_by_microsite_type.map((payment) => payment.total_value);
            new Chart(ctxSubscriptions, {
                type: "line",
                data: {
                    labels: [
                        usePage().props.$t.dashboard.sunday,
                        usePage().props.$t.dashboard.monday,
                        usePage().props.$t.dashboard.tuesday,
                        usePage().props.$t.dashboard.wednesday,
                        usePage().props.$t.dashboard.thursday,
                        usePage().props.$t.dashboard.friday,
                        usePage().props.$t.dashboard.saturday
                    ],
                    datasets: [
                        {
                            label: usePage().props.$t.dashboard.money_raised,
                            data: subscriptions,
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 2,
                            fill: false,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });


            const ctxDonations = document.getElementById("donationsChart").getContext("2d");
            const donations = usePage().props.donations_payments_by_microsite_type.map((payment) =>payment.total_value);
            new Chart(ctxDonations, {
                type: "line",
                data: {
                    labels: [
                        usePage().props.$t.dashboard.sunday,
                        usePage().props.$t.dashboard.monday,
                        usePage().props.$t.dashboard.tuesday,
                        usePage().props.$t.dashboard.wednesday,
                        usePage().props.$t.dashboard.thursday,
                        usePage().props.$t.dashboard.friday,
                        usePage().props.$t.dashboard.saturday
                    ],
                    datasets: [
                        {
                            label: usePage().props.$t.dashboard.money_raised,
                            data: donations,
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 2,
                            fill: false,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            const ctx = document.getElementById("salesPieChart").getContext("2d");
            const data = usePage().props.paid_pending_invoices.map((item) => item.total_value);
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: [usePage().props.$t.dashboard.pending, usePage().props.$t.dashboard.paid],
                    datasets: [
                        {
                            label: usePage().props.$t.invoices.title,
                            data: data,
                            backgroundColor: [
                                "rgba(255, 99, 132, 0.6)",
                                "rgba(54, 162, 235, 0.6)",
                            ],
                            borderColor: [
                                "rgba(255, 99, 132, 1)",
                                "rgba(54, 162, 235, 1)",
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    let value = tooltipItem.raw;
                                    return ` ${tooltipItem.label}: $${value} ${usePage().props.$t.dashboard.total}`;
                                },
                            },
                        },
                    },
                },
            });
        });
    },
};
</script>

<style scoped>
.container {
    background-color: #f9f9f9;
    min-height: 100vh;
}
</style>
