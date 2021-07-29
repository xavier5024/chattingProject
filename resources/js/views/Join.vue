<template>
  <div class="c-app flex-row align-items-center">
    <CContainer>
      <CCard>
        <CCardHeader>
          <h2>
            Join
          </h2>
        </CCardHeader>
        <form>
          <CCardBody>
            <CRow>
              <CCol sm="6">
                <CInput
                    label="아이디"
                    placeholder="아이디를 입력해주세요"
                    size="lg"
                    name="mb_id"
                    v-model="data.id"
                    required
                />
              </CCol>
              <CCol sm="6">
                <CInput
                    label="이름"
                    placeholder="이름을 입력해주세요"
                    size="lg"
                    name="mb_name"
                    v-model="data.name"
                    required
                />
              </CCol>
            </CRow>
            <CRow>
              <CCol sm="6">
                <CInput
                    label="이메일"
                    type="email"
                    placeholder="이메일을 입력해주세요"
                    size="lg"
                    name="mb_email"
                    v-model="data.email"
                    required
                />
              </CCol>
              <CCol sm="6">
                <CInput
                    label="핸드폰"
                    type="text"
                    placeholder="핸드폰 번호를 '-'없이 숫자만 입력해주세요"
                    size="lg"
                    name="mb_tel"
                    v-model="data.tel"
                    maxlength=11
                    @keypress="isNumber($event)"
                    required
                />
              </CCol>            
            </CRow>
          </CCardBody>
          <CCardBody>
            <CRow>
              <CCol>
                <label size="lg">프로필 이미지</label>
              </CCol>
            </CRow>

              <CRow style="margin-bottom: 1em">
                <CCol sm="2">
                  <img width="100px" height="100px" ref="profile_img" src='//static2.isidae.com/img1/winsidaero/ai_recruit/profile.png'>
                </CCol>
              </CRow>
            
            <CCol sm="12">
              <CRow>
                <CCol xs="1">
                  <CButton block color="info" @click="fileClick" style="min-width: 100px">파일 업로드</CButton>
                  <input @change="fileUpload" type="file" style="display:none" ref="fileInput"/>
                </CCol>
                <CCol xs="1">
                  <CButton block color="danger" @click="fileDelete" style="min-width: 100px">삭제</CButton>
                </CCol>
              </CRow>
            </CCol>            
          </CCardBody>
          <CCardBody style="border-top: 1px solid; border-color: #d8dbe0;">
            <CRow>
              <CCol sm="6">
                <CInput
                    label="비밀번호"
                    type="password"
                    placeholder="비밀번호를 입력해주세요"
                    size="lg"
                    v-model="data.password"
                    required
                />
              </CCol>
              <CCol sm="6">
                <CInput
                    v-model="password_conval"
                    label="비밀번호 재입력"
                    type="password"
                    placeholder="비밀번호를 다시 입력해주세요"
                    size="lg"
                    required
                />
              </CCol>
            </CRow>
          </CCardBody>
        </form>
        <CCardFooter>
          <CRow>
            <CCol :md="{size:'2'}">
              <CButton block color="secondary" @click="$router.back()">취소</CButton>
            </CCol>
            <CCol :md="{size:'2', offset:8}">
              <CButton block color="info" @click="resister">가입하기</CButton>
            </CCol>
          </CRow>
        </CCardFooter>
      </CCard>
     </CContainer>
  </div>
</template>

