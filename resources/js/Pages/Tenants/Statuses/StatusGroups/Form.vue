<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import ColorPicker from 'primevue/colorpicker';

const props = defineProps(['item']);
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <div class="grid grid-cols-12 gap-x-2">
                <InputText type="text" v-model="item.title" class="col-span-11"
                           placeholder="Вкажіть назву"/>
                <ColorPicker v-model="item.hex" format="hex" class="col-span-1 w-full h-full"/>
            </div>
        </div>
    </div>
</template>

<style>
.p-colorpicker-preview{
    width: 100%;
    height: 100%;
}
</style>
