<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";

const isOpen = ref(false);
</script>
<template>
    <div class="flex">
        <button @click="isOpen = !isOpen"
                class="absolute top-8 left-2 z-50 bg-zinc-200 dark:bg-zinc-700 p-2 rounded w-10 h-10">
            <i :class="{'pi pi-times open' : isOpen,'pi pi-bars' : !isOpen}" class="open-button"></i>
        </button>
        <transition name="slide-aside">
            <template v-if="isOpen">
                <div
                    class="z-40 absolute flex flex-col h-screen p-4 w-80 duration-300 bg-zinc-300 dark:bg-zinc-800 shadow">
                    <div class="font-medium text-zinc-700 dark:text-zinc-300 text-4xl text-end">UnityCRM</div>
                    <div class="space-y-3">
                        <div class="flex-1 mt-10">
                            <ul class="pt-2 pb-4 space-y-1 text-lg">
                                <li class="rounded-sm">
                                    <Link :href="route('dashboard')" class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-home" style="font-size:1.3rem"></i>
                                        <span class="text-zinc-700 dark:text-zinc-300">Дашборд</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('catalog.products')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-th-large" style="font-size:1.3rem"></i>
                                        <span class="text-zinc-700 dark:text-zinc-300">Товари</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('catalog.categories')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-folder" style="font-size:1.3rem"></i>
                                        <span class="text-zinc-700 dark:text-zinc-300">Категорії</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('crm.clients')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-user" style="font-size:1.3rem"></i>
                                        <span class="text-zinc-700 dark:text-zinc-300">Клієнти</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('crm.orders')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <span class="text-zinc-700 dark:text-zinc-300">Замовлення</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('options.sources')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <span class="text-zinc-700 dark:text-zinc-300">Джерела</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('options.statuses')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <span class="text-zinc-700 dark:text-zinc-300">Статуси</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('options.delivery-services')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <span class="text-zinc-700 dark:text-zinc-300">Служби доставки</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('logout')" method="post" as="button"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <span class="text-zinc-700 dark:text-zinc-300">Вийти</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>
        </transition>
        <div class="min-h-screen w-full p-4 pl-14">
            <slot></slot>
            <div v-show="isOpen" class="fixed inset-0 transform transition-all" @click="isOpen = !isOpen    ">
                <div class="absolute inset-0 bg-zinc-500 dark:bg-zinc-900 opacity-75"/>
            </div>
        </div>
    </div>
</template>

<style>
.slide-aside-enter-active,
.slide-aside-leave-active {
    transition: transform 0.3s;
}

.slide-aside-enter-from,
.slide-aside-leave-to {
    transform: translateX(-100%);
}

.slide-aside-enter-to,
.slide-aside-leave-from {
    transform: translateX(0);
}

.open-button {
    transition: transform 0.3s;
}

.open-button.open {
    transform: rotate(90deg);
}
</style>
