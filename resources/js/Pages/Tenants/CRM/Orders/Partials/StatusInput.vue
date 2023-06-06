<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import {defineAsyncComponent, onMounted, reactive} from "vue";
import StatusGroupsRepository from "@/Repositories/Tenants/Options/StatusGroupsRepository.js";
import {toast} from "vue3-toastify";
import StatusesRepository from "@/Repositories/Tenants/Options/StatusesRepository.js";

const StatusModal = defineAsyncComponent(() => import('@/Pages/Tenants/Statuses/Statuses/Modal.vue'))

const props = defineProps(['item', 'statuses', 'errors']);

const statusModal = reactive({
    isShow: false,
    item: {
        title: null,
        group_slug: null,
    }
});

const state = reactive({
    statuses: [],
    isLoadingStatusesDropdown: false
});

onMounted(async () => {
    if (props.statuses) {
        state.statuses = mapData(props.statuses);
    } else {
        await getStatuses();
    }
    if (props.item.status_id) {
        props.item.status_id = {code: props.item.status_id};
    }
});

const switchStatusModal = (val) => val ? statusModal.isShow = val : statusModal.isShow = !statusModal.isShow;

const getStatuses = async () => {
    state.isLoadingStatusesDropdown = true;
    try {
        const data = await StatusGroupsRepository.list();
        state.statuses = mapData(data.result);
    } catch (e) {
        console.error(e);
        toast.error("Failed to get statuses");
    }
    state.isLoadingStatusesDropdown = false;
}

const onCreateStatus = async () => {
    try {
        if (statusModal.item.group_slug) {
            statusModal.item.group_slug = statusModal.item.group_slug.value;
        }
        await StatusesRepository.create(statusModal.item);
        statusModal.item = {
            title: null,
            group_slug: null,
        };
        await getStatuses();
        switchStatusModal(false);
    } catch (e) {
        console.error(e);
        toast.error("Failed to create status");
    }
}

const mapData = (data) => {
    return data.map((item) => {
        return {
            label: item.title,
            hex: item.hex,
            items: item.statuses.map((item) => {
                return {
                    label: item.title,
                    code: item.id
                }
            })
        }
    })
}
</script>

<template>
    <InputLabel :required="true">Статус замовлення</InputLabel>
    <div class="p-inputgroup">
        <Dropdown v-model="item.status_id"
                  :options="state.statuses"
                  optionLabel="label"
                  data-key="code"
                  optionGroupLabel="label"
                  optionGroupChildren="items"
                  placeholder="Оберіть статус"
                  :class="['w-full', { 'p-invalid': errors.status_id }]"
                  :loading="state.isLoadingStatusesDropdown"
                  panelClass="order-statuses-dropdown"
        >
            <template #optiongroup="slotProps">
                <div class="p-2 status-drowdown font-bold" :style="`background: ${slotProps.option.hex};`">
                    <div>{{ slotProps.option.label }}</div>
                </div>
            </template>

            <template #option="slotProps">
                <div class="pl-4">
                    <div>{{ slotProps.option.label }}</div>
                </div>
            </template>

        </Dropdown>
        <Button @click="switchStatusModal" type="button" icon="pi pi-plus"></Button>
    </div>
    <small class="p-error" v-if="errors.status_id" v-for="error in errors.status_id">{{ error }}</small>

    <StatusModal v-if="statusModal.isShow" :show="statusModal.isShow" :item="statusModal.item"
                 @close="switchStatusModal" @submit="onCreateStatus"/>
</template>

<style>
.order-statuses-dropdown.p-dropdown-panel .p-dropdown-items .p-dropdown-item-group {
    padding: 0 !important;
}
</style>
