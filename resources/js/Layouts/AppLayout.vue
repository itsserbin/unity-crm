<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";

const isOpen = ref(false);
</script>
<template>
    <div class="flex">
        <button @click="isOpen = !isOpen"
                class="absolute top-4 left-4 z-50 bg-gray-200 dark:bg-gray-700 p-2 rounded w-9 h-9">
            <i :class="{'pi pi-times open' : isOpen,'pi pi-bars' : !isOpen}" class="open-button"></i>
        </button>
        <transition name="slide-aside">
            <template v-if="isOpen">
                <div class="z-40 absolute flex flex-col h-screen p-3 w-80 duration-300 bg-gray-300 dark:bg-gray-800 shadow">
                    <div class="space-y-3">
                        <div class="flex-1 mt-10">
                            <ul class="pt-2 pb-4 space-y-1 text-lg">
                                <li class="rounded-sm">
                                    <Link :href="route('dashboard')" class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-home" style="font-size:1.3rem"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Дашборд</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('products')" class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-th-large" style="font-size:1.3rem"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Товари</span>
                                    </Link>
                                </li>

                                <li class="rounded-sm">
                                    <Link :href="route('categories')"
                                          class="flex items-center p-2 space-x-3 rounded-md">
                                        <i class="pi pi-folder" style="font-size:1.3rem"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Категорії</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>
        </transition>
        <div class="min-h-screen w-full p-4 ml-12">
            <slot></slot>
            <div v-show="isOpen" class="fixed inset-0 transform transition-all" @click="isOpen = !isOpen    ">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"/>
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
