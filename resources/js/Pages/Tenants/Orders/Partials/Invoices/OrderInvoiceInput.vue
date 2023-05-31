<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import OrderInvoiceTable from "@/Pages/Tenants/Orders/Partials/Invoices/OrderInvoiceTable.vue";
import {defineAsyncComponent, reactive} from "vue";

const OrderInvoiceModal = defineAsyncComponent(() => import('@/Pages/Tenants/Orders/Partials/Invoices/OrderInvoiceModal.vue'));

const props = defineProps({
    item: {
        type: Object,
        required: true
    },
});

const modal = reactive({
    isShow: false,
    index: null,
    action: '',
    item: null
});

const toggleModal = (val = !modal.isShow) => modal.isShow = val;

const onEdit = ({index, data}) => {
    modal.action = 'edit';
    modal.index = index;
    modal.item = {...data};
    if (data.payment_type) {
        modal.item.payment_type = {value: data.payment_type};
    }
    if (data.status) {
        modal.item.status = {value: data.status};
    }
    toggleModal();
}

const onSubmit = (e) => {
    if (modal.item.payment_type.value) {
        modal.item.payment_type = modal.item.payment_type.value;
    }
    if (modal.item.status.value) {
        modal.item.status = modal.item.status.value;
    }
    if (modal.action === 'create') {
        props.item.invoices.push(modal.item);
    }
    if (modal.action === 'edit') {
        props.item.invoices[modal.index] = modal.item;
    }
    toggleModal();
}

const onCreate = () => {
    modal.action = 'create';
    modal.item = {
        title: null,
        sum: null,
        payment_type: null,
        status: null,
        comment: null,
    };
    toggleModal();
}

const onDestroy = (index) => {
    props.item.invoices.splice(index, 1);
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

    <OrderInvoiceModal v-if="modal.isShow" :show="modal.isShow" :item="modal.item"
                       @close="toggleModal" @submit="onSubmit"/>
</template>
