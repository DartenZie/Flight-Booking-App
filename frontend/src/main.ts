import './assets/main.css';
import 'flag-icons/css/flag-icons.css';

import { createApp } from 'vue';
import {createPinia} from 'pinia';

import * as ConfirmDialog from 'vuejs-confirm-dialog';
import App from './App.vue';
import router from './router';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import floatingUIDirective from './directives/v-floating-ui-trigger';

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(ConfirmDialog);
app.use(pinia);

app.component('date-picker', VueDatePicker);

// Register directives
app.directive('floating-ui-trigger', floatingUIDirective);

app.mount('#app');
