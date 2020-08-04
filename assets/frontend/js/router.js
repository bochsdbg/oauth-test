import Vue from "vue";
import VueRouter from "vue-router";
import AuthPage from "./components/AuthPage";
import LoginPage from "./components/LoginPage";
import ProfilePage from "./components/ProfilePage";
import HomePage from "./components/HomePage";

import store from "./store";
import TokensPage from "./components/TokensPage";

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    base: '/frontend/',
    linkActiveClass: 'active',
    routes: [
        {
            path: '/', beforeEnter: (to, from, next) => {
                if (store.getters.isAuthorized) {
                    next('/home');
                } else {
                    next('/login');
                }

            }
        },
        {path: '/auth', component: AuthPage},
        {path: '/login', component: LoginPage},
        {path: '/profile', component: ProfilePage},
        {path: '/home', component: HomePage},
        {path: '/tokens', component: TokensPage},
        {
            path: '/logout', beforeEnter: (to, from, next) => {
                store.commit('clearTokens');
                next('/login')
            }
        },
    ],
});