<script setup>
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputSwitch from 'primevue/inputswitch';

const props = defineProps(['item']);
const emits = defineEmits(['onEdit']);

const onDestroyItem = (i) => {
    props.item.items.splice(i, 1);
}

const onEdit = (e) => {
    emits('onEdit', e);
}
</script>

<template>
    <DataTable :value="item.items"
               @rowSelect="onEdit"
               selectionMode="single"
    >
        <Column field="additional_sale">
            <template #header>
                <div class="text-center w-full">
                    Дод.продаж
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    <Button text type="button">
                        <InputSwitch v-model="data.additional_sale"
                                     :true-value="1"
                                     :false-value="0"
                        />
                    </Button>
                </div>
            </template>
        </Column>
        <Column field="product_id" header="SUKA EBANAYA" />
        <Column field="image">
            <template #header>
                <div class="text-center w-full">
                    Зображення
                </div>
            </template>
            <template #body="{data}">
                <img :src="data.image ? data.image : '/storage/no_image.jpeg'" :alt="data.title"
                     class="max-w-[50px] mx-auto">
            </template>
        </Column>
        <Column field="sku">
            <template #header>
                <div class="text-center w-full">
                    Артикул
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    <span v-if="!data.sku">(Пусто)</span>
                    <span v-else>{{ data.sku }}</span>
                </div>
            </template>
        </Column>
        <Column field="title">
            <template #header>
                <div class="text-center w-full">
                    Назва
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ data.title }}
                </div>
            </template>
        </Column>
        <Column field="comment">
            <template #header>
                <div class="text-center w-full">
                    Коментар
                </div>
            </template>
        </Column>
        <Column field="count">
            <template #header>
                <div class="text-center w-full">
                    Кількість
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ data.count }} шт.
                </div>
            </template>
        </Column>
        <Column field="trade_price">
            <template #header>
                <div class="text-center w-full">
                    Ціна закупівлі
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ $filters.formatMoney(data.trade_price) }} грн.
                </div>
            </template>
        </Column>
        <Column field="product_price">
            <template #header>
                <div class="text-center w-full">
                    Ціна товару
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ $filters.formatMoney(data.product_price) }}
                </div>
            </template>
        </Column>
        <Column field="discount">
            <template #header>
                <div class="text-center w-full">
                    Знижка
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ $filters.formatMoney(data.discount) }}
                </div>
            </template>
        </Column>
        <Column field="sale_price">
            <template #header>
                <div class="text-center w-full">
                    Ціна продажу
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ $filters.formatMoney(data.sale_price) }}
                </div>
            </template>
        </Column>
        <Column>
            <template #body="{index}">
                <Button icon="pi pi-trash"
                        outlined
                        rounded
                        severity="danger"
                        @click="onDestroyItem(index)"
                />
            </template>
        </Column>

        <template #empty>
            Товари не додані
        </template>
    </DataTable>
</template>
