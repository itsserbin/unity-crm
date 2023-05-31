<script setup>
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps(['item']);
</script>

<template>
    <InputLabel>Коментар до замовлення</InputLabel>
    <Textarea v-model="item.comment" class="w-full" rows="6"
              placeholder="Вкажіть коментар"/>

    <InputLabel>Знижка на замовлення</InputLabel>
    <TextInput v-model="item.discount" class="w-full" type="number"
               placeholder="Сума в грн"/>
</template>
