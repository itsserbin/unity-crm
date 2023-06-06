<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputText from 'primevue/inputtext';

import ProductsRepository from "@/Repositories/Tenants/Catalog/ProductsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import Heading from "@/Components/Heading.vue";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps(['products', 'categories']);

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

onMounted(async () => state.data = props.products);

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
    data.page = lazyParams.value.page += 1;
    return data;
}
const fetch = async () => {
    switchLoader();
    try {
        const data = await ProductsRepository.fetch(queryParams());
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

const availabilityStatus = (val) => {
    switch (val) {
        case -1:
            return 'Нема в наявності';
        case 0 :
            return 'Закінчується';
        case 1:
            return 'Є в наявності';
    }
}

const availabilityStatusLabel = (val) => {
    switch (val) {
        case -1:
            return 'danger';
        case 0 :
            return 'warning';
        case 1:
            return 'success';
    }
}

const filterItems = [
    {
        label: 'Всі',
        value: null
    },
    {
        label: 'В наявності',
        value: 1
    },
    {
        label: 'Нема в наявності',
        value: -1
    },
    {
        label: 'Закінчуються',
        value: 0
    }
];
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
        sku: null,
        categories: []
    };
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        if (item.value.availability) {
            item.value.availability = item.value.availability.value;
        }
        if (item.value.categories.length) {
            item.value.categories = item.value.categories.map((item) => {
                return item.code;
            });
        }

        item.value.id
            ? await ProductsRepository.update(item.value)
            : await ProductsRepository.create(item.value);

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
        const data = await ProductsRepository.edit(id);
        item.value = data.result;
        item.value.categories = data.result.categories.map((item) => {
            return {
                label: item.title,
                code: item.id
            }
        });
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
                await ProductsRepository.destroy(id);
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
            const data = await ProductsRepository.search(state.search);
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
                        <Heading>Товари</Heading>
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
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <form @submit.prevent="onSearch" class="flex gap-2 items-center">
                            <InputText v-model="state.search" placeholder="Пошук..."/>
                            <Button severity="secondary"
                                    text
                                    rounded
                                    icon="pi pi-search"
                                    type="submit"
                            />
                        </form>
                        <div class="flex justify-end">
                            <Button link type="button" v-for="item in filterItems"
                                    @click="onFilter(item.value)"
                            >{{ item.label }}
                            </Button>
                        </div>
                    </div>
                </template>

                <Column field="id" header="ID" sortable="" header-class="flex justify-center">
                    <template #body="{data}">
                        <div class="text-center">{{ data.id }}</div>
                    </template>
                </Column>
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
                <Column field="availability" header="Наявність" sortable="" header-class="flex justify-center">
                    <template #body="{data}">
                        <div class="text-center">
                            <Tag :value="availabilityStatus(data.availability)"
                                 :severity="availabilityStatusLabel(data.availability)"/>
                        </div>
                    </template>
                </Column>
                <Column field="title" header="Назва"></Column>
                <Column field="trade_price" header="Ціна купівлі" sortable="" style="width:8%">
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.trade_price) }}</div>
                    </template>
                </Column>
                <Column field="price" header="Ціна продажу" sortable="" style="width:8%">
                    <template #body="{data}">
                        <div class="text-center">{{ $filters.formatMoney(data.price) }}</div>
                    </template>
                </Column>
                <Column field="discount_price" header="Ціна зі знижкою" sortable="" style="width:10%">
                    <template #body="{data}">
                        {{ data.discount_price ? $filters.formatMoney(data.discount_price) : null }}
                    </template>
                </Column>
                <Column field="categories" header="Категорії">
                    <template #body="{data}">
                        <div class="flex gap-2" v-if="data.categories.length">
                            <Tag v-for="item in data.categories" :value="item.title"/>
                        </div>
                    </template>
                </Column>
                <Column field="sku" header="Артикул"></Column>
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
               @close="toggleModal(false)"
               :isLoadingModal="state.isLoadingModal"
               :item="item"
               @submit="onSubmit"
               :categories="categories"
        />
    </AppLayout>
</template>
