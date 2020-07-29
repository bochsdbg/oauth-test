import '../scss/app.scss';

import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './components/App';
import RegisterPage from "./components/RegisterPage";
import LoginPage from "./components/LoginPage";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {path: '/login', component: LoginPage},
        {path: '/register', component: RegisterPage},
    ],
});

new Vue({
    router,
    el: '#app',
    render: h => h(App)
});
