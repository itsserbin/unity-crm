<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import InputText from "primevue/inputtext";
import Button from "primevue/button";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Оновити пароль</h2>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Переконайтеся, що ваш обліковий запис використовує довгий довільний пароль, щоб залишатися в безпеці.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Поточний пароль"/>

                <InputText
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.current_password" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="password" value="Новий пароль"/>

                <InputText
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Підтвердьте пароль"/>

                <InputText
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2"/>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing">Зберегти</Button>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-zinc-600 dark:text-zinc-400">Збережено</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
