import {createApp} from 'vue';
import ConfirmModal from '@/Components/ConfirmationModal/ConfirmationModal.vue';
import PrimeVue from 'primevue/config';

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
