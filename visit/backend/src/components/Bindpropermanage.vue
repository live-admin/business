<template>
  <el-row class="warp" v-loading="loading" v-if="$route.name == '绑定专属客户经理'" >
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>
        <el-breadcrumb-item>绑定专属客户经理</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <el-form ref="form" :model="form" label-width="140px">
        <el-form-item label="业主姓名">
          <el-input v-model="form.user_name"  placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="业主手机号">
          <el-input v-model="form.user_mobile" placeholder="请输入手机号码"></el-input>
        </el-form-item>
        <el-form-item label="业主所在管辖部门">
          <el-select v-model="dep.uuid1" placeholder="请选择" v-loading="loading">
            <el-option v-for="item in dep.option1" :label="item.org_name" :value="item.org_uuid" :key="item.org_uuid" value-key="id"></el-option>
          </el-select>

          <el-select v-model="dep.uuid2" placeholder="请选择" v-if="dep.option2.length > 0 && dep.uuid1" v-loading="loading">
            <el-option v-for="item in dep.option2" :label="item.org_name" :value="item.org_uuid" :key="item.org_uuid" value-key="id"></el-option>
          </el-select>

          <el-select v-model="dep.uuid3" placeholder="请选择"  v-if="dep.option3.length > 0 && dep.uuid2"  v-loading="loading">
            <el-option v-for="item in dep.option3" :label="item.org_name" :value="item.org_uuid" :key="item.org_uuid" value-key="id"></el-option>
          </el-select>

          <el-select v-model="dep.uuid4" placeholder="请选择"  v-if="dep.option4.length > 0 && dep.uuid3" v-loading="loading">
            <el-option v-for="item in dep.option4" :label="item.org_name" :value="item.org_uuid" :key="item.org_uuid" value-key="id"></el-option>
          </el-select>

          <el-select v-model="dep.uuid5" placeholder="请选择"  v-if="dep.option5.length > 0 && dep.uuid4" v-loading="loading">
            <el-option v-for="item in dep.option5" :label="item.org_name" :value="item.org_uuid" :key="item.org_uuid" value-key="id"></el-option>
          </el-select>
        </el-form-item>

         <el-form-item label="绑定状态">
          <el-select v-model="form.bind_state" placeholder="请选择绑定状态">
            <el-option v-for="item in state"  :label="item.label" :value="item.value" :key="item.value" value-key="id"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="客户经理姓名">
          <el-input v-model="form.manager_name" placeholder="请输入客户经理姓名"></el-input>
        </el-form-item>
        <el-form-item label="客户经理手机号">
          <el-input v-model="form.manager_mobile" placeholder="请输入客户经理手机号码"></el-input>
        </el-form-item>
         <el-form-item label="客户经理OA账号">
          <el-input v-model="form.manager_oa_username" placeholder="请输入客户经理OA账号"></el-input>
        </el-form-item>

        <el-form-item label="时间">
          <el-date-picker
            v-model="form.times"
            type="datetimerange"
            range-separator="至"
            start-placeholder="绑定开始时间"
            end-placeholder="绑定截止时间"
            @change="dataChange">
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="" @click="emptyFun">重置</el-button>
          <el-button type="primary" icon="el-icon-search" @click="search">搜索</el-button>
        </el-form-item>
      </el-form>

      <el-table
        ref="multipleTable"
        :data="content.data"
        tooltip-effect="dark"
        style="width: 100%;"
        @selection-change="handleSelectionChange">
        <el-table-column
          type="selection"
          width="55">
        </el-table-column>
        <el-table-column
          prop="user_name"
          label="业主姓名">
        </el-table-column>
        <el-table-column
          prop="user_mobile"
          label="业主手机号"
          width="120">
        </el-table-column>
        <el-table-column
          prop="community_name"
          label="业主所在小区"
          width="200">
        </el-table-column>
        <el-table-column
          prop="manager_name"
          label="客户经理姓名"
          show-overflow-tooltip>
        </el-table-column>
        <el-table-column
          prop="manager_mobile"
          label="客户经理手机">
        </el-table-column>
        <el-table-column
          prop="manager_oa"
          label="客户经理OA">
        </el-table-column>
       <!--  <el-table-column
          prop="managedep"
          label="事业部">
        </el-table-column> -->
        <el-table-column
          prop="bind_state"
          label="绑定状态">
        </el-table-column>
        <el-table-column
          label="绑定时间"
          width="200">
          <template slot-scope="scope">{{ scope.row.time_bind }}</template>
        </el-table-column>
        <el-table-column
          label="解绑时间"
          width="200">
          <template slot-scope="scope">{{ scope.row.time_unbind }}</template>
        </el-table-column>
        <el-table-column label="操作" width="200">
          <template slot-scope="scope">
            <el-button
              size="mini"
              plain
              @click="handleUnbind(scope.$index, scope.row)" v-if="scope.row.bind_state != '已解绑'">解绑</el-button>
            <el-button
              size="mini"
              type="warning"
              plain
              @click="handleDetail(scope.$index, scope.row)">查看</el-button>
          </template>
        </el-table-column>
      </el-table>

      <el-button @click="batchunbind" type="danger" plain  style="margin-top: 20px"  v-if="content.data.length > 0">批量解绑</el-button>
      <div class="page">
        <el-pagination
          @current-change="handleCurrentChange"
          :current-page="content.current_page"
          :page-size="content.per_page"
          layout="total, prev, pager, next, jumper"
          :total="content.total">
        </el-pagination>
      </div>
      <div style="margin-top: 60px">
        <el-button type="primary" icon="el-icon-download" @click="exportDatas">导出数据</el-button>
      </div>

      <el-dialog
        title="此操作不可撤回，确认解绑吗?"
        :visible.sync="dialogVisible"
        width="30%"
        :before-close="handleClose" v-loading="loading">
        <span slot="footer" class="dialog-footer">
          <el-button @click="cancelBind">取 消</el-button>
          <el-button type="primary" @click="batchunbindSure">确 定</el-button>
        </span>
      </el-dialog>

    </el-col>
  </el-row>
  <el-row v-else="$route.name == '绑定专属客户经理查看详情'">
    <el-col>
      <transition name="fade" mode="out-in">
         <router-view></router-view>
      </transition>
    </el-col>
  </el-row>
