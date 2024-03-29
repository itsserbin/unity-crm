<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';
import Heading from "@/Components/Heading.vue";

import RolesRepository from "@/Repositories/Tenants/Options/RolesRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent, inject} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(
    () => import('./Modal.vue')
);

const props = defineProps([
    'roles',
    'permissions'
]);
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

onMounted(async () => {
    if (props.roles) {
        state.data = props.roles
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
    if (can('read-roles')) {
        switchLoader();
        try {
            const data = await RolesRepository.fetch(queryParams());
            state.data = data.success ? data.result : {data: []};
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
    if (can('create-roles')) {
        item.value = {
            id: null,
            name: null,
            permissions: [],
        };
        toggleModal();
    }
}

const onSubmit = async () => {
    if (can('update-roles')) {
        state.isLoadingModal = true;
        state.errors = [];
        try {
            const data = item.value.id
                ? await RolesRepository.update(item.value)
                : await RolesRepository.create(item.value);

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
    if (can('update-roles')) {
        switchLoader();
        try {
            const data = await RolesRepository.edit(id);
            item.value = data.result;
            item.value.permissions = item.value.permissions.map(item => item.name);
            toggleModal();
        } catch (e) {
            console.error(e);
            toast.error("Failed to get data");
        }
        switchLoader();
    }
}

const onDestroy = async (id) => {
    if (can('delete-roles')) {
        await useConfirm({
            message: 'Ви точно бажаєте видалити цей запис?',
            header: 'Підтвердження дії',
            icon: 'pi pi-exclamation-triangle',
            accept: async () => {
                try {
                    await RolesRepository.destroy(id);
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
    if (can('read-roles')) {
        if (state.search) {
            switchLoader();
            try {
                const data = await RolesRepository.search(state.search);
                state.data = data.success ? data.result : [];
            } catch (e) {
                console.error(e);
                toast.error("Failed to fetch data");
            }
            switchLoader();
        }
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
    <AppLayout :can="can('read-roles')">
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
                        <Heading>Ролі користувачів</Heading>
                    </div>
                </template>
                <template #end v-if="can('create-roles')">
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

                <Column field="id" header="ID" sortable=""></Column>
                <Column field="name" header="Назва"></Column>
                <Column>
                    <template #body="{data}" v-if="can('delete-roles')">
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
               :item="item"
               :errors="state.errors"
               :permissions="permissions"
               :show="state.isShowModal"
               :isLoadingModal="state.isLoadingModal"
               @submit="onSubmit"
               @close="toggleModal(false)"
        />
    </AppLayout>
</template>
