<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputMask from 'primevue/inputmask';
import Loader from "@/Components/Loader.vue";

const form = useForm({
    name: '',
    phone: '',
    email: '',
    tanant_name: '',
    domain: '',
    password: '',
    password_confirmation: '',
});

const appDomain = import.meta.env.VITE_APP_DOMAIN;
const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout :isShowHeading="!form.processing">
        <Head title="Реєстрація"/>

        <div v-if="form.processing" class="text-2xl tex-center flex items-center justify-center min-h-[400px]">
            <div class="text-center flex flex-col justify-center gap-6">
                <div class="w-full flex justify-center">
                    <div class="w-24 h-24">
                        <Loader/>
                    </div>
                </div>
                Зараз я створюю вашу CRM систему, зачейте трохи :)
            </div>
        </div>
        <form v-if="!form.processing" @submit.prevent="submit" class="grid grid-cols-1 gap-3">
            <div>
                <InputLabel for="name" value="Імʼя"/>

                <InputText
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Ваше імʼя"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div>
                <InputLabel for="name" value="Телефон"/>

                <InputMask
                    id="phone"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autofocus
                    autocomplete="phone"
                    placeholder="+38 ("
                    mask="+38 (999) 999-99-99"
                />

                <InputError class="mt-2" :message="form.errors.phone"/>
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
                    placeholder="example@domain.com"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div>
                <InputLabel for="tanant_name" value="Назва CRM"/>

                <InputText
                    id="tanant_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.tanant_name"
                    required
                    placeholder="Вкажіть назву"
                />

                <InputError class="mt-2" :message="form.errors.tanant_name"/>
            </div>

            <div>
                <InputLabel for="domain" value="Адрес ЦРМ"/>

                <div class="p-inputgroup">
                    <InputText
                        id="domain"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.domain"
                        required
                        autocomplete="domain"
                        placeholder="domain"
                    />
                    <Button type="button" disabled :label="'.' + appDomain"/>
                </div>


                <InputError class="mt-2" :message="form.errors.domain"/>
            </div>

            <div>
                <InputLabel for="password" value="Пароль"/>

                <InputText
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Підтвердження паролю"/>

                <InputText
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-zinc-800"
                >
                    Вже зареєстровані?
                </Link>

                <Button type="submit" class="ml-4" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                    Зареєструватись
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
