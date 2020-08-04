<template>
    <main class="login-wrapper">
        <div class="card" v-if="!$store.state.user.id">
          <div class="card-header">
            <h3 class="text-center">Login</h3>
          </div>

          <form action="/backend/auth" method="post">
            <div class="card-body">
              <div class="form-group" v-bind:class="{ 'has-error': $store.state.auth_error }">
                <label class="form-label" for="login[username]">Username</label>
                <input class="form-input" id="login[username]" name="form[username]" type="text"
                       v-model="$store.state.user.username">
                <p v-if="$store.state.auth_error" class="form-input-hint">{{ $store.state.auth_error }}</p>
              </div>

              <div class="form-group">
                <label class="form-label" for="login[password]">Password</label>
                <input class="form-input" id="login[password]" name="form[password]" type="password" v-model="password">
                    </div>
            </div>


            <div class="card-footer">
              <div class="btn-group btn-group-block">
                <router-link class="btn" to="/signup">Create New Account</router-link>
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </div>
          </form>
        </div>
      <div class="card" v-else>
        <div class="card-header">
          <h3 class="text-center">You are authenticated as {{ $store.state.user.id }}</h3>
        </div>
        <form action="/backend/logout">
          <div class="card-body">
            <button type="submit">Logout</button>
          </div>
        </form>
      </div>
    </main>
</template>

<script>
    export default {
        name: "LoginPage",
        data() {
            return {
                password: null,
            };
        }
    }
</script>

<style scoped>

</style>