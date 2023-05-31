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
    <DataTable :value="item.costs" @rowSelect="onEdit" selectionMode="single">
        <Column header="Назва" field="title"/>
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
            Витрат не додано
        </template>
    </DataTable>
</template>
