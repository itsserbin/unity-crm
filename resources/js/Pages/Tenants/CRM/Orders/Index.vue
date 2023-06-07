<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Heading from "@/Components/Heading.vue";

import TrackingCodesRepository from "@/Repositories/Tenants/CRM/TrackingCodesRepository.js";
import OrdersRepository from "@/Repositories/Tenants/CRM/OrdersRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))
const TrackingCodeModal = defineAsyncComponent(() => import('./TrackingCode/TrackingCodeModal.vue'))

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
    isShowTrackingCodeModal: false,
    isLoadingModal: false,
    isLoadingRefreshButton: false,
    data: {},
    search: null,
    trackingItem: {},
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
    data.page = (lazyParams.value.page || 0) + 1;
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
const toggleTrackingCodeModal = (val) => val ? state.isShowTrackingCodeModal = val : state.isShowTrackingCodeModal = !state.isShowTrackingCodeModal;
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
        comment: null,
        discount: null,
        tracking_codes: [
            {
                code: null,
                delivery_service_id: null,
            }
        ],
        items: [],
        costs: [],
        invoices: [],
    };
    toggleModal();
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
        } = item.value;
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
        if (!item.value.tracking_codes.length) {
            item.value.tracking_codes.push({
                code: null,
                delivery_service_id: null,
            })
        } else {
            item.value.tracking_codes = item.value.tracking_codes.map((i) => {
                return {
                    delivery_service_id: {value: i.delivery_service_id},
                    code: i.code
                }
            })
        }
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

const showTrackingCodeLog = async (id) => {
    try {
        const response = await TrackingCodesRepository.edit(id);
        state.trackingItem = response.result;
        toggleTrackingCodeModal();
    } catch (e) {
        console.error(e);
    }
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
                                    <div class="p-2 status-drowdown font-bold"
                                         :style="`background: ${slotProps.option.hex};`">
                                        <div>{{ slotProps.option.label }}</div>
                                    </div>
                                </template>
                            </MultiSelect>
                        </div>
                    </div>
                </template>

                <Column field="id" sortable="" style="width:5%">
                    <template #header>
                        <div class="w-full text-center">ID</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ data.id }}</div>
                    </template>
                </Column>
                <Column field="status_id" style="width:10%">
                    <template #header>
                        <div class="w-full text-center">Статус</div>
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
                <Column field="source_id" style="width:10%">
                    <template #header>
                        <div class="w-full text-center">Джерело</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.source.title }}
                        </div>
                    </template>
                </Column>
                <Column field="manager_id" style="width:10%">
                    <template #header>
                        <div class="w-full text-center">Менеджер</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.manager && data.manager.name ? data.manager.name : '(Пусто)' }}
                        </div>
                    </template>
                </Column>
                <Column field="client_id" style="width:10%">
                    <template #header>
                        <div class="w-full text-center">Клієнт</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.client && data.client.full_name ? data.client.full_name : '(Пусто)' }}
                        </div>
                    </template>
                </Column>
                <Column style="width:10%">
                    <template #header>
                        <div class="w-full text-center">Телефон</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            <div v-if="data.client && data.client.phones.length"
                                 v-for="phone in data.client.phones">
                                {{ $filters.formatPhone(phone.number) }}
                            </div>
                            <div v-else>(Пусто)</div>
                        </div>
                    </template>
                </Column>
                <Column field="tracking_codes" style="width:15%">
                    <template #header>
                        <div class="flex justify-center w-full">
                            Трекінг-код
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="grid grid-cols-1 gap-2">
                            <Button v-if="data.tracking_codes.length"
                                    v-for="item in data.tracking_codes"
                                    :label="item.code"
                                    text
                                    size="small"
                                    @click="showTrackingCodeLog(item.id)"
                            />
                            <div v-else class="text-center">(Пусто)</div>
                        </div>
                    </template>
                </Column>
                <Column field="tracking_codes" style="width:20%">
                    <template #header>
                        <div class="flex justify-center w-full">
                            Статусу доставки
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="grid grid-cols-1 gap-2">
                            <Button v-if="data.tracking_codes.length"
                                    v-for="tracking_code in data.tracking_codes"
                                    :label="tracking_code.log[(tracking_code.log).length - 1]['status']"
                                    text
                                    size="small"
                                    @click="showTrackingCodeLog(tracking_code.id)"
                            />
                        </div>
                    </template>
                </Column>
                <Column field="items" style="width:10%">
                    <template #header>
                        <div class="flex justify-center w-full">
                            Загальна сума
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.total_price) }}</div>
                    </template>
                </Column>

                <Column field="items" header="Товари" style="width:10%">
                    <template #body="{data}">
                        <div v-if="data.items.length" v-for="item in data.items">
                            {{ item.title }}
                        </div>
                        <div v-else>(Пусто)</div>
                    </template>
                </Column>

                <Column field="items" header="Ціни" style="width:8%">
                    <template #body="{data}">
                        <div v-if="data.items.length" v-for="item in data.items">
                            {{ $filters.formatMoney(item.sale_price) }}
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
        <Modal v-if="state.isShowModal"
               :show="state.isShowModal"
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
        <TrackingCodeModal v-if="state.isShowTrackingCodeModal"
                           :show="state.isShowTrackingCodeModal"
                           :item="state.trackingItem"
                           @close="toggleTrackingCodeModal"
        />
    </AppLayout>
</template>

<style>
.orders-status-filter-select.p-multiselect-panel .p-multiselect-items .p-multiselect-item-group {
    padding: 0;
}
</style>
