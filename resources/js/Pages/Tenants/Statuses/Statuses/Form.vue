<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import {reactive, onMounted} from "vue";
import {toast} from "vue3-toastify";
import StatusGroupsRepository from "@/Repositories/Tenants/StatusGroupsRepository.js";

const props = defineProps(['item']);

const state = reactive({
    groups: []
});

onMounted(async () => await getGroups());

const getGroups = async () => {
    try {
        const data = await StatusGroupsRepository.list();
        state.groups = data.result.map((item) => {
            return {
                label: item.title,
                value: item.slug,
                hex: item.hex
            }
        });
    } catch (e) {
        console.error(e);
        toast.error('Failed to get groups');
    }
}
</script>

<template>
    <div class="grid gap-4">

        <div class="block">
            <InputLabel :required="true">Група</InputLabel>
            <Dropdown v-model="item.group_slug"
                      :options="state.groups"
                      optionLabel="label"
                      dataKey="value"
                      class="w-full"
                      placeholder="Оберіть групу"
            >
                <template #option="slotProps">
                    <div :style="`background: ${slotProps.option.hex};`" class="p-3">
                        {{ slotProps.option.label }}
                    </div>
                </template>
            </Dropdown>
        </div>

        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text" v-model="item.title" class="w-full"
                       placeholder="Вкажіть назву"/>
        </div>
    </div>
</template>
