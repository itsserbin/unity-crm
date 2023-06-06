<script setup>
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Heading from "@/Components/Heading.vue";
import Button from "primevue/button";
import {defineAsyncComponent, reactive} from "vue";
import OrdersRepository from "@/Repositories/Tenants/CRM/OrdersRepository.js";
import {toast} from "vue3-toastify";

const OrderModal = defineAsyncComponent(() => import('@/Pages/Tenants/CRM/Orders/Modal.vue'));
const props = defineProps(['item']);

const state = reactive({
    errors: [],
    isShowModal: false,
    isLoading: false,
    modalItem: {}
});

const switchLoader = () => state.isLoading = !state.isLoading;
const toggleOrderItemModal = () => state.isShowModal = !state.isShowModal;


const onEdit = async (id) => {
    switchLoader();
    try {
        const data = await OrdersRepository.edit(id);
        state.modalItem = data.result;
        state.modalItem.source = {code: data.result.source};
        if (!state.modalItem.tracking_codes.length) {
            state.modalItem.tracking_codes.push({
                code: null,
                delivery_service_id: null,
            })
        } else {
            state.modalItem.tracking_codes = state.modalItem.tracking_codes.map((i) => {
                return {
                    delivery_service_id: {value: i.delivery_service_id},
                    code: i.code
                }
            })
        }
        toggleOrderItemModal();
    } catch (e) {
        console.error(e);
        toast.error("Failed to get data");
    }
    switchLoader();
}

const onRowSelect = (e) => {
    onEdit(e.data.id);
}

const onSubmit = async () => {
    try {
        state.isLoadingModal = true;
        const {
            tracking_codes,
            source_id,
            status_id,
            client_id,
            manager_id,
            delivery_service_id,
            id,
            ...rest
        } = state.modalItem;
        const updatedItem = {
            ...(source_id && {source_id: source_id.value}),
            ...(status_id && {status_id: status_id.code}),
            ...(client_id && {client_id: client_id.value}),
            ...(manager_id && {manager_id: manager_id.value}),
            ...(tracking_codes.length && tracking_codes[0].code && {
                tracking_codes: tracking_codes.map((item) => {
                    if (item.delivery_service_id) {
                        return {
                            delivery_service_id: item.delivery_service_id.value,
                            code: item.code
                        }
                    }
                })
            }),
            ...rest
        };
        const data = id ? await OrdersRepository.update(id, updatedItem) : await OrdersRepository.create(updatedItem);
        if (data.success) {
            toggleOrderItemModal();
            toast.success("Success");
        } else {
            state.errors = data.data;
            toast.error("Error");
        }
    } catch (e) {
        console.error(e);
        toast.error("Error");
    } finally {
        state.isLoadingModal = false;
    }
};
</script>

<template>
    <DataTable v-if="item.orders.length"
               :value="item.orders"
               class="mb-4"
               selectionMode="single"
               @rowSelect="onRowSelect"
               :loading="state.isLoading"
    >
        <Column field="id">
            <template #header>
                <div class="w-full text-center">
                    Номер замовлення
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">{{ data.id }}</div>
            </template>
        </Column>
        <Column field="status_id">
            <template #header>
                <div class="w-full text-center">
                    Статус
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">
                    <Button :label="data.status.title"
                            type="button"
                            :style="`background: ${data.status.group.hex}`"
                    />
                </div>
            </template>
        </Column>
        <Column field="id">
            <template #header>
                <div class="w-full text-center">
                    Сума замовлення
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">{{ $filters.formatMoney(data.total_price) }}</div>
            </template>
        </Column>
        <Column field="id">
            <template #header>
                <div class="w-full text-center">
                    Дата створення
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center">{{ $filters.formatDateTime(data.created_at) }}</div>
            </template>
        </Column>
    </DataTable>
    <Heading v-else>Замовлень не знайдено</Heading>
    <OrderModal v-if="state.isShowModal" :show="state.isShowModal"
                :item="state.modalItem" @close="toggleOrderItemModal"
                @submit="onSubmit" :errors="state.errors"/>
</template>
