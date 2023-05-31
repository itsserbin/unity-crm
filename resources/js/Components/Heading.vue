<script setup>
import {computed} from "vue";

const props = defineProps({
    size: {
        required: false,
        default: 'xl'
    }
});

const sizeClass = computed(() => {
    return {
        sm: 'text-sm',
        md: 'text-base',
        lg: 'text-lg',
        xl: 'text-xl',
        '2xl': 'text-2xl',
        '3xl': 'text-3xl',
        '4xl': 'text-4xl',
        '5xl': 'text-5xl',
    }[props.size];
});
</script>
<template>
    <h2 class="font-semibold text-zinc-800 dark:text-zinc-200 leading-tight" :class="sizeClass">
        <slot></slot>
    </h2>
</template>
