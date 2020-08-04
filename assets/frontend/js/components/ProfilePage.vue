<template>
  <main>
    <h1>Profile Page</h1>
    <p>User #{{ this.user.id }}</p>
    <p>Username: {{ this.user.username }}</p>
    <p>Created at: {{ new Date(this.user.created_at * 1000) }}</p>
    <p>Roles: {{ this.user.roles }}</p>
  </main>
</template>

<script>
import store from "../store";

export default {
  name: "ProfilePage",
  data() {
    return {
      user: store.state.user,
    };
  },
  beforeCreate() {

  }
}
</script>

<style scoped>

</style>