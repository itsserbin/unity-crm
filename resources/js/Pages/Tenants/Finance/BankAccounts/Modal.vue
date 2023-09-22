<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import Form from "./Form.vue";
import Button from "primevue/button";

defineProps(['item', 'show', 'isLoadingModal']);
const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <Form :item="item" @submit="emits('submit')" @close="emits('close')"/>
        </template>
    </Modal>
</template>
