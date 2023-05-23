<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';

const props = defineProps(['item']);

const options = [
    {
        label: 'Інше',
        code: 'other'
    }
];
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text" v-model="item.title" class="w-full"
                       placeholder="Вкажіть назву джерела"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Тип джерела</InputLabel>
            <Dropdown v-model="item.source"
                      :options="options"
                      optionLabel="label"
                      dataKey="code"
                      class="w-full"
                      placeholder="Оберіть тип джерела"
            />
        </div>
    </div>
</template>
