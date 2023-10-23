<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';
import Heading from "@/Components/Heading.vue";

import UsersRepository from "@/Repositories/Tenants/Options/UsersRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent, inject} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";

const Modal = defineAsyncComponent(
    () => import('./Modal.vue')
);

const props = defineProps(['users','roles']);
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
    if (props.sources) {
        state.data = props.sources
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
    if (can('read-users')) {
        switchLoader();
        try {
            const data = await UsersRepository.fetch(queryParams());
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
    if (can('create-users')) {
        item.value = {
            name: null,
            phone: null,
            email: null,
            roles: [],
            password: null,
            password_confirmation: null,
        };
        toggleModal();
    }
}

const onSubmit = async () => {
    if (can('update-users')) {
        state.isLoadingModal = true;
        state.errors = [];
        try {
            const responseData = JSON.parse(JSON.stringify(item.value));
            responseData.roles = responseData.roles.map(item => item.name);
            responseData.phone = responseData.phone.replace(/\D/g, '');

            const data = item.value.id
                ? await UsersRepository.update(responseData)
                : await UsersRepository.create(responseData);

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
    if (can('update-users')) {
        switchLoader();
        try {
            const data = await UsersRepository.edit(id);
            item.value = data.result;
            toggleModal();
        } catch (e) {
            console.error(e);
            toast.error("Failed to get data");
        }
        switchLoader();
    }
}

const onDestroy = async (id) => {
    if (can('delete-users')) {
        await useConfirm({
            message: 'Ви точно бажаєте видалити цей запис?',
            header: 'Підтвердження дії',
            icon: 'pi pi-exclamation-triangle',
            accept: async () => {
                try {
                    await UsersRepository.destroy(id);
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
    if (can('read-users')) {
        if (state.search) {
            switchLoader();
            try {
                const data = await UsersRepository.search(state.search);
                state.data = data.success ? data.result : {data: []};
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
    <AppLayout :can="can('read-users')">
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
                        <Heading>Користувачі</Heading>
                    </div>
                </template>
                <template #end v-if="can('create-users')">
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
                <Column field="name" header="Імʼя"></Column>
                <Column field="email" header="Email"></Column>
                <Column field="role" header="Роль">
                    <template #body="{data}">
                        <div v-for="item in data.roles">
                            {{item.name}}
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header="Дата реєстрації">
                    <template #body="{data}">
                        {{ $filters.formatDateTime(data.created_at) }}
                    </template>
                </Column>
                <Column>
                    <template #body="{data}" v-if="can('delete-users')">
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
               :roles="roles"
               :errors="state.errors"
               :show="state.isShowModal"
               :isLoadingModal="state.isLoadingModal"
               @submit="onSubmit"
               @close="toggleModal(false)"
        />
    </AppLayout>
</template>
