<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Statuses from "@/Pages/Tenants/Options/Statuses/Statuses/Index.vue";
import StatusGroups from "@/Pages/Tenants/Options/Statuses/StatusGroups/Index.vue";
import {inject} from "vue";

const can = inject('$can');

defineProps(['statuses', 'statusGroups'])
</script>

<template>
    <AppLayout :can="can('read-statuses')">
        <div class="card">
            <TabView>
                <TabPanel header="Статуси замовлень">
                    <Statuses :statuses="statuses"/>
                </TabPanel>
                <TabPanel header="Групи статусів">
                    <StatusGroups :statuses="statusGroups"/>
                </TabPanel>
            </TabView>
        </div>
    </AppLayout>
</template>
