import './Includes/bootstrap.js';
import './Includes/theme.js';
import filters from './Includes/filters.js';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

import PrimeVue from 'primevue/config';
import Vue3Toastify from 'vue3-toastify';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(Vue3Toastify, {
                autoClose: 3000, pauseOnHover: false, pauseOnFocusLoss: false
            })
            .use(PrimeVue);

        app.config.globalProperties.$filters = filters;

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).then(r => {
});
