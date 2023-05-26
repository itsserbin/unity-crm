<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import {reactive} from "vue";
import ItemsModal from "@/Pages/Orders/Partials/ItemsModal.vue";
import ItemsTable from "@/Pages/Orders/Partials/ItemsTable.vue";

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
        image: productModal.item.product_id.image,
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
    productModal.index = e.index;
    productModal.item = e.data;
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

    <ItemsModal :show="productModal.isShow" :item="productModal.item"
                @close="switchProductModal" @submit="onSubmit" :products="products"/>
</template>
