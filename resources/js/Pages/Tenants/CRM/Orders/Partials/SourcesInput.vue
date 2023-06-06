<script setup>
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import SourcesRepository from "@/Repositories/Tenants/Options/SourcesRepository.js";
import {toast} from "vue3-toastify";
import {reactive, onMounted, defineAsyncComponent} from "vue";

const SourcesModal = defineAsyncComponent(() => import('@/Pages/Tenants/Options/Sources/Modal.vue'))

const props = defineProps(['item', 'sources', 'errors']);

const state = reactive({
    isLoadingSourcesDropdown: false,
    sources: []
});

const sourceModal = reactive({
    isShow: false,
    item: {
        title: null,
        source: null,
    }
});

onMounted(async () => {
    if (props.sources) {
        state.sources = mapData(props.sources);
    } else {
        await getSources();
    }

    if (props.item.source_id) {
        props.item.source_id = {value: props.item.source_id};
    }
});

const switchSourceModal = (val) => val ? sourceModal.isShow = val : sourceModal.isShow = !sourceModal.isShow;

const getSources = async () => {
    state.isLoadingSourcesDropdown = true;
    try {
        const data = await SourcesRepository.list();
        state.sources = mapData(data.result);
    } catch (e) {
        console.error(e);
        toast.error("Failed to get sources");
    }
    state.isLoadingSourcesDropdown = false;
}

const onCreateSource = async () => {
    try {
        if (sourceModal.item.source) {
            sourceModal.item.source = sourceModal.item.source.code;
        }
        await SourcesRepository.create(sourceModal.item);
        await getSources();
        switchSourceModal(false);
        toast.success("Запис додано");
        sourceModal.item = {
            title: null,
            source: null,
        };
    } catch (e) {
        console.error(e);
        toast.error("Failed to create source");
    }
}

const mapData = (data) => {
    return data.map((item) => {
        return {
            label: item.title,
            value: item.id
        }
    });
}
</script>

<template>
    <InputLabel :required="true">Джерело</InputLabel>
    <div class="p-inputgroup">
        <Dropdown v-model="item.source_id"
                  :options="state.sources"
                  optionLabel="label"
                  dataKey="value"
                  placeholder="Оберіть джерело"
                  :class="['w-full', { 'p-invalid': errors.source_id }]"
        />
        <Button @click="switchSourceModal" type="button" icon="pi pi-plus"></Button>
    </div>
    <small class="p-error" v-if="errors.source_id" v-for="error in errors.source_id">{{ error }}</small>

    <SourcesModal v-if="sourceModal.isShow" :show="sourceModal.isShow" :item="sourceModal.item"
                  @close="switchSourceModal" @submit="onCreateSource"/>
</template>
