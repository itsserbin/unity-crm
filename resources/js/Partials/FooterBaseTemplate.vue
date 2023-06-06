<script setup>
import Button from "primevue/button";

defineProps({
    cancelLabel: {
        type: String,
        default: 'Скасувати'
    },
    cancelIcon: {
        type: String,
        default: 'pi pi-times'
    },
    submitLabel: {
        type: String,
        default: 'Зберегти'
    },
    submitIcon: {
        type: String,
        default: 'pi pi-check'
    },
})

const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Button :label="cancelLabel"
            :icon="cancelIcon"
            @click="emits('close')"
            text
    />
    <Button :label="submitLabel"
            :icon="submitIcon"
            @click="emits('submit')"
            autofocus
    />
</template>
