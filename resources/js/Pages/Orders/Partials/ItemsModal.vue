<script setup>
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';

defineProps(['show', 'item', 'products']);
const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel :required="true">Товар</InputLabel>
                    <Dropdown v-model="item.product_id"
                              :options="products"
                              optionLabel="title"
                              dataKey="id"
                              placeholder="Оберіть товар"
                              class="w-full"
                    />
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
                    <InputLabel>Знижка</InputLabel>
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
