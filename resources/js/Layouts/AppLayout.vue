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
        title: 'Фінанси',
        icon: 'pi pi-dollar',
        isActive: false,
        child: [
            {
                href: route('finance.movement-categories'),
                title: 'Категорії рухів'
            },
            {
                href: route('finance.bank-accounts'),
                title: 'Банківські акаунти'
            },
            {
                href: route('finance.accounts'),
                title: 'Рахунки'
            },
            {
                href: route('finance.bank-account-movements'),
                title: 'Грошові рухи'
            },
            {
                href: route('finance.profit-and-loss'),
                title: 'P&L'
            },
            {
                href: route('finance.cash-flow'),
                title: 'CashFlow'
            },
        ]
    },
    {
        title: 'Статистика',
        icon: 'pi pi-chart-line',
        isActive: false,
        child: [
            {
                href: route('finance.bank-account-movements'),
                title: 'В розробці'
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
        href: route('profile.edit'),
        title: 'Акаунт',
        icon: 'pi pi-user'
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
