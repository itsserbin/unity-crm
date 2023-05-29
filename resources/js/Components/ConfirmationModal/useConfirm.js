import {createApp, defineAsyncComponent} from 'vue';
import PrimeVue from 'primevue/config';

const ConfirmModal = defineAsyncComponent(() => import('@/Components/ConfirmationModal/ConfirmationModal.vue'));

export function useConfirm({message, header, icon, accept, reject}) {
    return new Promise((resolve) => {
        createApp(ConfirmModal, {
            message,
            header,
            icon,
            accept,
            reject,
            resolve,
        })
            .use(PrimeVue)
            .mount(document.body.appendChild(document.createElement('div')));
    });
}
