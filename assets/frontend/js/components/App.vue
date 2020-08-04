<template>
  <div id="app-root" class="container">
    <div class="app columns" v-if="$store.getters.isAuthorized">
      <div class="column col-3">
        <ul class="nav">
          <NavLink to="/home">Home</NavLink>
          <NavLink to="/profile">Profile</NavLink>
          <NavLink to="/tokens" v-if="$store.getters.isAdmin">Tokens</NavLink>
          <NavLink to="/logout">Logout</NavLink>
        </ul>
      </div>


      <div class="columns col-9">
        <transition name="fade">
          <router-view></router-view>
        </transition>
      </div>
    </div>
    <div class="app" v-else>
      <transition name="fade">
        <router-view></router-view>
      </transition>
    </div>
  </div>
</template>

<script>
import NavLink from "./NavLink";
import axios from "axios";
import store from "../store";

export default {
  name: 'App',
  components: {NavLink},
  data() {
    return {};
  },
  mounted() {
    if (this.$store.getters.isAuthorized) {
      axios.get('/backend/api/profile').then(response => {
        store.commit('setUser', response.data);
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.app {
  display: flex;
}
</style>