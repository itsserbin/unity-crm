<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import {computed, onMounted, reactive, ref} from "vue";
import Button from 'primevue/button';
import MultiSelect from 'primevue/multiselect';
import Chart from './Chart.vue';
import CashFlowRepository from "@/Repositories/Tenants/Finance/CashFlowRepository.js";

const props = defineProps(['cashFlow']);
const state = reactive({
    data: [],
    isLoading: false,
});

const lazyParams = ref({
    date: null,
    page: 0,
    first: 0,
    rows: 15,
    sortField: null,
    sortOrder: null,
});

const columns = reactive([
    {name: 'Місяць', code: 'month'},
    {name: 'Залишок на початок місяця', code: 'start_month_balance'},
    {name: 'Надходження', code: 'approved_income'},
    {name: 'Списання', code: 'approved_consumption'},
    {name: 'Залишок на кінець місяця', code: 'end_month_balance'},
    {name: 'Чистий грошовий потік', code: 'difference'},
]);

const selectedColumns = ref(columns);
const expandedRows = ref([]);
const expandRowData = reactive({});

const getParams = computed(() => {
    let sort = {};

    if (lazyParams.value.sortField) {
        sort.sort = lazyParams.value.sortField;

        if (lazyParams.value.sortOrder === 1) {
            sort.param = 'asc';
        } else if (lazyParams.value.sortOrder === -1) {
            sort.param = 'desc';
        }
    }

    return {
        perPage: lazyParams.value.rows || 15,
        sort: sort.sort && sort.param ? sort.sort : null,
        param: sort.sort && sort.param ? sort.param : null,
        page: (lazyParams.value.page || 0) + 1,
    };
});

onMounted(async () => {
    if (props.cashFlow) {
        state.data = props.cashFlow;
    } else {
        await fetch();
    }
})

const toggleIsLoading = (val) => val ? state.isLoading = val : state.isLoading = !state.isLoading;

const fetch = async () => {
    toggleIsLoading();
    try {
        const data = await CashFlowRepository.fetch(getParams.value);
        state.data = data.result;
    } catch (e) {
        console.error(e);
    }
    toggleIsLoading();
}

const isSelectedColumn = (val) => {
    return selectedColumns.value.some(item => item.code === val);
}

const onPage = async (e) => {
    lazyParams.value = e;
    await fetch();
}

const onSort = async (e) => {
    lazyParams.value = e;
    await fetch();
}

const onRowExpand = async (event) => {
    state.isLoading = true;
    await axios.get(route('api.statistics.bank-card-movements.cost-and-profit', event.data.month))
        .then(({data}) => {
            expandRowData[event.data.id] = data.result;
            state.isLoading = false;
        });
};
</script>

<template>
    <AppLayout>
        <div class="grid grid-cols-1 gap-4">
            <Chart v-if="state.data.data" :data="state.data.data"/>

            <DataTable
                resizableColumns
                columnResizeMode="expand"
                v-model:expandedRows="expandedRows"
                @rowExpand="onRowExpand"
                selectionMode="single"
                ref="dt"
                dataKey="month"
                :loading="state.isLoading"
                :value="state.data.data"
                class="p-datatable-sm cash-flow-table"
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
                    <MultiSelect v-model="selectedColumns"
                                 :options="columns"
                                 optionLabel="name"
                                 placeholder="Оберіть стовпці"
                                 class="w-full md:w-20rem"
                    />
                </template>

                <Column expander/>

                <Column v-if="isSelectedColumn('month')"
                        sortable=""
                        field="month"
                        header="Місяць"
                >
                    <template #body="{data}">
                        <div class="text-center whitespace-nowrap">
                            {{ $filters.formatDate(data.month) }}
                        </div>
                    </template>
                </Column>

                <Column v-if="isSelectedColumn('start_month_balance')"
                        sortable=""
                        field="start_month_balance"
                >
                    <template #header>
                        Залишок<br>на початок місяця
                    </template>
                    <template #body="{data}">
                        <div class="text-center whitespace-nowrap">
                            {{ $filters.formatMoney(data.start_month_balance) }}
                        </div>
                    </template>
                </Column>

                <Column v-if="isSelectedColumn('approved_income')"
                        sortable=""
                        field="approved_income"
                        header="Надходження"
                >
                    <template #body="{data}">
                        <div class="text-center whitespace-nowrap">
                            {{ $filters.formatMoney(data.approved_income) }}
                        </div>
                    </template>
                </Column>

                <Column v-if="isSelectedColumn('approved_consumption')"
                        sortable=""
                        field="approved_consumption"
                        header="Списання"
                >
                    <template #body="{data}">
                        <div class="text-center whitespace-nowrap">
                            {{ $filters.formatMoney(data.approved_consumption) }}
                        </div>
                    </template>
                </Column>

                <Column v-if="isSelectedColumn('end_month_balance')"
                        sortable=""
                        field="end_month_balance"
                >
                    <template #header>
                        Залишок<br>на кінець місяця
                    </template>
                    <template #body="{data}">
                        <div class="text-center whitespace-nowrap">
                            {{ $filters.formatMoney(data.end_month_balance) }}
                        </div>
                    </template>
                </Column>

                <Column v-if="isSelectedColumn('difference')"
                        sortable=""
                        field="difference"
                >
                    <template #header>
                        Чистий<br>грошовий<br>потік
                    </template>
                    <template #body="{data}">
                        <div class="text-center whitespace-nowrap">
                            {{ $filters.formatMoney(data.difference) }}
                        </div>
                    </template>
                </Column>

                <template #footer>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            Показано з {{ state.data.from }} по {{ state.data.to }} із {{ state.data.total }} записів
                        </div>
                        <div class="flex justify-end">
                            <Button icon="pi pi-file-export"
                                    iconPos="right"
                                    type="button"
                                    label="Експортувати"
                                    @click=""
                            />
                        </div>
                    </div>
                </template>

                <template #expansion="slotProps">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <DataTable v-if="expandRowData[slotProps.data.id]"
                                   :value="expandRowData[slotProps.data.id].costs"
                                   class="p-datatable cash-flow-profits-table"
                        >
                            <Column header="Витрати">
                                <template #body="{data}">
                                    {{ data.key }}
                                </template>
                            </Column>

                            <Column>
                                <template #body="{data}">
                                    {{ $filters.formatMoney(-data.value) }}
                                </template>
                            </Column>
                        </DataTable>

                        <DataTable v-if="expandRowData[slotProps.data.id]"
                                   :value="expandRowData[slotProps.data.id].profits"
                                   class="p-datatable cash-flow-profits-table"
                        >
                            <Column header="Надходження">
                                <template #body="{data}">
                                    {{ data.key }}
                                </template>
                            </Column>

                            <Column>
                                <template #body="{data}">
                                    {{ $filters.formatMoney(data.value) }}
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </template>
            </DataTable>
        </div>
    </AppLayout>
</template>

<style>
.cash-flow-table.p-datatable .p-column-header-content {
    justify-content: center;
    text-align: center;
}

.cash-flow-profits-table.p-datatable .p-column-header-content {
    justify-content: start;
}
</style>
