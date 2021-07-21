<template>
  <CSidebar 
    fixed 
    :minimize="minimize"
    :show="show"
    @update:show="(value) => $store.commit('set', ['rSidebarShow', value])"
  >
    TEST
  </CSidebar>
</template>

<script>
export default {
  name: 'RightSidebar',
  data() {
    return {
      userAuth
    }
  },
  computed: {
    show () {
      return this.$store.state.rSidebarShow 
    },
    minimize () {
      return this.$store.state.rSidebarMinimize 
    }
  }
};
</script>

<style scoped>
/**
 * common
 */


/**
 * Mobile
 */
@media all and (max-width:768px) {

}
/**
 * Tablet & Desktop
 */
@media all and (min-width:768px) {

}
</style>
