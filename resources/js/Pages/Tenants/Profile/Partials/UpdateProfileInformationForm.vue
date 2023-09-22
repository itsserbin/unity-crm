<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import Button from "primevue/button";
import InputText from "primevue/inputtext";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Особиста інформація</h2>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Оновіть дані профілю та електронну адресу свого облікового запису.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Ім'я"/>

                <InputText
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div>
                <InputLabel for="email" value="Email"/>

                <InputText
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-zinc-800 dark:text-zinc-200">
                    Ваша електронна адреса не підтверджена.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-zinc-800"
                    >
                        Натисніть тут, щоб повторно надіслати електронний лист для підтвердження.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    На вашу електронну адресу надіслано нове посилання для підтвердження.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :disabled="form.processing">Зберегти</Button>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-zinc-600 dark:text-zinc-400">Збережено</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
