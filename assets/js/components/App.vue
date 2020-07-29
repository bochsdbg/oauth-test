<template>
    <div id="app-root">
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {
                    username: null,
                },
            };
        },
    };
</script>

<style scoped>

</style>