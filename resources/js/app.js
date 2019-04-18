require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from './routes.js';
import MainApp from './MainApp.vue';
import Vuesax from 'vuesax'
import 'vuesax/dist/vuesax.css'
import VueTabs from 'vue-nav-tabs'
import 'vue-nav-tabs/themes/vue-tabs.css'
import 'material-icons/iconfont/material-icons.css';
import BootstrapVue from 'bootstrap-vue';

//custom components
import PaginationComponent from './page/PaginationCustom';
import SpinnerLoading from './page/SpinnerLoading';

import Vuex from 'vuex';
import {initialize} from './helpers/general';
import StoreData from './store';

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Vuesax);
Vue.use(VueTabs);
Vue.use(BootstrapVue);

Vue.component('pagination', PaginationComponent);
Vue.component('spinner-loading', SpinnerLoading);

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components:{
        MainApp
    }
});
