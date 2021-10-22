<template>
  <div>
    <CRow>
      <CCol col="12" sm="6" lg="4">
        <CWidgetIcon
          :header="total_user"
          text="회원 수"
          color="primary"
          :icon-padding="false"
        >
          <CIcon name="cil-people" class="mx-5 " width="24"/>
          <template #footer>
            <CCardFooter class="card-footer px-3 py-2">
              <CLink
                class="font-weight-bold font-xs btn-block text-muted"
                href="/#/member/memberList"
              >
                View more
                <CIcon name="cil-arrowRight" class="float-right" width="16"/>
              </CLink>
            </CCardFooter>
          </template>
        </CWidgetIcon>
      </CCol>
      <CCol col="12" sm="6" lg="4">
        <CWidgetIcon
          :header="total_chats"
          text="누적 채팅"
          color="info"
          :icon-padding="false"
        >
          <CIcon name="cil-contact" class="mx-5 " width="24"/>
          <template #footer>
            <CCardFooter class="card-footer px-3 py-2">
              <CLink
                class="font-weight-bold font-xs btn-block text-muted"
                href="/#/member/chatting"
              >
                View more
                <CIcon name="cil-arrowRight" class="float-right" width="16"/>
              </CLink>
            </CCardFooter>
          </template>
        </CWidgetIcon>
      </CCol>
      <CCol col="12" sm="6" lg="4">
        <CWidgetIcon
          :header="total_member_free_board"
          text="게시판 글"
          color="warning"
          :icon-padding="false"
        >
          <CIcon name="cil-settings" class="mx-5 " width="24"/>
          <template #footer>
            <CCardFooter class="card-footer px-3 py-2">
              <CLink
                class="font-weight-bold font-xs btn-block text-muted"
                href="#/member/freeBoard"
              >
                View more
                <CIcon name="cil-arrowRight" class="float-right" width="16"/>
              </CLink>
            </CCardFooter>
          </template>
        </CWidgetIcon>
      </CCol>
    </CRow>

    <CCardGroup class="mb-4">
      <CWidgetProgressIcon
        :header="total_user"
        text="회원 수"
        color="info"
      >
        <CIcon name="cil-people" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        :header="total_chats"
        text="누적채팅"
        color="success"
      >
        <CIcon name="cil-contact" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        header="4"
        text="게시판 글"
        color="warning"
      >
        <CIcon name="cil-settings" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        :header="total_join_member+'명'"
        text="현재 접속 인원"
      >
        <CIcon name="cil-chartPie" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        :header="join_percent"
        text="접속률"
        color="danger"
      >
        <CIcon name="cil-speedometer" height="36"/>
      </CWidgetProgressIcon>
    </CCardGroup>
  </div>
</template>

<script>
import {dashboardListApi} from '../../api';
export default {
  name: 'DashBoard',
    data() {
      return {
        total_user:0,
        total_chats:0,
        total_member_free_board:0,
        total_join_member:0,
        join_percent:0,
      }
    },
    methods:{
    },
    created() {
        dashboardListApi().then(response => {
          this.total_user = response.data.total_user
          this.total_chats = response.data.total_chats
          this.total_member_free_board = response.data.total_member_free_board
          window.Echo.join('monitoring.common')
          .here((users) => {
            this.total_join_member = users.length;
            this.join_percent = users.length / this.total_user;
          })

        }).catch(error => {
          this.$swal({ text: error, icon: 'error' });
        });
    }
  }
</script>

<style scoped>
.c-app {
  background-color: #2A364E !important;
}

</style>
