<script setup>
const props = defineProps(['item']);

const totalPrice = () => {
    const sale_price = props.item.items.reduce((accumulator, currentValue) => accumulator + currentValue.sale_price, 0);
    console.log(sale_price);
    console.log(props.item);
    return sale_price - props.item.discount;
}


const totalProductDiscountPrice = () => {
    return props.item.items.reduce((accumulator, currentValue) => accumulator + currentValue.discount, 0);
}

const totalTradePrice = () => {
    return props.item.items.reduce((accumulator, currentValue) => accumulator + currentValue.trade_price, 0);
}
</script>

<template>
    <table class="w-full">
        <tr>
            <td class="py-2 text-start md:text-end">Сума за товари:</td>
            <td class="py-2 text-lg font-bold">
                {{ $filters.formatMoney(totalPrice()) }} грн.
            </td>
        </tr>

        <tr>
            <td class="py-2 text-start md:text-end">Ціна закупки:</td>
            <td class="py-2 text-lg font-bold">
                {{ $filters.formatMoney(totalTradePrice()) }} грн.
            </td>
        </tr>

        <tr>
            <td class="py-2 text-start md:text-end">Знижка на замовлення:</td>
            <td class="py-2 text-lg font-bold flex items-center">
                {{ $filters.formatMoney(item.discount) }} грн.
            </td>
        </tr>

        <tr>
            <td class="py-2 text-start md:text-end">Знижка на товари:</td>
            <td class="py-2 text-lg font-bold">
                {{ $filters.formatMoney(totalProductDiscountPrice()) }} грн.
            </td>
        </tr>

        <tr>
            <td class="py-2 text-start md:text-end">Чистий прибуток:</td>
            <td class="py-2 text-lg font-bold">
                {{ $filters.formatMoney(totalPrice() - totalTradePrice()) }} грн.
            </td>
        </tr>
    </table>
</template>
