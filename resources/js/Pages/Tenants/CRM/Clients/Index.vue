<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Heading from "@/Components/Heading.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

import ClientsRepository from "@/Repositories/Tenants/CRM/ClientsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['clients']);

const state = reactive({
    isLoading: false,
    isShowModal: false,
    isLoadingModal: false,
    isLoadingRefreshButton: false,
    data: {},
    errors: [],
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
    data.page = (lazyParams.value.page || 0) + 1;
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
        phones: [''],
        emails: [''],
        full_name: null,
        comment: null,
        addresses: [],
        orders: []
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    state.errors = [];
    try {
        const data = item.value.id
            ? await ClientsRepository.update(item.value)
            : await ClientsRepository.create(item.value);

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
                    <Button label="Додати" size="small" icon="pi pi-plus" class="mr-2" @click="onCreate"/>
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
                    <form @submit.prevent="onSearch" class="flex gap-2 items-center">
                        <InputText v-model="state.search" placeholder="Пошук..."/>
                        <Button severity="secondary"
                                text
                                rounded
                                icon="pi pi-search"
                                type="submit"
                        />
                    </form>
                </template>

                <Column field="id" sortable="" style="width:5%;">
                    <template #header>
                        <div class="w-full text-center">ID</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ data.id }}</div>
                    </template>
                </Column>
                <Column field="full_name" style="width:10%;" sortable="">
                    <template #header>
                        <div class="w-full text-center">Повне імʼя</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ data.full_name }}</div>
                    </template>
                </Column>
                <Column field="phones" style="width:10%;">
                    <template #header>
                        <div class="w-full text-center">Телефон</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            <div v-for="phone in data.phones" v-if="data.phones.length">
                                <a :href="'tel:' + phone">{{ $filters.formatPhone(phone) }}</a>
                            </div>
                            <div v-else>Не вказано</div>
                        </div>
                    </template>
                </Column>
                <Column field="emails" style="width:10%;">
                    <template #header>
                        <div class="w-full text-center">Email</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            <div v-for="email in data.emails" v-if="data.emails.length">
                                <a :href="'mailto:' + email">{{ email }}</a>
                            </div>
                            <div v-else>Не вказано</div>
                        </div>
                    </template>
                </Column>
                <Column field="number_of_orders" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Кількість <br> замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ data.number_of_orders }}</div>
                    </template>
                </Column>
                <Column field="number_of_success_orders" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Кількість <br> виконаних <br> замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ data.number_of_success_orders }}</div>
                    </template>
                </Column>
                <Column field="average_check" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Середній <br> чек
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.average_check) }}</div>
                    </template>
                </Column>
                <Column field="success_average_check" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Середній <br> чек <br> виконаних <br> замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.success_average_check) }}</div>
                    </template>
                </Column>
                <Column field="general_check" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Загальний <br> чек
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.general_check) }}</div>
                    </template>
                </Column>
                <Column field="success_general_check" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Загальний <br> чек <br> виконаних <br> замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.success_general_check) }}</div>
                    </template>
                </Column>
                <Column field="last_order_created_at" sortable="">
                    <template #header>
                        <div class="w-full text-center">
                            Останне <br> замовлення
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.last_order_created_at ? $filters.formatDateTime(data.last_order_created_at) : '-' }}
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
               :isLoadingModal="state.isLoadingModal"
               :errors="state.errors"
               @submit="onSubmit"
               @close="toggleModal(false)"
        />
    </AppLayout>
</template>
