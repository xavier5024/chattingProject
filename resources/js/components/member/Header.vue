<template>
  <CHeader fixed with-subheader light>
    <CToggler
      in-header
      class="ml-3 d-lg-none"
      @click="$store.commit('toggleSidebarMobile')"
    />
    <CToggler
      in-header
      class="ml-3 d-md-down-none"
      @click="$store.commit('toggleSidebarDesktop')"
    />
    <!--// Mobile Logo -->
    <CHeaderBrand class="mx-auto d-lg-none" to="/">
      <CImg
        class="c-sidebar-brand-full" 
        src="/images/winsidaero-logo.png"
        block
        :width="200"
      />
    </CHeaderBrand>
    
    <CHeaderNav class="d-md-down-none mr-auto">
      <CHeaderNavItem class="px-3">
      </CHeaderNavItem>
    </CHeaderNav>
    <CHeaderNav class="mr-4">
      <HeaderDropdownMenu/>
    </CHeaderNav>

    <!--// Navigation Menu -->
    <!-- <CSubheader class="px-3">
      <CBreadcrumbRouter class="border-0 mb-0"/>
    </CSubheader> -->
  </CHeader>
</template>

<script>
import HeaderDropdownMenu from './HeaderDropdownMenu'

export default {
  name: 'TheHeader',
  components: {
    HeaderDropdownMenu,
  }
}
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
