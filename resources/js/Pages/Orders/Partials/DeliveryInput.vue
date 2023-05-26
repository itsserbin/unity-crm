<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
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
</script>

<template>
    <div class="block">
        <InputLabel>Служба доставки</InputLabel>
        <Dropdown v-model="item.delivery_service_id"
                  :options="state.delivery_services"
                  optionLabel="label"
                  dataKey="value"
                  placeholder="Оберіть службу"
                  class="w-full"
                  :loading="state.isLoadingDeliveryServicesDropdown"
        />
    </div>

    <div class="block">
        <InputLabel>Трекінг-код</InputLabel>
        <InputText type="text"
                   v-model="item.tracking_code"
                   placeholder="Вкажіть трекінг-код"
                   class="w-full"
        />
    </div>
</template>
