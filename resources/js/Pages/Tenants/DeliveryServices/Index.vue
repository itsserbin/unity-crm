<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';

import DeliveryServicesRepository from "@/Repositories/Tenants/DeliveryServicesRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import Heading from "@/Components/Heading.vue";
import InputSwitch from "primevue/inputswitch";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['deliveryServices', 'globalServices']);

const state = reactive({
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
});

const item = ref();

onMounted(async () => state.data = props.deliveryServices);

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
    return data;
}
const fetch = async () => {
    switchLoader();
    try {
        const data = await DeliveryServicesRepository.fetch(queryParams());
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
        title: null,
        type: null,
        api_key: null,
        configuration: [],
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        if (item.value.type) {
            item.value.type = item.value.type.code;
        }

        item.value.id
            ? await DeliveryServicesRepository.update(item.value)
            : await DeliveryServicesRepository.create(item.value);

        await fetch();
        toggleModal();
        toast.success("Success");
    } catch (e) {
        console.error(e);
        toast.error("Error");
    }
    state.isLoadingModal = false;
}

const onEdit = async (id) => {
    switchLoader();
    try {
        const data = await DeliveryServicesRepository.edit(id);
        item.value = data.result;
        item.value.type = {code: data.result.type};
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
                await DeliveryServicesRepository.destroy(id);
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
            const data = await DeliveryServicesRepository.search(state.search);
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
    });
    await fetch();
    switchLoaderRefreshButton();
}

const setPublishedStatus = async (id, val) => {
    switchLoader();
    try {
        await DeliveryServicesRepository.setPublished({id: id, value: val});
        toast.success("Оновлено!");
    } catch (e) {
        console.error(e);
        toast.error("Failed set status");
    }
    switchLoader();
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
                        <Heading>Служби доставки</Heading>
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
                <Column field="title" header="Назва"></Column>
                <Column field="type" header="Тип"></Column>
                <Column field="published" header="Активний">
                    <template #body="{data}">
                        <Button type="button" text>
                            <InputSwitch v-model="data.status"
                                         :true-value="1"
                                         :false-value="0"
                                         @change="setPublishedStatus(data.id,data.published)"
                                         :disabled="data.orders_count > 0"
                            />
                        </Button>
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
               :globalServices="globalServices"
        />
    </AppLayout>
</template>
