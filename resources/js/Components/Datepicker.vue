<script setup>
import isDark from "@/Helpers/isDark.js";
import Datepicker from '@vuepic/vue-datepicker';
import {endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths} from 'date-fns';
import {ref, computed} from "vue";

const props = defineProps(['modelValue']);
const emits = defineEmits(['update:modelValue']);

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emits("update:modelValue", value);
    },
});

const presetDate = ref([
    {
        label: 'Сьогодні',
        value: [
            new Date(),
            new Date()
        ]
    },
    {
        label: 'Вчора',
        value: [
            new Date() - 86400000,
            new Date() - 86400000
        ]
    },
    {
        label: 'Поточний місяць',
        value: [
            startOfMonth(new Date()),
            endOfMonth(new Date())
        ],
    },
    {
        label: 'Попередній місяць',
        value: [
            startOfMonth(subMonths(new Date(), 1)),
            endOfMonth(subMonths(new Date(), 1))
        ],
    },
    {
        label: 'Поточний рік',
        value: [
            startOfYear(new Date()),
            endOfYear(new Date())
        ]
    },
]);
</script>

<template>
    <Datepicker v-model="value"
                class="w-100"
                locale="uk"
                placeholder="Оберіть дату"
                autoApply
                :monthChangeOnScroll="false"
                :enableTimePicker="false"
                range
                :presetDates="presetDate"
                :dark="isDark"
    >
    </Datepicker>
</template>
