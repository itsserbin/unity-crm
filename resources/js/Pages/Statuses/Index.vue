<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Statuses from "@/Pages/Statuses/Statuses/Index.vue";
import StatusGroups from "@/Pages/Statuses/StatusGroups/Index.vue";

defineProps(['statuses','statusGroups'])
</script>

<template>
    <AppLayout>
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
