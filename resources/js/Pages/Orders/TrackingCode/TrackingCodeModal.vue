<script setup>
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import InputText from "primevue/inputtext";
import Modal from "@/Components/Modal/Modal.vue";
import SelectButton from "primevue/selectbutton";

const props = defineProps(['show', 'item']);
const emits = defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            asd
            {{ item }}
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
