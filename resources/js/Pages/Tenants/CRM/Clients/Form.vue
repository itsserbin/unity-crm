<script setup>
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import GeneralForm from "@/Pages/Tenants/CRM/Clients/Partials/GeneralForm.vue";
import AddressesForm from "@/Pages/Tenants/CRM/Clients/Partials/AddressesForm.vue";
import OrdersForm from "@/Pages/Tenants/CRM/Clients/Partials/OrdersForm.vue";

defineProps(['item']);
</script>

<template>
    <TabView class="w-full min-h-[300px]">
        <TabPanel header="Загальне">
            <GeneralForm :item="item"/>
        </TabPanel>
        <TabPanel header="Адреси">
            <AddressesForm :item="item"/>
        </TabPanel>
        <TabPanel header="Замовлення" v-if="item.orders.length">
            <OrdersForm :item="item"/>
        </TabPanel>
    </TabView>
</template>
