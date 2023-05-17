import {createApp} from 'vue';
import CreateModal from '@/Components/Modal/CreateModal.vue';
import PrimeVue from 'primevue/config';

export function useModal({size, head, body, footer, reject, confirm}) {
    return new Promise((resolve) => {
        createApp(CreateModal, {
            size,
            head,
            body,
            footer,
            reject,
            confirm,
            resolve,
        })
            .use(PrimeVue)
            .mount(document.body.appendChild(document.createElement('div')));
    });
}
