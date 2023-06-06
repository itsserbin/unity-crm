<script setup>
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

defineProps(['item']);

const emits = defineEmits(['onEdit', 'onDestroy']);
const onEdit = (e) => {
    emits('onEdit', e);
}

const onDestroy = (i) => {
    emits('onDestroy', i);
}
</script>

<template>
    <DataTable :value="item.invoices" @rowSelect="onEdit" selectionMode="single">
        <Column field="date">
            <template #header>
                <div class="text-center w-full">
                    Дата
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ $filters.formatDate(data.date) }}
                </div>
            </template>
        </Column>
        <Column field="status">
            <template #header>
                <div class="text-center w-full">
                    Статус
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ data.status }}
                </div>
            </template>
        </Column>
        <Column field="payment_type">
            <template #header>
                <div class="text-center w-full">
                    Тип оплати
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ data.payment_type }}
                </div>
            </template>
        </Column>
        <Column field="sum">
            <template #header>
                <div class="text-center w-full">
                    Сума
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    {{ $filters.formatMoney(data.sum) }}
                </div>
            </template>
        </Column>
        <Column header="Коментар" field="comment"/>
        <Column>
            <template #body="{index}">
                <Button icon="pi pi-trash"
                        outlined
                        rounded
                        severity="danger"
                        @click="onDestroy(index)"
                />
            </template>
        </Column>
        <template #empty>
            Оплат не додано
        </template>
    </DataTable>
</template>
