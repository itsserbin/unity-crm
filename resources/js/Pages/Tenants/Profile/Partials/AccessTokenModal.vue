<script setup>
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";

const props = defineProps(['show', 'item']);
const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel :required="true">Назва</InputLabel>
                    <InputText v-model="item.name"
                               class="w-full"
                               placeholder="Вкажіть назву"
                               type="text"
                    />
                </div>

                <div class="block" v-if="item.token">
                    <InputLabel>Токен</InputLabel>
                    <InputText v-model="item.token"
                              class="w-full"
                              disabled
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
