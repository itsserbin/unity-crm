<script>
import {inject, reactive} from "vue";
import {Link} from "@inertiajs/vue3";

const getRoute = (v) => route(v);

export default {
    components: {Link},
    setup() {
        let previousActive = null;

        const can = inject('$can');

        const hasAccess = (val) => {
            return val.some(item => item === true);
        }

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
                        href: getRoute('catalog.products'),
                        title: 'Товари',
                        can: can('read-products')
                    },
                    {
                        href: getRoute('catalog.categories'),
                        title: 'Категорії',
                        can: can('read-categories')
                    },
                    {
                        href: getRoute('catalog.images'),
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
                        href: getRoute('crm.orders'),
                        title: 'Замовлення',
                        can: can('read-orders')
                    },
                    {
                        href: getRoute('crm.clients'),
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
                        href: getRoute('finance.movement-categories'),
                        title: 'Категорії рухів',
                        can: can('read-bank-account-movement-categories')
                    },
                    {
                        href: getRoute('finance.bank-accounts'),
                        title: 'Банківські акаунти',
                        can: can('read-bank-accounts')
                    },
                    {
                        href: getRoute('finance.accounts'),
                        title: 'Рахунки',
                        can: can('read-finance-account')
                    },
                    {
                        href: getRoute('finance.bank-account-movements'),
                        title: 'Грошові рухи',
                        can: can('read-bank-account-movements')
                    },
                    {
                        href: getRoute('finance.profit-and-loss'),
                        title: 'P&L',
                        can: can('read-profit-and-loss-statistics')
                    },
                    {
                        href: getRoute('finance.cash-flow'),
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
                        href: getRoute('statistics.orders'),
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
                        href: getRoute('options.sources'),
                        title: 'Джерела',
                        can: can('read-sources')
                    },
                    {
                        href: getRoute('options.statuses'),
                        title: 'Статуси',
                        can: can('read-statuses')
                    },
                    {
                        href: getRoute('options.delivery-services'),
                        title: 'Служби доставки',
                        can: can('read-delivery-services')
                    },
                    {
                        href: getRoute('options.users'),
                        title: 'Користувачі',
                        can: can('read-users')
                    },
                    {
                        href: getRoute('options.roles'),
                        title: 'Ролі',
                        can: can('read-roles')
                    },
                ]
            },
            {
                href: getRoute('profile.edit'),
                title: 'Акаунт',
                icon: 'pi pi-user',
                can: true
            },
            {
                href: getRoute('logout'),
                method: 'POST',
                title: 'Вихід',
                icon: 'pi pi-sign-out',
                can: true
            },
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

        return {
            handleClick,
            items,
            can,
            hasAccess
        };
    }
}
</script>

<template>
    <div
        class="md:hidden z-50 bg-white dark:bg-zinc-900 fixed bottom-0 w-full flex justify-around items-center p-3 shadow-lg">
        <div
            v-for="(item, index) in items"
            :key="index"
            class="cursor-pointer flex flex-col items-center transition-all duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-105"
            @click="handleClick(item)"
        >
            <Link as="button" :href="item.href || ''" v-if="!item.child" :method="item.method ? item.method : 'get'">
                <p
                    class="text-sm text-center dark:text-white transition-all duration-500 ease-in-out"
                    :class="{ 'text-blue-500': item.isActive, 'font-bold': item.isActive }"
                >
                    <i :class="item.icon"></i>

                    <!--                    {{ item.title }}-->
                </p>
            </Link>
            <p v-else
               class="text-sm text-center dark:text-white transition-all duration-500 ease-in-out"
               :class="{ 'text-blue-500': item.isActive, 'font-bold': item.isActive }"
            >
                <i :class="item.icon"></i>

                <!--                {{ item.title }}-->
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
