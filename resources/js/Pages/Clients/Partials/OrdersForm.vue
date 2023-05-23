<script setup>
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Heading from "@/Components/Heading.vue";

defineProps(['item']);
</script>

<template>
    <DataTable v-if="item.orders.length" :value="item.orders" class="mb-4">
        <Column field="order_id" header="Номер замовлення"></Column>
    </DataTable>
    <Heading v-else>Замовлень не знайдено</Heading>
</template>
