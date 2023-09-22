<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import Form from './Form.vue';
import FooterBaseTemplate from "@/Partials/FooterBaseTemplate.vue";

const props = defineProps([
    'item',
    'show',
    'isLoadingModal',
    'categories',
    'errors'
]);
const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <Form :item="item" :categories="categories" :errors="errors"/>
        </template>
        <template #footer>
            <FooterBaseTemplate @close="emits('close')" @submit="emits('submit')"/>
        </template>
    </Modal>
</template>
