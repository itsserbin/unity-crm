<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import {DatePicker} from "v-calendar";
import Dropdown from "primevue/dropdown";
import InputErrors from "@/Components/InputErrors.vue";
import AccountsRepository from "@/Repositories/Tenants/Finance/AccountsRepository.js";
import {onMounted, ref} from "vue";

const isDark = () => window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

defineProps([
    'item',
    'categories',
    'errors'
]);

const accounts = ref([]);
const getAccounts = async () => {
    try {
        const data = await AccountsRepository.list();
        accounts.value = data.result.map((item) => {
            return {
                label: item.title,
                value: item.id
            }
        })
    } catch (e) {
        console.error(e);
    }
}

onMounted(async () => {
    await getAccounts();
})
</script>

<template>
    <div class="grid grid-cols-1 gap-4">
        <div class="block">
            <InputLabel :required="true">Дата</InputLabel>
            <DatePicker v-model="item.date"
                        mode="dateTime"
                        is24hr
                        hide-time-header
                        :is-dark="isDark"
            >
                <template #default="{ togglePopover, inputValue }">
                    <InputText placeholder="Вкажіть дату"
                               :value="inputValue"
                               @click="togglePopover"
                               class="w-full"
                    />
                </template>
            </DatePicker>
            <InputErrors :errors="errors.date"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Рахнок</InputLabel>
            <Dropdown v-model="item.account_id"
                      :options="accounts"
                      optionLabel="label"
                      dataKey="value"
                      class="w-full"
                      placeholder="Оберіть рахунок"
            />
            <InputErrors :errors="errors.account_id"/>
        </div>
        <div class="block">
            <InputLabel>Категорія</InputLabel>
            <Dropdown v-model="item.category_id"
                      :options="categories"
                      optionLabel="label"
                      dataKey="value"
                      class="w-full"
                      placeholder="Оберіть категорію"
            />
        </div>
        <div class="block">
            <InputLabel :required="true">Сума</InputLabel>
            <InputText placeholder="Вкажіть дату"
                       v-model="item.sum"
                       class="w-full"
                       type="number"
            />
            <InputErrors :errors="errors.sum"/>
        </div>
        <div class="block">
            <InputLabel>Коментар</InputLabel>
            <Textarea placeholder="Вкажіть коментар"
                      v-model="item.comment"
                      class="w-full"
                      rows="4"
            />
        </div>
    </div>
</template>
