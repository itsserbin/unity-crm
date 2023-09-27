<script setup>
import {inject, ref} from "vue";
import SidebarMenu from '@/Components/Sidebar/SidebarMenu.vue'
import NavbarComponent from "@/Layouts/NavbarComponent.vue";

const can = inject('$can');

const hasAccess = (val) => {
    return val.some(item => item === true);
}

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
        can: true
    },
    {
        title: 'Каталог',
        icon: 'pi pi-th-large',
        isActive: false,
        can: hasAccess([
            can('read-products'),
            can('read-categories'),
            can('read-images')
        ]),
        child: [
            {
                href: route('catalog.products'),
                title: 'Товари',
                can: can('read-products')
            },
            {
                href: route('catalog.categories'),
                title: 'Категорії',
                can: can('read-categories')
            },
            {
                href: route('catalog.images'),
                title: 'Зображення',
                can: can('read-images')
            }
        ]
    },
    {
        title: 'CRM',
        icon: 'pi pi-database',
        isActive: false,
        can: hasAccess([
            can('read-orders'),
            can('read-clients'),
        ]),
        child: [
            {
                href: route('crm.orders'),
                title: 'Замовлення',
                can: can('read-orders')
            },
            {
                href: route('crm.clients'),
                title: 'Клієнти',
                can: can('read-clients')
            },
        ]
    },
    {
        title: 'Фінанси',
        icon: 'pi pi-dollar',
        isActive: false,
        can: hasAccess([
            can('read-bank-account-movement-categories'),
            can('read-bank-accounts'),
            can('read-finance-account'),
            can('read-bank-account-movements'),
            can('read-profit-and-loss-statistics'),
            can('read-cash-flow-statistics')
        ]),
        child: [
            {
                href: route('finance.movement-categories'),
                title: 'Категорії рухів',
                can: can('read-bank-account-movement-categories')
            },
            {
                href: route('finance.bank-accounts'),
                title: 'Банківські акаунти',
                can: can('read-bank-accounts')
            },
            {
                href: route('finance.accounts'),
                title: 'Рахунки',
                can: can('read-finance-account')
            },
            {
                href: route('finance.bank-account-movements'),
                title: 'Грошові рухи',
                can: can('read-bank-account-movements')
            },
            {
                href: route('finance.profit-and-loss'),
                title: 'P&L',
                can: can('read-profit-and-loss-statistics')
            },
            {
                href: route('finance.cash-flow'),
                title: 'CashFlow',
                can: can('read-cash-flow-statistics')
            },
        ]
    },
    {
        title: 'Статистика',
        icon: 'pi pi-chart-line',
        isActive: false,
        can: hasAccess([
            can('read-order-statistics'),
        ]),
        child: [
            {
                href: route('statistics.orders'),
                title: 'Замовлення',
                can: can('read-order-statistics')
            },
        ]
    },
    {
        title: 'Налаштування',
        icon: 'pi pi-cog',
        isActive: false,
        can: hasAccess([
            can('read-sources'),
            can('read-statuses'),
            can('read-delivery-services'),
        ]),
        child: [
            {
                href: route('options.sources'),
                title: 'Джерела',
                can: can('read-sources')
            },
            {
                href: route('options.statuses'),
                title: 'Статуси',
                can: can('read-statuses')
            },
            {
                href: route('options.delivery-services'),
                title: 'Служби доставки',
                can: can('read-delivery-services')
            },
            {
                href: route('options.users'),
                title: 'Користувачі',
                can: can('read-users')
            },
            {
                href: route('options.roles'),
                title: 'Ролі',
                can: can('read-roles')
            },
        ]
    },
    {
        href: route('profile.edit'),
        title: 'Акаунт',
        icon: 'pi pi-user',
        can: true
    },
    {
        href: route('logout'),
        method: 'POST',
        title: 'Вихід',
        icon: 'pi pi-sign-out',
        can: true
    },
];

const props = defineProps(['can']);

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
            <template v-if="!can">
                <div class="w-full h-screen flex items-center justify-center text-4xl font-bold">
                    Sorry, Ви не маєте доступа до цього розділу :(
                </div>
            </template>
            <template v-else>
                <slot></slot>
            </template>
        </div>
    </div>
</template>
