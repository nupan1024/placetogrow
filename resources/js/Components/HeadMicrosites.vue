<template>
    <div class="p-4 border-b-2 mb-6">
        <h2 class="mx-auto text-center font-semibold text-2xl text-gray-800 leading-tight underline">{{ title }}</h2>
    </div>
    <div class="text-center" v-if="logo">
        <LogoMicrositio :url="`/storage/${logo}`" class="w-20 h-20 fill-current text-gray-500" />
    </div>
</template>
<script setup>
import LogoMicrositio from '@/Components/LogoMicrositio.vue';

defineProps({
    title: String,
    logo: String,
});
</script>
