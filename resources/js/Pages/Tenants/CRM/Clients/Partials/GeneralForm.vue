<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Button from "primevue/button";
import InputMask from 'primevue/inputmask';
import Textarea from 'primevue/textarea';

const props = defineProps(['item']);

const addPhone = () => props.item.phones.push('');
const addEmail = () => props.item.emails.push('');
const removePhone = (i) => props.item.phones.splice(i, 1);
const removeEmail = (i) => props.item.emails.splice(i, 1);
</script>

<template>
    <div class="grid grid-cols-1 gap-4">
        <div class="block">
            <InputLabel :required="true">Повне імʼя</InputLabel>
            <InputText v-model="item.full_name"
                       class="w-full"
                       placeholder="Вкажіть повне імʼя клієнта"
                       type="text"
            />
        </div>

        <div class="block">
            <InputLabel :required="true">Телефон</InputLabel>
            <div class="flex gap-2"
                 :class="{'mb-2':item.phones.length > 1}"
                 v-for="(phone,i) in item.phones"
            >
                <InputMask
                    v-model="item.phones[i]"
                    class="w-full"
                    placeholder="+38 ("
                    mask="+38 (999) 999-99-99"
                    type="tel"
                />
                <template v-if="i === 0">
                    <Button icon="pi pi-plus"
                            type="button"
                            size="small"
                            @click="addPhone"

                    />
                </template>
                <template v-if="i !== 0">
                    <Button icon="pi pi-trash"
                            type="button"
                            size="small"
                            @click="removePhone(i)"
                            severity="secondary"
                    />
                </template>
            </div>
        </div>

        <div class="block">
            <InputLabel>Email</InputLabel>
            <div class="flex gap-2"
                 :class="{'mb-2':item.emails.length > 1}"
                 v-for="(email, i) in item.emails"
            >
                <InputText
                    v-model="item.emails[i]"
                    class="w-full"
                    placeholder="example@domain.com"
                    type="email"
                />
                <template v-if="i === 0">
                    <Button
                        icon="pi pi-plus"
                        type="button"
                        size="small"
                        @click="addEmail"
                    />
                </template>
                <template v-else>
                    <Button
                        icon="pi pi-trash"
                        type="button"
                        size="small"
                        @click="removeEmail(i)"
                        severity="secondary"
                    />
                </template>
            </div>
        </div>
        <div class="block">
            <InputLabel>Коментар</InputLabel>
            <Textarea v-model="item.comment"
                      class="w-full"
                      placeholder="Вкажіть коментарі про клієнта"
                      type="text"
                      rows="6"
            />
        </div>
    </div>
</template>
