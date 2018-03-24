<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>
        <el-breadcrumb-item>权限管理</el-breadcrumb-item>
        <el-breadcrumb-item>员工列表</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <el-input v-model="oaId" placeholder="请输入oa账号查询"></el-input><el-button type="primary" @click="search">查询</el-button>
    </el-col>
    <el-col :span="24">
      <el-table
        :data="content.data"
        style="width: 100%"
        v-loading="loadingtable">
        <el-table-column
          type="index"
          label="编号"
          width="100">
        </el-table-column>
        <el-table-column
          prop="oa_username"
          label="oa账号">
        </el-table-column>
        <el-table-column
          prop="name"
          label="姓名">
        </el-table-column>
        <el-table-column
          prop="job_name"
          label="岗位">
        </el-table-column>
        <el-table-column
          prop="mobile"
          label="手机号码">
        </el-table-column>
        <el-table-column
          prop="type_value"
          label="角色类型">
        </el-table-column>
        <el-table-column
          prop="operation_oa"
          label="是否可解绑oa绑定彩之云">
        </el-table-column>
        <el-table-column
          prop="operation_manager"
          label="是否可解绑专属经理">
        </el-table-column>
        <el-table-column label="操作" width="300">
          <template slot-scope="scope">
            <el-button
              size="mini"
              plain
              @click="handleImpower(scope.$index, scope.row)">授权</el-button>
            <el-button
              size="mini"
              type="warning"
              plain
              @click="handlevisibleCommunity(scope.$index, scope.row)">查看可见小区</el-button>  <!--   v-loading.fullscreen.lock="fullscreenLoading" -->
          </template>
        </el-table-column>
      </el-table>
      <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="content.current_page"
      :page-size="10"
      layout="total, prev, pager, next, jumper"
      :total="content.total">
    </el-pagination>
    </el-col>

   <!-- 授权 -->
    <el-dialog title="授权" :visible.sync="dialogImpowerFormVisible"  v-loading="loadding">
      <el-form :model="form">
        <el-form-item label="角色" :label-width="formLabelWidth">
          <el-select v-model="form.type" :placeholder="current.row.type_value">
           <el-option :label="item.label" :value="item.value" v-for="item in type" :key="item.value" value-key="id"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label='是否可解绑"OA绑定彩之云账号"' :label-width="formLabelWidth">
          <el-select v-model="form.operation_oa" :placeholder="current.row.operation_oa">
             <el-option :label="item.label" :value="item.value" v-for="item in operation_oa" :key="item.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label='是否可解绑"绑定专属经理"' :label-width="formLabelWidth">
          <el-select v-model="form.operation_manager" :placeholder="current.row.operation_manager">
             <el-option :label="item.label" :value="item.value" v-for="item in operation_manager" :key="item.value"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogImpowerFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="privilegeSure">确 定</el-button> <!-- v-loading.fullscreen.lock="fullscreenLoading" -->
      </div>
    </el-dialog>

<!-- 编辑可见小区 -->
    <el-dialog title="编辑可见小区" :visible.sync="dialogEditFormVisible" v-loading="loadding">
  <el-form :model="form">
    <el-form-item>
      <el-input v-model="communitySearch" placeholder="搜索小区名称" style="width:60%"></el-input><el-button type="primary" @click="editdialogsearch">搜索</el-button>
    </el-form-item>
    <el-form-item v-if="searchCommunityList.length > 0">
      <el-button
        v-for="tag in searchCommunityList"
        :key="tag.community_uuid"
        @click="addcommonity(tag)"
        size="small"
        type="primary" plain
        >
        {{tag.community_name}}<i class="el-icon-plus el-icon--right"></i>
      </el-button>
    </el-form-item>
    <el-form-item v-if="searchCommunityList.length == 0 && flag">
      <p style="color:#ccc;">没有搜索到相关小区</p>
    </el-form-item>


    <el-form-item v-if="communityList.length != 0" class="visible">
      <p>已有可见小区</p>
      <el-tag
        v-for="tag in communityList"
        :key="tag.name"
        closable
        type="success"
        :disable-transitions="false"
        @close="handleClose(tag)">
        {{tag.community_name}}
      </el-tag>
    </el-form-item>

    <el-form-item v-if="communityList.length == 0">
      <p style="color:#ccc;">暂无可见小区</p>
    </el-form-item>
  </el-form>