<script>
  import {memberJoinApi} from '../api';

  export default {
    name: "memberJoin",
    data() {
      return {
        data: {
          id: '',
          password: '',
          name:'',
          email:'',
          tel:'',
        },
        profile:null,
        password_conval:"",
        loading:true,
      }
    },
    methods:{
      async resister(event){ 
        let confirm_message = "";
        let confirm_btn = "";

        confirm_message = "등록하시겠습니까?";
        confirm_btn = "등록하기";

        const confirm = await this.confirm("", confirm_message, confirm_btn);

        if(!confirm)return;

        if(!this.validateId(this.data.id)){  //아이디 체크
          return;
        }

        if(!(this.validatePassword(this.data.password) && this.password_confirm())){
            return;
        }

        if(this.isEmail(this.data.email) && this.isPhone(this.data.tel)){
          memberJoinApi(this.data, this.profile).then(response => {
            const resultData = response.data;
            let self = this;
            if(resultData.result) {
              this.$swal({ text: resultData.message, icon: 'info' }).then(function (alert) {
                userAuth = new Object();
                userAuth = resultData.data;
                self.$router.replace({
                  name: 'memberHome'
                });
              });
            } else {
              this.$swal({ html: resultData.message, icon: 'error' });
            }
          })
          .catch(error => {
            this.$swal({ text: error, icon: 'error' });
          });
        }
        if(!this.data.password)this.data.password = '';
      },
      validateId (id){
        if (id.length === 0 ){
          this.$swal({ text: '아이디(ID)를 입력하세요', icon: 'question' });
          return false;
        }
        if (id.search(/\s/) !== -1 ) {
          this.$swal({ text: '아이디(ID)는 공백없이 입력하세요', icon: 'error' });
          return false;
        }
        if (id.search(/\s/) !== -1 ) {
          this.$swal({ text: '아이디(ID)는 공백없이 입력하세요', icon: 'error' });
          return false;
        }
        if(this.data.name.length ===0){
          this.$swal({ text: '이름을 입력하세요', icon: 'question' });
          return false;
        }
        if(this.data.email.length ===0){
          this.$swal({ text: '이메일을 입력하세요', icon: 'question' });
          return false;
        }        
        return true;
        }, // 이메일 체크 정규식
        isEmail(email) {
          var regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
          if(!regExp.test(email)){
            this.$swal({ text: '이메일을 형식을 확인해주세요!', icon: 'question' });
            return false;
          }
          return true; // 형식에 맞는 경우 true 리턴	
        },
        isPhone(tel) { //  핸드폰 번호 체크 정규식
          var regExp = /^01(?:0|1|[6-9])(?:\d{3}|\d{4})\d{4}$/;
          if(!regExp.test(tel)){
            this.$swal({ text: '전화번호 형식을 확인해주세요!', icon: 'question' });
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
        },
        password_confirm(){
          if(this.data.password != this.password_conval){
            this.$swal({ text: '비밀번호 확인이 일치하지 않습니다. 다시 한 번 확인해주세요', icon: 'error' });
            return false;
          }
          return true;
        },
        isNumber(evt) {
        evt = (evt) ? evt : window.event;
        let charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
          evt.preventDefault();;
        } else {
          return true;
        }
      },
      fileClick(file,event){
        const fileInput = this.$refs.fileInput;
        fileInput.click();
      },
      fileUpload(event){
        let file = event.currentTarget.files[0];
        let input = event.currentTarget;
        let url = input.value;
        let ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();        
        if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){
          this.profile = file;
          let reader = new FileReader();
          let self = this;
          reader.onload = function (e) {
            self.$refs.profile_img.src = e.target.result;
          }
        reader.readAsDataURL(input.files[0]);
       }else{
         this.$swal({ text: '이미지 형식이 올바르지 않습니다. 다시한번 확인해주세요', icon: 'error' });
       }
      },
      fileDelete(event){
        this.$refs.fileInput.value = null;
        this.$refs.profile_img.src = "/img/profile.png";
        this.profile = "file_deleted";
      },
      async confirm(title, text, btnText){
        let result;
        await this.$swal({
          title : title,
          text : text,
          showCancelButton : true,
          confirmButtonText : btnText,
          cancelButtonText : "아니오",
        }).then(function (isConfirm) {
          if(isConfirm.dismiss == "cancel"){
            result = false;
          }else{
            result = true;
          }
        });
        return result;
      },
      goToList(){
        this.$router.replace({
          name: 'Login'
        });
      }
    },
    created() {

    }
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