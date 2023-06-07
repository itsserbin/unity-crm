<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';
import Heading from "@/Components/Heading.vue";
import DownloadDataButton from './DownloadData/Button.vue'
import CreateDataButtons from '@/Pages/Tenants/Statistics/BankAccountMovements/DataEditor/CreateButtons.vue'

import BankAccountMovementsRepository from "@/Repositories/Tenants/Statistics/BankAccountMovementsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const UpdateItem = defineAsyncComponent(() => import('./DataEditor/UpdateItem.vue'))

const props = defineProps(['data']);

const isShowUpdateItemModal = ref(false);
const item = ref(null);

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

onMounted(async () => {
    if (props.data) {
        state.data = props.data
    } else {
        await fetch();
    }
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
    data.page = (lazyParams.value.page || 0) + 1;
    return data;
}
const fetch = async () => {
    switchLoader();
    try {
        const data = await BankAccountMovementsRepository.fetch(queryParams());
        state.data = data.success ? data.result : [];
    } catch (e) {
        console.error(e);
        toast.error("Failed to fetch data");
    }
    switchLoader();
}

const toggleUpdateItemModal = () => isShowUpdateItemModal.value = !isShowUpdateItemModal.value;
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

const onDestroy = async (id) => {
    await useConfirm({
        message: 'Ви точно бажаєте видалити цей запис?',
        header: 'Підтвердження дії',
        icon: 'pi pi-exclamation-triangle',
        accept: async () => {
            try {
                await BankAccountMovementsRepository.destroy(id);
                await fetch();
                toast.success('Запис успішно видалено');
            } catch (error) {
                console.error(error);
                toast.error('Виникла помилка');
            }
        }
    });
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

const formatComment = (val) => {
    return val && val.length > 30 ? val.slice(0, 30) + "..." : val;
}

const onSubmitUpdateItem = async () => {
    await fetch();
    toggleUpdateItemModal();
}
const onRowSelect = async (e) => {
    try {
        const data = await BankAccountMovementsRepository.edit(e.data.id);
        item.value = data.result;
        if (item.value.category_id) {
            item.value.category_id = {value: item.value.category_id};
        }
        if (item.value.account_id) {
            item.value.account_id = {value: item.value.account_id};
        }
        toggleUpdateItemModal();
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
                        <Button icon="pi pi-refresh" type="button"
                                size="small" @click="refreshData"
                                :loading="state.isLoadingRefreshButton"
                        />
                        <Heading>Грошові рухи</Heading>
                    </div>
                </template>
                <template #center>
                    <CreateDataButtons @submit="refreshData"/>
                </template>
                <template #end>
                    <DownloadDataButton @submit="refreshData"/>
                </template>
            </Toolbar>

            <DataTable resizableColumns columnResizeMode="expand"
                       selectionMode="single" @rowSelect="onRowSelect"
                       dataKey="id" :loading="state.isLoading"
                       v-if="state.data" :value="state.data.data"
                       lazy paginator :rows="state.data.per_page"
                       :totalRecords="state.data.total"
                       @page="onPage($event)" @sort="onSort($event)"
                       :rowsPerPageOptions="[15, 50, 100, 500]"
                       paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            >
                <Column sortable="" field="date" header="Дата">
                    <template #body="{data}">
                        {{ $filters.formatDate(data.date) }}
                        <br>
                        <span class="text-[0.7rem]">
                            {{ $filters.formatTime(data.date) }}
                        </span>
                    </template>
                </Column>

                <Column field="account_id" header="Рахунок">
                    <template #body="{data}">
                        {{ data.account_id ? data.account.name : '(Пусто)' }}
                    </template>
                </Column>

                <Column field="category_id" header="Категорія">
                    <template #body="{data}">
                        {{ data.category_id ? data.category.title : '(Пусто)' }}
                    </template>
                </Column>

                <Column sortable="" field="sum" header="Сума">
                    <template #body="{data}">
                        {{ $filters.formatMoney(data.sum) }}
                    </template>
                </Column>

                <Column sortable="" field="balance" header="Залишок">
                    <template #body="{data}">
                        {{ $filters.formatMoney(data.balance) }}
                    </template>
                </Column>

                <Column sortable="" field="comment" header="Коментар" style="width:20px;">
                    <template #body="{data}">
                        {{ formatComment(data.comment) }}
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
            </DataTable>
        </div>
        <UpdateItem :show="isShowUpdateItemModal"
                    v-if="isShowUpdateItemModal"
                    :item="item"
                    @close="toggleUpdateItemModal"
                    @submit="onSubmitUpdateItem"
        />
    </AppLayout>
</template>
