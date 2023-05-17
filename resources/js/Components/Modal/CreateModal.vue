<script setup>
import {onMounted, ref} from 'vue';
import Modal from '@/Components/Modal/Modal.vue';

const props = defineProps([
    'head',
    'body',
    'footer',
    'reject',
    'confirm',
    'resolve',
    'size',
]);

const show = ref(false);

onMounted(() => show.value = true);

const handleClose = () => {
    show.value = false;
    if (typeof props.reject === 'function') {
        props.reject();
    }
};

const handleAccept = () => {
    show.value = false;
    if (typeof props.accept === 'function') {
        props.accept();
    }
    props.resolve();
};
</script>

<template>
    <Modal :show="show" :maxWidth="size" @close="handleClose">
        <template #head v-if="head">
            <component v-if="head.component" :is="head.component" v-bind="head.props"/>
            <div v-else>{{ head }}</div>
        </template>
        <template #body v-if="body">
            <component v-if="body.component" :is="body.component" v-bind="body.props"/>
            <div v-else>{{ body }}</div>
        </template>
        <template #footer v-if="footer">
            <component v-if="footer.component" :is="footer.component" v-bind="footer.props"/>
            <div v-else>{{ footer }}</div>
        </template>
    </Modal>
</template>
