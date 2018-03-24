<template>
    <div class="tab-content">
        <ul class="block">
            <li style="float:left;">
                <span class="demonstration">角色名称</span>
                <el-input
                        placeholder="请输入角色名称"
                        v-model="RoleName" style="width:240px;" size="small">
                </el-input>
                <el-button type="primary" icon="search" @click="RoleSearch" size="small"></el-button>
                <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
            <li style="float: right;margin-right:100px;">
                <el-button type="info" @click="NewAdd" size="small"> + 新增角色</el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table :data="tableData" v-loading="loadListing" border style="width: 100%;font-size:0.8em">
                <el-table-column label="角色名称" width="200" text-align: center >
                    <template slot-scope="scope">
                        <span style="margin-left: 10px">{{ scope.row.name}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="角色权限说明" text-align: center >
                    <template slot-scope="scope">
                        <span style="margin-left: 10px">{{ scope.row.desc}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="300">
                    <template slot-scope="scope">
                        <el-button size="mini" type="success" icon="edit" @click="role_Edit(scope.$index, scope.row)">编辑</el-button>
                        <el-button size="mini" type="info" icon="user-renyuan" @click="role_user(scope.$index, scope.row)">员工</el-button>
                        <!--<el-button size="small" type="danger" icon="delete" @click="role_Delete(scope.$index, scope.row)">删除</el-button>-->
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--新增界面-->
        <el-dialog title="新增角色" v-model="Add_Visible" :close-on-click-modal="false" width="50%">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="角色名称" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="权限说明" prop="desc">
                    <el-input type="textarea" v-model="addForm.desc" :rows="8"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addCancelRole('addForm')">取消</el-button>
                <el-button type="primary" @click.native="addSubmit('addForm')" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>
        <!--员工添加-->
        <el-dialog title="新增员工" v-model="Role_authorization" :close-on-click-modal="false" width="50%">
            <ul class="block">
                <li>
                    <span class="demonstration">oa账号</span>
                    <el-select
                            v-model="value"
                            :multiple="false"
                            filterable
                            remote
                            reserve-keyword
                            placeholder="请输入关键词"
                            :remote-method="remoteMethod"
                            :focus="GetFocus"
                            :loading="loading"
                            style="margin-bottom:20px;">
                        <el-option
                                v-for="item in options4"
                                :key="item.employeeAccount"
                                :label="item.realname+'('+item.jobName+')'"
                                :value="item.employeeAccount">
                        </el-option>
                    </el-select>
                    <el-button type="primary" icon="plus" @click="AddingOA"></el-button>
                </li>
                <li style="margin:10px 0">
                    <el-tag
                            v-for="tag in tags"
                            :key="tag.employee_account"
                            :closable="true"
                            type="primary"
                            @close="handleClose(tag)"
                            style="margin:3px 5px;">
                        {{tag.name}}
                    </el-tag>
                </li>
            </ul>
            <!--<el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">-->
                <!--<el-form-item label="OA账号" prop="name">-->
                    <!--<el-input v-model="addForm.name" auto-complete="off"></el-input>-->
                <!--</el-form-item>-->
                <!--&lt;!&ndash;<el-form-item label="权限说明" prop="author">&ndash;&gt;-->
                <!--&lt;!&ndash;<el-input v-model="addForm.author" auto-complete="off"></el-input>&ndash;&gt;-->
                <!--&lt;!&ndash;</el-form-item>&ndash;&gt;-->
                <!--&lt;!&ndash;<el-form-item label="出版日期">&ndash;&gt;-->
                <!--&lt;!&ndash;<el-date-picker type="date" placeholder="选择日期" v-model="addForm.publishAt"></el-date-picker>&ndash;&gt;-->
                <!--&lt;!&ndash;</el-form-item>&ndash;&gt;-->
                <!--<el-form-item label="现有员工:">-->
                    <!--<template scope="scope">-->
                        <!---->
                    <!--</template>-->
                <!--</el-form-item>-->
            <!--</el-form>-->
            <!--<div slot="footer" class="dialog-footer">-->
                <!--<el-button @click.native="addFormVisible = false">取消</el-button>-->
                <!--<el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>-->
            <!--</div>-->
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {backendRoleList,backendRoleAdd,employeeAddEmployee,employeeGetRole,EmployeeApiSearch,employeeUpdateRole,employeeUpdateStatus,rolePrivilegeDel,backendEmployeeDel,backendRoleSearch} from '../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                RoleName:'',//角色名称
                tableData: [],
                //新增
                Add_Visible:false,//界面是否显示
                addLoading: false,
                addFormRules: {
                    name: [
                        {required: true, message: '请输入角色', trigger: 'blur'}
                    ],
                    desc: [
                        {required: true, message: '请输入角色权限说明', trigger: 'blur'}
                    ]
                },
                addForm: {
                    name: '',
                    desc: ''
                },
                tags: [],//已添加人员
                Role_authorization:false,//角色授权
                currentPage:1,//显示页数
                page_size:15,//每页数量
                total:1,//总页数
                PageDisplay:false,
                //搜索
                options4: [],
                nameArray:[],
                value:'',
                list: [],
                loading: false,
                //角色ID
                RoleOneID:'',
                loadListing:true,//加载loading
                user_on:true,
            };
        },
        created: function () {
            this.RoleList();
        },

        methods: {
            emptyFun(){
              window.location.reload();
            },
            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.RoleList();
            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.RoleList();
            },
            RoleList(){//角色管理列表
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                console.log(localRoutes.access_token);
                let RoleParams={
                    access_token:localRoutes.access_token,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                backendRoleList(RoleParams).then(data=>{
                    console.log(data);
                    if(data.code==0){
                        this.loadListing=false;
                        this.tableData=data.content.data;
                        this.total=data.content.total;
                        if(data.content.total>15){
                            this.PageDisplay=true;
                        }else{
                            this.PageDisplay=false;
                        }
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                        console.log(data.code);
                        if(data.code=='402'||data.code=='9001'){
                            this.$router.push({ path: '/login' });
                            sessionStorage.removeItem('user');
                        }
                    }
                })
            },
            RoleSearch(){//角色搜索
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                if(this.RoleName==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入角色名称',
                        type: 'warning'
                    });
                }else{
                    let RoleParams={
                        access_token:localRoutes.access_token,
                        name:this.RoleName
                    };
                    backendRoleSearch(RoleParams).then(data=>{
                        if(data.code==0){
                            this.tableData=data.content;
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    })
                }
            },
            addSubmit(addForm){//添加角色
                this.$refs[addForm].validate((valid) => {
                    if (valid) {
                        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                        let DetailsParams={ access_token:localRoutes.access_token};
                        let SplitAddParams=this.addForm;
                        let obj = Object.assign(DetailsParams, SplitAddParams);
                        backendRoleAdd(obj).then(data=>{
                            if(data.code==0){
                                this.$notify({
                                    title: '成功',
                                    message: '添加成功',
                                    type: 'success'
                                });
                                this.RoleList();
                                this.Add_Visible = false;
                            }else{
                                this.$notify.error({
                                    title: '错误提示',
                                    message: data.message
                                });
                            }
                        })
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            remoteMethod(query) {
                if (query !== '') {
                    this.loading = true;
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let MerchantSearch={
                        access_token:localRoutes.access_token,
                        name:query
                    };
                    EmployeeApiSearch(MerchantSearch).then(data=>{
                        this.loading = false;
                        if(data.code==0){
                            this.options4 =data.content;
                            if(this.options4.length!=0){
                                this.nameArray=this.options4;
                                this.user_on=true;
                            }
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    });
                } else {
                    this.options4 = [];
                    this.value='';
                }
            },
            GetFocus(){
                this.options4 = [];
                this.value='';
                this.nameArray=[];
            },
            role_user(index,row){//员工添加
                this.value='';
                this.options4='';
                this.RoleOneID=row.id;
                this.Role_authorization=true;
                this.SeeOA();
            },
            SeeOA(){//查看ID
                this.tags=[];
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let peopleSSearch={
                    access_token:localRoutes.access_token,
                    role_id:this.RoleOneID
                };
                employeeGetRole(peopleSSearch).then(data=>{
                    if(data.code==0){
                        for(let i=0;i<data.content.length;i++){
                            let OneArray={
                                name:data.content[i].realname,
                                id:data.content[i].employee_account
                            };
                            this.tags.push(OneArray)
                        }
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                });
            },
            handleClose(value){//删除OA与角色关联
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let peopleDELSearch={
                    access_token:localRoutes.access_token,
//                    role_id:this.RoleOneID,
                    employee_account:value.id,
                    role_id:0
                };
                employeeUpdateRole(peopleDELSearch).then(data=>{
                    if(data.code==0){
                        this.SeeOA();
//                        this.GetFocus();
                        this.$notify({
                            title: '成功',
                            message: '账号删除成功',
                            type: 'success'
                        });
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                });
            },
            AddingOA(){//添加OA账号
                if(this.nameArray.length==0){
                    this.$notify({
                        title: '警告',
                        message: '请输入搜索信息',
                        type: 'warning'
                    });
                }else{
                  if(this.user_on){
                    this.user_on=false;
                    for(let i=0;i<this.nameArray.length;i++){
                        if(this.nameArray[i].employeeAccount==this.value){
                            let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                            let peopleSearch={
                                access_token:localRoutes.access_token,
                                role_id:this.RoleOneID,
                                employee_account:this.nameArray[i].employeeAccount
                            };
                            employeeAddEmployee(peopleSearch).then(data=>{
                              if(data.code==0){
                                this.SeeOA();
                                this.GetFocus();
                                this.$notify({
                                  title: '成功',
                                  message: '账号添加成功',
                                  type: 'success'
                                });
                              }else{
                                this.$notify.error({
                                  title: '错误提示',
                                  message: data.message
                                });
                              }
                            });
                            return;
                          }
                        }
                    }
                }
            },
            addCancelRole(addForm){
                this.$refs[addForm].resetFields();
            },
            details_Click() {
                var _this = this;
                _this.$router.push({ path: '/Merchant_details' });
            },
            NewAdd(){//新增
                this.Add_Visible = true;
            },
            role_Delete(index,row){//删除
            },
            role_Edit(index,row){//编辑
                let _this = this;
                _this.$router.push({ path: '/roleAuthorization',query:{role_id:row.id}});
            },
        }
    };
</script>

<style lang="css">
    /*居中*/
    .cell{
        text-align: center;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
        overflow: hidden;
    }
    .block li{
      float: left;
      margin:5px 5px;
    }
    .block input{
        /*width:80%;*/
    }
    .box_table{
        text-align: center;
    }
</style>
