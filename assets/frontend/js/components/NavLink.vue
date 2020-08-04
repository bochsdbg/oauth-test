<template>
  <router-link :to="to" v-slot="{ href, route, navigate, isActive, isExactActive, linkActiveClass }">
    <li :class="['nav-item', isActive && 'active', isExactActive && 'router-link-exact-active']">
      <a :href="href" @click="navigate">
        <slot></slot>
      </a>
    </li>
  </router-link>
</template>

<script>
export default {
  props: ['to'],
}
</script>