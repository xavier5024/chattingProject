<template>
  <div>
    <CRow>
      <CCol col="12" sm="6" lg="4">
        <CWidgetIcon
          header="5,263"
          text="회원 수"
          color="primary"
          :icon-padding="false"
        >
          <CIcon name="cil-people" class="mx-5 " width="24"/>
          <template #footer>
            <CCardFooter class="card-footer px-3 py-2">
              <CLink
                class="font-weight-bold font-xs btn-block text-muted"
                href="/#/admin/users"
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
          header="24"
          text="감독관 수"
          color="info"
          :icon-padding="false"
        >
          <CIcon name="cil-contact" class="mx-5 " width="24"/>
          <template #footer>
            <CCardFooter class="card-footer px-3 py-2">
              <CLink
                class="font-weight-bold font-xs btn-block text-muted"
                href="/#/admin/supervisors"
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
          header="4"
          text="관리자 수"
          color="warning"
          :icon-padding="false"
        >
          <CIcon name="cil-settings" class="mx-5 " width="24"/>
          <template #footer>
            <CCardFooter class="card-footer px-3 py-2">
              <CLink
                class="font-weight-bold font-xs btn-block text-muted"
                href="/#/admin/admins"
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
        header="5,283"
        text="회원 수"
        color="info"
      >
        <CIcon name="cil-people" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        header="25"
        text="감독관 수"
        color="success"
      >
        <CIcon name="cil-contact" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        header="4"
        text="Products sold"
        color="warning"
      >
        <CIcon name="cil-settings" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        header="28%"
        text="Returning Visitors"
      >
        <CIcon name="cil-chartPie" height="36"/>
      </CWidgetProgressIcon>
      <CWidgetProgressIcon
        header="5:34:11"
        text="Avg. Time"
        color="danger"
      >
        <CIcon name="cil-speedometer" height="36"/>
      </CWidgetProgressIcon>
    </CCardGroup>
  </div>
</template>

<script>
export default {
  name: 'DashBoard',
  methods:{
  }
}
</script>

<style scoped>
.c-app {
  background-color: #2A364E !important;
}

</style>
