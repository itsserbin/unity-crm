<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps(['item', 'isLoadingModal']);
const emits = defineEmits(['submit', 'close']);
</script>

<template>
    <div class="block">
        <InputLabel :required="true">Назва</InputLabel>
        <InputText type="text"
                   v-model="item.title"
                   class="w-full"
                   placeholder="Вкажіть назву рахунку"
        />
    </div>
</template>
