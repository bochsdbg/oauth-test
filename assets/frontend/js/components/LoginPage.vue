<template>
  <main class="login-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="text-center">Login</h3>
      </div>

      <div class="card-body">
        <button class="btn btn-primary btn-block" v-on:click="auth">Authorization</button>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  name: "LoginPage",
  data() {
    return {};
  },
  methods: {
    auth: function (e) {
      this.$store.commit('generateCodeChallenge');
      const url = this.$store.state.auth_url
          + '?response_type=code&client_id=' + this.$store.state.client_id
          + '&scope=read&code_challenge=' + this.$store.state.code_challenge
          + '&code_challenge_method=plain';
      window.location.href = url;
    }
  }
}
</script>

<style scoped>

</style>