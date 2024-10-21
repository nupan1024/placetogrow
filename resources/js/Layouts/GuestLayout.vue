<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Alert from '@/Components/Alert.vue';

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
                <details class="dropdown">
                    <summary class="btn m-1">{{ $page.props.$t.labels.language }}</summary>
                    <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                        <li><a :href="route('locale','es')">{{ $page.props.$t.labels.spanish }}</a></li>
                        <li><a :href="route('locale','en')">{{ $page.props.$t.labels.english }}</a></li>
                    </ul>
                </details>

                <div class="flex-none" v-if="$page.props.auth.user">
                    <ul class="menu menu-horizontal px-1">
                        <li>
                            <details>
                                <summary>{{ user.name }}</summary>
                                <ul class="bg-base-100 rounded-t-none p-2 z-50">
                                    <li v-if="user.role_id === $page.props.$t.roles.role_guest">
                                        <a :href="route('user.invoices.list')">
                                            {{ $page.props.$t.invoices.title }}
                                        </a>
                                    </li>
                                    <li v-if="user.role_id === $page.props.$t.roles.role_guest">
                                        <a :href="route('user.payments.list')">
                                            {{ $page.props.$t.payments.title }}
                                        </a>
                                    </li>
                                    <li v-if="user.role_id === $page.props.$t.roles.role_guest">
                                        <a :href="route('user.subscriptions.list')">
                                            {{ $page.props.$t.subscriptions.title }}
                                        </a>
                                    </li>
                                    <li v-if="user.role_id === $page.props.$t.roles.role_guest">
                                        <a :href="route('profile.edit')">
                                            {{ $page.props.$t.auth.profile }}
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
                <div v-else class="flex justify-between space-x-1">
                    <div>
                        <Link :href="route('login')">{{ $page.props.$t.auth.login }}</Link>
                    </div>
                </div>
            </div>
            <main>
                <div v-if="$page.props.flash.message">
                    <Alert :type="$page.props.flash.type" :message="$page.props.flash.message" />
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>
