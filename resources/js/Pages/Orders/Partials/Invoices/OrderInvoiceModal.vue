<script setup>
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import SelectButton from 'primevue/selectbutton';
import {computed, onMounted, ref} from "vue";

const props = defineProps(['show', 'item']);
const emits = defineEmits(['close', 'submit']);

const costs = [
    'Доставка поштовою службою',
    'Доставка курʼєром',
    'Бонус співробітнику',
    'Упаковка',
    'Комісія платіжної системи',
    'Компенсація клієнту',
];

const titleType = ref({value: 0});
const titleOptions = [
    {
        name: 'Із списка',
        value: 0
    },
    {
        name: 'Вручну',
        value: 1
    },
];
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel :required="true">Дата</InputLabel>

                </div>
                <div class="block">
                    <InputLabel :required="true">Сума</InputLabel>
                    <InputText v-model="item.sum"
                               class="w-full"
                               placeholder="Вкажіть суму"
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
