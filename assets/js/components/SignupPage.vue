<template>
    <main class="login-wrapper">
        <div class="card">
            <div class="card-header">
                <h3>
                    <router-link class="btn" to="/login"><i class="icon icon-back"></i></router-link>
                    Signup
                </h3>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="signup[username]">Username</label>
                        <input class="form-input" id="signup[username]" type="text"
                               v-model="$store.state.user.username">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="signup[password]">Password</label>
                        <input class="form-input" id="signup[password]" type="password" v-model="password">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="signup[password_repeat]">Repeat Password</label>
                        <input class="form-input" id="signup[password_repeat]" type="password"
                               v-model="password_repeat">
                    </div>
                </div>


                <div class="card-footer">
                    <div class="btn-group btn-group-block">
                        <router-link class="btn" to="/login">Back to Login</router-link>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</template>

<script>
    export default {
        name: "RegisterPage",
        data() {
            return {
                password: null,
                password_repeat: null,
            };
        }
    }
</script>

<style scoped>

</style>