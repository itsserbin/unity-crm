<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import List from "@/Pages/Tenants/Catalog/Images/List.vue";
import {inject} from "vue";

const can = inject('$can');
</script>

<template>
    <AppLayout :can="can('read-images')">
        <List/>
    </AppLayout>
</template>
