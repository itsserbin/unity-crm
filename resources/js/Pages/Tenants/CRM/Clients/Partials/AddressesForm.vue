<script setup>
import Button from "primevue/button";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import {defineAsyncComponent, reactive} from "vue";

const AddressesModal = defineAsyncComponent(() => import('@/Pages/Tenants/CRM/Clients/Partials/AddressesModal.vue'))

const props = defineProps(['item']);

const itemTemplate = {
    address: null,
    city: null,
    region: null,
    zip_code: null,
};

const state = reactive(({
    isModal: false,
    item: JSON.parse(JSON.stringify(itemTemplate))
}));

const toggleModal = () => state.isModal = !state.isModal;

const submit = () => {
    props.item.addresses.push(state.item);
    state.item = JSON.parse(JSON.stringify(itemTemplate));
    toggleModal();
}
</script>

<template>
        <DataTable v-if="item.addresses.length" :value="item.addresses" class="mb-4">
            <Column field="address" header="Адреса"></Column>
            <Column field="city" header="Місто"></Column>
            <Column field="region" header="Регіон"></Column>
            <Column field="zip_code" header="Поштовий індекс"></Column>
        </DataTable>
        <div class="flex items-center justify-center">
            <Button label="Додати адресу" @click="toggleModal"/>
        </div>
        <AddressesModal v-if="state.isModal"
                        :show="state.isModal"
                        :item="state.item"
                        @close="toggleModal"
                        @submit="submit"
        />
</template>
