<script setup>
import Card from "primevue/card";
import OrderCostsInput from '@/Pages/Tenants/CRM/Orders/Partials/Costs/OrderCostsInput.vue'
import OrderInvoiceInput from '@/Pages/Tenants/CRM/Orders/Partials/Invoices/OrderInvoiceInput.vue'
import ClientInput from '@/Pages/Tenants/CRM/Orders/Partials/ClientInput.vue'
import DeliveryInput from '@/Pages/Tenants/CRM/Orders/Partials/TrackingCodesInput.vue'
import StatusInput from '@/Pages/Tenants/CRM/Orders/Partials/StatusInput.vue'
import SourcesInput from '@/Pages/Tenants/CRM/Orders/Partials/SourcesInput.vue'
import ManagerInput from '@/Pages/Tenants/CRM/Orders/Partials/ManagerInput.vue'
import ItemsInput from '@/Pages/Tenants/CRM/Orders/Partials/Items/ItemsInput.vue'
import TotalInput from '@/Pages/Tenants/CRM/Orders/Partials/TotalInput.vue'
import CommentInput from '@/Pages/Tenants/CRM/Orders/Partials/CommentInput.vue'

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
                <CommentInput :item="item"/>
            </template>
        </Card>


        <TotalInput :item="item"/>
    </div>
</template>
