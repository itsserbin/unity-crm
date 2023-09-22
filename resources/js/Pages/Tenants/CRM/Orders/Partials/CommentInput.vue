<script setup>
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps(['item']);
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="block">
            <InputLabel>Коментар клієнта</InputLabel>
            <Textarea v-model="item.client_comment" class="w-full" rows="6"
                      placeholder="Введіть коментар"/>
        </div>
        <div class="block">
            <InputLabel>Коментар менеджера</InputLabel>
            <Textarea v-model="item.manager_comment" class="w-full" rows="6"
                      placeholder="Введіть коментар"/>
        </div>
    </div>
</template>
