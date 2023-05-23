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
            <Form :item="item"/>
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
