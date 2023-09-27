<script setup>
import {Head, Link} from '@inertiajs/vue3';
import Heading from "@/Components/Heading.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});
</script>

<template>
    <Head title="Welcome"/>

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-zinc-100 dark:bg-dots-lighter dark:bg-zinc-900 selection:bg-red-500 selection:text-white"
    >


        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center items-center flex-col gap-8 h-[70vh]">
                <div>
                    <ApplicationLogo class="mx-auto fill-current text-zinc-500 w-20 h-20"/>
                    <Heading size="5xl" class="text-center">UnityCRM</Heading>
                </div>
                <div class="flex justify-center">
                    <Link
                        :href="route('login')"
                        class="font-semibold text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Вхід
                    </Link>

                    <Link
                        :href="route('register')"
                        class="ml-4 font-semibold text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Реєстрація
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
