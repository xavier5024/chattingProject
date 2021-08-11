<template>
  <div>
    <CCard class="member_info">
      <CCardHeader v-text="(member_id) ? '관리자 수정하기' : '관리자 추가하기'">
      </CCardHeader>
      <form id="member_form">
        <CCardBody>
          <CRow>
            <CCol sm="6">
              <CInput
                  label="아이디"
                  placeholder="아이디를 입력해주세요"
                  size="lg"
                  name="id"
                  v-model="member.id"
                  :disabled="(member_id > 0) ? true : false "
                  required
              />
            </CCol>
            <CCol sm="6">
              <CInput
                  label="이름"
                  placeholder="이름을 입력해주세요"
                  size="lg"
                  name="name"
                  v-model="member.name"
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
                  name="email"
                  v-model="member.email"
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
                  v-model="member.tel"
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
                <img width="100px" height="100px" ref="profile_img" :src="(member.profile_src) ? member.profile_src : '//static2.isidae.com/img1/winsidaero/ai_recruit/profile.png'">
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
                  v-model="member.password"
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
            <CButton block color="secondary" @click="$router.replace({ name: 'memberList'})">목록보기</CButton>
          </CCol>
          <CCol v-if="member_id" :md="{size:'2', offset:4}">
            <CButton block color="danger" @click="member_delete">삭제하기</CButton>
          </CCol>
          <CCol :md="(!member_id) ?{size:'2', offset:6} :{size:'2'}">
            <CButton block color="info" @click="resister" v-text="(member_id) ? '수정하기' : '생성하기'"></CButton>
          </CCol>
          <CCol :md="{size:'2'}">
            <CButton block color="secondary" @click="$router.back()">취소하기</CButton>
          </CCol>
        </CRow>
      </CCardFooter>
      <CElementCover 
        v-if="loading"
        :boundaries="[{ sides: ['top', 'left'], query: '.card.member_info' }]"
        :opacity="0.8"
      >
        <h1 class="d-inline">Loading... </h1><CSpinner size="5xl" color="success"/>
      </CElementCover>
    </CCard>
  </div>
</template>

<script>
  import {memberRegisterApi, memberReadApi, memberUpdateApi, memberDeleteApi} from '../../api';

  export default {
    name: "MemberResisterView",
    data() {
      return {
        member: {
          id: '',
          password: '',
          name:'',
          email:'',
          company_seq:userAuth.company_seq,
          tel:'',
        },
        profile:null,
        password_conval:"",
        member_id:this.$route.params.member_id,
        loading:true,
      }
    },
    methods:{
      async resister(event){ 
        let confirm_message = "";
        let confirm_btn = "";

        if(this.member_id){
          confirm_message = "수정하시겠습니까?";
          confirm_btn = "수정하기";
        }else{
          confirm_message = "등록하시겠습니까?";
          confirm_btn = "등록하기";
        }

        const confirm = await this.confirm("", confirm_message, confirm_btn);

        if(!confirm)return;

        if(!this.validateId(this.member.id)){  //아이디 체크
          return;
        }

        if(this.member_id > 0 && this.member.password.length == 0){
          delete this.member.password;
        }else{
          if(!(this.validatePassword(this.member.password) && this.password_confirm())){
            return;
          }
        }

        if(this.isEmail(this.member.email) && this.isPhone(this.member.tel)){
          memberRegisterApi(this.member, this.profile, this.member_id).then(response => {
            const resultData = response.data;
            let self = this;
            if(resultData.result) {
              this.$swal({ text: resultData.message, icon: 'info' }).then(function (alert) {
                self.$router.replace({
                  name: 'memberList'
                });
                if(userAuth.id == self.member_id){
                  window.location.reload();
                }
              });
            } else {
              this.$swal({ text: resultData.message, icon: 'error' });
            }
          })
          .catch(error => {
            this.$swal({ text: error, icon: 'error' });
          });
        }
        if(!this.member.password)this.member.password = '';
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
        if(this.member.name.length ===0){
          this.$swal({ text: '이름을 입력하세요', icon: 'question' });
          return false;
        }
        if(this.member.email.length ===0){
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
          if(this.member.password != this.password_conval){
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
        this.$refs.profile_img.src = "//static2.isidae.com/img1/winsidaero/ai_recruit/profile.png";
        this.profile = "file_deleted";
      },
      async member_delete(event){
        const confirm = await this.confirm("", "정말로 삭제하시겠습니까?", "삭제하기");
        if(!confirm)return;
        if(userAuth.member_id == this.member_id){
          this.$swal({ text: "자기 자신은 삭제할 수 없습니다!", icon: 'error' });
          return;
        }
        memberDeleteApi(this.member_id).then(response => {
          const resultData = response.data;
          if(resultData.result) {
            this.$swal({ text: resultData.message, icon: 'info' });
            this.$router.replace({
              name: 'memberList'
            });
          } else {
            this.$swal({ text: resultData.message, icon: 'error' });
          }
        })
        .catch(error => {
          this.$swal({ text: error, icon: 'error' });
        });
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
      }
    },
    created() {
      if(this.member_id && this.member_id > 0){
        memberReadApi(this.member_id).then(response => {
          response.data.password = '';
          this.member = response.data;
          this.loading = false;
        }).catch(error => {
          this.$swal({ text: error, icon: 'error' });
          this.loading = false;
        });
      }else{
        this.loading = false;
      }

    }
  }
</script>

<style scoped>

</style>