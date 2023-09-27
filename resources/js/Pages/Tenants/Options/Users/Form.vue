<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import InputErrors from "@/Components/InputErrors.vue";
import MultiSelect from "primevue/multiselect";
import Password from 'primevue/password';

const props = defineProps([
    'item',
    'errors',
    'roles'
]);
console.log(props.errors)
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Імʼя</InputLabel>
            <InputText type="text" v-model="item.name" class="w-full"
                       placeholder="Вкажіть імʼя"/>
            <InputErrors :errors="errors.name" v-if="errors.name"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Телефон</InputLabel>
            <InputText type="email" v-model="item.phone" class="w-full"
                       placeholder="Вкажіть номер телефону"/>
            <InputErrors :errors="errors.phone" v-if="errors.phone"/>
        </div>

        <div class="block">
            <InputLabel :required="true">Email</InputLabel>
            <InputText type="email" v-model="item.email" class="w-full"
                       placeholder="Вкажіть електронну пошту"/>
            <InputErrors :errors="errors.email" v-if="errors.email"/>
        </div>

        <div class="block">
            <InputLabel>Ролі</InputLabel>
            <MultiSelect v-model="item.roles"
                         :options="roles"
                         optionLabel="name"
                         dataKey="id"
                         placeholder="Оберіть одну або більше"
                         class="w-full"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div class="block">
                <InputLabel>Новий пароль</InputLabel>
                <Password v-model="item.password" class="w-full user-password-input"
                          placeholder="Вкажіть новий пароль"></Password>
                <InputErrors :errors="errors.password" v-if="errors.password"/>
            </div>
            <div class="block">
                <InputLabel>Новий пароль</InputLabel>
                <InputText type="password" v-model="item.password_confirmation" class="w-full"
                           placeholder="Підтвердіть новий пароль"/>
                <InputErrors :errors="errors.password_confirmation" v-if="errors.password_confirmation"/>
            </div>
        </div>
    </div>
</template>

<style>
.user-password-input input {
    width: 100%;
}
</style>
