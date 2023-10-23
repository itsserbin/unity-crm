<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import Heading from "@/Components/Heading.vue";
import Datepicker from '@/Components/Datepicker.vue'

import OrderStatisticsRepository from "@/Repositories/Tenants/Statistics/OrderStatisticsRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive} from 'vue';
import {endOfMonth, startOfMonth} from "date-fns";

const props = defineProps([
    'statuses',
    'statusGroups'
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

const dataSet = ref(1);

const lazyParams = ref({
    set: dataSet.value,
    date: [],
    page: 0,
    first: 0,
    rows: 15,
    sortField: null,
    sortOrder: null,
});

const item = ref();

onMounted(async () => {
    setDefaultDateRange();
    await fetch();
});

const setDefaultDateRange = () => {
    lazyParams.value.date[0] = startOfMonth(new Date());
    lazyParams.value.date[1] = endOfMonth(new Date());
}

const queryParams = () => {
    let data = {};
    if (lazyParams.value.date.length === 2) {
        data.date_start = lazyParams.value.date[0].toLocaleDateString();
        data.date_end = lazyParams.value.date[1].toLocaleDateString();
    }
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
        ...value.reduce((acc, item) => ({...acc, [item.group_slug]: item.count}), {}),
    }));
}

const fetch = async () => {
    switchLoader();
    try {
        const data = await OrderStatisticsRepository.fetch(queryParams());
        state.data = data.result;
        state.data.data = mapData(data.result.data);
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

const refreshData = async () => {
    switchLoaderRefreshButton();
    lazyParams.value = ({
        date: [],
        page: 0,
        first: 0,
        rows: 15,
        sortField: null,
        sortOrder: null,
    });
    setDefaultDateRange();
    await fetch();
    switchLoaderRefreshButton();
}

const dataSetItems = [
    {
        label: 'Всі статуси',
        value: 0
    },
    {
        label: 'По групам',
        value: 1
    }
];

const onChangeDataSet = (val) => {
    dataSet.value = val;
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

            <Toolbar class="mb-4">
                <template #start>
                    <Datepicker class="w-full" v-model="lazyParams.date" @update:modelValue="fetch"/>
                </template>
                <template #end>
                    <Button link type="button"
                            v-for="item in dataSetItems"
                            @click="onChangeDataSet(item.value)"
                            :disabled="dataSet === item.value"
                    >{{ item.label }}
                    </Button>
                </template>
            </Toolbar>
            <DataTable resizableColumns
                       columnResizeMode="expand"
                       selectionMode="single"
                       dataKey="date"
                       :loading="state.isLoading"
                       lazy
                       v-if="dataSet === 1"
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

                <Column v-for="item in statusGroups"
                        :field="item.slug"
                        :key="'statusGroups_' + item.slug"
                >
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

            <DataTable resizableColumns
                       columnResizeMode="expand"
                       selectionMode="single"
                       dataKey="date"
                       :loading="state.isLoading"
                       lazy
                       v-if="dataSet === 0"
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

                <Column v-if="dataSet === 0"
                        v-for="item in statuses"
                        :field="item.group_slug"
                        :key="'statuses_' + item.id"
                >
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
