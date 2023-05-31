<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import {onMounted, reactive} from "vue";

const state = reactive({
    delivery_services: [],
    isLoadingDeliveryServicesDropdown: false
});

const props = defineProps(['item', 'deliveryServices']);

onMounted(async () => {
    state.isLoadingDeliveryServicesDropdown = true;
    try {
        state.delivery_services = props.deliveryServices.map((item) => {
            return {
                label: item.title,
                value: item.id
            }
        });
    } catch (e) {
        console.error(e);
    }
    state.isLoadingDeliveryServicesDropdown = false;
});

const addRow = () => {
    props.item.tracking_codes.push({
        code: null,
        delivery_service_id: null,
    });
}

const destroyRow = (i) => {
    props.item.tracking_codes.splice(i, 1);
}
</script>

<template>
    <div v-for="(code,i) in item.tracking_codes" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="block w-full">
            <InputLabel>Служба доставки</InputLabel>
            <Dropdown v-model="code.delivery_service_id"
                      :options="state.delivery_services"
                      optionLabel="label"
                      dataKey="value"
                      placeholder="Оберіть службу"
                      class="w-full"
                      :loading="state.isLoadingDeliveryServicesDropdown"
            />
        </div>

        <div class="flex gap-4">
            <div class="block w-full">
                <InputLabel>Трекінг-код</InputLabel>
                <InputText type="text"
                           v-model="code.code"
                           placeholder="Вкажіть трекінг-код"
                           class="w-full"
                />
            </div>

            <div class="block">
                <InputLabel>&nbsp;</InputLabel>
                <Button icon="pi pi-plus" v-if="i === 0" text @click="addRow"/>
                <Button icon="pi pi-minus" v-if="i !== 0" text @click="destroyRow(i)"/>
            </div>
        </div>
    </div>
</template>
