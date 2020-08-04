import '../scss/app.scss';

import store from './store';
import Vue from 'vue';

import App from './components/App';

import router from './router';

store.commit('init');

new Vue({
    router,
    store,
    el: '#app',
    render: h => h(App),
    beforeCreate() {
        store.commit('init');
    },
});