</el-dialog>
</el-row>
</template>
<style type="text/css" scoped>
.warp-main .el-input{
  float:left;
  width:20%;
  margin-right:10px;
}
.el-pagination{
  float: right;
  margin-top:20px;
}
.el-form-item__content .el-tag{
  margin-right:10px;
}
</style>
<script>
  import { employeeList , privilege , getCommunity , addVisibleCommunity , delVisibleCommunity , community } from '../api/api';

  export default {
    data() {
      return {
        oaId: '',// 搜索oa账号
        content:{ // 员工列表
            current_page:1,
            data:[],
            total:0,
        },

        dialogImpowerFormVisible: false,
        dialogEditFormVisible: false,
        form: {
          type:'',
          operation_oa:'',
          operation_manager:'',
        },

        // 角色
        type:[
            {label:'管理员',value:'2'},
            {label:'操作人员',value:'3'}
        ],
        // 是否可解绑"OA绑定彩之云账号
        operation_oa:[
          {label:'是',value:'1'},
          {label:'否',value:'0'}
        ],
        // 是否可解绑"绑定专属经理"
        operation_manager:[
          {label:'是',value:'1'},
          {label:'否',value:'0'}
        ],

        formLabelWidth: '250px',

        current:{
          index:'',
          row:{},
        },

        // 可见小区
        communityList: [],

        searchCommunityList:[],

        communitySearch:'',

        loadingtable:true,// 加载表格loading
        fullscreenLoading:false,// 授权loadding
        loadding:false,

        rowoausername:'',

        flag:false,// 没有搜索到相关小区第一次不显示

      }
    },
    methods: {
      initPage() {
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          page:this.content.current_page,
          oa_username:this.oaId,
          type:'',
          disable:'',
          operation_oa:'',
          operation_manager:'',
        };
        employeeList(param).then((res) => {
          this.loadingtable = false;
          if(res.code == "0"){
            this.content = res.content.result;
            for(var i = 0;i<this.content.data.length;i++){
              if(this.content.data[i].operation_oa == "1"){
                  this.content.data[i].operation_oa = "是";
              }else{
                  this.content.data[i].operation_oa = "否";
              }
              if(this.content.data[i].operation_manager == "1"){
                  this.content.data[i].operation_manager = "是";
              }else{
                  this.content.data[i].operation_manager = "否";
              }

             }
          }else{
            this.$message.error(res.message);
          }
        });
      },
      search(){
        console.log(this.oaId);
        if(this.oaId){
          this.initPage();
        }else{
          this.$message.error("请输入oa账号查询");
        }

      },
      handleImpower(index,row){
        this.dialogImpowerFormVisible = true;
        this.current.index = index;
        this.current.row = row;
      },
      handlevisibleCommunity(index,row){
        this.dialogEditFormVisible = true;
        this.loadding = true;
        this.rowoausername = row.oa_username;
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          oa_username:row.oa_username,
          community_name:'',
          page:'',
        };
        getCommunity(param).then((res) =>{
          this.loadding = false;
          if(res.code == "0"){
            this.communityList = res.content.result.data;
          }else{
            this.$message.error(res.message);
          }
        })
      },
      privilegeSure(){
        this.dialogImpowerFormVisible = false;
        // this.fullscreenLoading = true;
        this.loadding = true;
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          oa_username:this.current.row.oa_username,
          type:this.form.type,
          status:'',
          operation_oa:this.form.operation_oa,
          operation_manager:this.form.operation_manager,
        };
        privilege(param).then(data =>{
          // this.fullscreenLoading = false;
          this.loadding = false;
          if(data.code == "0" && data.content.result == "1"){
              if(this.form.type !== ''){
                if(this.form.type == "2"){
                  this.content.data[this.current.index].type_value = "管理员";
                }else if(this.form.type == "3"){
                  this.content.data[this.current.index].type_value = "操作人员";
                }
              }

              if(this.form.operation_oa !== ''){
                if(this.form.operation_oa == "1"){
                  this.content.data[this.current.index].operation_oa = "是";
                }else if(this.form.operation_oa == "0"){
                  this.content.data[this.current.index].operation_oa = "否";
                }
              }

              if(this.form.operation_manager !== ''){
                if(this.form.operation_manager == "1"){
                  this.content.data[this.current.index].operation_manager = "是";
                }else if(this.form.operation_manager == "0"){
                  this.content.data[this.current.index].operation_manager = "否";
                }
              }
              this.$message.success("授权成功");
          }else{
              this.$message.error(data.message);
          }
        })
      },
      editdialogsearch:function(){
        this.flag = true;
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          name:this.communitySearch,
        };
        community(param).then(data =>{
          if(data.code == "0"){
            this.searchCommunityList = data.content.result;
          }else{
              this.$message.error(data.message);
          }
        })
      },
      addcommonity(tag){
        this.loadding = true;
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          oa_username:this.rowoausername,
          community_uuid:tag.community_uuid,
          community_name:tag.community_name,
        };
        addVisibleCommunity(param).then(data =>{
          this.loadding = false;
          if(data.code == "0"){
            this.communityList.push(tag);
          }else{
            this.$message.error(data.message);
          }
        })
      },
      handleClose(tag){
        this.loadding = true;
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          oa_username:this.rowoausername,
          community_uuid:tag.community_uuid,
          community_name:tag.community_name,
        };
        delVisibleCommunity(param).then(data =>{
          this.loadding = false;
          if(data.code == "0"){
            this.communityList.splice(this.communityList.indexOf(tag), 1);
          }else{
              this.$message.error(data.message);
          }
        })

      },
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
      },
      handleCurrentChange(val) {
        this.content.current_page = val;
        this.initPage();
      },
    },
    created() {
      this.initPage();
    }

  }
</script>
