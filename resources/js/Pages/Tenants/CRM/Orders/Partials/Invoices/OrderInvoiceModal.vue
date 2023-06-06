<script setup>
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import OrderInvoiceForm from "@/Pages/Tenants/CRM/Orders/Partials/Invoices/OrderInvoiceForm.vue";

const props = defineProps(['show', 'item']);
const emits = defineEmits(['close', 'submit']);

</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <OrderInvoiceForm :item="item"/>
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
            />
        </template>
    </Modal>
</template>
