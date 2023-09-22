<script setup>
import Card from "primevue/card";

const props = defineProps(['item']);

const totalPrice = () => {
    const sale_price = props.item.items.reduce((accumulator, currentValue) => accumulator + currentValue.sale_price, 0);
    return sale_price - props.item.discount;
}

const costs = () => {
    return props.item.costs.reduce((accumulator, currentValue) => accumulator + currentValue.sum, 0);
}

const totalProductDiscountPrice = () => {
    return props.item.items.reduce((accumulator, currentValue) => accumulator + currentValue.discount, 0);
}

const totalTradePrice = () => {
    return props.item.items.reduce((accumulator, currentValue) => accumulator + currentValue.trade_price, 0);
}

const items = [
    {
        title: ' Сума за товари:',
        value: totalPrice()
    },
    {
        title: ' Ціна закупки:',
        value: totalTradePrice()
    },
    {
        title: ' Знижка на замовлення:',
        value: props.item.discount
    },
    {
        title: ' Знижка на товари:',
        value: totalProductDiscountPrice()
    },
    {
        title: ' Витрати на замовлення:',
        value: costs()
    },
    {
        title: ' Чистий прибуток:',
        value: totalPrice() - totalTradePrice() - costs()
    },
];
</script>

<template>
    <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
        <Card v-for="item in items">
            <template #subtitle>
                <div class="text-center">
                    {{ item.title }}
                </div>
            </template>
            <template #content>
                <div class="text-center">
                    {{ $filters.formatMoney(item.value) }} грн.
                </div>
            </template>
        </Card>
    </div>
</template>
