<script setup>
import Button from "primevue/button";
import {defineAsyncComponent, reactive} from "vue";

const DownloadDataModal = defineAsyncComponent(() => import('./Modal.vue'))
const emits = defineEmits(['submit']);

const modal = reactive({
    isShow: false,
    item: null,
    isLoading: false
});
const onDownloadData = () => {
    toggleModal();
    modal.item = {
        bank: null,
        date: null,
    }
}

const toggleModal = (val) => val ? modal.isShow = val : modal.isShow = !modal.isShow;
</script>

<template>
    <Button label="Завантажити" size="small"
            icon="pi pi-download" class="mr-2"
            @click="onDownloadData"
    />

    <DownloadDataModal v-if="modal.isShow"
                       :show="modal.isShow"
                       :item="modal.item"
                       @close="toggleModal(false)"
                       @submit="emits('submit')"
                       :isLoadingModal="modal.isLoading"
    />
</template>
