<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import {onMounted, ref} from "vue";

const props = defineProps(['item']);

const globalServices = [
    {'label': 'НоваПошта', 'code': 'novaposhta'},
    {'label': 'УкрПошта', 'code': 'ukrposhta'}
];
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text" v-model="item.title" class="w-full"
                       placeholder="Вкажіть назву"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Тип джерела</InputLabel>
            <Dropdown v-model="item.type"
                      :options="globalServices"
                      optionLabel="label"
                      dataKey="code"
                      class="w-full"
                      placeholder="Оберіть тип"
            />
        </div>

        <div class="block">
            <InputLabel>API ключ</InputLabel>
            <InputText type="text" v-model="item.api_key" class="w-full"
                       placeholder="Вкажіть API ключ"/>
        </div>
    </div>
</template>
