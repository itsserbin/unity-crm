<script setup>
// import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
// import SecondaryButton from '@/Components/SecondaryButton.vue';
// import TextInput from '@/Components/TextInput.vue';
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Видалити аккаунт</h2>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Після видалення вашого облікового запису всі його ресурси та дані буде остаточно видалено. Перед видаленням
                свого облікового запису, завантажте будь-які дані чи інформацію, які ви бажаєте зберегти.
            </p>
        </header>

        <Button severity="danger" @click="confirmUserDeletion">Видалити аккаунт</Button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                    Ви впевнені, що хочете видалити свій обліковий запис?
                </h2>

                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Після видалення вашого облікового запису всі його ресурси та дані буде остаточно видалено. Будь ласка
                    введіть свій пароль, щоб підтвердити, що ви хочете остаточно видалити свій обліковий запис.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Пароль" class="sr-only" />

                    <InputText
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="w-full"
                        placeholder="Ваш пароль"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <Button link @click="closeModal">Скасувати</Button>

                    <Button
                        severity="danger"
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Видалити акаунт
                    </Button>
                </div>
            </div>
        </Modal>
    </section>
</template>
