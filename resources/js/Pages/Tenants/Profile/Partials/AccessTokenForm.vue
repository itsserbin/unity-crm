<script setup>
import Button from "primevue/button";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import AccessTokenModal from "@/Pages/Tenants/Profile/Partials/AccessTokenModal.vue";

import UsersRepository from "@/Repositories/Tenants/UsersRepository.js";
import {usePage} from '@inertiajs/vue3';
import {toast} from "vue3-toastify";
import {onMounted, reactive} from "vue";
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import ClientsRepository from "@/Repositories/Tenants/CRM/ClientsRepository.js";

const props = defineProps(['tokens']);
const user = usePage().props.auth.user;

const state = reactive({
    isLoading: false,
    isModal: false,
    modalAction: 0,
    data: [],
    item: null
});

onMounted(async () => {
    if (props.tokens.length) {
        state.data = props.tokens
    } else {
        await fetch();
    }
});

const toggleLoader = (val) => val
    ? state.isLoading = val
    : state.isLoading = !state.isLoading;

const toggleModal = (val) => val
    ? state.isModal = val
    : state.isModal = !state.isModal;

const fetch = async () => {
    toggleLoader();
    try {
        const data = await UsersRepository.tokens.list();

        state.data = data.success ? data.result : [];
    } catch (e) {
        console.error(e);
    }
    toggleLoader();
}

const onEdit = async (event) => {
    state.modalAction = 0;
    toggleLoader();
    try {
        const data = await UsersRepository.tokens.edit(event.data.id);
        state.item = data.result;
        toggleModal();
    } catch (e) {
        console.error(e);
        toast.error("Failed to get data");
    }
    toggleLoader();
}

const onDestroy = async (id) => {
    toggleLoader();
    try {
        await UsersRepository.tokens.destroy(id);
        await fetch();
    } catch (e) {
        console.error(e);
        toast.error("Failed to destroy data");
    }
    toggleLoader();
}

const onCreate = () => {
    state.modalAction = 1;
    state.item = {
        name: null,
        token: null,
    }
    toggleModal();
}

const onSubmit = async () => {
    try {
        if (state.modalAction === 0) {
            await UsersRepository.tokens.update(state.item);
        }
        if (state.modalAction === 1) {
            const data = await UsersRepository.tokens.create(state.item);
            await useConfirm({
                maxWidth: 'xl',
                textConfirmButton: 'Ok',
                textRejectButton: 'Скопійувати',
                message: 'Збережіть токен у надійному місці, після закриття цього вікна він не буде доступний\n' +
                    data.result,
                header: 'API токен спішно створено',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    try {
                        await fetch();
                        toggleModal();
                    } catch (error) {
                        console.error(error);
                        toast.error('Виникла помилка');
                        toggleModal();
                    }
                },
                reject: async () => {
                    try {
                        await navigator.clipboard.writeText(data.result);
                        toast.success('Токен скопійовано');
                        toggleModal();
                    } catch (e) {
                        console.error(error);
                        toast.error('Виникла помилка');
                        toggleModal();
                    }
                }
            });
        }
    } catch (e) {
        console.error(e);
    }
}
</script>

<template>
    <section>
        <header class="flex justify-between">
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">API токен</h2>
            <Button label="Додати"
                    size="small"
                    icon="pi pi-plus"
                    class="mr-2"
                    @click="onCreate"
                    v-if="state.data.length"
            />
        </header>

        <DataTable :value="state.data" @rowSelect="onEdit" selectionMode="single">
            <Column header="Назва" field="name"/>
            <Column header="Токен" field="token"/>
            <Column>
                <template #body="{data}">
                    <Button icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="onDestroy(data.id)"
                    />
                </template>
            </Column>
            <template #empty>
                <div class="w-full text-xl flex flex-col gap-y-2 items-center">
                    Токени відсутні
                    <div>
                        <Button label="Додати" size="small" icon="pi pi-plus" class="mr-2" @click="onCreate"/>
                    </div>
                </div>
            </template>
        </DataTable>
        <AccessTokenModal :show="state.isModal" :item="state.item"
                          @close="toggleModal" @submit="onSubmit"/>
    </section>
</template>
