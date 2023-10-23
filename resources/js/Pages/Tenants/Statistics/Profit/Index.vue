<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import Heading from "@/Components/Heading.vue";
import Datepicker from '@/Components/Datepicker.vue'

import ProfitStatisticsRepository from "@/Repositories/Tenants/Statistics/ProfitStatisticsRepository.js";
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

const lazyParams = ref({
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

// const mapData = (params) => {
//     return Object.entries(params).map(([date, value]) => ({
//         date,
//         ...value.reduce((acc, item) => ({...acc, [item.group_slug]: item.count}), {}),
//     }));
// }

const fetch = async () => {
    switchLoader();
    try {
        const data = await ProfitStatisticsRepository.fetch(queryParams());
        state.data = data.result;
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
                        <Heading>Статистика прибутку</Heading>
                    </div>
                </template>
            </Toolbar>

            <Toolbar class="mb-4">
                <template #start>
                    <Datepicker class="w-full" v-model="lazyParams.date" @update:modelValue="fetch"/>
                </template>
            </Toolbar>

            <DataTable resizableColumns
                       columnResizeMode="expand"
                       selectionMode="single"
                       dataKey="date"
                       :loading="state.isLoading"
                       lazy
                       paginator
                       :value="state.data.data"
                       :rows="state.data.per_page"
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
                            {{ $filters.formatDate(data.date) }}
                        </div>
                    </template>
                </Column>

                <Column field="orders_clear_price">
                    <template #header>
                        <div class="w-full text-center">
                            Чистий прибуток замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.orders_clear_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="orders_cost">
                    <template #header>
                        <div class="w-full text-center">
                            Витрати на замовлення
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.orders_cost) }}
                        </div>
                    </template>
                </Column>

                <Column field="orders_sale_price">
                    <template #header>
                        <div class="w-full text-center">
                            Сумма закупки по замовленням
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.orders_sale_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="orders_trade_price">
                    <template #header>
                        <div class="w-full text-center">
                            Загальний чек замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.orders_trade_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="orders_trade_price">
                    <template #header>
                        <div class="w-full text-center">
                            Загальний чек замовлень
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.orders_trade_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="total_additional_sales">
                    <template #header>
                        <div class="w-full text-center">
                            Кількість дод.продажів
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.total_additional_sales }}
                        </div>
                    </template>
                </Column>

                <Column field="total_additional_sales_items_sale_price">
                    <template #header>
                        <div class="w-full text-center">
                            Сума закупки дод.продажів
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.total_additional_sales_items_sale_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="total_additional_sales_items_trade_price_price">
                    <template #header>
                        <div class="w-full text-center">
                            Сума дод.продажів
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.total_additional_sales_items_trade_price_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="total_additional_sales_items_trade_clear_price">
                    <template #header>
                        <div class="w-full text-center">
                            Чистий прибуток дод.продажів
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.total_additional_sales_items_trade_clear_price) }}
                        </div>
                    </template>
                </Column>

                <Column field="total_sales_of_air">
                    <template #header>
                        <div class="w-full text-center">
                            Кількість продажів повітря
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.total_sales_of_air }}
                        </div>
                    </template>
                </Column>

                <Column field="total_sales_of_air_sum">
                    <template #header>
                        <div class="w-full text-center">
                            Сума продажів повітря
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.total_sales_of_air_sum) }}
                        </div>
                    </template>
                </Column>

                <Column field="total_prepayment">
                    <template #header>
                        <div class="w-full text-center">
                            Кількість передоплат
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ data.total_prepayment }}
                        </div>
                    </template>
                </Column>

                <Column field="total_prepayment_sum">
                    <template #header>
                        <div class="w-full text-center">
                            Сума передоплат
                        </div>
                    </template>
                    <template #body="{data}">
                        <div class="text-center">
                            {{ $filters.formatMoney(data.total_prepayment_sum) }}
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
