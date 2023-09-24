<script setup>
import {defineAsyncComponent, onMounted, ref} from 'vue';
import Button from 'primevue/button';

const Modal = defineAsyncComponent(() => import('@/Components/Modal/Modal.vue'))

const props = defineProps({
    showReject: {
        type: Boolean,
        default: true
    },
    maxWidth: {
        type: String,
        default: 'sm'
    },
    showConfirm: {
        type: Boolean,
        default: true
    },
    textRejectButton: {
        type: String,
        default: 'Ні'
    },
    textConfirmButton: {
        type: String,
        default: 'Так'
    },
    message: String,
    header: String,
    icon: String,
    accept: Function,
    reject: Function,
    resolve: Function,
});

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
    <Modal :show="show" :maxWidth="maxWidth" @close="handleClose">
        <template #head v-if="header">
            {{ header }}
        </template>
        <template #body>
            <div class="flex items-center gap-x-4 text-base">
                <i v-if="icon" style="font-size: 2.5rem" :class="icon"></i>
                {{ message }}
            </div>
        </template>
        <template #footer>
            <div class="grid grid-cols-2 gap-x-3">
                <Button v-if="showReject"
                        :label="textRejectButton"
                        icon="pi pi-times"
                        text
                        @click="handleClose"
                />
                <Button v-if="showConfirm"
                        :label="textConfirmButton"
                        icon="pi pi-check"
                        autofocus
                        @click="handleAccept"
                />
            </div>
        </template>
    </Modal>
</template>
