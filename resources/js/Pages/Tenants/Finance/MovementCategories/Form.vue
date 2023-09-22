<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import InputErrors from "@/Components/InputErrors.vue";

const props = defineProps(['item', 'errors']);

const types = [
    {'label': 'Витрата', 'value': 0},
    {'label': 'Надходження', 'value': 1}
];
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text" v-model="item.title" class="w-full"
                       placeholder="Вкажіть назву"/>
            <InputErrors :errors="errors.title"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Тип категорії</InputLabel>
            <Dropdown v-model="item.type"
                      :options="types"
                      optionLabel="label"
                      dataKey="value"
                      class="w-full"
                      placeholder="Оберіть тип"
            />
            <InputErrors :errors="errors.type"/>
        </div>
    </div>
</template>
