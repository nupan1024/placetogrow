<template>
    <div class="avatar text-center">
        <div class="w-24 rounded">
            <img alt="logo" :src="url" />
        </div>
    </div>
</template>
<script>
export default {
    props: {
        url: {
            type: String,
            required: true,
        }
    },
};
</script>
