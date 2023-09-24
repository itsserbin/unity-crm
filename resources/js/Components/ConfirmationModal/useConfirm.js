import {createApp, defineAsyncComponent} from 'vue';
import PrimeVue from 'primevue/config';

const ConfirmModal = defineAsyncComponent(() => import('@/Components/ConfirmationModal/ConfirmationModal.vue'));

export function useConfirm({
                               showReject,
                               showConfirm,
                               textRejectButton,
                               textConfirmButton,
                               message,
                               header,
                               icon,
                               accept,
                               reject,
                               maxWidth
                           }) {
    return new Promise((resolve) => {
        createApp(ConfirmModal, {
            showReject,
            showConfirm,
            textRejectButton,
            textConfirmButton,
            message,
            header,
            icon,
            accept,
            reject,
            resolve,
            maxWidth,
        })
            .use(PrimeVue)
            .mount(document.body.appendChild(document.createElement('div')));
    });
}
