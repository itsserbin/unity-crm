<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import {onMounted, reactive} from "vue";
import SourcesRepository from "@/Repositories/SourcesRepository.js";
import {toast} from "vue3-toastify";

const props = defineProps(['item']);

const state = reactive({
    sources: [],
    isLoadingCategories: false
});

onMounted(async () => await getSources());

const getSources = async () => {
    try {
        const data = await SourcesRepository.list();
        state.sources = data.result.map((item) => {
            return {
                label: item.title,
                value: item.id
            }
        });
    } catch (e) {
        console.error(e);
        toast.error("Failed to get sources");
    }
}
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="block">
            <InputLabel :required="true">Джерело</InputLabel>
            <Dropdown v-model="item.source"
                      :options="state.sources"
                      optionLabel="label"
                      dataKey="value"
                      placeholder="Оберіть джерело"
                      class="w-full"
            />
        </div>
    </div>
</template>
