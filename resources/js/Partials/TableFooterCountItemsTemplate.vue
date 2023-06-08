<script setup>
defineProps(['from', 'to', 'total'])
</script>

<template>
    <div class="text-end">
        Показано з {{ from }} по {{ to }} із {{ total }} записів
    </div>
</template>
