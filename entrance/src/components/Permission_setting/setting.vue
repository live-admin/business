<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <!--<el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>-->
        <el-breadcrumb-item>权限设置</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>
    <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
      <el-form :inline="true">
        <el-form-item>
          <el-input v-model="realname" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" v-on:click="SetQuery">查询</el-button>
          <el-button type="primary" v-on:click="emptyFun">刷新</el-button>
        </el-form-item>
      </el-form>
    </el-col>
          <el-table
            :data="tableData"
            v-loading="listLoading"
            stripe border>
            <el-table-column
              prop="id"
              label="#"
              width="50">
            </el-table-column>
            <el-table-column
              prop="employee_account"
              label="oa账号"
              width="100">
            </el-table-column>
            <el-table-column
              prop="realname"
              label="姓名"
              width="100">
            </el-table-column>
            <el-table-column
              prop="job_name"
              label="岗位"
              width="180">
            </el-table-column>
            <el-table-column
              prop="mobile"
              label="手机号码"
              width="180">
            </el-table-column>
            <el-table-column
              prop="sex"
              label="性别"
              width="50">
            </el-table-column>
            <el-table-column
              label="状态"
              width="100">
              <template slot-scope="scope">
                <el-tag color="#409EFF" v-if="scope.row.status==1">可用</el-tag>
                <el-tag type="danger" v-if="scope.row.status==0">不可用</el-tag>
              </template>
            </el-table-column>
            <el-table-column
              prop="create_at"
              label="开始登录时间"
              width="180">
            </el-table-column>
            <el-table-column
              prop="update_at"
              label="最近登录时间"
              width="180">
            </el-table-column>
            <el-table-column
              label="角色类型"
              width="100">
              <template slot-scope="scope">
                <el-tag color="#409EFF" v-if="scope.row.admin==1">超级管理员</el-tag>
                <el-tag type="danger" v-if="scope.row.admin==2">管理员</el-tag>
                <el-tag type="danger" v-if="scope.row.admin==3">普通员工</el-tag>
              </template>
            </el-table-column>
            <el-table-column>
              <el-table-column label="操作" width="300">
                <template slot-scope="scope">
                  <el-button size="mini" v-if="scope.row.status==1"  type="warning" round @click="DisableFun(scope.$index,scope.row)">禁用</el-button>
                  <el-button size="mini" v-if="scope.row.status==0"  type="primary" round @click="EnableFun(scope.$index,scope.row)">启用</el-button>
                  <el-button size="mini" v-if="scope.row.admin==3" type="success" round @click="DetermineFun(scope.$index,scope.row)">授权管理员</el-button>
                  <el-button size="mini" v-if="scope.row.admin==2" type="danger" round @click="cancelFun(scope.$index,scope.row)">取消管理员</el-button>
                </template>
              </el-table-column>
            </el-table-column>
          </el-table>
          <el-col :span="24" class="toolbar">
            <el-pagination
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
              :current-page.sync="currentPage"
              :page-size="page_size"
              layout="prev, pager, next, jumper"
              :total="total"
              style="float:right;margin:5px 30px 0 0;">
            </el-pagination>
          </el-col>
  </el-row>
</template>
<script>
  import {backendEmployeeList,backendEmployeeEdit} from '../../api/api';
  export default{
    data(){
      return {
        listLoading:false,
        tableData:[],
        total: 0,
        currentPage:1,
        page: 1,
        page_size:10,
        admin:'',
        status:'',
        realname:''
      }
    },
    methods: {
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
        this.page = val;
        this.ActivityList();
      },
      handleCurrentChange(val) {
        this.page = val;
        this.ActivityList();
      },
      SetQuery(){
          if(this.realname==''){
            this.$notify({
              title: '警告',
              message: '请输入姓名',
              type: 'warning'
            });
            return
          }else{
            this.StartSet();
          }
      },
      StartSet(){
          let set = {params:{
            page: this.page,
            page_size: this.page_size,
            name:this.realname,
            admin: this.admin,
            status: this.status
          }};
          this.listLoading = true;
          backendEmployeeList(set).then((res) => {
            if(res.code==0){
                this.tableData=res.content.result.data;
                this.total = res.content.result.total;
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
              if(res.code=='9001'||res.code=='402'){
                sessionStorage.removeItem('user_router');
                sessionStorage.removeItem('access-user');
                this.$router.push({ path: '/login' });
              }
            }
            this.listLoading = false;
          })
      },
      emptyFun(){
        window.location.reload();
      },
      DisableFun(index,row){//禁用
        this.setEdit(row.id,row.admin,0)
      },
      EnableFun(index,row){//启用
        this.setEdit(row.id,row.admin,1)
      },
      DetermineFun(index,row){//授权
        this.setEdit(row.id,2,row.status)
      },
      cancelFun(index,row){//取消授权
        this.setEdit(row.id,3,row.status)
      },
      setEdit(employee_id,admin,status){
        let setEdit ={
          employee_id:employee_id,
          admin:admin,
          status:status
        };
        this.listLoading = true;
        backendEmployeeEdit(setEdit).then((res) => {
          console.log(res);
          if(res.code==0){
              this.$notify({
                title: '成功',
                message: '操作成功',
                type: 'success'
              });
              this.StartSet();
          }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
          }
          this.listLoading = false;
        })
      }
    },
    mounted() {
      this.StartSet();
    }
  }
</script>
