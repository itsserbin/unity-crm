<script setup>
import Button from 'primevue/button';
import Toolbar from 'primevue/toolbar';
import Card from 'primevue/card';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Heading from "@/Components/Heading.vue";
import {Link} from "@inertiajs/vue3";
import {defineAsyncComponent, onMounted, reactive} from "vue";
import TenantsRepository from "@/Repositories/TenantsRepository.js";
import {toast} from "vue3-toastify";

const TenantModal = defineAsyncComponent(() => import('./CreateTenantModal.vue'));

const props = defineProps(['tenants']);

const protocol = import.meta.env.VITE_APP_PROTOCOL;

const state = reactive({
    data: [],
    errors: [],
    isShowModal: false,
    isLoadingModal: false,
    modalItem: {}
});

onMounted(async () => {
    if (props.tenants) {
        state.data = props.tenants
    } else {
        await fetch();
    }
})

const toggleModal = () => state.isShowModal = !state.isShowModal;

const onCreate = () => {
    state.modalItem = {
        id: null
    }
    toggleModal();
}

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {
        state.errors = [];
        const data = await TenantsRepository.create(state.modalItem);
        if (data.success) {
            await fetch();
            toggleModal();
            toast.success("CRM успішно створена");
        } else {
            state.errors = data.data;
            toast.error("Перевірте корректність даних");
        }
    } catch (e) {
        console.error(e);
    }
    state.isLoadingModal = false;
}
const fetch = async () => {
    try {
        const data = await TenantsRepository.fetch();
        state.data = data.result;
    } catch (e) {
        console.error(e);
    }
}
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

        <Link :href="route('tenant',tenant.id)" v-for="tenant in state.data.data" target="_blank">
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
        </Link>

        <div class="flex justify-center">
            <Button icon="pi pi-plus" label="Створити CRM" @click="onCreate"/>
        </div>
    </div>
    <TenantModal v-if="state.isShowModal" :show="state.isShowModal"
                 :item="state.modalItem" @close="toggleModal"
                 :errors="state.errors" @submit="onSubmit"/>
</template>
