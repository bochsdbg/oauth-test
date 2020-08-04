<template>
  <main class="login-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="text-center">Authentication...</h3>
      </div>

      <div class="card-body">
        <div class="loading loading-lg"></div>
      </div>
    </div>
  </main>
</template>

<script>
import axios from "axios";
import qs from "qs";
import router from "../router";
import store from "../store";

export default {
  name: "AuthPage",
  data() {
    return {};
  },
  beforeCreate() {
    if (store.getters.isPerformingLogin) return;

    store.commit('startLogin');

    const resp = axios.post(store.state.token_url, qs.stringify({
      grant_type: 'authorization_code',
      client_id: store.state.client_id,
      code_verifier: store.state.code_challenge,
      code: this.$route.query.code,
    }), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
    }).then(function (resp) {
      store.commit('setTokens', resp.data);
      router.replace('/home');
      axios.get('/backend/api/profile').then(response => {
        store.commit('setUser', response.data);
      });
    });
  }
}
</script>

<style scoped>

</style>