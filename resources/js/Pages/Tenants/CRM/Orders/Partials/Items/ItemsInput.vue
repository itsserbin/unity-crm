<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import ItemsTable from "@/Pages/Tenants/CRM/Orders/Partials/Items/ItemsTable.vue";
import {defineAsyncComponent, reactive} from "vue";

const ItemsModal = defineAsyncComponent(() => import('@/Pages/Tenants/CRM/Orders/Partials/Items/ItemsModal.vue'))

const props = defineProps(['item', 'products']);

const productModal = reactive({
    action: '',
    isShow: false,
    index: null,
    item: {
        additional_sale: false,
        product_id: null,
        count: 1,
        comment: null,
        discount: null,
    }
});

const switchProductModal = (val) => val ? productModal.isShow = val : productModal.isShow = !productModal.isShow;

const onCreateItem = (item) => {
    props.item.items.push(item);
    productModal.item = {
        product_id: null,
        count: 1,
        comment: null,
        discount: null,
    };
    switchProductModal();
}

const onSubmit = () => {
    const item = {
        product_id: productModal.item.product_id.id,
        additional_sale: false,
        count: productModal.item.count,
        comment: productModal.item.comment,
        discount: productModal.item.discount,
        preview_id: productModal.item.product_id.preview_id,
        preview: productModal.item.product_id.preview,
        sku: productModal.item.product_id.sku,
        title: productModal.item.product_id.title,
        trade_price: productModal.item.product_id.trade_price * productModal.item.count,
        product_price: (productModal.item.product_id.discount_price ? productModal.item.product_id.discount_price : productModal.item.product_id.price) * productModal.item.count,
        sale_price: ((productModal.item.product_id.discount_price ? productModal.item.product_id.discount_price : productModal.item.product_id.price) * productModal.item.count) - productModal.item.discount
    };

    if (productModal.action === 'edit') {
        props.item.items[productModal.index] = item;
        switchProductModal();
    }
    if (productModal.action === 'create') {
        onCreateItem(item);
    }
}
const onEdit = (e) => {
    productModal.action = 'edit';
    productModal.index = JSON.parse(JSON.stringify(e.index));
    productModal.item = JSON.parse(JSON.stringify(e.data));
    productModal.item.product_id = props.products.find((item) => item.id === productModal.item.product_id);
    switchProductModal();
}

const onCreate = () => {
    productModal.action = 'create';
    switchProductModal();
}
</script>

<template>
    <div class="flex justify-between">
        <InputLabel>Товари</InputLabel>
        <Button type="button"
                label="Додати товар"
                size="small"
                @click="onCreate"
                icon="pi pi-plus"
        />
    </div>

    <ItemsTable :item="item" @onEdit="onEdit"/>

    <ItemsModal v-if="productModal.isShow" :show="productModal.isShow" :item="productModal.item"
                @close="switchProductModal" @submit="onSubmit" :products="products"/>
</template>

