<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from './Modal.vue';
import Form from './Form.vue';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';

import OrdersRepository from "@/Repositories/OrdersRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import Heading from "@/Components/Heading.vue";
import Dropdown from "primevue/dropdown";

const props = defineProps([
    'orders',
    'sources',
    'statuses',
    'users',
    'deliveryServices',
    'products',
    'clients',
]);

const state = reactive({
    errors: [],
    isLoading: false,
    isShowModal: false,
    isLoadingModal: false,
    isLoadingRefreshButton: false,
    data: {},
    search: null
});

const lazyParams = ref({
    date: null,
    page: 0,
    first: 0,
    rows: 15,
    sortField: null,
    sortOrder: null,
    statuses: []
});

const item = ref();

onMounted(async () => {
    state.data = props.orders;
});

const queryParams = () => {
    let data = {};
    if (lazyParams.value.rows) {
        data.perPage = lazyParams.value.rows;
    }
    if (lazyParams.value.sortField && lazyParams.value.sortOrder) {
        data.sort = {
            column: lazyParams.value.sortField,
            type: lazyParams.value.sortOrder === 1 ? 'asc' : 'desc'
        };
    }
    if (lazyParams.value.filter) {
        data.filter = lazyParams.value.filter;
    }
    if (lazyParams.value.statuses && lazyParams.value.statuses.length) {
        data.statuses = lazyParams.value.statuses.map((item) => {
            return item.code
        });
    }
    return data;
}
const fetch = async () => {
    switchLoader();
    try {
        const data = await OrdersRepository.fetch(queryParams());
        state.data = data.success ? data.result : [];
    } catch (e) {
        console.error(e);
        toast.error("Failed to fetch data");
    }
    switchLoader();
}

const toggleModal = (val) => val ? state.isShowModal = val : state.isShowModal = !state.isShowModal;
const switchLoader = (val) => val ? state.isLoading = val : state.isLoading = !state.isLoading;
const switchLoaderRefreshButton = (val) => val ? state.isLoadingRefreshButton = val : state.isLoadingRefreshButton = !state.isLoadingRefreshButton;

const onPage = async (e) => {
    lazyParams.value = e;
    await fetch();
}

const onSort = async (e) => {
    lazyParams.value = e;
    await fetch();
}
const onRowSelect = (event) => {
    onEdit(event.data.id);
};

