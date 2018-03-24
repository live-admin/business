<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <!--<el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>-->
        <el-breadcrumb-item>入口考核基数</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>
    <el-col :span="24" class="toolbar" style="padding:10px;">
          <el-cascader
            :options="organizational"
            placeholder="请选择地区"
            change-on-select
            @change="structure"
          ></el-cascader>
          <el-button type="primary" v-on:click="baseSearch">查询</el-button>
          <el-button type="primary" v-on:click="emptyFun">刷新</el-button>
    </el-col>
    <el-col :span="24" class="warp-main">
        <el-table :data="tableData3" v-loading="listLoading" border>
            <el-table-column label="组织架构" width="380">
              <el-table-column prop="region_name" label="大区" width="140">
              </el-table-column>
              <el-table-column prop="branch_name" label="事业部" width="140">
              </el-table-column>
              <el-table-column prop="area_name" label="片区事业部" width="140">
              </el-table-column>
              <el-table-column prop="community_name" label="小区名称" width="140">
              </el-table-column>
            </el-table-column>
            <el-table-column label="资源管理系统数据">
              <el-table-column prop="all_num" label="总户数">
              </el-table-column>
              <el-table-column prop="civilian_num" label="住宅户数">
              </el-table-column>
              <el-table-column prop="commercial_num" label="商铺户数">
              </el-table-column>
              <el-table-column prop="stay_civilian_num" label="已入住住宅户数">
              </el-table-column>
              <el-table-column prop="stay_commercial_num" label="已入住商铺户数">
              </el-table-column>
              <el-table-column prop="stay_num" label="已入住户数">
              </el-table-column>
            </el-table-column>
            <el-table-column label="软硬入口考核基数">
              <el-table-column prop="check_num" label="考核户数">
              </el-table-column>
              <el-table-column prop="converted_pro" label="折算比例">
              </el-table-column>
            </el-table-column>
          <el-table-column label="操作" width="200" style="text-align: center">
            <template slot-scope="scope">
              <el-button size="small" @click="EditorialExamination(scope.$index,scope.row)">编辑</el-button>
            </template>
          </el-table-column>
        </el-table>
        <!--工具条-->
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
        <!--编辑-->
        <el-dialog title="编辑" v-model="editFormVisible" :close-on-click-modal="false">
          <el-form :model="editForm" label-width="120px" :rules="editFormRules" ref="editForm">
            <el-form-item label="已入住商铺户数">
              <el-input v-model="editForm.stay_commercial_num" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="已入住住宅户数">
              <el-input v-model="editForm.stay_civilian_num" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="住宅户数">
              <el-input v-model="editForm.civilian_num" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="商铺户数">
              <el-input v-model="editForm.commercial_num" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="考核户数">
              <el-input v-model="editForm.check_num" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="折算比例">
              <el-input v-model="editForm.converted_pro" auto-complete="off"></el-input>
            </el-form-item>
          </el-form>
          <div slot="footer" class="dialog-footer">
            <el-button @click.native="editFormVisible = false">取消</el-button>
            <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
          </div>
        </el-dialog>
    </el-col>
  </el-row>
