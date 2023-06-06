<script setup>
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import InputText from "primevue/inputtext";
import Modal from "@/Components/Modal/Modal.vue";
import SelectButton from "primevue/selectbutton";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import {ref} from "vue";
import Card from 'primevue/card';

const props = defineProps(['show', 'item']);
const emits = defineEmits(['close', 'submit']);

const data = ref([]);

if (props.item.delivery_services.type === 'novaposhta') {
    data.value = [
        {
            label: 'Тип оплати:',
            key: 'PaymentMethod'
        },
        {
            label: 'Очікувана дата доставки:',
            key: 'ScheduledDeliveryDate'
        },
        {
            label: 'Фактична дата доставки:',
            key: 'ActualDeliveryDate'
        },
        {
            label: 'Вартість доставки:',
            key: 'DocumentCost'
        },
        {
            label: 'Місто отримувача:',
            key: 'CityRecipient'
        },
        {
            label: 'Склад отримувача:',
            key: 'WarehouseRecipient'
        },
        {
            label: 'Адреса відділення одержувача:',
            key: 'WarehouseRecipientAddress'
        },
        {
            label: 'Місто відправника:',
            key: 'CitySender'
        },
        {
            label: 'Відділення відправника:',
            key: 'WarehouseSender'
        },
        {
            label: 'Адреса відділення відправника:',
            key: 'WarehouseSenderAddress'
        },
        {
            label: 'Дата створення ЕН:',
            key: 'DateCreated'
        },
    ];
}
</script>

<template>
    <Modal :show="show" @close="emits('close')" max-width="4xl">
        <template #body>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" v-if="item.data && item.log">
                <ul>
                    <li v-for="val in data">
                        <strong>{{ val.label }}</strong>
                        {{ item.data[val.key] }}
                    </li>
                </ul>
                <div class="flex flex-col gap-4">
                    <Card v-for="val in item.log">
                        <template #subtitle>
                            {{ val.scan_date }}
                        </template>
                        <template #content>
                            {{ val.status }}
                        </template>
                    </Card>
                </div>
            </div>
            <div v-else class="flex items-center justify-center text-2xl min-h-[300px]">
                Дані оновлюються, спробуйте трохи пізніше
            </div>
        </template>
        <template #footer>
            <Button label="Закрити"
                    icon="pi pi-times"
                    @click="emits('close')"
                    text
            />
        </template>
    </Modal>
</template>
