import '../scss/app.scss';

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';

import App from './components/App';
import SignupPage from "./components/SignupPage";
import LoginPage from "./components/LoginPage";

Vue.use(Vuex);
Vue.use(VueRouter);

const store = new Vuex.Store({
    state: {
        user: {
            id: null,
            username: 'aaa',
        },
    }
});

const router = new VueRouter({
    mode: 'history',
    base: '/backend/',
    routes: [
        {path: '/', redirect: '/login'},
        {path: '/login', component: LoginPage},
        {path: '/signup', component: SignupPage},
    ],
});

new Vue({
    router,
    store,
    el: '#app',
    render: h => h(App)
});
