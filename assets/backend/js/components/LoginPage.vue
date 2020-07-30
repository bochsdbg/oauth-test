<template>
    <main class="login-wrapper">
        <div class="card">
            <div class="card-header">
                <h3>Login</h3>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="login[username]">Username</label>
                        <input class="form-input" id="login[username]" type="text" v-model="$store.state.user.username">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="login[password]">Password</label>
                        <input class="form-input" id="login[password]" type="password" v-model="password">
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