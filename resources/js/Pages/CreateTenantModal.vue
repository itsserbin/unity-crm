<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import Button from "primevue/button";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "primevue/inputtext";
import InputError from "@/Components/InputError.vue";

defineProps(['item', 'show', 'isLoadingModal', 'errors']);
const emits = defineEmits(['close', 'submit']);

const appDomain = import.meta.env.VITE_APP_DOMAIN;
</script>

<template>
    <Modal :show="show" @close="emits('close')" max-width="sm">
        <template #body>
            <div class="grid grid-cols-1 gap-4">
                <div class="block">
                    <InputLabel for="name" value="Назва" :required="true"/>
                    <InputText
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="item.name"
                        required
                        placeholder="Вкажіть назву CRM"
                    />
                    <InputError class="mt-2" v-if="errors.name" v-for="error in errors.name" :message="error"/>
                </div>
                <div class="block">
                    <InputLabel for="domain" value="Адреса" :required="true"/>
                    <div class="p-inputgroup">
                        <InputText
                            id="domain"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="item.id"
                            required
                            placeholder="example"
                        />
                        <Button type="button" disabled :label="'.' + appDomain"/>
                    </div>

                    <InputError class="mt-2" v-if="errors.id" v-for="error in errors.id" :message="error"/>
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
                    :loading="isLoadingModal"
            />
        </template>
    </Modal>
</template>