const onCreate = () => {
    item.value = {
        id: null,
        source_id: null,
        status_id: null,
        client_id: null,
        manager_id: null,
        delivery_service_id: null,
        tracking_code: null,
        comment: null,
        discount: null,
        items: [],
        costs: [],
    };
    toggleModal();
}
const onSubmit = async () => {
    try {
        state.isLoadingModal = true;
        const {source_id, status_id, client_id, manager_id, delivery_service_id, id, ...rest} = item.value;
        const updatedItem = {
            ...(source_id && {source_id: source_id.value}),
            ...(status_id && {status_id: status_id.code}),
            ...(client_id && {client_id: client_id.value}),
            ...(manager_id && {manager_id: manager_id.value}),
            ...(delivery_service_id && {delivery_service_id: delivery_service_id.value}),
            ...rest
        };
        const data = id ? await OrdersRepository.update(id,updatedItem) : await OrdersRepository.create(updatedItem);
        if (data.success) {
            await fetch();
            toggleModal();
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

const onEdit = async (id) => {
    switchLoader();
    try {
        const data = await OrdersRepository.edit(id);
        item.value = data.result;
        item.value.source = {code: data.result.source};
        toggleModal();
    } catch (e) {
        console.error(e);
        toast.error("Failed to get data");
    }
    switchLoader();
}

const onDestroy = async (id) => {
    await useConfirm({
        message: 'Ви точно бажаєте видалити цей запис?',
        header: 'Підтвердження дії',
        icon: 'pi pi-exclamation-triangle',
        accept: async () => {
            try {
                await OrdersRepository.destroy(id);
                await fetch();
                toast.success('Запис успішно видалено');
            } catch (error) {
                console.error(error);
                toast.error('Виникла помилка');
            }
        }
    });
}

const onSearch = async () => {
    if (state.search) {
        switchLoader();
        try {
            const data = await OrdersRepository.search(state.search);
            state.data = data.success ? data.result : [];
        } catch (e) {
            console.error(e);
            toast.error("Failed to fetch data");
        }
        switchLoader();
    }
}

const refreshData = async () => {
    switchLoaderRefreshButton();
    lazyParams.value = ({
        date: null,
        page: 0,
        first: 0,
        rows: 15,
        sortField: null,
        sortOrder: null,
        statuses: []
    });
    await fetch();
    switchLoaderRefreshButton();
}

const selectStatuses = props.statuses.map((item) => {
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

const onFilterStatus = async () => {
    lazyParams.value.page = 1;
    await fetch();
}
</script>

<template>
    <AppLayout>
        <div class="card">
            <Toolbar class="mb-4">
                <template #start>
                    <div class="flex gap-2 items-center">
                        <Button icon="pi pi-refresh"
                                type="button"
                                size="small"
                                @click="refreshData"
                                :loading="state.isLoadingRefreshButton"
                        />
                        <Heading>Замовлення</Heading>
                    </div>
                </template>
                <template #end>
                    <Button label="Додати" size="small" icon="pi pi-plus" class="mr-2" @click="onCreate"/>
                </template>
            </Toolbar>

            <DataTable resizableColumns
                       columnResizeMode="expand"
                       selectionMode="single"
                       @rowSelect="onRowSelect"
                       dataKey="id"
                       :loading="state.isLoading"
                       v-if="state.data"
                       :value="state.data.data"
                       lazy
                       paginator
                       :rows="state.data.per_page"
                       :totalRecords="state.data.total"
                       @page="onPage($event)"
                       @sort="onSort($event)"
                       :rowsPerPageOptions="[15, 50, 100, 500]"
                       paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            >
                <template #header>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <form @submit.prevent="onSearch" class="flex gap-2 items-center">
                            <InputText v-model="state.search" placeholder="Пошук..."/>
                            <Button severity="secondary"
                                    text
                                    rounded
                                    icon="pi pi-search"
                                    type="submit"
                            />
                        </form>

                        <div class="w-full flex justify-end">
                            <MultiSelect v-model="lazyParams.statuses"
                                         :options="selectStatuses"
                                         optionLabel="label"
                                         data-key="code"
                                         optionGroupLabel="label"
                                         optionGroupChildren="items"
                                         placeholder="Фільтр за статусом"
                                         class="w-full max-w-sm"
                                         @change="onFilterStatus"
                                         filter
                                         :maxSelectedLabels="3"
                                         scrollHeight="50vh"
                                         panelClass="orders-status-filter-select"
                            >
                                <template #optiongroup="slotProps">
                                    <div class="p-2 status-drowdown font-bold" :style="`background: ${slotProps.option.hex};`">
                                        <div>{{ slotProps.option.label }}</div>
                                    </div>
                                </template>
                            </MultiSelect>
                        </div>
                    </div>
                </template>

                <Column field="id" header="ID" sortable=""></Column>
                <Column field="status_id" header="Статус">
                    <template #body="{data}">
                        <Button :label="data.status.title"
                                type="button"
                                :style="`background: ${data.status.group.hex}`"
                        />
                    </template>
                </Column>
                <Column field="source_id" header="Джерело">
                    <template #body="{data}">
                        {{ data.source.title }}
                    </template>
                </Column>
                <Column field="manager_id" header="Менеджер">
                    <template #body="{data}">
                        {{ data.manager && data.manager.name ? data.manager.name : '(Пусто)' }}
                    </template>
                </Column>
                <Column field="client_id" header="Клієнт">
                    <template #body="{data}">
                        {{ data.client && data.client.full_name ? data.client.full_name : '(Пусто)' }}
                    </template>
                </Column>
                <Column header="Телефон">
                    <template #body="{data}">
                        <div v-if="data.client && data.client.phones.length"
                             v-for="phone in data.client.phones">
                            {{ $filters.formatPhone(phone.number) }}
                        </div>
                        <div v-else>(Пусто)</div>
                    </template>
                </Column>
                <Column field="delivery_service_id" header="Служба доставки">
                    <template #body="{data}">
                        {{
                            data.delivery_service && data.delivery_service.title ? data.delivery_service.title : '(Пусто)'
                        }}
                    </template>
                </Column>
                <Column field="tracking_code" header="Трекінг-код">
                    <template #body="{data}">
                        {{ data.tracking_code ? data.tracking_code : '(Пусто)' }}
                    </template>
                </Column>
                <Column field="items" header="Товари">
                    <template #body="{data}">
                        <div v-if="data.items.length" v-for="item in data.items">
                            {{ item.title }}
                        </div>
                        <div v-else>(Пусто)</div>
                    </template>
                </Column>

                <Column>
                    <template #body="{data}">
                        <Button icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="onDestroy(data.id)"
                        />
                    </template>
                </Column>
                <template #footer>
                    <div class="text-end">
                        Показано з {{ state.data.from }} по {{ state.data.to }} із {{ state.data.total }} записів
                    </div>
                </template>
            </DataTable>
        </div>
        <Modal :show="state.isShowModal"
               :item="item"
               @close="toggleModal(false)"
               @submit="onSubmit"
               :isLoadingModal="state.isLoadingModal"
               :sources="sources"
               :statuses="statuses"
               :users="users"
               :deliveryServices="deliveryServices"
               :products="products"
               :clients="clients"
               :errors="state.errors"
        />
    </AppLayout>
</template>

<style>
.orders-status-filter-select.p-multiselect-panel .p-multiselect-items .p-multiselect-item-group{
    padding: 0;
}
</style>
