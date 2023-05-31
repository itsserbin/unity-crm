<script setup>
import Card from "primevue/card";
import OrderCostsInput from '@/Pages/Tenants/Orders/Partials/Costs/OrderCostsInput.vue'
import OrderInvoiceInput from '@/Pages/Tenants/Orders/Partials/Invoices/OrderInvoiceInput.vue'
import ClientInput from '@/Pages/Tenants/Orders/Partials/ClientInput.vue'
import DeliveryInput from '@/Pages/Tenants/Orders/Partials/TrackingCodesInput.vue'
import StatusInput from '@/Pages/Tenants/Orders/Partials/StatusInput.vue'
import SourcesInput from '@/Pages/Tenants/Orders/Partials/SourcesInput.vue'
import ManagerInput from '@/Pages/Tenants/Orders/Partials/ManagerInput.vue'
import ItemsInput from '@/Pages/Tenants/Orders/Partials/Items/ItemsInput.vue'
import TotalInput from '@/Pages/Tenants/Orders/Partials/TotalInput.vue'
import CommentInput from '@/Pages/Tenants/Orders/Partials/CommentInput.vue'

defineProps([
    'item',
    'sources',
    'statuses',
    'users',
    'deliveryServices',
    'products',
    'clients',
    'errors',
]);
</script>

<template>
    <div class="grid grid-cols-1 gap-8">
        <Card>
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="block">
                        <SourcesInput :item="item" :sources="sources" :errors="errors"/>
                    </div>

                    <div class="block">
                        <StatusInput :item="item" :statuses="statuses" :errors="errors"/>
                    </div>

                    <div class="block">
                        <ManagerInput :item="item" :users="users"/>
                    </div>
                </div>
            </template>
        </Card>

        <ClientInput :item="item" :clients="clients"/>

        <Card>
            <template #content>
                <DeliveryInput :item="item" :deliveryServices="deliveryServices"/>
            </template>
        </Card>

        <Card>
            <template #content>
                <ItemsInput :item="item" :products="products"/>
            </template>
        </Card>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Card>
                <template #content>
                    <OrderCostsInput :item="item"/>
                </template>
            </Card>
            <Card>
                <template #content>
                    <OrderInvoiceInput :item="item"/>
                </template>
            </Card>
        </div>

        <Card>
            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="block">
                        <CommentInput :item="item"/>
                    </div>
                    <div class="block">
                        <TotalInput :item="item"/>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>
