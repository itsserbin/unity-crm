<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import InputErrors from "@/Components/InputErrors.vue";

const props = defineProps([
    'item',
    'sourceTypes',
    'errors'
]);

const options = props.sourceTypes.map(item => {
    return {
        label: item.title,
        code: item.value
    }
});
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text" v-model="item.title" class="w-full"
                       placeholder="Вкажіть назву джерела"/>
            <InputErrors :errors="errors.title" v-if="errors.title"/>
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
            <InputErrors :errors="errors.source" v-if="errors.source"/>
        </div>
    </div>
</template>
