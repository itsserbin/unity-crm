<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import Heading from "@/Components/Heading.vue";

import OrderStatisticsRepository from "@/Repositories/Tenants/Statistics/OrderStatisticsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive} from 'vue';

const props = defineProps([
    'orderStatistics',
    'statuses'
]);

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
    state.data = props.orderStatistics;
    state.data.data = mapData(props.orderStatistics.data);
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

const mapData = (params) => {
    return Object.entries(params).map(([date, value]) => ({
        date,
        ...value.reduce((acc, item) => ({ ...acc, [item.group_slug]: item.count }), {}),
    }));
}

const fetch = async () => {
    switchLoader();
    try {
        const data = await OrderStatisticsRepository.fetch(queryParams());
        state.data.data = mapData(data.result.data);
        console.log(state.data);
    } catch (e) {
        console.error(e);
        toast.error("Failed to fetch data");
    }
    switchLoader();
}

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
                        <Heading>Статистика замовлень</Heading>
                    </div>
                </template>
            </Toolbar>
            <DataTable resizableColumns
                       columnResizeMode="expand"
                       selectionMode="single"
                       @rowSelect="onRowSelect"
                       dataKey="date"
                       :loading="state.isLoading"
                       lazy
                       paginator
                       :value="state.data.data"
                       :rows="state.data.perPage"
                       :totalRecords="state.data.total"
                       @sort="onSort($event)"
                       :rowsPerPageOptions="[15, 50, 100, 500]"
                       paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            >
                <Column field="date">
                    <template #header>
                        <div class="w-full text-center">Дата</div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.date }}
                        </div>
                    </template>
                </Column>

                <Column v-for="item in statuses" :field="item.slug">
                    <template #header>
                        <div class="w-full text-center">{{ item.title }}</div>
                    </template>
                    <template #body="{data,field}">
                        <div class="text-center">
                            {{ data[field] }}
                        </div>
                    </template>
                </Column>

                <template #footer>
                    <div class="text-end">
                        Показано з {{ state.data.from }} по {{ state.data.to }} із {{ state.data.total }} записів
                    </div>
                </template>
            </DataTable>
        </div>
    </AppLayout>
</template>
