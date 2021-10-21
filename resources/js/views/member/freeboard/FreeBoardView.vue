<template>
  <div>
    <CRow>
      <CCol>
        <CCard class="board_info">
          <CCardHeader>            
            <CRow alignVertical="center">
             <CCol size="4"><h2>{{data.subject}}</h2></CCol>
             <CCol :md="{size:'2', offset:5}">작성일 : {{data.in_dt}} </CCol><CCol size="1">작성자 : <img :src='data.profile_src' width="30px" height="30px"/>{{data.name}}</CCol>
            </CRow>
          </CCardHeader>          
          <CCardBody>
            <CRow>
              <CCol style="min-height:500px;" v-html="data.contents">
              </CCol>
            </CRow>
          </CCardBody>
          <CCardFooter>
            <CRow>
              <CCol :md="{size:'2'}">
                <CButton block color="secondary" @click="$router.replace({name: 'memberFreeBoardList'});">목록으로</CButton>
              </CCol>
              <CCol :md="{size:'2', offset:6}">
                <CButton block color="danger" >삭제하기</CButton>
              </CCol>
              <CCol :md="{size:'2'}">
                <CButton block color="info" @click="modify">수정하기</CButton>
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
  import {memberFreeBoardViewApi} from '../../../api';
  
  export default {
    name: "memberFreeBoardView",
    data() {
      return {
        data: [],
        loading: true,
        board_id:this.$route.params.board_id,
      }
    },
    methods:{
      modify(){
        this.$router.replace({
          name: 'memberFreeBoardModify', params: {board_id:this.board_id}
        });
      }
    },
    created() {
      memberFreeBoardViewApi(this.board_id).then(response => {
        this.data = response.data;
        this.loading = false;
      }).catch(error => {
        this.$swal({ text: error, icon: 'error' });
        this.loading = false;
      });
    }
  }
</script>

<style scoped>

</style>