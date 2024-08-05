<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const user = usePage().props.auth.user ?? '';
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <div class="navbar bg-base-100">
                <div class="flex-1">
                    <Link :href="route('home')">
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-gray-800"
                        />
                    </Link>
                </div>
                <div class="flex-none" v-if="$page.props.auth.user">
                    <ul class="menu menu-horizontal px-1">
                        <li>
                            <details>
                                <summary>{{ user.name }}</summary>
                                <ul class="bg-base-100 rounded-t-none p-2 z-50">
                                    <li v-if="user.role_id === $page.props.$t.roles.role_guest">
                                        <a :href="route('invoice.listUser')">
                                            {{ $page.props.$t.invoices.title }}
                                        </a>
                                    </li>
                                    <li v-if="user.role_id === $page.props.$t.roles.role_guest">
                                        <a :href="route('payments.listUser')">
                                            {{ $page.props.$t.payments.title }}
                                        </a>
                                    </li>
                                    <li v-if="$page.props.auth.user_permissions.length === 0">
                                        <Link
                                            :href="route('logout')" method="post" as="button">
                                            {{ $page.props.$t.auth.sign_off }}
                                        </Link>
                                    </li>
                                    <li v-else>
                                        <a :href="route('dashboard')">Dashboard</a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <a :href="route('login')">{{ $page.props.$t.auth.login }}</a>
                </div>
            </div>
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
