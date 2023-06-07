<script setup>
import Images from '@/Pages/Tenants/Catalog/Images/List.vue';
import Modal from '@/Components/Modal/Modal.vue'
import {ref} from "vue";
import Button from "primevue/button";

defineProps(['show']);
const emits = defineEmits(['close', 'submit']);
const selectItem = ref();

const onSelect = (e) => {
    selectItem.value = e;
}

</script>

<template>
    <Modal :show="show" @close="emits('close')" max-width="7xl">
        <template #body>
            <div class="text-xl text-center pb-4">Оберіть зображення</div>
            <Images :selectable="true" @selected="onSelect"/>
        </template>

        <template #footer>
            <Button label="Скасувати"
                    icon="pi pi-times"
                    @click="emits('close')"
                    text
            />
            <Button label="Зберегти"
                    icon="pi pi-check"
                    @click="emits('submit',selectItem)"
                    autofocus
            />
        </template>
    </Modal>
</template>
