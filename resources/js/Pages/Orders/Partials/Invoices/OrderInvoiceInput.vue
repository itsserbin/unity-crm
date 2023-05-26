<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import OrderInvoiceModal from "@/Pages/Orders/Partials/Invoices/OrderInvoiceModal.vue";
import OrderInvoiceTable from "@/Pages/Orders/Partials/Invoices/OrderInvoiceTable.vue";
import {reactive, ref} from "vue";

const props = defineProps(['item']);

const modal = reactive({
    isShow: false,
    index: null,
    action: '',
    item: null
});

const switchModal = (val) => val ? modal.isShow = val : modal.isShow = !modal.isShow;
const onEdit = (e) => {
    modal.action = 'edit';
    modal.index = JSON.parse(JSON.stringify(e.index));
    modal.item = JSON.parse(JSON.stringify(e.data));
    switchModal();
}

const onSubmit = (e) => {
    if (modal.action === 'create') {
        props.item.costs.push(modal.item);
    }
    if (modal.action === 'edit') {
        props.item.costs[modal.index] = modal.item;
    }
    switchModal();
}

const onCreate = () => {
    modal.action = 'create';
    modal.item = {
        title: null,
        sum: null,
        comment: null,
    };
    switchModal();
}

const onDestroy = (i) => {
    props.item.costs.splice(i, 1);
}
</script>

<template>
    <div class="block">
        <div class="flex justify-between">
            <InputLabel>Оплати клієнта</InputLabel>
            <Button type="button"
                    label="Додати оплату"
                    size="small"
                    @click="onCreate"
                    icon="pi pi-plus"
            />
        </div>
    </div>

    <OrderInvoiceTable :item="item" @onEdit="onEdit" @onDestroy="onDestroy"/>

    <OrderInvoiceModal :show="modal.isShow" :item="modal.item"
                       @close="switchModal" @submit="onSubmit"/>
</template>
