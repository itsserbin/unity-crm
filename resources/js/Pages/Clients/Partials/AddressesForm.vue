<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal/Modal.vue';
import Button from "primevue/button";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import {reactive} from "vue";

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
        <Column field="zip_code" header="Поштвий індекс"></Column>
    </DataTable>
    <div class="flex items-center justify-center">
        <Button label="Додати адресу" @click="toggleModal"/>
    </div>
    <Modal :show="state.isModal" @close="toggleModal">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel :required="true">Адреса або відділення</InputLabel>
                    <InputText v-model="state.item.address"
                               class="w-full"
                               placeholder="Вкажіть адресу"
                               type="text"
                    />
                </div>
                <div class="block">
                    <InputLabel>Місто</InputLabel>
                    <InputText v-model="state.item.city"
                               class="w-full"
                               placeholder="Вкажіть місто"
                               type="text"
                    />
                </div>
                <div class="block">
                    <InputLabel>Область</InputLabel>
                    <InputText v-model="state.item.region"
                               class="w-full"
                               placeholder="Вкажіть область"
                               type="text"
                    />
                </div>
                <div class="block">
                    <InputLabel>Поштовий індекс</InputLabel>
                    <InputText v-model="state.item.zip_code"
                               class="w-full"
                               placeholder="Вкажіть поштовий індекс"
                               type="text"
                    />
                </div>
            </div>
        </template>
        <template #footer>
            <Button label="Скасувати"
                    icon="pi pi-times"
                    @click="toggleModal"
                    text
            />
            <Button label="Зберегти"
                    icon="pi pi-check"
                    @click="submit"
                    autofocus
            />
        </template>
    </Modal>
</template>
