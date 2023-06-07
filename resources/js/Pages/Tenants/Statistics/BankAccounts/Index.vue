<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';
import Heading from "@/Components/Heading.vue";

import BankAccountsRepository from "@/Repositories/Tenants/Statistics/BankAccountsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['bankAccounts']);

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
    if (props.bankAccounts) {
        state.data = props.bankAccounts
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
        const data = await BankAccountsRepository.fetch(queryParams());
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
        name: null,
        data: {},
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        if (item.value.source) {
            item.value.source = item.value.source.code;
        }

        item.value.id
            ? await BankAccountsRepository.update(item.value)
            : await BankAccountsRepository.create(item.value);

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
        const data = await BankAccountsRepository.edit(id);
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
                await BankAccountsRepository.destroy(id);
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
                        <Heading>Банківські рахунки</Heading>
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
                            {{ data.name }}
                        </div>
                    </template>
                </Column>
                <Column>
                    <template #header>
                        <div class="text-center w-full">
                            Тип
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.data.bank.label }}
                        </div>
                    </template>
                </Column>
                <Column field="data" header="Дані">
                    <template #body="{data}">
                        <div class="flex flex-col gap-3">
                            <div v-if="data.data">
                                {{ data.data.name }}
                            </div>
                            <div v-if="data.data.account">
                                {{ data.data.account.label }}
                            </div>
                            <div v-if="data.data.account">
                                {{ data.data.account.iban }}
                            </div>
                        </div>
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
        <Modal v-if="state.isShowModal"
               :show="state.isShowModal"
               :item="item"
               @close="toggleModal(false)"
               @submit="onSubmit"
               :isLoadingModal="state.isLoadingModal"
        />
    </AppLayout>
</template>
