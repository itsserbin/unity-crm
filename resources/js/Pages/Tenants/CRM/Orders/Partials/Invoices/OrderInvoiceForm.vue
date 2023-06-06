<script setup>
import Datepicker from "@/Components/Datepicker.vue";
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import InputText from "primevue/inputtext";

defineProps(['item']);

const paymentTypes = [
    {label: 'Переказ на картку', value: 'card'},
    {label: 'Оплата на сайті', value: 'website'},
    {label: 'Оплата рахунку', value: 'invoice'},
    {label: 'Готівка', value: 'invoice'},
];

const paymentStatuses = [
    {label: 'Оплачено', value: 1},
    {label: 'Не оплачено', value: 0},
];
</script>

<template>
    <div class="grid grid-cols-1 gap-4">
        <div class="block">
            <InputLabel :required="true">Дата</InputLabel>
            <Datepicker v-model="item.date"
                        :time-picker-enabled="true"
                        placeholder="Оберіть дату"
                        class="w-full"
            />
        </div>
        <div class="block">
            <InputLabel :required="true">Тип оплати</InputLabel>
            <Dropdown v-model="item.payment_type"
                      :options="paymentTypes"
                      optionLabel="label"
                      dataKey="value"
                      placeholder="Оберіть тип оплати"
                      class="w-full"
            />
        </div>
        <div class="block">
            <InputLabel :required="true">Сума</InputLabel>
            <InputText v-model="item.sum"
                       class="w-full"
                       placeholder="Вкажіть суму"
                       type="number"
            />
        </div>
        <div class="block">
            <InputLabel :required="true">Статусу оплати</InputLabel>
            <Dropdown v-model="item.status"
                      :options="paymentStatuses"
                      optionLabel="label"
                      dataKey="value"
                      placeholder="Оберіть статус"
                      class="w-full"
            />
        </div>
        <div class="block">
            <InputLabel>Коментар</InputLabel>
            <Textarea v-model="item.comment"
                      class="w-full"
                      rows="4"
                      placeholder="Вкажіть коментар"
            />
        </div>
    </div>
</template>
