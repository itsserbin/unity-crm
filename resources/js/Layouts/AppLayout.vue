<script setup>
import {ref} from "vue";
import SidebarMenu from '@/Components/Sidebar/SidebarMenu.vue'
import NavbarComponent from "@/Layouts/NavbarComponent.vue";

const menu = [
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
        title: 'Статистика',
        icon: 'pi pi-database',
        isActive: false,
        child: [
            {
                href: route('statistics.bank-accounts'),
                title: 'Банківські рахунки'
            },
            {
                href: route('statistics.bank-account-movements'),
                title: 'Грошові рухи'
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
    },
    {
        href: route('logout'),
        method: 'POST',
        title: 'Вихід',
        icon: 'pi pi-sign-out'
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
        <NavbarComponent :items="menu"/>
        <sidebar-menu @update:collapsed="toggleCollapse"
                      :menu="menu"
                      :collapsed="true"
                      :theme="isDark()"
                      class="hidden md:flex"
        />
        <div class="w-full p-4" :class="{'md:pl-[75px]' : isOpen,'md:pl-[300px]' : !isOpen}">
            <slot></slot>
        </div>
    </div>
</template>
