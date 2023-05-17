<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal/Modal.vue';
import Form from './Form.vue';
import InputText from 'primevue/inputtext';

import CategoriesRepository from "@/Repositories/CategoriesRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import isDark from "@/Includes/isDark.js";

const state = reactive({
    isLoading: false,
    isShowModal: false,
    isLoadingModal: false,
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

onMounted(async () => await fetch());

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
        const data = await CategoriesRepository.fetch(queryParams());
        state.data = data.success ? data.result : [];
    } catch (e) {
        console.error(e);
        toast.error("Failed to fetch data", {
            autoClose: 3000,
        });
    }
    switchLoader();
}

const toggleModal = (val) => val ? state.isShowModal = val : state.isShowModal = !state.isShowModal;
const switchLoader = (val) => val ? state.isLoading = val : state.isLoading = !state.isLoading;

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

const onFilter = async (val) => {
    lazyParams.value.filter = val !== null ? {
        column: 'availability',
        operator: '=',
        key: val
    } : null;
    await fetch();
}
const onCreate = () => {
    item.value = {
        id: null,
        availability: {value: -1},
        title: null,
        description: null,
        trade_price: null,
        price: null,
        discount_price: null,
        image: null,
        sku: null
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        item.value.id
            ? await CategoriesRepository.update(item.value)
            : await CategoriesRepository.create(item.value);

        await fetch();
        toggleModal();
        toast.success("Success", {
            autoClose: 2000,
        });
    } catch (e) {
        console.error(e);
        toast.error("Error", {
            autoClose: 2000,
        });
    }
    state.isLoadingModal = false;
}

const onEdit = async (id) => {
    switchLoader();
    try {
        const data = await CategoriesRepository.edit(id);
        item.value = data.result;
        item.value.availability = {value: data.result.availability};
        toggleModal();
    } catch (e) {
        console.error(e);
        toast.error("Failed to get data", {
            autoClose: 3000,
        });
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
                await CategoriesRepository.destroy(id);
                await fetch();
                toast.success('Запис успішно видалено', {
                    autoClose: 3000,
                    theme: isDark ? 'dark' : 'light'
                });
            } catch (error) {
                console.error(error);
                toast.error('Виникла помилка', {
                    autoClose: 3000,
                    theme: isDark ? 'dark' : 'light'
                });
            }
        }
    });
}

const onSearch = async () => {
    if (state.search) {
        switchLoader();
        try {
            const data = await CategoriesRepository.search(state.search);
            state.data = data.success ? data.result : [];
        } catch (e) {
            console.error(e);
            toast.error("Failed to fetch data", {
                autoClose: 3000,
            });
        }
        switchLoader();
    }
}
</script>

<template>
    <AppLayout>
        <div class="card">
            <Toolbar class="mb-4">
                <template #start>
                    <Button label="Додати" icon="pi pi-plus" severity="success" class="mr-2" @click="onCreate"/>
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
                <Column field="title" header="Назва"></Column>
                <Column field="description" header="Опис"></Column>
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
        <Modal :show="state.isShowModal" @close="toggleModal(false)">
            <template #body>
                <Form :item="item"/>
            </template>
            <template #footer>
                <Button label="Скасувати"
                        icon="pi pi-times"
                        @click="toggleModal(false)"
                        text
                />
                <Button label="Зберегти"
                        icon="pi pi-check"
                        @click="onSubmit"
                        autofocus
                        :loading="state.isLoadingModal"
                />
            </template>
        </Modal>
    </AppLayout>
</template>
