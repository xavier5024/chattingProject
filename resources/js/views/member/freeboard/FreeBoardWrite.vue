<template>
  <div>
    <CRow>
      <CCol>
        <CCard class="board_info">
          <CCardHeader>            
            <CRow alignVertical="center" alignHorizontal="center" style="padding-top:10px;">
             <CCol :md="{size:'12  '}"><CInput placeholder="제목을 입력해 주세요" v-model="data.subject" /></CCol>
            </CRow>
          </CCardHeader>          
          <CCardBody>
            <CRow>
              <CCol style="min-height:500px;">
                  <ckeditor 
                  v-model="data.contents" :config="editorConfig" 
                  @drop.prevent
                  @dragover.prevent
                  class="w-full  text-gray-700 bg-gray-200 rounded" id="board-content" name="board-content" required="true" placeholder="내용" aria-label="Name">
                  </ckeditor>
              </CCol>
            </CRow>
          </CCardBody>
          <CCardFooter>
            <CRow>
              <CCol :md="{size:'2'}">
                <CButton block color="secondary" :disabled="btnStatusApply.disabled" @click="$router.replace({name: 'memberFreeBoardView', params: {board_id:data.id}});">목록으로</CButton>
              </CCol>
              <CCol :md="{size:'2', offset:6}">
                <CButton block color="danger" :disabled="btnStatusApply.disabled">삭제하기</CButton>
              </CCol>                                                                                                                                                                                            
              <CCol :md="{size:'2'}">
                <CButton block color="info" :disabled="btnStatusApply.disabled" v-on:click="create">등록하기</CButton>
              </CCol>
            </CRow>
          </CCardFooter>
          <CElementCover 
            v-if="loading"
            :boundaries="[{ sides: ['top', 'left'], query: '.card.board_info' }]"
            :opacity="0.8"
          >
            <h1 class="d-inline">Loading... </h1><CSpinner size="5xl" color="success"/>
          </CElementCover>          
        </CCard>
      </CCol>
    </CRow>
  </div>
</template>
<script>
  import {memberFreeBoardViewApi, memberFreeBoardWriteApi} from '../../../api';
  
  export default {
    name: "memberFreeBoardWrite",
    data() {
      return {
        data: {
          id : null,
          subject:'',
          contents:'',
          name:null,
          profile_src:null,
          parent_id:null,
          iscomment:'N',
          in_dt:null
        }
        ,
        loading: true,
        board_id:this.$route.params.board_id,
        editorConfig: { 
            filebrowserUploadUrl : '',
            filebrowserImageUploadUrl  : '',
            filebrowserUploadMethod : 'post',
            height : 500
        },
        btnStatusApply:{
          disabled: false,
        },
      }
    },
    methods:{
    create() {
      let error_msg = '';

      if(this.data.subject.length == 0){
        error_msg = '제목을 입력하세요';
        return;
      }

      if(this.data.contents.length == 0){
        error_msg = '내용을 입력하세요';
        return;
      }
    
      let vm = this;
      let recruit_data = this.data;
     
      this.$swal({
        title: '게시글 등록',
        text: '게시글을 등록하시겠습니까?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `네, 등록합니다`,
        cancelButtonText: `아니오`,
        reverseButtons: true
      }).then((result) => {
          if (result.isConfirmed) {
          vm.loading = true;
          vm.btnStatusApply.disabled = true;
          memberFreeBoardWriteApi(recruit_data).
          then(res => {
            const apiData = res.data;
            if(apiData.result){
              vm.loading = false;
              this.$swal('등록되었습니다', '', 'success').then(() => {
          }).then(() => {
              this.$router.replace({name: 'memberFreeBoardView',  params: {board_id:this.board_id}})
          })
            } else {
                this.$swal({
                title: '등록 실패',
                html: apiData.message,
                icon: 'error'
              })
              vm.btnStatusApply.disabled = false;
            }
          })
          .catch(err => {
            console.log('catch',err);
            vm.btnStatusApply.disabled = false;
          })
        }
      })
    },

    },
    created() {
      if(this.board_id){
        memberFreeBoardViewApi(this.board_id).then(response => {
          this.data = response.data;
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