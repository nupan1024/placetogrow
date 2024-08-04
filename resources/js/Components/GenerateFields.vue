<template>
    <div class="mt-3" v-if="type === 'input'">
        <InputLabel :value="label" />
        <TextInput
            type="text"
            class="mt-1 block w-full"
            :required="Array.isArray(attributes) && attributes.includes('required')"
            :autofocus="Array.isArray(attributes) && attributes.includes('autofocus')"
            :autocomplete="Array.isArray(attributes) && attributes.includes('autofocus') ? 'on' : 'off'"
            v-model="model"
        />
    </div>
    <div class="mt-3" v-if="type === 'textarea'">
        <InputLabel :value="label" />
        <TextArea
            class="mt-1 block w-full"
            :required=" Array.isArray(attributes) && attributes.includes('required')"
            :autofocus="Array.isArray(attributes) && attributes.includes('autofocus')"
            :autocomplete="Array.isArray(attributes) && attributes.includes('autofocus') ? 'on' : 'off'"
            v-model="model"
        />
    </div>
    <div class="mt-3" v-if="type === 'checkbox'">
        <div class="flex items-center">
            <Checkbox
                class="mr-2"
                :required="Array.isArray(attributes) && attributes.includes('required')"
                :autofocus="Array.isArray(attributes) && attributes.includes('autofocus')"
                :autocomplete="Array.isArray(attributes) && attributes.includes('autofocus') ? 'on' : 'off'"
                :checked="false"
                v-model="model"
            />
            <InputLabel for="value" :value="label" />
        </div>
    </div>
</template>


<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import Checkbox from '@/Components/Checkbox.vue';
defineProps({
    fieldName: String,
    type: String,
    label: String,
    attributes: Array,
});

const model = defineModel({
    type: String,
    required: true,
});

</script>
