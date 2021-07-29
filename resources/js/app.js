import 'core-js/stable';
import Vue from 'vue';
import App from './App.vue';
import { router } from './router';
import CoreuiVue from '@coreui/vue'
import VueCompositionApi from '@vue/composition-api';
import VueMeta from 'vue-meta';
import { iconsSet as icons } from './assets/icons/icons.js';
import store from './store';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';
import * as VueMenu from '@hscmap/vue-menu'
import '@progress/kendo-ui'
import '@progress/kendo-theme-default/dist/all.css'

import { Chat, ChatInstaller } from '@progress/kendo-chat-vue-wrapper'


import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

let echo_config = {
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster:process.env.MIX_PUSHER_APP_CLUSTER,
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false
};

if(process.env.MIX_PUSHER_SSL == "true"){
  echo_config.wssPort = 6001;  // https 시 추가
  echo_config.encrypted = true;  // ssl사용 시 encrypted: true 설정
  echo_config.enabledTransports = ['ws', 'wss'];
}

window.Echo = new Echo(echo_config);

Vue.use(ChatInstaller)


Vue.config.devtools = true;
Vue.config.performance = true;
Vue.config.productionTip = true;

Vue.use(CoreuiVue);
Vue.use(VueCompositionApi);
Vue.use(VueMeta);
Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(VueMenu)

new Vue({
  el: '#app',
  render: h => h(App),
  router,
  icons,
  store,
  template: '<App/>',
  components: {
    App
  }
});