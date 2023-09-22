<script setup>
import {ref, onMounted} from "vue";
import Chart from 'primevue/chart';
import Card from 'primevue/card';

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const chartData = ref();
const chartOptions = ref();

const props = defineProps(['data']);

const data = JSON.parse(JSON.stringify(props.data));

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: data.reverse().map(item => item.month),
        datasets: [
            {
                label: 'Надходження',
                backgroundColor: documentStyle.getPropertyValue('--blue-500'),
                borderColor: documentStyle.getPropertyValue('--blue-500'),
                data: data.map(item => item.approved_income)

            },
            {
                label: 'Спасання',
                backgroundColor: documentStyle.getPropertyValue('--pink-500'),
                borderColor: documentStyle.getPropertyValue('--pink-500'),
                data: data.map(item => -item.approved_consumption)
            },
            {
                label: 'Чистий грошовий потік',
                backgroundColor: documentStyle.getPropertyValue('--yellow-500'),
                borderColor: documentStyle.getPropertyValue('--yellow-500'),
                data: data.map(item => item.difference)
            },
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        interaction: {
            intersect: false,
            mode: 'index',
        },
        plugins: {
            legend: {
                labels: {
                    fontColor: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
                }
            }
        }
    };
}
</script>

<template>
   <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
       <Card>
           <template #content>
               <Chart :height="300" type="bar" :data="chartData" :options="chartOptions" />
           </template>
       </Card>

       <Card>
           <template #content>
               <Chart :height="300" type="line" :data="chartData" :options="chartOptions" />
           </template>
       </Card>
   </div>
</template>
