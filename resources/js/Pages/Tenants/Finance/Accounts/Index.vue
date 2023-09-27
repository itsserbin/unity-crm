<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';
import Heading from "@/Components/Heading.vue";

import AccountsRepository from "@/Repositories/Tenants/Finance/AccountsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent, inject} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['accounts']);
const can = inject('$can');

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

onMounted(async () => {
    if (props.accounts) {
        state.data = props.accounts
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
    if (can('read-finance-accounts')) {
        switchLoader();
        try {
            const data = await AccountsRepository.fetch(queryParams());
            state.data = data.success ? data.result : [];
        } catch (e) {
            console.error(e);
            toast.error("Failed to fetch data");
        }
        switchLoader();
    }
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
    if (can('create-finance-accounts')) {
        item.value = {
            id: null,
            title: null,
        };
        toggleModal();
    }
}

const onSubmit = async () => {
    if (can('update-finance-accounts')) {
        state.isLoadingModal = true;
        try {
            item.value.id
                ? await AccountsRepository.update(item.value)
                : await AccountsRepository.create(item.value);

            await fetch();
            toggleModal();
            toast.success("Success");
        } catch (e) {
            console.error(e);
            toast.error("Error");
        }
        state.isLoadingModal = false;
    }
}

const onEdit = async (id) => {
    if (can('update-finance-accounts')) {
        switchLoader();
        try {
            const data = await AccountsRepository.edit(id);
            item.value = data.result;
            item.value.source = {code: data.result.source};
            toggleModal();
        } catch (e) {
            console.error(e);
            toast.error("Failed to get data");
        }
        switchLoader();
    }
}

const onDestroy = async (id) => {
    if (can('delete-finance-accounts')) {
        await useConfirm({
            message: 'Ви точно бажаєте видалити цей запис?',
            header: 'Підтвердження дії',
            icon: 'pi pi-exclamation-triangle',
            accept: async () => {
                try {
                    await AccountsRepository.destroy(id);
                    await fetch();
                    toast.success('Запис успішно видалено');
                } catch (error) {
                    console.error(error);
                    toast.error('Виникла помилка');
                }
            }
        });
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
</script>

<template>
    <AppLayout :can="can('read-finance-accounts')">
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
                        <Heading>Рахунки</Heading>
                    </div>
                </template>
                <template #end v-if="can('create-finance-accounts')">
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
                <Column field="id" sortable="" style="width:5%;">
                    <template #header>
                        <div class="text-center w-full">
                            ID
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.id }}
                        </div>
                    </template>
                </Column>
                <Column>
                    <template #header>
                        <div class="text-center w-full">
                            Назва
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.title }}
                        </div>
                    </template>
                </Column>
                <Column>
                    <template #header>
                        <div class="text-center w-full">
                            Баланс
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.balance) }}
                        </div>
                    </template>
                </Column>
                <Column>
                    <template #body="{data}" v-if="can('create-finance-accounts')">
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
        <Modal v-if="state.isShowModal"
               :show="state.isShowModal"
               :item="item"
               @close="toggleModal(false)"
               @submit="onSubmit"
               :isLoadingModal="state.isLoadingModal"
        />
    </AppLayout>
</template>
