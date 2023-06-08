<script setup>
import Button from "primevue/button";
import {defineAsyncComponent, reactive, ref} from "vue";
import MovementCategoriesRepository from "@/Repositories/Tenants/Statistics/MovementCategoriesRepository.js";
import {toast} from "vue3-toastify";
import BankAccountMovementsRepository from "@/Repositories/Tenants/Statistics/BankAccountMovementsRepository.js";
import {fromIso} from "@/Helpers/dateTimeFormats.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))
const emits = defineEmits(['submit']);

const modal = reactive({
    isShow: false,
    item: null,
    isLoading: false
});

const categories = ref([]);
const errors = ref([]);

const isLoading = reactive({
    costButton: false,
    profitButton: false,
    submitButton: false,
});

const toggleModal = (val) => val ? modal.isShow = val : modal.isShow = !modal.isShow;

const onCreate = async (val) => {
    val === 1 ? isLoading.profitButton = true : isLoading.costButton = true;
    await getCategories(val);
    toggleModal();
    modal.item = {
        bank: null,
        date: new Date(),
    }
    val === 1 ? isLoading.profitButton = false : isLoading.costButton = false;
}

const getCategories = async (val) => {
    try {
        const data = await MovementCategoriesRepository.list({type: val});
        categories.value = data.result.map((item) => {
            return {
                label: item.title,
                value: item.id
            }
        });
    } catch (e) {
        console.error(e);
    }
}

const onSubmit = async () => {
    isLoading.submitButton = true;
    try {
        const modifiedItem = {...modal.item};
        if (modifiedItem.category_id) {
            modifiedItem.category_id = modifiedItem.category_id.value;
        }
        if (modifiedItem.account_id) {
            modifiedItem.account_id = modifiedItem.account_id.value;
        }
        modifiedItem.date = fromIso(modifiedItem.date);
        const data = await BankAccountMovementsRepository.create(modifiedItem);
        if (data.success) {
            toast.success('Дані успішно оновлені!');
            emits('submit');
            toggleModal();
        } else {
            errors.value = data.data;
        }
    } catch (e) {
        console.error(e);
        toast.error('Викикла помилка, перевірте корректність даних');
    }
    isLoading.submitButton = false;
}
</script>

<template>
    <Button label="Додати витрату"
            size="small"
            icon="pi pi-minus"
            class="mr-2"
            @click="onCreate(0)"
            severity="danger"
            :loading="isLoading.costButton"
    />
    <Button label="Додати надходження"
            size="small"
            icon="pi pi-plus"
            class="mr-2"
            @click="onCreate(1)"
            :loading="isLoading.profitButton"
    />

    <Modal v-if="modal.isShow"
           :show="modal.isShow"
           :item="modal.item"
           @close="toggleModal(false)"
           @submit="onSubmit"
           :isLoadingModal="modal.isLoading"
           :categories="categories"
           :errors="errors"
    />
</template>
