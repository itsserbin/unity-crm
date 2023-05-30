<script setup>
import {ref} from "vue";
import SidebarMenu from '@/Components/Sidebar/SidebarMenu.vue'

const menu = [
    {
        header: 'UnityCRM',
        hiddenOnCollapse: true
    },
    {
        href: '/',
        title: 'Dashboard',
        icon: 'pi pi-bars'
    },
    {
        title: 'Каталог',
        icon: 'pi pi-th-large',
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
    },
    {
        href: route('logout'),
        method: 'POST',
        title: 'Вихід',
        icon: 'pi pi-user'
    },
];

const isOpen = ref(true);

const toggleCollapse = (e) => {
    isOpen.value = e;
}

const isDark = () => {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
        ? ''
        : 'white-theme';
}
</script>
<template>
    <div class="flex">
        <sidebar-menu @update:collapsed="toggleCollapse"
                      :menu="menu"
                      :collapsed="true"
                      :theme="isDark()"
        />
        <div class="w-full p-4" :class="{'pl-[75px]' : isOpen,'pl-[300px]' : !isOpen}">
            <slot></slot>
        </div>
    </div>
</template>
