import axios from "axios";
import store from "./store";
import router from './router';

import qs from "qs";

axios.interceptors.request.use(config => {
    const token = store.state.access_token;
    // config.baseURL = '/backend';
    if (token) {
        config.headers['Authorization'] = 'Bearer ' + token;
    }
    // config.headers['Content-Type'] = 'application/json';
    return config;
});

axios.interceptors.response.use(response => {
    return response;
}, function (error) {
    const originalRequest = error.config;

    if ((error.response.status === 401 || error.response.status === 400) && originalRequest.url.endsWith(store.state.token_url)) {
        store.commit('clearTokens');
        router.replace('/login');
        return Promise.reject(error);
    }

    if (error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;
        const refreshToken = store.state.refresh_token;
        return axios.post(store.state.token_url,
            qs.stringify({
                refresh_token: refreshToken,
                grant_type: 'refresh_token',
                client_id: store.state.client_id,
            }), {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(res => {
                if (res.status === 201) {
                    store.commit('setTokens', res.data);
                    // axios.defaults.headers['Authorization'] = 'Bearer ' + store.state.access_token;
                    // return axios(originalRequest);
                }
            })
    }
    return Promise.reject(error);
});

export default axios;
