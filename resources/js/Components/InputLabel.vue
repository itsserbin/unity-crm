<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <label class="block font-medium text-base text-gray-700 dark:text-gray-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot/></span>
        <span v-if="required" class="text-red-600">*</span>
    </label>
</template>
