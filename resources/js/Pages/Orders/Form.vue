<script setup>
import SourcesInput from "@/Pages/Orders/Partials/SourcesInput.vue";
import StatusInput from "@/Pages/Orders/Partials/StatusInput.vue";
import ManagerInput from "@/Pages/Orders/Partials/ManagerInput.vue";
import ClientInput from "@/Pages/Orders/Partials/ClientInput.vue";
import DeliveryInput from "@/Pages/Orders/Partials/DeliveryInput.vue";
import ItemsInput from "@/Pages/Orders/Partials/ItemsInput.vue";
import TotalInput from "@/Pages/Orders/Partials/TotalInput.vue";
import CommentInput from "@/Pages/Orders/Partials/CommentInput.vue";
import OrderCostsInput from "@/Pages/Orders/Partials/OrderCostsInput.vue";
import Card from "primevue/card";

const props = defineProps([
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <DeliveryInput :item="item" :deliveryServices="deliveryServices"/>
                </div>
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
