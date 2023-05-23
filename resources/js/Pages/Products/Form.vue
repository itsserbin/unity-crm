<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import FileUpload from 'primevue/fileupload';
import MultiSelect from 'primevue/multiselect';

import {onMounted, reactive} from "vue";
import CategoriesRepository from "@/Repositories/CategoriesRepository.js";
import {toast} from "vue3-toastify";

defineProps(['item']);

const state = reactive({
    categories: [],
    isLoadingCategories: false
});

onMounted(async () => await getCategories());

const getCategories = async () => {
    switchIsLoadingCategories();
    try {
        const data = await CategoriesRepository.list();
        state.categories = data.result.map((item) => {
            return {
                label: item.title,
                code: item.id
            }
        })
    } catch (e) {
        console.error(e);
        toast.error("Failed to get categories");
    }
    switchIsLoadingCategories();
}

const switchIsLoadingCategories = () => state.isLoadingCategories = !state.isLoadingCategories;

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

const onUpload = (e) => {
    console.log(e);
}
</script>

<template>
    <div class="grid gap-4">
        <div class="grid grid cols-1 md:grid-cols-2 gap-4">
            <div class="block">
                <InputLabel :required="true">Наявність товару</InputLabel>
                <Dropdown v-model="item.availability"
                          :options="availabilityOptions"
                          optionLabel="label"
                          dataKey="value"
                          placeholder="Оберіть статус"
                          class="w-full"
                />
            </div>
            <div class="block">
                <InputLabel :required="true">Категорії</InputLabel>
                <MultiSelect v-model="item.categories"
                             :options="state.categories"
                             optionLabel="label"
                             placeholder="Оберіть одну або більше"
                             class="w-full"
                             filter
                             :loading="state.isLoadingCategories"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md grid-cols-12 gap-4">
            <div class="md:col-span-8">
                <InputLabel :required="true">Назва товару</InputLabel>
                <InputText type="text" v-model="item.title" class="w-full"
                           placeholder="Вкажіть назву товара"/>
            </div>
            <div class="md:col-span-4">
                <InputLabel>Зображення товару</InputLabel>
                <FileUpload mode="basic"
                            accept="image/*"
                            :maxFileSize="1000000"
                            @upload="onUpload($event)"
                />
            </div>
        </div>

        <div class="block">
            <InputLabel>Опис</InputLabel>
            <Textarea v-model="item.description" rows="5" class="w-full"
                      placeholder="Вкажіть опис товара"/>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="block">
                <div class="block">
                    <InputLabel>Ціна купівлі</InputLabel>
                    <InputText type="number" v-model="item.trade_price"
                               placeholder="грн" class="w-full"/>
                </div>
            </div>
            <div class="block">
                <InputLabel>Артикул</InputLabel>
                <InputText type="text" v-model="item.sku" class="w-full"
                           placeholder="Вкажіть артикул"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="block">
                <InputLabel :required="true">Ціна продажу</InputLabel>
                <InputText type="number" v-model="item.price"
                           placeholder="грн" class="w-full"/>
            </div>
            <div class="block">
                <InputLabel>Ціна продажу зі знижкою</InputLabel>
                <InputText type="number" v-model="item.discount_price"
                           placeholder="грн" class="w-full"/>
            </div>
        </div>
    </div>
</template>
