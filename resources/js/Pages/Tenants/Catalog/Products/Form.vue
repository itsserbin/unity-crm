<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import MultiSelect from 'primevue/multiselect';
import Card from 'primevue/card';

import {defineAsyncComponent, onMounted, reactive} from "vue";
import CategoriesRepository from "@/Repositories/Tenants/Catalog/CategoriesRepository.js";
import {toast} from "vue3-toastify";
import InputErrors from "@/Components/InputErrors.vue";

const ImagesModal = defineAsyncComponent(() => import('./ImagesModal.vue'))

const props = defineProps([
    'item',
    'categories',
    'errors'
]);
console.log(props.errors)

const state = reactive({
    categories: [],
    isShowImagesModal: false,
    isLoadingCategories: false
});

onMounted(async () => {
    if (props.categories) {
        state.categories = mapData(props.categories);
    } else {
        await getCategories();
    }
});

const getCategories = async () => {
    switchIsLoadingCategories();
    try {
        const data = await CategoriesRepository.list();
        state.categories = mapData(data.result);
    } catch (e) {
        console.error(e);
        toast.error("Failed to get categories");
    }
    switchIsLoadingCategories();
}

const mapData = (data) => {
    return data.map((item) => {
        return {
            label: item.title,
            code: item.id
        }
    });
}

const switchIsLoadingCategories = () => state.isLoadingCategories = !state.isLoadingCategories;
const toggleImagesModal = () => state.isShowImagesModal = !state.isShowImagesModal;

const availabilityOptions = [
    {
        label: 'Нема в наявності',
        value: -1
    },
    {
        label: 'Закінчується',
        value: 0
    },
    {
        label: 'Є в наявності',
        value: 1
    }
];

const setPreview = (e) => {
    props.item.preview_id = e.id;
    props.item.preview = e;
    toggleImagesModal();
}

const onDestroyPreview = () => {
    props.item.preview_id = null;
    props.item.preview = null;
}
</script>

<template>
    <div class="grid gap-4">
        <Card>
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-9">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="block">
                                <InputLabel :required="true">Наявність товару</InputLabel>
                                <Dropdown v-model="item.availability"
                                          :options="availabilityOptions"
                                          optionLabel="label"
                                          dataKey="value"
                                          placeholder="Оберіть статус"
                                          class="w-full"
                                />
                                <InputErrors :errors="errors.availability" v-if="errors.availability"/>
                            </div>
                            <div class="block">
                                <InputLabel>Категорії</InputLabel>
                                <MultiSelect v-model="item.categories"
                                             :options="state.categories"
                                             optionLabel="label"
                                             placeholder="Оберіть одну або більше"
                                             class="w-full"
                                             filter
                                             :loading="state.isLoadingCategories"
                                />
                            </div>
                            <div class="block">
                                <InputLabel :required="true">Назва товару</InputLabel>
                                <InputText type="text" v-model="item.title" class="w-full"
                                           placeholder="Вкажіть назву товара"/>
                                <InputErrors :errors="errors.title" v-if="errors.title"/>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <div v-if="!item.preview" class="w-full h-full flex items-center justify-center">
                            <Button label="Обрати зображення товару" @click="toggleImagesModal"/>
                        </div>
                        <div class="relative" v-if="item.preview">
                            <InputLabel>Зображення товару</InputLabel>
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
            </template>
        </Card>

        <Card>
            <template #content>
                <div class="block">
                    <InputLabel>Опис</InputLabel>
                    <Textarea v-model="item.description" rows="5" class="w-full"
                              placeholder="Вкажіть опис товара"/>
                </div>
            </template>
        </Card>

        <Card>
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="block">
                        <InputLabel>Ціна купівлі</InputLabel>
                        <InputText type="number" v-model="item.trade_price"
                                   placeholder="грн" class="w-full"/>
                    </div>
                    <div class="block">
                        <InputLabel>Артикул</InputLabel>
                        <InputText type="text" v-model="item.sku" class="w-full"
                                   placeholder="Вкажіть артикул"/>
                    </div>
                    <div class="block">
                        <InputLabel :required="true">Ціна продажу</InputLabel>
                        <InputText type="number" v-model="item.price"
                                   placeholder="грн" class="w-full"/>
                        <InputErrors :errors="errors.price" v-if="errors.price"/>
                    </div>
                    <div class="block">
                        <InputLabel>Ціна продажу зі знижкою</InputLabel>
                        <InputText type="number" v-model="item.discount_price"
                                   placeholder="грн" class="w-full"/>
                    </div>
                </div>
            </template>
        </Card>
    </div>
    <ImagesModal :show="state.isShowImagesModal" @close="toggleImagesModal"
                 @submit="setPreview"/>
</template>
