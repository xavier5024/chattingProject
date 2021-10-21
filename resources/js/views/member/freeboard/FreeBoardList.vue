<template>
  <div>
    <CCard>
      <CCardHeader>
        <CRow alignVertical="center">
          <CCol sm="2">
            <CInput
                placeholder="제목, 작성자 검색"
                style="margin-bottom: auto"
                :lazy="true"
                :value.sync="filter"
            />
          </CCol>
          <CCol sm="8"/>
          <CCol sm="2">
            <CButton block color="info" @click="freeBoardWrite">글쓰기</CButton>
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
            :noItemsView="{ noResults: '해당 글이 존재하지 않습니다.', noItems: '로딩중입니다.' }"
            @row-clicked="freeBoardView"
            @pages-change="setTablePage"
            class="text-center"
        >
          <template #row_num={index}>
            <td>{{index+1}}</td>
          </template>
          <template #name="{item}">
            <td>
              <img :src='item.profile_src' width="30px" height="30px"/>
              {{item.name}}
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
  import {memberFreeBoardListApi} from '../../../api';

  export default {
    name: 'memberFreeBoardList',
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
          {key: 'subject', label: '제목', _style: 'min-width:250px'},
          {key: 'name' , label: '작성자'},
          {key: 'in_dt', label: '작성일', filter: false},
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
      freeBoardWrite(){
        this.$router.replace({
          name: 'memberFreeBoardWrite', params: {board_id:null}
        });
      },
      freeBoardView(item){
        this.$router.replace({
          name: 'memberFreeBoardView', params: {board_id:item.id}
        });

      }
    },
    beforeMount() {
      memberFreeBoardListApi().then(response => {
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
