<template>
  <CSidebar 
    fixed 
    :minimize="minimize"
    :show="show"
    @update:show="(value) => $store.commit('set', ['sidebarShow', value])"
  >
    <CSidebarBrand class="d-md-down-none" to="/">
      <CImg
        class="c-sidebar-brand-full" 
        src="/images/logo.png"
        block
        :width="256"
        :height="56"
      />
      <!--
      <CImg
        class="c-sidebar-brand-minimized" 
        src="/images/shinhan-m-logo.png"
        block
      />
      -->
    </CSidebarBrand>
    <!--// Profile -->
    <div class="c-sidebar-brand d-md-down-none">
      <div class="profile">
        <div class="img"><img :src="(userAuth.profile_src) ? userAuth.profile_src : '/images/default-avatar.png'"></div>
        <div class="info">
          <div class="name">{{ userAuth.name }}</div>
          <div class="title">AI채용 {{userAuth.name}}</div>
        </div>
      </div>
    </div>
    <!--// Menu -->
    <CRenderFunction flat :content-to-render="$options.nav"/>
    <!--
    <CSidebarMinimizer
      class="d-md-down-none"
      @click.native="$store.commit('set', ['sidebarMinimize', !minimize])"
    />
    -->
  </CSidebar>
</template>

<script>
import nav from './_nav';

export default {
  name: 'Sidebar',
  data() {
    return {
      userAuth
    }
  },
  nav,
  computed: {
    show () {
      return this.$store.state.sidebarShow 
    },
    minimize () {
      return this.$store.state.sidebarMinimize 
    }
  }
};
</script>

<style scoped>
/**
 * common
 */
.c-sidebar-brand {
  background-color: #3C4B64 !important;
}

.profile {
  display: inline-flex;
  width: 100%;
  height: 56px;
  background-color: #333B4F;
}

.profile .img img {
  width: 56px;
  height: 56px;
  background-color:#ebedef;
}

.profile .info div {
  width: 140px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.profile .info .name {
  padding: 8px 0 0 10px;
  font-size: 14px;
}

.profile .info .title {
  padding: 4px 0 0 10px;
  font-size: 12px;
  color: #ebedef;
}


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
