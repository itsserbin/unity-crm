<script setup>
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";

defineProps(['show', 'item']);
const emits = defineEmits(['submit', 'close'])
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel :required="true">Адреса або відділення</InputLabel>
                    <InputText v-model="item.address"
                               class="w-full"
                               placeholder="Вкажіть адресу"
                               type="text"
                    />
                </div>
                <div class="block">
                    <InputLabel>Місто</InputLabel>
                    <InputText v-model="item.city"
                               class="w-full"
                               placeholder="Вкажіть місто"
                               type="text"
                    />
                </div>
                <div class="block">
                    <InputLabel>Область</InputLabel>
                    <InputText v-model="item.region"
                               class="w-full"
                               placeholder="Вкажіть область"
                               type="text"
                    />
                </div>
                <div class="block">
                    <InputLabel>Поштовий індекс</InputLabel>
                    <InputText v-model="item.zip_code"
                               class="w-full"
                               placeholder="Вкажіть поштовий індекс"
                               type="text"
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
