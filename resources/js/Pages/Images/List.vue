<script setup>
import Button from "primevue/button";
import Heading from "@/Components/Heading.vue";
import Toolbar from "primevue/toolbar";
import FileUpload from 'primevue/fileupload';
import Loader from "@/Components/Loader.vue";

import ImagesRepository from "@/Repositories/ImagesRepository.js";
import {toast} from 'vue3-toastify';
import {ref, onMounted, reactive, defineAsyncComponent} from 'vue';
import {useConfirm} from "@/Components/ConfirmationModal/useConfirm.js";
import UploadRepository from "@/Repositories/UploadRepository.js";

const Modal = defineAsyncComponent(() => import('./Modal.vue'))

const props = defineProps({
    images: {
        type: Object,
        required: false
    },
    selectable: {
        type: Boolean,
        required: false,
        default: false
    },
    selectableMode: {
        type: String,
        required: false,
        default: 'single'
    },
    selected: {
        type: Array || Object,
        required: false,
        default: {}
    }
});
const emits = defineEmits([
    'upload',
    'selected'
]);

const state = reactive({
    isLoading: false,
    isShowModal: false,
    isLoadingModal: false,
    isLoadingRefreshButton: false,
    isLoadingFiles: false,
    data: {},
    search: null
});

const item = ref();

onMounted(async () => {
    if (props.images) {
        state.data = props.images;
    } else {
        await fetch();
    }
});

const fetch = async () => {
    switchLoader();
    try {
        const data = await ImagesRepository.fetch();
        state.data = data.success ? data.result : [];
    } catch (e) {
        console.error(e);
        toast.error("Failed to fetch data");
    }
    switchLoader();
}

const switchLoader = (val) => val ? state.isLoading = val : state.isLoading = !state.isLoading;
const switchLoaderRefreshButton = (val) => val ? state.isLoadingRefreshButton = val : state.isLoadingRefreshButton = !state.isLoadingRefreshButton;

const onSubmit = async () => {
    state.isLoadingModal = true;
    try {

        item.value.id
            ? await ImagesRepository.update(item.value)
            : await ImagesRepository.create(item.value);

        await fetch();
        toast.success("Success");
    } catch (e) {
        console.error(e);
        toast.error("Error");
    }
    state.isLoadingModal = false;
}
const onDestroy = async (id) => {
    await useConfirm({
        message: 'Ви точно бажаєте видалити цей запис?',
        header: 'Підтвердження дії',
        icon: 'pi pi-exclamation-triangle',
        accept: async () => {
            try {
                await ImagesRepository.destroy(id);
                await fetch();
                toast.success('Запис успішно видалено');
            } catch (error) {
                console.error(error);
                toast.error('Виникла помилка');
            }
        }
    });
}

const refreshData = async () => {
    switchLoaderRefreshButton();
    await fetch();
    switchLoaderRefreshButton();
}

const uploadImages = async (e) => {
    state.isLoadingFiles = true;
    for (const image of e.files) {
        if (image) {
            try {
                let formData = new FormData();
                formData.append('image', image);
                const data = await UploadRepository.uploadImage(formData);
                if (data.success) {
                    await fetch();
                    emits('upload');
                }
            } catch (error) {
                console.error(error);
            }
        }
    }
    state.isLoadingFiles = false;
};

const selected = ref({});

const updateSelected = () => {
    emits('selected', selected.value);
}
</script>

<template>
    <div class="card">
        <Toolbar class="mb-4">
            <template #start>
                <div class="flex gap-2 items-center">
                    <Button icon="pi pi-refresh"
                            type="button"
                            size="small"
                            @click="refreshData"
                            :loading="state.isLoadingRefreshButton"
                    />
                    <Heading>Зображення</Heading>
                </div>
            </template>
            <template #end>
                <Loader v-if="state.isLoadingFiles"/>
                <FileUpload v-if="!state.isLoadingFiles"
                            mode="basic"
                            @select="uploadImages"
                            chooseLabel="Додати"
                            icon="pi pi-plus"
                            class="mr-2"
                            multiple
                />
            </template>
        </Toolbar>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            <a href="javascript:" v-for="item in state.data.data" class="relative">
                <Button icon="pi pi-times" link class="absolute right-0" @click="onDestroy(item.id)"/>
                <div v-if="selectable">
                    <input :type="selectableMode !== 'single' ? 'checkbox' : 'radio'"
                           name="images"
                           v-model="selected"
                           :value="item"
                           :id="item.id"
                           class="hidden peer"
                           @change="updateSelected"
                    >
                    <label
                        :for="item.id"
                        class="
                                inline-flex
                                justify-between
                                items-center
                                p-0.5
                                w-full
                                bg-white
                                rounded-lg
                                border-2
                                border-zinc-200
                                cursor-pointer
                                dark:border-zinc-700
                                peer-checked:border-blue-600
                                hover:bg-zinc-50
                                dark:bg-zinc-800
                                dark:hover:bg-zinc-900
                            "
                    >
                        <picture>
                            <source :srcset="route('images',item.data.webp)"
                                    type="image/webp">

                            <img :src="route('images',item.data.jpeg)"
                                 :alt="item.data.alt"
                                 class="object-cover"
                                 loading="lazy"
                            >
                        </picture>
                    </label>
                </div>
                <picture v-else>
                    <source :srcset="route('images',item.data.webp)"
                            type="image/webp">

                    <img :src="route('images',item.data.jpeg)"
                         :alt="item.data.alt"
                         class="object-cover"
                         loading="lazy"
                    >
                </picture>
            </a>
        </div>
    </div>
</template>
