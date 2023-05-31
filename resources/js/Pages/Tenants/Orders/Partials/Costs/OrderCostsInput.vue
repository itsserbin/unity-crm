<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import {reactive, defineAsyncComponent} from "vue";

const OrderCostsTable = defineAsyncComponent(() => import('@/Pages/Tenants/Orders/Partials/Costs/OrderCostsTable.vue'))
const OrderCostModal = defineAsyncComponent(() => import('@/Pages/Tenants/Orders/Partials/Costs/OrderCostModal.vue'))

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
            <InputLabel>Витрати по замовленню</InputLabel>
            <Button type="button"
                    label="Додати витрату"
                    size="small"
                    @click="onCreate"
                    icon="pi pi-plus"
            />
        </div>
    </div>

    <OrderCostsTable :item="item" @onEdit="onEdit" @onDestroy="onDestroy"/>

    <OrderCostModal v-if="modal.isShow"
                    :show="modal.isShow" :item="modal.item"
                    @close="switchModal" @submit="onSubmit"/>
</template>
