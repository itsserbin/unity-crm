<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {Head, useForm} from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Підтвердьте пароль"/>

        <div class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
            Це безпечна область програми. Перш ніж продовжити, підтвердьте свій пароль.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password"/>
                <InputText
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="flex justify-end mt-4">
                <Button type="submit"
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        label="Підтвердити"
                ></Button>
            </div>
        </form>
    </GuestLayout>
</template>