</template>
<script>
  import {backOrginfoList,orginfoEdit,backendOrgRegion} from '../../api/api';
  export default{
    data(){
      return {
        listLoading:false,
        page:1,
        page_size:10,
        total: 0,
        currentPage:1,
        org_type:'',
        org_uuid:'',
        tableData3: [{
          LargeArea: '深圳大区事业部',
          Business: '深圳事业部',
          entryName: '彩科大厦',
          households: '1000',
          Proportion: '1.02%',
          Whether: 1,
        }],
        editFormVisible:false,
        editLoading:false,
        editForm:{
          always_num: '',
          civilian_num: '',
          commercial_num: '',
          check_num: '',
          converted_pro:'',
          stay_civilian_num:'',
          stay_commercial_num:'',
          community_uuid:''
        },
        editFormRules: {
          always_num: [
            {required: true, message: '请输入总户数', trigger: 'blur'}
          ],
          civilian_num: [
            {required: true, message: '请输入住宅户数', trigger: 'blur'}
          ],
          commercial_num: [
            {required: true, message: '请输入商铺户数', trigger: 'blur'}
          ],
          check_num: [
            {required: true, message: '请输入考核户数', trigger: 'blur'}
          ],
          converted_pro: [
            {required: true, message: '请输入折算比例', trigger: 'blur'}
          ]
        },
        organizational:[],
      }
    },
    methods:{
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
        this.page = val;
        this.baseList();
      },
      handleCurrentChange(val) {
        this.page = val;
        this.baseList();
      },
      EditorialExamination(index,row){
          this.editFormVisible = true;
          console.log(index);
          console.log(row);
          this.editForm = Object.assign({}, row);
      },
      baseList(){
        let para = {params:{
          page: this.page,
          page_size:this.page_size,
          org_type:this.org_type,
          org_uuid:this.org_uuid,
        }};
        this.listLoading = true;
        backOrginfoList(para).then((res) => {
          console.log(res);
          if(res.code==0){
            this.total = res.content.result.total;
            this.tableData3 = res.content.result.data;
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
      editSubmit(){
        let para = {
          community_uuid: this.editForm.community_uuid ,
          community_id:  this.editForm.community_id ,
          always_num:this.editForm.always_num,
          commercial_num:this.editForm.commercial_num,
          civilian_num:this.editForm.civilian_num,
          check_num:this.editForm.check_num,
          converted_pro:this.editForm.converted_pro,
          stay_civilian_num:this.editForm.stay_civilian_num,
          stay_commercial_num:this.editForm.stay_commercial_num
        };
        orginfoEdit(para).then((res) => {
          console.log(res);
          if(res.code==0){
              if(res.content.result==1){
                this.$notify({
                  title: '成功',
                  message: '编辑成功',
                  type: 'success'
                });
              }
            this.editFormVisible = false;
            this.baseList();
          }else{
            this.$notify.error({
              title: '错误提示',
              message: res.message
            });
          }
        })

      },
      Residential(){
        let OrderListParams ={params:{org_uuid:''}};
        backendOrgRegion(OrderListParams).then(data => {
          if(data.code==0){
            let Array=[];
            for(let i=0;i<data.content.result.data.length;i++){
              let rpl={
                label:data.content.result.data[i].org_name,
                value:data.content.result.data[i].org_uuid,
                children:[]
              };
              Array.push(rpl);
            }
            this.organizational=Array;
          }else{
            this.$notify.error({
              title: '错误提示',
              message: data.message
            });
            if(data.code=='402'){
              this.$router.push({ path: '/login' });
              sessionStorage.removeItem('access-user');
            }
          }
        });
      },
      structure(value){//选择组织架构
        this.org_type=value.length;
        console.log(value);
        for(let i=0;i<value.length;i++){
          this.org_uuid=value[value.length-1];
        }
        if(value.length==1){
          let OrderListParams ={params:{org_uuid:value[0]}
          };
          backendOrgRegion(OrderListParams).then(data => {
            console.log(data);
            if(data.code==0){
              for(let j=0;j<this.organizational.length;j++){
                if(value[0]==this.organizational[j].value){
                  let Array=[];
                  for(let i=0;i<data.content.result.data.length;i++){
                    let rpl={
                      label:data.content.result.data[i].org_name,
                      value:data.content.result.data[i].org_uuid,
                      children:[]
                    };
                    Array.push(rpl);
                  }
                  this.organizational[j].children=Array;
                }
              }
            }else{
              this.$notify.error({
                title: '错误提示',
                message: data.message
              });
              if(data.code=='402'){
                this.$router.push({ path: '/login' });
                sessionStorage.removeItem('access-user');
              }
            }
          });
        }else if(value.length==2){
          let OrderListParams ={params:{org_uuid:value[1]}};
          backendOrgRegion(OrderListParams).then(data => {
            console.log(data);
            if(data.code==0){
              for(let j=0;j<this.organizational.length;j++){
                if(value[0]==this.organizational[j].value){
                  for(let p=0;p<this.organizational[j].children.length;p++){
                    if(value[1]==this.organizational[j].children[p].value){
                      let Array=[];
                      for(let i=0;i<data.content.result.data.length;i++){
                        let rpl={
                          label:data.content.result.data[i].org_name,
                          value:data.content.result.data[i].org_uuid,
                        };
                        Array.push(rpl);
                      }
                      this.organizational[j].children[p].children=Array;
                    }
                  }
                }
              }
            }else{
              this.$notify.error({
                title: '错误提示',
                message: data.message
              });
              if(data.code=='402'){
                this.$router.push({ path: '/login' });
                sessionStorage.removeItem('access-user');
              }
            }
          });
        }
//        else if(value.length==3) {
//          let OrderListParams ={params:{org_uuid:value[2]}};
//          backendOrgRegion(OrderListParams).then(data => {
//            console.log(data);
//            if(data.code==0){
//              for(let j=0;j<this.organizational.length;j++){
//                if(value[0]==this.organizational[j].value){
//                  for(let p=0;p<this.organizational[j].children.length;p++){
//                    if(value[1]==this.organizational[j].children[p].value){
//                      for(let m=0;m<this.organizational[j].children[p].children.length;m++){
//                        if(value[2]==this.organizational[j].children[p].children[m].value){
//                          let Array=[];
//                          for(let i=0;i<data.content.result.data.length;i++){
//                            let rpl={
//                              label:data.content.result.data[i].org_name,
//                              value:data.content.result.data[i].org_uuid
//                            };
//                            Array.push(rpl);
//                          }
//                          this.organizational[j].children[p].children[m].children=Array;
//                        }
//                      }
//                    }
//                  }
//                }
//              }
//            }else{
//              this.$notify.error({
//                title: '错误提示',
//                message: data.message
//              });
//              if(data.code=='402'){
//                this.$router.push({ path: '/login' });
//                sessionStorage.removeItem('access-user');
//              }
//            }
//          });
//        }
      },
      baseSearch(){
        if(this.org_type==''&&this.org_uuid==''){
          this.$notify({
            title: '警告',
            message: '请输入查询信息',
            type: 'warning'
          });
        }else{
          this.baseList();
        }
      },
      emptyFun(){
        window.location.reload();
      },
    },
    mounted() {
      this.baseList();
      this.Residential();
    }
  }
</script>
