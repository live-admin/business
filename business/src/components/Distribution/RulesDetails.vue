<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">选择科目</span>
                <el-select v-model="SubjectsID" placeholder="请选择科目" size="small">
                    <el-option v-for="item in options" :key="item.id" :label="item.name" :value="item.id">
                    </el-option>
                </el-select>
            </li>
            <li>
              <span class="demonstration">是否可用</span>
              <el-select v-model="state" clearable placeholder="请选择" size="small">
                <el-option
                  v-for="item in availableOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </li>
            <li>
                <span class="demonstration">创建人</span>
                <el-input
                placeholder="请输入oa账号"
                v-model="creater" style="width:160px;" size="small">
                </el-input>
            </li>
            <li>
              <el-button type="primary" icon="search" @click="SubjectSearch" size="small"></el-button>
              <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
            <li style="float: right;margin-right:100px;">
                <!--<el-button :plain="true" type="info">科目</el-button>-->
                <el-button :plain="true" type="info" @click="TJ_GZ()" size="small"> + 添加规则</el-button>
                <!--<a href="javascript:history.go(-1);location.reload()" style="display:block;float:right;margin-left:20px;">-->
                    <el-button :plain="true" type="info" @click="blackPage" size="small">返回</el-button>
                <!--</a>-->
                <!--<el-button :plain="true" type="info"><a href="javascript:history.go(-1);location.reload()">返回</a></el-button>-->
            </li>
        </ul>
        <div class="box_table">
            <div class="Business_Name">
                <p>商户名称：{{BusinessName}}</p>
            </div>
            <el-table  v-loading="loadListing" :data="DistributionRecordS" border style="width: 100%;font-size: 0.8em;">
                <el-table-column prop="id" label="分配编号" width="100">
                </el-table-column>
                <el-table-column prop="tag_uuid" label="科目ID" width="100">
                </el-table-column>
                <!--<el-table-column prop="business_name" label="商户名称">-->
                <!--</el-table-column>-->
                <el-table-column prop="tag_name" label="科目名称" width="100">
                </el-table-column>
                <el-table-column prop="create_at" label="添加时间">
                </el-table-column>
                <el-table-column prop="update_at" label="最新更新时间">
                </el-table-column>
                <el-table-column label="是否可用">
                    <template slot-scope="scope">
                        <el-tag type="primary" v-if="scope.row.state==1">是</el-tag>
                        <el-tag type="warning" v-else>否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="creater" label="创建人" width="140">
                </el-table-column>
                <el-table-column label="操作" width="240">
                    <template slot-scope="scope">
                        <el-button type="success" icon="edit" size="mini" @click="EditingRulesEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="success" size="mini" @click="GE_details(scope.$index, scope.row)">规则详情</el-button>
                        <el-button size="mini" type="success" @click="DistributionDecord_Click(scope.$index, scope.row)">分配记录</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--新增规则-->
        <el-dialog title="添加规则" :visible.sync="AddingRules" :close-on-click-modal="false" width="50%">
            <el-form :model="addForm" :rules="addFormRules" ref="addForm">
                <el-form-item label="科目名称" prop="name"  label-width="80px">
                    <el-select v-model="SubjectID" filterable placeholder="请输入选择科目" style="width:100%" @change="XZ_Subject">
                        <el-option
                                v-for="item in SubjectNameS"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer"  label-width="80px">
                <el-button type="warning" @click.native="AddingRules = false">取消</el-button>
                <el-button type="primary" @click.native="add_rule()">创建规则</el-button>
            </div>
        </el-dialog>
        <!--创建分账规则-->
        <el-dialog title="创建分账规则" :visible.sync="CreateRules" :close-on-click-modal="false" width="50%">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="科目:">
                    <div>{{SubjectName}}</div>
                </el-form-item>
                <el-form-item>
                    <el-radio-group v-model="is_copy">
                        <el-radio :label="2">新建规则</el-radio>
                        <el-radio :label="1">选择已有规则</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="商户名称" v-if="is_copy==1">
                    <el-select v-model="YYKU_Income" filterable remote placeholder="请输入选择商户名称" :remote-method="funBusinessName_one" :loading="loading" style="width:100%" @change="orgBusinessChang_one">
                        <el-option v-for="item in BusinessArray" :key="item.uuid" :label="item.name" :value="item.uuid">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="科目名称" v-if="is_copy==1">
                    <el-select v-model="YYKU_name" filterable placeholder="请输入选择科目名称" style="width:100%">
                        <el-option
                                v-for="item in accountArray"
                                :key="item.tag_uuid"
                                :label="item.name"
                                :value="item.tag_uuid">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="是否可用:" v-if="is_copy==2">
                    <el-radio-group v-model="gz_state">
                        <el-radio :label="1">是</el-radio>
                        <el-radio :label="2">否</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="备注" v-if="is_copy==2">
                     <el-input type="textarea" v-model="addForm.remark" :rows="3"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click.native="add_end">完成</el-button>
            </div>
        </el-dialog>
        <!--编辑规则-->
        <el-dialog title="编辑规则" :visible.sync="BJ_EditingRules" :close-on-click-modal="false" width="50%">
            <el-form :model="addForm" :rules="addFormRules" ref="addForm">
                <el-form-item label="科目名称:" label-width="80px">
                    <div>{{Edit_SubjectName}}</div>
                </el-form-item>
                <el-form-item   label-width="80px">
                    <el-radio-group v-model="is_copy" @change="ExistingRules">
                        <el-radio :label="2">新建规则</el-radio>
                        <el-radio :label="1">选择已有规则</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="商户名称" v-if="is_copy==1"   label-width="80px">
                    <el-select v-model="YYKU_Income" filterable="" remote placeholder="请输入关键词" :remote-method="funBusinessName" :loading="loading" style="width:100%" @change="orgBusinessChang">
                        <el-option v-for="item in BusinessArray" :key="item.uuid" :label="item.name" :value="item.uuid">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="科目名称" v-if="is_copy==1"   label-width="80px">
                    <el-select v-model="YYKU_name" filterable placeholder="请输入选择科目" style="width:100%">
                        <el-option
                                v-for="item in accountArray"
                                :key="item.tag_uuid"
                                :label="item.name"
                                :value="item.tag_uuid">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="是否可用:" v-if="is_copy==2"   label-width="80px">
                    <el-radio-group v-model="Edit_radio">
                        <el-radio :label="1">是</el-radio>
                        <el-radio :label="2">否</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="备注" v-if="is_copy==2"   label-width="80px">
                    <el-input type="textarea" v-model="addForm.description" :rows="3"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer"  label-width="80px">
                <el-button type="primary" @click.native="edit_rule">修改完成</el-button>
            </div>
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {RuleList,TagList,RuleAdd,RuleEdit,BusinessList,BusinessTagList} from './../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                BJ_EditingRules:false,//编辑分账规则
                AddingRules:false,//添加规则
                input1:'',//规则名称
                radio:'1',
                SubjectsID: '',
                IncomeSources: '',
                OrderNumbers: '',
                options: [],//刚开始科目搜索列表
                SubjectName: '',//科目名称
                SubjectName_01: '',
                SubjectID:'',//科目id
                SubjectNameS: [],
                status:'',//订单状态
                BusinessName:'',
                BusinessID:'',
                DistributionRecordS:[],
                gz_state:1,
                Edit_SubjectName:'',
                map_id:'',//规则ID
                Edit_radio:'',//编辑时候状态
                //新增规则
                addFormRules: {
                    name: [
                        {required: true, message: '请选择科目名称', trigger: 'blur'}
                    ]
                },
                addForm: {
                    remark: ''//备注信息
                },
                //创建分账规则
                CreateRules:false,
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                loading:false,
                BusinessArray:[],//商户数组
                YYKU_Income:'',//已有收入源
                is_copy:2,
                YYKU_name:'',//已有科目
                accountArray:[],//
                PageDisplay:false,
                loadListing:true,
                creater:'',//创建人
                state:"",//是否可用
                availableOptions:[{
                    value: '1',
                    label: '是'
                  },{
                    value: '2',
                    label: '否'
                  }]

            };
        },
        created: function () {
            this.BusinessName=this.$route.query.businessName;
            this.start();
            this.taglist();
        },
        methods: {
            emptyFun(){
              window.location.reload();
            },
            handleSizeChange(val) {
//                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.start();
            },
            handleCurrentChange(val) {
//                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.start();
            },
            ExistingRules(value){//修改的时候选择已有规则
                if(value==1){

                }
            },
            blackPage(){
                this.$router.go(-1);
            },
              funBusinessName_one(value){//选择已有规则获取商户名称
                this.accountArray=[];
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let name={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,name:value};
                BusinessList(name).then(data=>{
                  if(data.code==0){
                  this.BusinessArray=data.content.list;
    //                            this.MerchantAccount();
                }else{
                  this.$notify.error({
                    title: '错误提示',
                    message: data.message
                  });
                }

              })
            },
            orgBusinessChang_one(id){//获取商户的已创建的科目
              let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
              let uuid={access_token:localRoutes.access_token,business_uuid:id};
              BusinessTagList(uuid).then(data=>{
                if(data.code==0){
                this.accountArray=data.content.list;
              }else{
                this.$notify.error({
                  title: '错误提示',
                  message: data.message
                });
              }

            })
            },
            funBusinessName(value){//选择已有规则获取商户名称
                this.accountArray=[];
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let name={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,name:value};
                BusinessList(name).then(data=>{
                    if(data.code==0){
                            this.BusinessArray=data.content.list;
//                            this.MerchantAccount();
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }

                })
            },
            orgBusinessChang(){//获取商户的已创建的科目
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let uuid={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid};
                BusinessTagList(uuid).then(data=>{
                    if(data.code==0){
                        this.accountArray=data.content.list;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }

                })
            },
            GE_details(index,row){//规则
                var _this=this;
//                _this.$router.push({path:'/details'});
                _this.$router.push({path: '/details', query:{map_id:row.id,business_uuid:row.business_uuid}});

            },
            TJ_GZ(){
                this.AddingRules=true;
                this.SubjectNameS=[];
                this.SubjectID='';
                this.SubjectsID='';
                this.taglist();
            },
            taglist(){//科目列表
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let tag={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid};
                TagList(tag).then(data=>{
                    if(data.code==0){
                        this.SubjectNameS=data.content.list;
                        this.options=data.content.list;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                        if(data.code=='402'){
                            _this.$router.push({ path: '/login' });
                            sessionStorage.removeItem('user');
                            sessionStorage.removeItem('user_router');
                        }
                    }

                })
            },
            SubjectSearch(){
                if(this.SubjectsID==''&&this.creater==''&&this.available==''){
                    this.$message('请选择科目');
                }else{
                    this.start();
                }

            },
            XZ_Subject(value){
                this.SubjectID=value;
            },
            add_end(){//完成结束
                this.AddingRules=false;
                this.CreateRules=false;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let gz_add={
                  access_token:localRoutes.access_token,
                  business_uuid:this.$route.query.business_uuid,
                  tag_uuid:this.SubjectID,
                  state:this.gz_state
                };
                console.log(gz_add);
                RuleAdd(gz_add).then(data=>{
                    if(data.code==0){
                        this.map_id=data.content.result;
                        this.Edit_radio=this.gz_state;
                        this.edit_rule()
//                        this.start();
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
            add_rule(){//创建规则
                this.YYKU_Income='';
                this.YYKU_name='';
                this.BusinessArray=[];
                this.accountArray=[];
                for(var i=0;i<this.SubjectNameS.length;i++){
                    if(this.SubjectID==this.SubjectNameS[i].id){
                        this.SubjectName=this.SubjectNameS[i].name;
                        this.CreateRules=true;
                        return
                    }
                }
            },
            DistributionDecord_Click(index,row){//分成记录
                var _this = this;
                _this.$router.push({path: '/DistributionDecord', query:{map_id:row.id,business_uuid:row.business_uuid,general_uuid:row.general_uuid,tag_uuid:row.tag_uuid}});
            },
            EditingRulesEdit(index,row){
                this.taglist();
      console.log(row);
                this.Edit_SubjectName=row.tag_name;
                this.map_id=row.id;
                this.Edit_radio=row.state;
                this.BJ_EditingRules=true;
                this.BusinessArray=[];
                this.accountArray=[];
                this.YYKU_Income='';
                this.YYKU_name='';
            },
            edit_rule(){
                this.BJ_EditingRules=false;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let EditParams = { access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid, map_id:this.map_id,state:this.Edit_radio,remark:'',is_copy:this.is_copy,copy_business_uuid:this.YYKU_Income,copy_tag_uuid:this.YYKU_name};
                RuleEdit(EditParams).then(data=>{
                    if(data.code==0){
                        this.$notify({
                            title: '成功',
                            message: '修改成功',
                            type: 'success'
                        });
                        this.start();
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message:data.message
                        });
                    }
                })
            },
            start(){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let SplitParams = {
                    access_token:localRoutes.access_token,
                    business_uuid:this.$route.query.business_uuid,
                    tag_uuid:this.SubjectsID,
                    state:this.state,
                    creater:this.creater,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                RuleList(SplitParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.DistributionRecordS=data.content.list;
                        this.total=data.content.total;
                        //this.BusinessName=data.content.list[0].business_name;
                        //this.BusinessID=data.content.list[0].business_uuid;
                        if(data.content.total>10){
                            this.PageDisplay=true;
                        }else{
                            this.PageDisplay=false;
                        }
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                });
            }
        }
    };
</script>

<style lang="css">
    /*居中*/
    .cell{
        text-align: center;
    }
    a{display:block;text-decoration:none;color:#1F2D3D;}
    a:hover{
        color:#50BFFF;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
    }
    .block li{
        float: left;
        margin:5px 10px;
    }
    .block input{
        /*width:80%;*/
    }
    .box_table{
        text-align: center;
    }
    .Business_Name{
        height:35px;
        background:#50BFFF;
        padding: 0 0 0 20px;
    }
    .Business_Name p{
        line-height:35px;
        text-align:left;
    }
</style>
