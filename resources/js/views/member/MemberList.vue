<template>
  <div>
    <CCard>
      <CCardHeader>
        <CRow alignVertical="center">
          <CCol sm="2">
            <CInput
                placeholder="아이디, 이름을 입력하세요"
                style="margin-bottom: auto"
                :lazy="true"
                :value.sync="filter"
            />
          </CCol>
          <CCol sm="8"/>
          <CCol sm="2">
            <CButton block color="info" @click="$router.replace({name: 'memberRegister'});">회원 생성</CButton>
          </CCol>
        </CRow>
      </CCardHeader>
      <CCardBody>
        <CDataTable
            :hover="true"
            :clickableRows="true"
            :striped="striped"
            :bordered="bordered"
            :small="small"
            :fixed="fixed"
            :items="data"
            :tableFilterValue='filter'
            :columnFilterValue="columnFilter"
            :fields="dataFields"
            :items-per-page="small ? 10 : 10"
            :dark="dark"
            :activePage="page"
            :sorter="sorter"
            :loading="loading"
            :noItemsView="{ noResults: '해당 회원이 존재하지 않습니다.', noItems: '로딩중입니다.' }"
            @row-clicked="memberModify"
            @pages-change="setTablePage"
            class="text-center"
        >
          <template #row_num={index}>
            <td>{{index+1}}</td>
          </template>
          <template #profile="{item}">
            <td>
              <img :src='item.profile_src' width="30px" height="30px"/>
            </td>
          </template>
        </CDataTable>
        <CPagination
            v-if="tablePage > 1"
            :activePage.sync="page"
            :pages="tablePage"
            size="lg"
            align="center"
        />
      </CCardBody>
    </CCard>
  </div>
</template>

<script>
  import {memberListApi} from '../../api';

  export default {
    name: 'MemberList',
    props: {
      items: Array,
      fields: {
        type: Array,
        default() {
          return []
        }
      },
      pageProp: {
        align: "center",
        limit: 5
      },
      caption: {
        type: String,
        default: 'Table'
      },
      hover: Boolean,
      striped: Boolean,
      bordered: Boolean,
      small: Boolean,
      fixed: Boolean,
      dark: Boolean,
      sorter: Boolean
    },
    components: {},
    data() {
      return {
        loading: true,
        data: [],
        dataFields: [
          {key: 'row_num' , label: '#'},
          {key: 'profile' , label: '프로필'},
          {key: 'user_id', label: '아이디'},
          {key: 'name', label: '이름'},
          {key: 'tel', label: '연락처'},
          {key: 'email', label: '이메일', filter: false},
          {key: 'created_at', label: '가입일', filter: false},
        ],
        page: 1,
        filter: "",
        tablePage: 20,
        columnFilter: new Object()
      }
    },
    methods: {
      setTablePage(TotalPage) {
        this.tablePage = TotalPage;
      },
      memberModify(member){
        console.log(member);

      }
    },
    beforeMount() {
      memberListApi().then(response => {
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
