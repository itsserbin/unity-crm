<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import InputErrors from "@/Components/InputErrors.vue";
import Checkbox from 'primevue/checkbox';

const props = defineProps([
    'item',
    'permissions',
    'errors'
]);

const groupedData = {};

props.permissions.forEach((item) => {
    const [_, type, ...rest] = item.name.split('-');
    const category = rest.join('-');

    if (!groupedData[type]) {
        groupedData[type] = [];
    }

    groupedData[type].push({id: item.id, name: item.name});
});
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text" v-model="item.name" class="w-full"
                       placeholder="Вкажіть назву"/>
            <InputErrors :errors="errors.name" v-if="errors.name"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Права</InputLabel>
            <div v-for="(permissions,i) in groupedData" class="mb-2">
                <InputLabel>{{ i }}</InputLabel>
                <div class="flex items-center" v-for="permission in permissions">
                    <Checkbox v-model="item.permissions" :value="permission.name"/>
                    <label class="ml-2">{{ permission.name }}</label>
                </div>
            </div>
        </div>
    </div>
</template>
