require('./bootstrap');
import Vue from 'vue';
import App from './components/app'
require('./bootstrap');
window.Vue = require('vue');
import vuetify from './vuetify';

import { library } from '@fortawesome/fontawesome-svg-core'

import { faHatWizard } from '@fortawesome/free-solid-svg-icons'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


library.add(faHatWizard)
Vue.component('font-awesome-icon', FontAwesomeIcon)

const app = new Vue({
    el:'#app',
    vuetify,
    components:{App}
});
