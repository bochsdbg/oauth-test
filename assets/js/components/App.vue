<template>
    <div id="app-root">
        <ul>
            <li>
                <router-link to="/login">Login</router-link>
            </li>
            <li>
                <router-link to="/register">Register</router-link>
            </li>
        </ul>

        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        data() {
            return {};
        },
    };
</script>

<style>
    .center {
        text-align: center;
    }
</style>