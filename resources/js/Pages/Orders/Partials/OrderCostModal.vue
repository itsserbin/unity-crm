<script setup>
import Button from "primevue/button";
import Modal from "@/Components/Modal/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import SelectButton from 'primevue/selectbutton';
import {ref} from "vue";

defineProps(['show', 'item']);
const emits = defineEmits(['close', 'submit']);

const costs = [
    'Доставка поштовою службою',
    'Доставка курʼєром',
    'Бонус співробітнику',
    'Упаковка',
    'Комісія платіжної системи',
    'Компенсація клієнту',
];

const titleType = ref({
    value: 0
});
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
                    <InputLabel :required="true">Тип витрати</InputLabel>
                    <div class="grid grid-cols-12">
                        <div class="col-span-4">
                            <SelectButton v-model="titleType" :options="titleOptions"
                                          optionLabel="name"/>
                        </div>
                        <div class="col-span-8">
                            <Dropdown v-if="titleType.value === 0"
                                      v-model="item.title"
                                      :options="costs"
                                      placeholder="Оберіть тип витрати"
                                      class="w-full"
                            />
                            <InputText v-else
                                       v-model="item.title"
                                       class="w-full"
                                       placeholder="Вкажіть назву"
                                       type="text"
                            />
                        </div>

                    </div>
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
