<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {Head, useForm} from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Забули пароль"/>

        <div class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
            Забули свій пароль? Без проблем. Просто повідомте нам свою адресу електронної пошти, і ми надішлемо вам лист
            для зміни пароля посилання, яке дозволить вам вибрати новий.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email"/>

                <InputText
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Підтвердити
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
