<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import FileUpload from 'primevue/fileupload';
import {defineAsyncComponent, reactive} from "vue";
import Button from "primevue/button";

const ImagesModal = defineAsyncComponent(() => import('./ImagesModal.vue'))

const props = defineProps(['item']);

const state = reactive({
    isShowImagesModal: false
});

const toggleImagesModal = () => state.isShowImagesModal = !state.isShowImagesModal;

const setPreview = (e) => {
    props.item.preview_id = e.id;
    props.item.preview = e;
    toggleImagesModal();
}

const onDestroyPreview = () => {
    props.item.preview_id = null;
    props.item.preview = null;
}

const onUpload = (e) => {
    console.log(e);
}
</script>

<template>
    <div class="grid gap-4">
        <div class="grid grid-cols-1 md:grid-cols-12  gap-4">
            <div class="md:col-span-9 grid gap-4">
                <div class="block">
                    <InputLabel :required="true">Назва</InputLabel>
                    <InputText type="text" v-model="item.title" class="w-full"
                               placeholder="Вкажіть назву категорії"/>
                </div>

                <div class="block">
                    <InputLabel>Опис</InputLabel>
                    <Textarea v-model="item.description" rows="6" class="w-full"
                              placeholder="Вкажіть опис категорії"/>
                </div>
            </div>
            <div class="md:col-span-3">
                <div v-if="!item.preview" class="w-full h-full flex items-center justify-center">
                    <Button label="Обрати зображення категорії" @click="toggleImagesModal"/>
                </div>
                <div class="relative" v-if="item.preview">
                    <InputLabel>Зображення категорії</InputLabel>
                    <Button icon="pi pi-times" link class="absolute right-0" @click="onDestroyPreview"/>
                    <picture>
                        <source :srcset="route('images',item.preview.data.webp)"
                                type="image/webp">

                        <img :src="route('images',item.preview.data.jpeg)"
                             :alt="item.preview.data.alt"
                             class="object-cover"
                             loading="lazy"
                        >
                    </picture>
                </div>
            </div>
        </div>
    </div>
    <ImagesModal :show="state.isShowImagesModal" @close="toggleImagesModal"
                 @submit="setPreview"/>
</template>
