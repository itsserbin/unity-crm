<script setup>
import Loader from "@/Components/Loader.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import {DatePicker} from "v-calendar";
import {onMounted, ref} from "vue";
import BankAccountsRepository from "@/Repositories/Tenants/Finance/BankAccountsRepository.js";

const isDark = () => window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
defineProps(['isLoading', 'item']);

const banks = ref([]);

onMounted(async () => {
    await getBanks();
});

const getBanks = async () => {
    try {
        const data = await BankAccountsRepository.list();
        banks.value = mapData(data.result);
    } catch (e) {
        console.error(e);
    }
}

const mapData = (val) => {
    return val.map((item) => {
        return {
            label: item.name,
            code: item.id
        }
    });
}

const rules = ref([
    {
        hours: 0,
        minutes: 0,
        seconds: 0,
        milliseconds: 0,
    },
    {
        hours: 23,
        minutes: 59,
        seconds: 59,
        milliseconds: 999,
    },
]);

</script>

<template>
    <div class="min-h-[270px] flex flex-col items-center justify-center" v-if="isLoading">
        <div class="w-20 h-20">
            <Loader/>
        </div>
        <div class="text-xl font-bold">Завантажую дані...</div>
    </div>
    <div v-if="!isLoading" class="min-h-[280px] p-4 flex flex-col gap-4">
        <div class="block">
            <InputLabel :required="true">Дата (не більше 30 днів)</InputLabel>
            <DatePicker v-model.range="item.date"
                        mode="date"
                        :is-dark="isDark"
                        :rules="rules"
            >
                <template #default="{ togglePopover, inputValue }">
                    <InputText
                        placeholder="Вкажіть дату"
                        :value="inputValue.start && inputValue.end ? inputValue.start + ' - ' + inputValue.end : null"
                        @click="togglePopover"
                        class="w-full"
                    />
                </template>
            </DatePicker>
        </div>
        <div class="block">
            <InputLabel :required="true">Рахунок</InputLabel>
            <Dropdown v-model="item.account"
                      :options="banks"
                      optionLabel="label"
                      option-value="code"
                      class="w-full"
                      placeholder="Оберіть рахунок"
            />
        </div>
        <div class="text-lg text-center">
            <p>Імпортовані дані не оновлюються під час повторного завантаження.</p>
            <p>Для повторної синхронизації поточних даних з банком, спочатку видаліть їх</p>
        </div>
    </div>
</template>
