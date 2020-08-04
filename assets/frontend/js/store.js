import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        refresh_token: null,
        access_token: null,
        access_token_expires: null,
        client_id: null,
        code_challenge: null,
        auth_url: null,
        token_url: null,
        performingLogin: false,
        user: {
            id: null,
            roles: [],
            created_at: null,
            updated_at: null,
            username: null,
        },
    },
    getters: {
        isAuthorized(state) {
            return state.access_token
                && state.access_token_expires
                && state.access_token_expires.getTime() > (new Date()).getTime();
        },

        isPerformingLogin(state) {
            return state.performingLogin;
        },

        isAdmin(state) {
            return state.user && state.user.roles && state.user.roles.indexOf('ROLE_ADMIN') !== -1;
        }
    },
    mutations: {
        generateCodeChallenge(state) {
            const bytes = new Uint8Array(36);
            window.crypto.getRandomValues(bytes);
            state.code_challenge = btoa(String.fromCharCode.apply(null, bytes)).replace(/\+/g, '-').replace(/\//g, '_');
            localStorage.setItem('code_challenge', state.code_challenge);
        },

        setTokens(state, response) {
            state.refresh_token = response.refresh_token;
            localStorage.setItem('refresh_token', state.refresh_token);
            state.access_token = response.access_token;
            localStorage.setItem('access_token', state.access_token);
            state.access_token_expires = new Date(Date.now() + response.expires_in * 1000);
            localStorage.setItem('access_token_expires', state.access_token_expires.getTime());
            state.performingLogin = false;
        },

        setUser(state, user) {
            state.user.id = user.id;
            state.user.username = user.username;
            state.user.roles = user.roles;
            state.user.created_at = user.created_at;
            state.user.updated_at = user.updated_at;
            console.log('User updated: ', state.user);
        },

        startLogin(state) {
            state.performingLogin = true;
        },

        clearTokens(state) {
            state.refresh_token = state.access_token = state.access_token_expires = null;
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('access_token');
            localStorage.removeItem('access_token_expires');
        },

        init(state) {
            state.client_id = window.clientGlobals.oauth_client_id || localStorage.getItem('oauth_client_id');
            state.auth_url = window.clientGlobals.oauth_auth_url || localStorage.getItem('oauth_auth_url');
            state.token_url = window.clientGlobals.oauth_token_url || localStorage.getItem('oauth_token_url');
            state.code_challenge = localStorage.getItem('code_challenge');
            state.refresh_token = localStorage.getItem('code_challenge');
            state.access_token = localStorage.getItem('access_token');
            state.access_token_expires = new Date(+localStorage.getItem('access_token_expires'));
        },
    },
});