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
                case 'Dashboard':
                    return route('dashboard');
                case 'Listado de micrositios':
                    return route('microsites');
            }
        }
    },
};
</script>
