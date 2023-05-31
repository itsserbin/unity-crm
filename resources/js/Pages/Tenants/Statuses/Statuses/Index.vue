<script setup>
import InputSwitch from "primevue/inputswitch";
import Column from "primevue/column";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Toolbar from "primevue/toolbar";

import {defineAsyncComponent, onMounted, reactive, ref} from "vue";
import StatusesRepository from "@/Repositories/Tenants/StatusesRepository.js";
import {toast} from "vue3-toastify";
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['statuses']);

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
    rows: 30,
    sortField: null,
    sortOrder: null,
});

const item = ref();

onMounted(async () => state.data = props.statuses);

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
        const data = await StatusesRepository.fetch(queryParams());
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
        title: null,
        group_slug: null,
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        if (item.value.group_slug) {
            item.value.group_slug = item.value.group_slug.value;
        }

        item.value.id
            ? await StatusesRepository.update(item.value)
            : await StatusesRepository.create(item.value);

        await fetch();
        toggleModal();
        toast.success("Success",);
    } catch (e) {
        console.error(e);
        toast.error("Error",);
    }
    state.isLoadingModal = false;
}

const onEdit = async (id) => {
    switchLoader();
    try {
        const data = await StatusesRepository.edit(id);
        item.value = data.result;
        item.value.group_slug = {value: data.result.group_slug};
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
                await StatusesRepository.destroy(id);
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
        rows: 30,
        sortField: null,
        sortOrder: null,
    });
    await fetch();
    switchLoaderRefreshButton();
}

const setPublishedStatus = async (id, val) => {
    switchLoader();
    try {
        await StatusesRepository.setPublished({id: id, value: val});
        toast.success("Оновлено!");
    } catch (e) {
        console.error(e);
        toast.error("Failed set status");
    }
    switchLoader();
}
</script>

<template>
    <Toolbar class="mb-4">
        <template #start>
            <div class="flex gap-2 items-center">
                <Button icon="pi pi-refresh"
                        type="button"
                        size="small"
                        @click="refreshData"
                        :loading="state.isLoadingRefreshButton"
                />
                <Button label="Додати" size="small" icon="pi pi-plus" class="mr-2" @click="onCreate"/>
            </div>
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
               paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
    >

        <Column field="title" header="Група" style="width:10%">
            <template #body="{data}">
                <Button type="button"
                        :label="data.group.title"
                        :style="`background: ${data.group.hex}; border-color: ${data.group.hex};`"
                />
            </template>
        </Column>
        <Column field="title" header="Назва"></Column>
        <Column field="orders_count"
                header="Кількість замовлень"
                headerClass="flex justify-center"
        >
            <template #body="{data}">
                <div class="text-center">
                    {{ data.orders_count }}
                </div>
            </template>
        </Column>
        <Column field="published" header="Активний">
            <template #body="{data}">
                <Button type="button" text>
                    <InputSwitch v-model="data.published"
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
                <div class="text-end">
                    <Button icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="onDestroy(data.id)"
                    />
                </div>
            </template>
        </Column>
    </DataTable>
    <Modal v-if="state.isShowModal"
           :show="state.isShowModal"
           :item="item"
           @close="toggleModal(false)"
           @submit="onSubmit"
           :isLoadingModal="state.isLoadingModal"
    />
</template>
