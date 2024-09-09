<template>
    <div class="breadcrumbs text-sm">
        <ul>
            <li v-for="(crumb, ci) in crumbs"
                :key="ci"
                class="breadcrumb-item align-items-center">
                <a :class="{ disabled: isLast(ci), underline: isLast(ci) }" :href="!isLast(ci) ? getUrl(crumb) : null">
                    {{ crumb }}
                </a>
            </li>
        </ul>
    </div>
</template>


<script>
import { usePage } from '@inertiajs/vue3';

export default {
    props: {
        crumbs: {
            type: Array,
            required: true,
        },
    },
    methods: {
        isLast(index) {
            return index === this.crumbs.length - 1;
        },
        selected(crumb) {
            this.$emit('selected', crumb);
        },
        getUrl(crumb) {
            switch (crumb) {
                case usePage().props.$t.labels.dashboard:
                    return route('dashboard');
                case usePage().props.$t.microsites.list:
                    return route('microsites');
                case usePage().props.$t.users.list:
                    return route('users');
                case usePage().props.$t.roles.list:
                    return route('roles');
                case usePage().props.$t.fields.list:
                    return route('fields');
                case usePage().props.$t.invoices.list:
                    return route('invoices');
                case usePage().props.$t.subscriptions.list:
                    return route('subscriptions');
            }
        }
    },
};
</script>
