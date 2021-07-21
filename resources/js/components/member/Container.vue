<template>
  <div class="c-app">
    <Sidebar></Sidebar>
    <!-- <RightSidebar></RightSidebarg> -->
    <div class="c-wrapper">
      <!-- Header content here -->
      <Header></Header>
      
      <div class="c-body">
        <main class="c-main">
          <!-- Main content here -->
          <CContainer fluid>
            <transition name="fade">
              <router-view></router-view>
            </transition>
          </CContainer>
        </main>
      </div>

      <footer class="c-footer">
        <!-- Footer content here -->
      </footer>
    </div>
  </div>
</template>

<script>
import Header from './Header';
import Sidebar from './Sidebar';
import RightSidebar from './RightSidebar';

export default {
  name: 'MemberLayout',
  metaInfo: {
    title: 'AI RECRUIT',
    titleTemplate: '%s - ADMIN',
    meta: [
      {
        'http-equiv': 'Content-Security-Policy',
        content: 'upgrade-insecure-requests'
      }
    ]
  },
  components: {
    Header,
    Sidebar,
    RightSidebar
  }
};
</script>

<style scoped>
/**
 * common
 */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
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
