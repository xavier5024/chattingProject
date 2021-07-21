<template>
  <CDropdown
    inNav
    class="c-header-nav-items"
    placement="bottom-end"
    add-menu-classes="pt-0"
  >
    <template #toggler>
      <CHeaderNavLink>
        <div class="c-avatar">
           <img :src="(userAuth.profile_src) ? userAuth.profile_src : '/images/default-avatar.png'" :width="50">
        </div>
      </CHeaderNavLink> 
    </template>
    <CDropdownHeader tag="div" class="text-center" color="light">
      <strong>Account</strong>
    </CDropdownHeader> 
    <CDropdownItem>
      <CIcon name="cil-user"/> Profile
    </CDropdownItem>
    <CDropdownDivider/>
    <CDropdownItem @click="userLogout">
      <CIcon name="cil-lock-locked" /> Logout
    </CDropdownItem>
  </CDropdown>
</template>

<script>
import { userLogoutApi } from '../../api';

export default {
  name: 'HeaderDropdownMenu',
  data() {
    return {
      userAuth
    }
  },
  methods: {
    userLogout() {
      this.$swal({
          text: '로그아웃 하시겠습니까?',
          icon: 'question',
          showCancelButton: true
      }).then((result) => {
        if(result.value){
          // 로그아웃 진행
          userLogoutApi () 
          .then(response => {
            const resultData = response.data;
            if(resultData.result){
              // 로그인 페이지로 이동
              userAuth = new Object();
              userAuth.auth = false;
              
              this.$router.replace({
                name: 'login'
              });
            } else {
              this.$swal({ text: resultData.message, icon: 'error' });  
            }
          })
          .catch(error => {
            this.$swal({ text: error, icon: 'error' });
          });
        }
      });
    }
  }
}
</script>

<style  scoped>
  
</style>