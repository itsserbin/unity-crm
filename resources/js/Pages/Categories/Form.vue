<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import FileUpload from 'primevue/fileupload';

defineProps(['item']);

const onUpload = (e) => {
    console.log(e);
}
</script>

<template>
    <div class="grid gap-4">
        <div class="grid grid-cols-1 md grid-cols-12 gap-4">
            <div class="md:col-span-8">
                <InputLabel :required="true">Назва</InputLabel>
                <InputText type="text" v-model="item.title" class="w-full"
                           placeholder="Вкажіть назву категорії"/>
            </div>
            <div class="md:col-span-4">
                <InputLabel>Зображення</InputLabel>
                <FileUpload mode="basic"
                            accept="image/*"
                            :maxFileSize="1000000"
                            @upload="onUpload($event)"
                />
            </div>
        </div>

        <div class="block">
            <InputLabel>Опис</InputLabel>
            <Textarea v-model="item.description" rows="5" class="w-full"
                      placeholder="Вкажіть опис категорії"/>
        </div>
    </div>
</template>
