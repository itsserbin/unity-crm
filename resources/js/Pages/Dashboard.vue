<script setup>
import Button from 'primevue/button';
import Toolbar from 'primevue/toolbar';
import Card from 'primevue/card';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Heading from "@/Components/Heading.vue";
import {Link} from "@inertiajs/vue3";

defineProps(['tenants']);

const protocol = import.meta.env.VITE_APP_PROTOCOL;
</script>

<template>
    <div class="max-w-3xl mx-auto grid grid-cols-1 gap-4">
        <Toolbar>
            <template #start>
                <div class="flex gap-4 items-center">
                    <ApplicationLogo class="w-10 h-10 fill-current text-zinc-500"/>
                    <Heading size="3xl">UnityCRM</Heading>
                </div>
            </template>

            <template #end>
                <div class="flex gap-4 items-center">
                    {{ $page.props.auth.user.email }}
                    <Link as="button" :href="route('logout')" method="post">
                        <Button icon="pi pi-sign-out"/>
                    </Link>
                </div>
            </template>
        </Toolbar>

        <a :href="protocol + tenant.domains[0].domain" v-for="tenant in tenants" target="_blank">
            <Card>
                <template #title>
                    {{ tenant.name }}
                </template>
                <template #subtitle>
                    <div class="flex justify-between">
                        <div class="block">
                            <b>Адреса:</b> {{ tenant.domains[0].domain }}<br>
                            <b>Створена:</b> {{ $filters.formatDate(tenant.created_at) }}
                        </div>
                        <Button icon="pi pi-angle-right"/>
                    </div>

                </template>
            </Card>
        </a>
    </div>
</template>
