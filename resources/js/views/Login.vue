<template>
  <div class="c-app flex-row align-items-center">
    <CContainer>
      <CRow class="justify-content-center">
        <div class="logo"></div>
      </CRow>
      <CRow class="justify-content-center">
        <CCol md="8">
          <CCardGroup>
            <CCard class="p-4">
              <CCardBody>
                <CForm>
                  <h1>Admin Login</h1>
                  <p class="text-muted">Sign In to your account</p>
                  <CInput
                    placeholder="ID"
                    v-model="user.id"
                    required
                  >
                    <template #prepend-content><CIcon name="cil-user"/></template>
                  </CInput>
                  <CInput
                    placeholder="Password"
                    type="password"
                    v-model="user.password"
                    @keyup.13="userLogin"
                    required
                  >
                    <template #prepend-content><CIcon name="cil-lock-locked"/></template>
                  </CInput>
                  <CRow>
                    <CCol col="6" class="text-left">
                      <CButton color="success" class="px-4" @click="$router.replace({name: 'memberJoin'});">Join</CButton>
                    </CCol>
                    <CCol col="6" class="text-right">
                      <CButton color="primary" class="px-4" @click="userLogin" :disabled="loginBtnState === true">Login</CButton>
                    </CCol>
                  </CRow>
                </CForm>
              </CCardBody>
              <span class="copyright">COPYRIGHT © MIT. ALL RIGHT RESERVED</span>
            </CCard>
          </CCardGroup>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>

<script>
import { userLoginApi } from '../api';

export default {
  name: 'Login',
  data () {
    return {
      user: {
        id: '',
        password: ''
      },
      loginBtnState: false
    }
  },
  metaInfo: {
    title: 'AI RECRUIT',
    titleTemplate: '%s - Login',
    meta: [
      {
        'http-equiv': 'Content-Security-Policy',
        content: 'upgrade-insecure-requests'
      }
    ]
  },
  methods: {
    userLogin () {
      this.loginBtnState = true;
      let id = this.user.id;
      const password = this.user.password;

      if (this.validateId(id) && this.validatePassword(password)) {
        userLoginApi (id, password)
        .then(response => {
          const resultData = response.data;
          if(resultData.result) {
            userAuth = new Object();
            userAuth = resultData.data;

            this.$router.replace({
              name: 'memberHome'
            });
          } else {
            this.$swal({ text: resultData.message, icon: 'error' });
            this.loginBtnState = false;
          }
        })
        .catch(error => {
          this.$swal({ text: error, icon: 'error' });
          this.loginBtnState = false;
        });
      } else {
        this.loginBtnState = false;
      }
    },
    validateId (id) {
      if (id.length === 0 ){
        this.$swal({ text: '아이디(ID)를 입력하세요', icon: 'question' });
        return false;
      }
      if (id.search(/\s/) !== -1 ) {
        this.$swal({ text: '아이디(ID)는 공백없이 입력하세요', icon: 'error' });
        return false;
      }
      return true;
    },
    validatePassword (password) {
      const num = /[0-9]/g;
      const eng = /[a-z]/ig;
      const spe = /[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi;

      if (password.length === 0 ) {
        this.$swal({ text: '비밀번호를 입력하세요', icon: 'question' });
        return false;
      }

      if (password.search(/\s/) !== -1 ) {
        this.$swal({ text: '비밀번호는 공백없이 입력하세요', icon: 'error' });
        return false;
      }

      if (password.search(num) < 0 || password.search(eng) < 0 || password.search(spe) < 0) {
        this.$swal({ text: '비밀번호는 영문자, 숫자, 특수문자 조합으로 입력해야합니다', icon: 'error' });
        return false;
      }

      return true;
    }
  },
}
</script>

<style scoped>
.c-app {
  background-color: #2A364E !important;
}

.logo {
  width: 300px;
  height: 34px;
  background-size: cover;
  margin-bottom: 30px;
}

.copyright {
  color: #3c4b64;
  font-size: 0.875rem;
  text-align: center;
  font-weight: 500;
}
</style>
