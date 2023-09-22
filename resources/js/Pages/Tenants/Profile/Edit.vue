<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import AccessTokenForm from './Partials/AccessTokenForm.vue';
import {Head} from '@inertiajs/vue3';
import Card from "primevue/card";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    tokens: Array,
});
</script>

<template>
    <Head title="Profile"/>

    <AppLayout>
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                <Card>
                    <template #content>
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"/>
                    </template>
                </Card>

                <Card>
                    <template #content>
                        <UpdatePasswordForm/>
                    </template>
                </Card>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Card>
                    <template #content>
                        <AccessTokenForm :tokens="tokens"/>
                    </template>
                </Card>

                <Card>
                    <template #content>
                        <DeleteUserForm/>
                    </template>
                </Card>
            </div>
        </div>

    </AppLayout>
</template>
