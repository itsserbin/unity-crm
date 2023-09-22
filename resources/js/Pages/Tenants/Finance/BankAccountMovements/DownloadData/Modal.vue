<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import Button from "primevue/button";
import Form from './MonobankForm.vue'
import {onMounted, ref} from "vue";
import BankAccountsRepository from "@/Repositories/Tenants/Finance/BankAccountsRepository.js";
import MonobankRepository from "@/Repositories/Tenants/MonobankRepository.js";
import BankAccountMovementsRepository from "@/Repositories/Tenants/Finance/BankAccountMovementsRepository.js";
import {toast} from "vue3-toastify";

const props = defineProps(['item', 'show', 'isLoadingModal']);
const emits = defineEmits(['close', 'submit']);

const isLoading = ref(false);

const onSubmit = async () => {
    isLoading.value = true;
    try {
        const data = await MonobankRepository.extract(props.item);
        await BankAccountMovementsRepository.massCreate(data.result.map((item) => {
            return {
                account_id: props.item.account,
                movement_id: item.id,
                sum: (item.amount / 100).toFixed(2),
                balance: (item.balance / 100).toFixed(2),
                comment: item.description,
                date: formatDate(item.time)
            }
        }));
        emits('submit');
        emits('close');
        toast.success('Дані успішно завантажені!');
    } catch (e) {
        toast.error('Виникла помилка, спробуйте пізніше');
        console.error(e);
    }
    isLoading.value = false;
}

const formatDate = (timestamp) => {
    const date = new Date(timestamp * 1000);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};
</script>

<template>
    <Modal :show="show" @close="emits('close')">
        <template #body>
            <Form :item="item" :isLoading="isLoading"/>
        </template>
        <template #footer>
            <Button label="Скасувати"
                    icon="pi pi-times"
                    @click="emits('close')"
                    text
            />
            <Button label="Зберегти"
                    icon="pi pi-check"
                    @click="onSubmit"
                    autofocus
            />
        </template>
    </Modal>
</template>
