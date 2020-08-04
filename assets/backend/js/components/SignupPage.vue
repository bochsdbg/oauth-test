<template>
  <main class="login-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="text-center">
          <router-link class="btn float-left" to="/login"><i class="icon icon-back"></i></router-link>
          Signup
        </h3>
      </div>

      <form>
        <div class="card-body">
          <div class="form-group" v-bind:class="{ 'has-error': errors.username }">
            <label class="form-label" for="signup[username]">Username</label>
            <input class="form-input" id="signup[username]" type="text"
                   v-model="$store.state.user.username">
            <p v-if="errors.username" class="form-input-hint">{{ errors.username }}</p>
          </div>

          <div class="form-group" v-bind:class="{ 'has-error': errors.password }">
            <label class="form-label" for="signup[password]">Password</label>
            <input class="form-input" id="signup[password]" type="password" v-model="password">
            <p v-if="errors.password" class="form-input-hint">{{ errors.password }}</p>
          </div>

          <div class="form-group" v-bind:class="{ 'has-error': errors.password_repeat }">
            <label class="form-label" for="signup[password_repeat]">Repeat Password</label>
            <input class="form-input" id="signup[password_repeat]" type="password"
                   v-model="password_repeat">
            <p v-if="errors.password_repeat" class="form-input-hint">{{ errors.password_repeat }}</p>
          </div>
        </div>


        <div class="card-footer">
          <div class="btn-group btn-group-block">
            <router-link class="btn" to="/login">Back to Login</router-link>
            <button type="submit" class="btn btn-primary" v-on:click="signup"
                    :disabled="!!(errors.username||errors.password||errors.password_repeat)">Register
            </button>
          </div>
        </div>
      </form>
    </div>

    <div class="modal" v-bind:class="{active: this.isSignupFinished}">
      <a href="#close" class="modal-overlay" aria-label="Close" v-on:click="closeModal"></a>
      <div class="modal-container">
        <div class="modal-header">
          <a href="#close" class="btn btn-clear float-right" aria-label="Close" v-on:click="closeModal"></a>
          <div class="modal-title h5">User created</div>
        </div>
        <div class="modal-body">
          <div class="content">
            User was created successfully. Now you can to log in
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import axios from "axios";

export default {
  name: "RegisterPage",
  data() {
    return {
      isSignupFinished: false,
      password: null,
      password_repeat: null,
      errors: {
        username: null,
        password: null,
        password_repeat: null,
      },
    };
  },
  watch: {
    username() {
      this.validate();
    },
    password() {
      this.validate();
    },
    password_repeat() {
      this.validate();
    },
  },
  methods: {
    closeModal() {
      this.isSignupFinished = false;
      this.$router.push('/login');
    },

    validate() {
      if (this.password !== this.password_repeat) {
        this.errors.password_repeat = this.errors.password = 'Passwords should match';
        return false;
      }
      this.errors.username = this.errors.password = this.errors.password_repeat = null;
      return true;
    },

    signup(e) {
      e.preventDefault();
      if (this.validate()) {
        axios.post('/auth/signup',
            {username: this.$store.state.user.username, password: this.password})
            .then(resp => {
              if (resp.data.result === 'ok') {
                this.isSignupFinished = true;
              } else {
                for (const k in resp.data.errors) {
                  this.errors[k] = resp.data.errors[k];
                }
              }
            });
      }
    }
  }

}
</script>

<style scoped>

</style>