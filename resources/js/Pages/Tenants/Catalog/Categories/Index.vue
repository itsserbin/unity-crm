<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';

import CategoriesRepository from "@/Repositories/Tenants/Catalog/CategoriesRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent, inject} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import Heading from "@/Components/Heading.vue";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['categories']);

const can = inject('$can');

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

onMounted(async () => state.data = props.categories);

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
    if (can('read-categories')) {
        switchLoader();
        try {
            const data = await CategoriesRepository.fetch(queryParams());
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
    if (can('read-categories')) {
        item.value = {
            id: null,
            title: null,
            description: null,
            preview_id: null,
            preview: null
        };
        toggleModal();
    }
}

const onSubmit = async () => {
    if (can('update-categories')) {
        state.isLoadingModal = true;
        state.errors = [];
        try {
            const data = item.value.id
                ? await CategoriesRepository.update(item.value)
                : await CategoriesRepository.create(item.value);

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
}

const onEdit = async (id) => {
    if (can('update-categories')) {
        switchLoader();
        try {
            const data = await CategoriesRepository.edit(id);
            item.value = data.result;
            item.value.availability = {value: data.result.availability};
            toggleModal();
        } catch (e) {
            console.error(e);
            toast.error("Failed to get data");
        }
        switchLoader();
    }
}

const onDestroy = async (id) => {
    if (can('delete-categories')) {
        await useConfirm({
            message: 'Ви точно бажаєте видалити цей запис?',
            header: 'Підтвердження дії',
            icon: 'pi pi-exclamation-triangle',
            accept: async () => {
                try {
                    await CategoriesRepository.destroy(id);
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

const onSearch = async () => {
    if (state.search && can('read-categories')) {
        switchLoader();
        try {
            const data = await CategoriesRepository.search(state.search);
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
    <AppLayout :can="can('read-categories')">
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
                        <Heading>Категорії</Heading>
                    </div>
                </template>
                <template #end v-if="can('create-categories')">
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

                <Column field="id" header="ID" sortable="" style="width:10%;"></Column>
                <Column field="preview_id">
                    <template #header>
                        <div class="w-full text-center">Зображення</div>
                    </template>
                    <template #body="{data}">
                        <picture v-if="data.preview">
                            <source :srcset="route('images',data.preview.data.webp)"
                                    type="image/webp">

                            <img :src="route('images',data.preview.data.jpeg)"
                                 :alt="data.preview.data.alt"
                                 class="object-cover w-[55px] mx-auto"
                                 loading="lazy"
                            >
                        </picture>
                        <img v-else class="mx-auto w-[55px]"
                             src="/storage/no_image.jpeg"
                             :alt="data.title"/>
                    </template>
                </Column>
                <Column field="title" header="Назва"></Column>
                <Column field="description" style="width:40%;">
                    <template #header>
                        <div class="w-full text-center">Опис</div>
                    </template>
                </Column>
                <Column>
                    <template #body="{data}" v-if="can('delete-categories')">
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
               :errors="state.errors"
               :isLoadingModal="state.isLoadingModal"
               @close="toggleModal(false)"
               @submit="onSubmit"
        />
    </AppLayout>
</template>
