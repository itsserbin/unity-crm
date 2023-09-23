<script setup>
defineProps(['errors']);
</script>

<template>
    <small v-if="errors" class="p-error" v-for="error in errors">
        {{ error }}&nbsp;
    </small>
</template>
