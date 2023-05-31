<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import {onMounted, reactive, defineAsyncComponent} from "vue";
import DeliveryServicesRepository from "@/Repositories/Tenants/DeliveryServicesRepository.js";
import {toast} from "vue3-toastify";

const DeliveryServiceModal = defineAsyncComponent(() => import('@/Pages/Tenants/DeliveryServices/Modal.vue'))

const state = reactive({
    delivery_services: [],
    isLoadingDeliveryServicesDropdown: false,
    isShowDeliveryServiceModal: false,
    isLoadingDeliveryServiceModal: false,
    deliveryServiceItem: {
        title: null,
        type: null,
        api_key: null,
        configuration: [],
    },
});

const props = defineProps(['item', 'deliveryServices']);

onMounted(async () => {
    state.isLoadingDeliveryServicesDropdown = true;
    if (props.deliveryServices) {
        state.delivery_services = mapData(props.deliveryServices);
    } else {
        await getDeliveryServices();
    }
    state.isLoadingDeliveryServicesDropdown = false;
});

const mapData = (data) => {
    return data.map((item) => {
        return {
            label: item.title,
            value: item.id
        }
    });
}

const getDeliveryServices = async () => {
    try {
        const data = await DeliveryServicesRepository.list();
        state.delivery_services = mapData(data.result);
    } catch (e) {
        console.error(e);
        toast.error("Failed to fetch data");
    }
}

const addRow = () => {
    props.item.tracking_codes.push({
        code: null,
        delivery_service_id: null,
    });
}

const destroyRow = (i) => {
    props.item.tracking_codes.splice(i, 1);
}

const onCreateDeliveryService = () => {
    state.deliveryServiceItem = {
        title: null,
        type: null,
        api_key: null,
        configuration: [],
    }
    toggleDeliveryServiceModal();
}

const onSubmitDeliveryService = async () => {
    state.isLoadingDeliveryServiceModal = true;
    try {
        if (state.deliveryServiceItem.type) {
            state.deliveryServiceItem.type = state.deliveryServiceItem.type.code;
        }

        state.deliveryServiceItem.id
            ? await DeliveryServicesRepository.update(state.deliveryServiceItem)
            : await DeliveryServicesRepository.create(state.deliveryServiceItem);

        await getDeliveryServices();
        toggleDeliveryServiceModal();
        toast.success("Служба доставки додана!");
    } catch (e) {
        console.error(e);
        toast.error("Перевірте введені дані");
    }
    state.isLoadingDeliveryServiceModal = false;
}

const toggleDeliveryServiceModal = () => state.isShowDeliveryServiceModal = !state.isShowDeliveryServiceModal;
</script>

<template>
    <div v-if="state.delivery_services.length"
         v-for="(code,i) in item.tracking_codes"
         class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
    <div v-if="!state.delivery_services.length">
        <div class="flex flex-col items-center justify-center gap-4">
            <div>Служби доставки не додані</div>
            <Button label="Додати" size="small" icon="pi pi-plus" class="mr-2" @click="onCreateDeliveryService"/>
        </div>
        <DeliveryServiceModal v-if="state.isShowDeliveryServiceModal"
                              :show="state.isShowDeliveryServiceModal"
                              :item="state.deliveryServiceItem"
                              @close="toggleDeliveryServiceModal"
                              @submit="onSubmitDeliveryService"
        />
    </div>
</template>
