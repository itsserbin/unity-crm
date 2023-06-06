<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import {onMounted, ref} from "vue";
import BankAccountsRepository from "@/Repositories/Tenants/Statistics/BankAccountsRepository.js";
import MonobankRepository from "@/Repositories/Tenants/MonobankRepository.js";
import {DatePicker} from 'v-calendar';
import BankAccountMovementsRepository from "@/Repositories/Tenants/Statistics/BankAccountMovementsRepository.js";
import Loader from "@/Components/Loader.vue";

const props = defineProps(['item', 'show', 'isLoadingModal']);
const emits = defineEmits(['close', 'submit']);

const banks = ref([]);
const isLoading = ref(false);

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

const isDark = () => window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

const onSubmit = async () => {
    isLoading.value = true;
    console.log(props.item)
    try {
        const data = await MonobankRepository.extract(props.item);
        await BankAccountMovementsRepository.massCreate(data.result.map((item) => {
            return {
                account_id: props.item.account,
                movement_id: item.id,
                sum: (item.amount / 100).toFixed(2),
                balance: (item.balance / 100).toFixed(2),
                comment: item.description,
                date: formatDate(item.time)
            }
        }));
        emits('submit');
        emits('close');
    } catch (e) {
        console.error(e);
    }
    isLoading.value = false;
}

const formatDate = (timestamp) => {
    const date = new Date(timestamp * 1000);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
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
        <template #footer>
            <Button label="Скасувати"
                    icon="pi pi-times"
                    @click="emits('close')"
                    text
            />
            <Button label="Зберегти"
                    icon="pi pi-check"
                    @click="onSubmit"
                    autofocus
            />
        </template>
    </Modal>
</template>
