<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import Heading from "@/Components/Heading.vue";
import DownloadDataButton from './DownloadData/Button.vue'
import AccountsGridToolbar from './AccountsGrid/Toolbar.vue'
import CreateDataButtons from '@/Pages/Tenants/Finance/BankAccountMovements/DataEditor/CreateButtons.vue'

import AccountsRepository from "@/Repositories/Tenants/Finance/AccountsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import BankAccountMovementsRepository from "@/Repositories/Tenants/Finance/BankAccountMovementsRepository.js";

const UpdateItem = defineAsyncComponent(() => import('./DataEditor/UpdateItem.vue'))

const props = defineProps(['data', 'accounts']);

const isShowUpdateItemModal = ref(false);
const item = ref(null);

const state = reactive({
    isLoading: false,
    isShowModal: false,
    isLoadingModal: false,
    isLoadingRefreshButton: false,
    data: {},
    banks: [],
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
    if (props.accounts) {
        state.banks = props.accounts
    } else {
        await getBanks();
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
    if (lazyParams.value.account_id) {
        data.account_id = lazyParams.value.account_id;
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
                await getBanks();
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
    await getBanks();
    switchLoaderRefreshButton();
}

const formatComment = (val) => {
    return val && val.length > 30 ? val.slice(0, 30) + "..." : val;
}

const onSubmitUpdateItem = async () => {
    await fetch();
    await getBanks()
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

const getBanks = async () => {
    try {
        const data = await AccountsRepository.list();
        state.banks = data.result;
    } catch (e) {
        console.error(e);
    }
}

const onSelectAccount = async (val) => {
    switchLoaderRefreshButton();
    lazyParams.value = ({
        page: 0,
        first: 0,
        rows: 15,
        sortField: null,
        sortOrder: null,
        account_id: val,
    });
    await fetch();
    switchLoaderRefreshButton();
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

            <AccountsGridToolbar @clickButton="onSelectAccount" :banks="state.banks"/>

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
                <Column sortable="" field="date">
                    <template #header>
                        <div class="text-center w-full">
                            Дата
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center w-full">
                            {{ $filters.formatDate(data.date) }}
                            <br>
                            <span class="text-[0.7rem]">
                            {{ $filters.formatTime(data.date) }}
                        </span>
                        </div>
                    </template>
                </Column>

                <Column field="account_id">
                    <template #header>
                        <div class="text-center w-full">
                            Рахунок
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.account_id ? data.account.title : '(Пусто)' }}
                        </div>
                    </template>
                </Column>

                <Column field="category_id">
                    <template #header>
                        <div class="text-center w-full">
                            Категорія
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.category_id ? data.category.title : '(Пусто)' }}
                        </div>
                    </template>
                </Column>

                <Column sortable="" field="sum">
                    <template #header>
                        <div class="text-center w-full">
                            Сума
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.sum) }}
                        </div>
                    </template>
                </Column>

                <Column sortable="" field="balance">
                    <template #header>
                        <div class="text-center w-full">
                            Залишок
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.balance) }}
                        </div>
                    </template>
                </Column>

                <Column sortable="" field="comment" header="Коментар" style="width:20px;">
                    <template #body="{data}">
                        {{ formatComment(data.comment) }}
                    </template>
                </Column>
                <Column>
                    <template #body="{data}">
                        <div class="text-end">
                            <Button icon="pi pi-trash"
                                    outlined
                                    rounded
                                    severity="danger"
                                    size="small"
                                    @click="onDestroy(data.id)"
                            />
                        </div>
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
