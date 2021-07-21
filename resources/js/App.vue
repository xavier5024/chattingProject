<template>
  <router-view></router-view>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="scss">
// Import Main styles for this application
@import 'assets/scss/style';

/* Router Transitions */
.page-enter-active, .page-leave-active {
  transition: opacity .2s ease;
}
.page-enter, .page-leave-to {
  opacity: 0;
}

::selection {
  background-color: #608BCB;
  color: #FFF;
}
</style>
