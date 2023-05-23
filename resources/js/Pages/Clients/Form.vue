<script setup>
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import GeneralForm from "@/Pages/Clients/Partials/GeneralForm.vue";
import AddressesForm from "@/Pages/Clients/Partials/AddressesForm.vue";
import OrdersForm from "@/Pages/Clients/Partials/OrdersForm.vue";

defineProps(['item']);
</script>

<template>
    <TabView class="w-full">
        <TabPanel header="Загальне">
            <GeneralForm :item="item"/>
        </TabPanel>
        <TabPanel header="Адреси">
            <AddressesForm :item="item"/>
        </TabPanel>
        <TabPanel header="Замовлення">
            <OrdersForm :item="item"/>
        </TabPanel>
    </TabView>
</template>
