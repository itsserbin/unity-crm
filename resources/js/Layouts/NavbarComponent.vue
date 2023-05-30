<template>
    <div class="md:hidden z-50 bg-white dark:bg-zinc-900 fixed bottom-0 w-full flex justify-around items-center p-3 shadow-lg">
        <div
            v-for="(item, index) in items"
            :key="index"
            class="cursor-pointer flex flex-col items-center transition-all duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-105"
            @click="handleClick(item)"
        >
            <i :class="item.icon"></i>
            <Link :href="item.href" v-if="!item.child">
                <p
                    class="text-sm text-center dark:text-white transition-all duration-500 ease-in-out"
                    :class="{ 'text-blue-500': item.isActive, 'font-bold': item.isActive }"
                >
                    {{ item.title }}
                </p>
            </Link>
            <p v-else
                class="text-sm text-center dark:text-white transition-all duration-500 ease-in-out"
                :class="{ 'text-blue-500': item.isActive, 'font-bold': item.isActive }"
            >
                {{ item.title }}
            </p>
            <transition name="bounce">
                <div
                    v-if="item.child && item.isActive"
                    class="absolute bottom-14 bg-white dark:bg-zinc-900 shadow-lg rounded-md"
                >
                    <Link
                        as="button"
                        :href="innerItem.href"
                        v-for="(innerItem, innerIndex) in item.child"
                        :key="`inner-${innerIndex}`"
                        class="text-sm p-2 dark:text-white"
                    >
                        {{ innerItem.title }}
                    </Link>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>
import {reactive} from "vue";
import {Link} from "@inertiajs/vue3";

let previousActive = null;

const items = reactive([
    {
        header: 'UnityCRM',
        hiddenOnCollapse: true
    },
    {
        href: '/',
        title: 'Dashboard',
        icon: 'pi pi-bars',
        isActive: false,
    },
    {
        title: 'Каталог',
        icon: 'pi pi-th-large',
        isActive: false,
        child: [
            {
                href: route('catalog.products'),
                title: 'Товари'
            },
            {
                href: route('catalog.categories'),
                title: 'Категорії'
            },
            {
                href: route('catalog.images'),
                title: 'Зображення'
            }
        ]
    },
    {
        title: 'CRM',
        icon: 'pi pi-database',
        isActive: false,
        child: [
            {
                href: route('crm.orders'),
                title: 'Замовлення'
            },
            {
                href: route('crm.clients'),
                title: 'Клієнти'
            },
        ]
    },
    {
        title: 'Налаштування',
        icon: 'pi pi-cog',
        isActive: false,
        child: [
            {
                href: route('options.sources'),
                title: 'Джерела'
            },
            {
                href: route('options.statuses'),
                title: 'Статуси'
            },
            {
                href: route('options.delivery-services'),
                title: 'Служби доставки'
            }
        ]
    }
]);

const handleClick = (item) => {
    if (previousActive === item) {
        item.isActive = !item.isActive;
        previousActive = null;
    } else {
        if (previousActive) {
            previousActive.isActive = false;
        }
        item.isActive = true;
        previousActive = item;
    }
};
</script>

<style scoped>
.bounce-enter-active {
    animation: bounce-in .5s;
}

.bounce-leave-active {
    animation: bounce-out .5s;
}

@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes bounce-out {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(0);
    }
}
</style>
