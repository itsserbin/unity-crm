<script setup>
import {onMounted, reactive} from 'vue';
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import ProductsRepository from "@/Repositories/Tenants/Catalog/ProductsRepository.js";

const props = defineProps(['show', 'item', 'products']);
const emits = defineEmits(['close', 'submit']);

const state = reactive({
    products: [],
    isLoadingDropdown: false
});

onMounted(async () => {
    if (props.products) {
        state.products = props.products;
    } else {
        await getProducts();
    }

    if (props.item.id && !state.products.some(item => item.id === props.item.id)) {
        state.products.push(props.item);
    }
});

const getProducts = async (params) => {
    try {
        const data = await ProductsRepository.list(params);
        state.products = data.result;
    } catch (e) {
        console.error(e);
    }
}

let searchTimeout = null;

const onSearch = async (query) => {
    clearTimeout(searchTimeout);
    if (typeof props.item.product_id !== 'object') {
        state.isLoadingDropdown = true;
        searchTimeout = setTimeout(async () => {
            await getProducts({
                query: query.value,
            });
            state.isLoadingDropdown = false;
        }, 700);
    }
}
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel :required="true">Товар</InputLabel>
                    <Dropdown v-model="item.product_id"
                              :options="state.products"
                              optionLabel="title"
                              dataKey="id"
                              placeholder="Оберіть товар"
                              class="w-full"
                              show-clear
                              @change="onSearch"
                              editable
                              :loading="state.isLoadingDropdown"
                    >
                        <template #option="slotProps">
                            <div class="flex gap-x-2 items-center" v-if="slotProps.option.title">
                                <div class="text-base">{{ slotProps.option.title }}</div>
                            </div>
                            <div class="flex gap-x-2 items-center">
                                <div class="text-sm text-zinc-800 dark:text-zinc-200">
                                    {{ slotProps.option.sku }}
                                </div>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div class="block">
                    <InputLabel>Кількість</InputLabel>
                    <InputText v-model="item.count"
                               class="w-full"
                               placeholder="Вкажіть кількість товару"
                               type="number"
                    />
                </div>

                <div class="block">
                    <InputLabel>Знижка</InputLabel>
                    <InputText v-model="item.discount"
                               class="w-full"
                               placeholder="Вкажіть знижку"
                               type="number"
                    />
                </div>

                <div class="block">
                    <InputLabel>Коментар</InputLabel>
                    <Textarea v-model="item.comment"
                              class="w-full"
                              rows="4"
                              placeholder="Вкажіть коментар"
                    />
                </div>
            </div>
        </template>
        <template #footer>
            <Button label="Скасувати"
                    icon="pi pi-times"
                    @click="emits('close')"
                    text
            />
            <Button label="Зберегти"
                    icon="pi pi-check"
                    @click="emits('submit')"
                    autofocus
            />
        </template>
    </Modal>
</template>
