<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <!--<el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>-->
        <el-breadcrumb-item>入口管理</el-breadcrumb-item>
        <el-breadcrumb-item>入口类别管理</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <!--列表-->
      <el-table :data="cateGorylist" highlight-current-row v-loading="listLoading"
                style="width: 100%;">
        <el-table-column prop="id" label="序号" width="100"></el-table-column>
        <el-table-column prop="name" label="入口名称" ></el-table-column>
        <el-table-column label="入口类型" width="100">
          <template slot-scope="scope">
            <el-tag v-show="scope.row.type==1" color="#20A0FF">软入口</el-tag>
            <el-tag v-show="scope.row.type==2" class="#ccc">硬入口</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="key" label="秘钥"  width="240"></el-table-column>
        <el-table-column prop="create_time" label="创建时间"></el-table-column>
        <el-table-column label="更新时间" prop="update_time"></el-table-column>
        <el-table-column label="操作" width="150" v-if="Jurisdiction">
          <template slot-scope="scope">
            <el-button size="small" @click="showEditDialog(scope.$index,scope.row)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>

      <!--工具条-->
      <el-col :span="24" class="toolbar">
        <el-col :span="12" style="margin:5px 30px 0 0;" >
          <el-button type="primary" size="small" @click.native="newAddSubmit">新增入口</el-button>
        </el-col>
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
      <!--编辑-->
      <el-dialog title="编辑" v-model="editFormVisible" :close-on-click-modal="false">
        <el-form label-width="100px" ref="editForm">
          <el-form-item label="入口类型">
            <el-radio-group v-model="editForm.type">
              <el-radio :label="1">软入口</el-radio>
              <el-radio :label="2">硬入口</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="入口名称">
            <el-input
              placeholder="请输入入口名称"
              v-model="editForm.name"
              clearable>
            </el-input>
          </el-form-item>
          <el-form-item label="密钥对">
            <el-input
              placeholder="传空则后台生成（可选）"
              v-model="editForm.secret"
              clearable>
            </el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click.native="editFormVisible = false">取消</el-button>
          <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
        </div>
      </el-dialog>
      <!--新增-->
      <el-dialog title="新增" v-model="addFormVisible" :close-on-click-modal="false">
        <el-form label-width="100px" ref="addForm">
          <el-form-item label="入口类型*:">
            <el-radio-group v-model="addForm.type">
              <el-radio :label="1">软入口</el-radio>
              <el-radio :label="2">硬入口</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="入口名称*:">
            <el-input
              placeholder="请输入入口名称"
              v-model="addForm.name"
              clearable>
            </el-input>
          </el-form-item>
          <el-form-item label="密钥*:">
            <el-input
              placeholder="必须是数字/字母/破折号/下划线"
              v-model="addForm.key"
              clearable>
            </el-input>
          </el-form-item>
          <el-form-item label="密钥对:">
            <el-input
              placeholder="传空则后台生成（可选）必须是数字/字母/破折号/下划线"
              v-model="addForm.secret"
              clearable>
            </el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click.native="addFormVisible = false">取消</el-button>
          <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
        </div>
      </el-dialog>
    </el-col>
  </el-row>
</template>
<script>
  import {backendCategoryList,backendCategoryAdd,backendCategoryEdit} from '../../api/api';
  export default{
    data(){
      return {
        Jurisdiction:false,//权限管理
        cateGorylist:[],
        total: 0,
        currentPage:1,
        page: 1,
        listLoading: false,
        page_size:10,
        type:2,
        //编辑相关数据
        editFormVisible: false,//编辑界面是否显示
        editLoading: false,
        editForm:{
          type:'',
          name:'',
          secret:'',
        },
        //添加相关数据
        addFormVisible: false,//编辑界面是否显示
        addLoading: false,
        addForm:{
          name:'',
          key:'',
          secret:'',
          type:'',
        },
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
      ScreeningDate(value){//日期间隔筛选
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
          console.log(strs[i]);
          this.start_time=strs[0];
          this.end_time=strs[1]
        }
      },
      //新增
      newAddSubmit:function () {
        this.addFormVisible = true;
      },
      //新增接口
      addSubmit(){
        if(this.addForm.type!='' && this.addForm.name!=''&& this.addForm.key!=''){
          let addpara={
            key:this.addForm.key,
            name:this.addForm.name,
            secret:this.addForm.secret,
            type:this.addForm.type,
          };
          this.addLoading=true;
          backendCategoryAdd(addpara).then((res) => {
            console.log(res);
            if(res.code==0){
              this.$notify({
                title: '成功',
                message: '编辑修改完成！',
                type: 'success'
              });
              this.addForm='';
              this.addFormVisible = false;
              this.addLoading=false;
              this.ActivityList();
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
              this.addLoading=false;
            }
          })
        }else{
          this.$notify.error({
            title: '错误提示',
            message: '请完善数据（包括类型选择/入口名称/以及秘钥）'
          });
        }
      },
      //显示编辑界面
      showEditDialog: function (index, row) {
        this.editFormVisible = true;
        this.editForm = Object.assign({}, row);
      },
      editSubmit(){
        if(this.editForm.type!='' && this.editForm.name!=''){
          let Editpara={
              id:this.editForm.id,
              name:this.editForm.name,
              secret:this.editForm.secret,
              type:this.editForm.type,
          };
          this.editLoading=true;
          backendCategoryEdit(Editpara).then((res) => {
            console.log(res);
            if(res.code==0){
              this.$notify({
                title: '成功',
                message: '编辑修改完成！',
                type: 'success'
              });
              this.editForm='';
              this.editFormVisible = false;
              this.editLoading=false;
              this.ActivityList();
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
              this.editLoading=false;
            }
          })
        }else{
          this.$notify.error({
            title: '错误提示',
            message: '请完善数据'
          });
        }
      },
      //软入口列表
      ActivityList(){
        let para = {params:{
          page: this.page,
          page_size:this.page_size
        }};
        this.listLoading = true;
        backendCategoryList(para).then((res) => {
          console.log(res);
          if(res.code==0){
            this.total = res.content.total;
            this.cateGorylist = res.content.data;
//            this.hard_Date=res.content.result.date;
          }else{
            this.$notify.error({
              title: '错误提示',
              message: res.message
            });
          }
          this.listLoading = false;
        })
      },
      ScreeningDate(value){//日期筛选
        console.log(value);
        this.editTimes=value;
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
          //document.write(strs[i]+"<br/>"); //分割后的字符输出
          this.start_time=strs[0];
          this.end_time=strs[1]
        }
      },
      Permissions_settings(){
        let user=sessionStorage.getItem('access-user');
        let admin=JSON.parse(user).admin;
        console.log(admin);
        if(admin==1||admin==2){
          this.Jurisdiction=true;
        }else if(admin==3){
          this.Jurisdiction=false;
        }
      }
    },
    mounted() {
      this.Permissions_settings();
      this.ActivityList();
    }
  }
</script>

<style>
  .demo-table-expand label {
    font-weight: bold;
  }
</style>
