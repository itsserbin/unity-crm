import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';

if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    import('primevue/resources/themes/vela-blue/theme.css');
} else {
    import('primevue/resources/themes/viva-light/theme.css');
}

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

import PrimeVue from 'primevue/config';
import numeral from "numeral";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue);

        app.config.globalProperties.$filters = {
            formatMoney(value) {
                return numeral(value).format('0,0[.]00');
            },
        };

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    }
    ,
});