</template>
<style type="text/css" scoped>
  .el-form > div{
    display: inline-block;
  }
  .page{
    float:right;
    margin-top:20px;
    margin-right:40px;
    font-size: 30px;
  }
</style>
<script>
  import { managerList , org , managerUnbind} from '../api/api';
  export default {
   data() {
      return {
        loading:true,
        form: {
          user_name: '',
          user_mobile:'',
          bind_state:'',
          manager_name:'',
          manager_mobile:'',
          manager_oa_username:'',
          org_uuid:'',
          times:[],
        },
        state:[
           {label:'全部',value:"0"},
           {label:'已绑定',value:"1"},
           {label:'已解绑',value:"2"},
           {label:'员工已离职',value:"3"},
        ],
        multipleSelection: [],
        content:{
            current_page:1,
            data:[],
            total:0,
            per_page:0,
        },

        // 组织架构筛选
        dep:{
          uuid1:'',
          uuid2:'',
          uuid3:'',
          uuid4:'',
          uuid5:'',

          option1:'',
          option2:'',
          option3:'',
          option4:'',
          option5:'',
        },

        ids:[],//解绑参数

        dialogVisible: false,

        curRow:{},//当前行
        //access_token
        access_token:'',
      }
    },
    methods:{
      emptyFun(){
        window.location.reload();
      },
      initPage(){
        let param = {
            access_token:this.access_token,
            page:this.content.current_page,
            manager_name:this.form.manager_name,
            manager_mobile:this.form.manager_mobile,
            manager_oa_username:this.form.manager_oa_username,
            org_uuid:this.form.org_uuid,
            user_name:this.form.user_name,
            user_mobile:this.form.user_mobile,
            bind_state:this.form.bind_state,
            time_start:this.form.times[0],
            time_end:this.form.times[1],
        };
        managerList(param).then((res) => {
          this.loading = false;
          if(res.code == "0"){
            this.content = res.content.result;
          }else{
            this.$message.error(res.message);
          }

        });
      },
      search() {
          this.initPage();
      },
      org(obj,uuid){
        let param = {
            access_token:this.access_token,
            org_uuid:this.dep[uuid],
        };
        org(param).then((res) => {
          if(res.code == "0"){
            this.loading = false;
            this.dep[obj] = res.content.result.data;
          }
        });
      },
      dataChange(val){
        if(val != null){
          this.form.times[0] = this.formatDate(val[0]);
          this.form.times[1] = this.formatDate(val[1]);
        }else{
          return;
        }
      },
      formatDate(strTime) {
        var date = new Date(strTime);
        return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
      },
      handleDetail(index,row){
        this.$router.push({path:'/viewdetail', query:{id:row.id, type:"2"},name:"绑定专属客户经理查看详情"});
      },
      handleUnbind(index,row){
        this.dialogVisible = true;
        this.ids = row.id;
      },
      batchunbind() {// 批量解绑
        if(this.multipleSelection.length > 0){
          for(var i = 0;i<this.multipleSelection.length;i++){
            this.ids.push(this.multipleSelection[i].id);
          }
          this.dialogVisible = true;
        }else{
          this.$message.error("没有选中数据！");
        }
      },
      batchunbindSure(){//确认解绑
        this.unbind();
      },
      cancelBind(){ //取消解绑
        this.dialogVisible = false;
        this.ids = [];
      },
      unbind(){//解绑接口
        let param = {
          access_token:this.access_token,
          ids:this.ids.toString(),
        };
        managerUnbind(param).then((res) => {
          this.loading = false;
          if(res.code == "0"){
            this.dialogVisible = false;
            this.$message.success("解绑成功！");
            this.ids = [];
            window.location.reload();
          }else{
            this.$message.error(res.data);
          }
        });
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
        console.log(val);
      },
      handleCurrentChange(val) {
        this.content.current_page = val;
//         alert(this.content.current_page);
        this.initPage();
      },
      exportDatas(){
        window.location.href=base+'/backend/manager/export?access_token='+this.access_token+'&manager_name='+this.form.manager_name+'&manager_mobile='+this.form.manager_mobile +'&manager_oa_username='+this.form.manager_oa_username+'&org_uuid='+this.form.org_uuid+'&user_name='+this.form.user_name+'&user_mobile='+this.form.user_mobile+'&bind_state='+this.form.bind_state+'&time_start='+this.form.times[0]+'&time_end='+this.form.times[1];
        // this.$router.push({path:base+'/backend/manager/export',query:{access_token:this.access_token, manager_name:this.form.manager_name , manager_mobile:this.form.manager_mobile , manager_oa_username:this.form.manager_oa_username , org_uuid:this.form.org_uuid, user_name:this.form.user_name , user_mobile:this.form.user_mobile , bind_state:this.form.bind_state , time_start:this.form.times[0] , time_end:this.form.times[1] }});
      },
      handleClose(done) {
        this.dialogVisible = false;
      }
    },
    computed:{
      uuid1() {
        return this.dep.uuid1;
      },
      uuid2() {
        return this.dep.uuid2;
      },
      uuid3(){
        return this.dep.uuid3;
      },
      uuid4(){
        return this.dep.uuid4;
      }
    },
    watch:{
      uuid1(){
        this.dep.uuid2 = '';
        this.dep.uuid3 = '';
        this.dep.uuid4 = '';
        this.dep.uuid5 = '';
        this.form.org_uuid = this.dep.uuid1;
        this.org("option2","uuid1");
      },
      uuid2(){
        this.dep.uuid3 = '';
        this.dep.uuid4 = '';
        this.dep.uuid5 = '';
        this.form.org_uuid = this.dep.uuid2;
        this.org("option3","uuid2");
      },
      uuid3(){
        this.dep.uuid4 = '';
        this.dep.uuid5 = '';
        this.form.org_uuid = this.dep.uuid3;
        this.org("option4","uuid3");
      },
      uuid4(){
        this.dep.uuid5 = '';
        this.form.org_uuid = this.dep.uuid4;
        this.org("option5","uuid4");
      },
      uuid5(){
        this.form.org_uuid = this.dep.uuid5;
        this.org("","uuid5");
      },
    },
    created() {
      console.log(JSON.parse(window.sessionStorage.getItem('user')));
      if(window.sessionStorage.getItem('user')!=null){
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        this.access_token=localRoutes.access_token;
      }else{
        this.access_token='';
      }
      this.initPage();
      this.org("option1");
    }

  }
</script>
