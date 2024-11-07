import './assets/main.css';
import 'flag-icons/css/flag-icons.css';

import { createApp } from 'vue';
import * as ConfirmDialog from 'vuejs-confirm-dialog';
import App from './App.vue';
import router from './router';

import floatingUIDirective from './directives/v-floating-ui-trigger';

const app = createApp(App);

app.use(router);
app.use(ConfirmDialog);

// Register directives
app.directive('floating-ui-trigger', floatingUIDirective);

app.mount('#app');
