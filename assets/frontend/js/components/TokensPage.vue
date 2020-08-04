<template>
  <main>
    <h1>Tokens Page</h1>
    <div class="form-group">
      <label class="form-switch">
        <input v-model="show_revoked_tokens" type="checkbox">
        <i class="form-icon"></i> Show Revoked Tokens
      </label>
    </div>


    <table class="table table-striped table-hover">
      <thead>
      <tr>
        <th colspan="3">Refresh Token</th>
        <th colspan="4">Access Token</th>
      </tr>
      <tr>
        <th>Id</th>
        <th>Expiration</th>
        <th>Is Revoked</th>
        <th>Id</th>
        <th>User</th>
        <th>Expiration</th>
        <th>Is Revoked</th>
      </tr>
      </thead>
      <tr v-for="tok in tokens">
        <td><span v-if="tok.identifier">{{ tok.identifier.slice(1, 12) }}...</span></td>
        <td><span v-if="tok.expiry">{{ new Date(tok.expiry.date).toLocaleString() }}</span></td>
        <td>{{ tok.revoked }}
          <button class="btn btn-link" v-on:click="revokeRefreshToken(tok)" title="Revoke this token">Revoke</button>
        </td>
        <td><span v-if="tok.accessToken && tok.accessToken.identifier">{{
            tok.accessToken.identifier.slice(1, 12)
          }}...</span></td>
        <td><span v-if="tok.accessToken && tok.accessToken.userIdentifier">{{ tok.accessToken.userIdentifier }} <button
            class="btn btn-primary" v-on:click="revokeAllTokensForUser(tok.accessToken.userIdentifier)"
            title="Revoke all tokens related to user">Revoke</button></span></td>
        <td><span v-if="tok.accessToken">{{ new Date(tok.accessToken.expiry.date).toLocaleString() }}</span></td>
        <td><span v-if="tok.accessToken">{{ tok.accessToken.revoked }} <button class="btn btn-link"
                                                                               v-on:click="revokeAccessToken(tok.accessToken)"
                                                                               title="Revoke this token">Revoke</button></span>
        </td>
      </tr>
    </table>
  </main>
</template>

<script>
import axios from 'axios';

export default {
  name: "ProfilePage",
  data() {
    return {
      user: this.$store.state.user,
      tokens: [],
      show_revoked_tokens: false,
    }
  },
  watch: {
    show_revoked_tokens: function () {
      this.updateTokens()
    },
  },
  mounted() {
    this.updateTokens();
  },
  methods: {
    updateTokens() {
      axios.get('/backend/api/tokens?show_revoked=' + (+this.show_revoked_tokens)).then(resp => {
        this.tokens = resp.data;
      });
    },
    revokeRefreshToken(token) {
      axios.get('/backend/api/revoke_refresh_token/' + token.identifier).then(resp => token.revoked = true);
    },
    revokeAllTokensForUser(username) {
      axios.get('/backend/api/revoke_user_tokens/' + username).then(resp => this.updateTokens());
    },
    revokeAccessToken(token) {
      axios.get('/backend/api/revoke_access_token/' + token.identifier).then(resp => token.revoked = true);
    },
  }
}
</script>

<style scoped>

</style>