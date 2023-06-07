<script setup>
import Modal from "./Modal.vue";
import {onMounted, ref} from "vue";
import {toast} from "vue3-toastify";
import BankAccountMovementsRepository from "@/Repositories/Tenants/Statistics/BankAccountMovementsRepository.js";
import MovementCategoriesRepository from "@/Repositories/Tenants/Statistics/MovementCategoriesRepository.js";
import {fromIso} from "@/Helpers/dateTimeFormats.js";

const props = defineProps(['item', 'show']);
const emits = defineEmits(['close', 'submit']);

const isLoading = ref(false);
const errors = ref([]);
const categories = ref([]);

onMounted(async () => {
    await getCategories(props.item.type === 1 ? 1 : 0);
});

const onSubmit = async () => {
    isLoading.value = true;
    try {
        const modifiedItem = {...props.item};
        if (modifiedItem.category_id) {
            modifiedItem.category_id = modifiedItem.category_id.value;
        }
        if (modifiedItem.account_id) {
            modifiedItem.account_id = modifiedItem.account_id.value;
        }
        modifiedItem.date = fromIso(modifiedItem.date);
        const data = await BankAccountMovementsRepository.update(modifiedItem);
        if (data.success) {
            emits('submit');
            toast.success('Дані успішно оновлені!');
        } else {
            errors.value = data.data;
            console.log(errors.value);
        }
    } catch (e) {
        console.error(e);
        toast.error('Викикла помилка, перевірте корректність даних');
    }
    isLoading.value = false;
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
</script>

<template>
    <Modal v-if="show"
           :show="show"
           :item="item"
           @close="emits('close')"
           @submit="onSubmit"
           :errors="errors"
           :isLoadingModal="isLoading"
           :categories="categories"
    />
</template>
