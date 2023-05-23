<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Heading from "@/Components/Heading.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import Modal from './Modal.vue';
import Form from './Form.vue';
import InputText from 'primevue/inputtext';

import ClientsRepository from "@/Repositories/ClientsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const props = defineProps(['clients']);

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

onMounted(async () => state.data = props.clients);

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
        const data = await ClientsRepository.fetch(queryParams());
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
        phones: [{number: null}],
        emails: [{address: null}],
        full_name: null,
        comment: null,
        addresses: [],
        orders: []
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        item.value.id
            ? await ClientsRepository.update(item.value)
            : await ClientsRepository.create(item.value);

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
        const data = await ClientsRepository.edit(id);
        item.value = data.result;
        item.value.availability = {value: data.result.availability};
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
                await ClientsRepository.destroy(id);
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
            const data = await ClientsRepository.search(state.search);
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
                        <Heading>Клієнти</Heading>
                    </div>
                </template>
                <template #end>
                    <Button label="Додати" icon="pi pi-plus" class="mr-2" @click="onCreate"/>
                </template>
            </Toolbar>

            <DataTable resizableColumns
                       columnResizeMode="expand"
                       selectionMode="single"
                       @rowSelect="onRowSelect"
                       dataKey="id"
                       :loading="state.isLoading"
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
                    <div class="p-input-icon-left">
                        <form @submit.prevent="onSearch">
                            <InputText v-model="state.search" placeholder="Пошук..."/>
                            <Button severity="secondary"
                                    text
                                    rounded
                                    icon="pi pi-search"
                                    type="submit"
                            />
                        </form>
                    </div>
                </template>

                <Column field="id" header="ID" sortable=""></Column>
                <Column field="full_name" header="Повне імʼя" sortable=""></Column>
                <Column field="phones" header="Телефон">
                    <template #body="{data}">
                        <div v-for="phone in data.phones" v-if="data.phones.length">
                            <a :href="'tel:' + phone.number">{{ $filters.formatPhone(phone.number) }}</a>
                        </div>
                        <div v-else>Пусто</div>
                    </template>
                </Column>
                <Column field="phones" header="Email">
                    <template #body="{data}">
                        <div v-for="email in data.emails" v-if="data.emails.length">
                            <a :href="'mailto:' + email.address">{{ email.email }}</a>
                        </div>
                        <div v-else>Пусто</div>
                    </template>
                </Column>
                <Column field="number_of_orders" header="Кількість замовлень" sortable=""></Column>
                <Column field="number_of_success_orders" header="Кількість виконаних замовлень" sortable=""></Column>
                <Column field="average_check" header="Середній чек" sortable=""></Column>
                <Column field="general_check" header="Загальний чек" sortable=""></Column>
                <Column field="last_order_created_at" header="Останне замовлення" sortable=""></Column>
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
        <Modal :show="state.isShowModal"
               :item="item"
               @close="toggleModal(false)"
               @submit="onSubmit"
               :isLoadingModal="state.isLoadingModal"
        />
    </AppLayout>
</template>
