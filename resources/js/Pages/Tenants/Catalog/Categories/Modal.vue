<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import Form from "@/Pages/Tenants/Catalog/Categories/Form.vue";
import Button from "primevue/button";

defineProps([
    'item',
    'show',
    'isLoadingModal',
    'errors'
]);

const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" @close="emits('close')" max-width="5xl">
        <template #body>
            <Form :item="item" :errors="errors"/>
        </template>
        <template #footer>
            <Button label="Скасувати"
                    icon="pi pi-times"
                    @click="emits('close')"
                    text
            />
            <Button label="Зберегти"
                    icon="pi pi-check"
                    @click="emits('submit')"
                    autofocus
                    :loading="isLoadingModal"
            />
        </template>
    </Modal>
</template>
