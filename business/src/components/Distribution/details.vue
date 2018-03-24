<template>
    <div class="tab-content">
        <ul class="block">
            <li style="float: right;margin-right:100px;">
                <el-button :plain="true" type="info" @click="AddPersonnel" size="small"> + 添加分成方</el-button>
                <el-button :plain="true" type="info" @click='blackPage' style="display:block;float:right;margin-left:20px;" size="small">返回</el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table v-loading="loadListing" :data="GE_DETAILS" border style="width: 100%">
                <el-table-column fixed prop="id" label="序号" width="80">
                </el-table-column>
                <el-table-column prop="split_target_name" label="分成方" width="200">
                </el-table-column>
                <el-table-column prop="finance_cano" label="金融账号">
                </el-table-column>
                <el-table-column prop="finance_cno" label="用户账号">
                </el-table-column>
                <el-table-column prop="split_type" label="分成方式" width="200">
                   <template slot-scope="scope">
                        <el-tag type="primary" v-if="scope.row.mode==1">按订单奖励固定金额</el-tag>
                        <el-tag type="primary" v-if="scope.row.mode==2">按交易金额百分比</el-tag>
                        <el-tag type="primary" v-if="scope.row.mode==3">按交易金额百分比Pro</el-tag>
                        <el-tag type="primary" v-if="scope.row.mode==4">剩余</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="amount" label="金额（元）" width="80">
                </el-table-column>
                <el-table-column prop="percent" label="比例（%）" width="80">
                </el-table-column>
                <el-table-column prop="upper_limit" label="金额上限" width="100">
                </el-table-column>
                <el-table-column prop="lower_limit" label="金额下限" width="100">
                </el-table-column>
                <el-table-column label="取整方式" width="180">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.truncate==1">保留2位小数</el-tag>
                        <el-tag type="success" v-if="scope.row.truncate==2">向上取整</el-tag>
                        <el-tag type="success" v-if="scope.row.truncate==3">向下取整</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="create_at" label="创建时间" width="180">
                </el-table-column>
                <el-table-column prop="update_at" label="修改时间" width="180">
                </el-table-column>
                <el-table-column prop="creater" label="创建人" width="100">
                </el-table-column>
                <el-table-column label="是否可用">
                    <template slot-scope="scope">
                        <el-tag type="primary" v-if="scope.row.state==1">是</el-tag>
                        <el-tag type="warning" v-else>否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="remark" label="备注" width="100">
                </el-table-column>
                <el-table-column fixed="right" label="操作" width="120">
                    <template slot-scope="scope">
                        <el-button type="success" icon="edit" size="mini" @click="EditPersonnel(scope.$index, scope.row)">编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--添加分成方-->
        <el-dialog title="添加分成方" :visible.sync="personnelS" :close-on-click-modal="false" width="50%">
            <el-form :model="ruleForm" label-width="80px" :rules="ruleFormRules" ref="ruleForm">
                <el-form-item label="分成方式:" prop="mode"  label-width="120px">
                    <el-select v-model="ruleForm.mode" filterable placeholder="请选择分成方式" @change="XZ_FCF" style="width:100%">
                        <el-option
                            v-for="item in SubjectNameS"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="分成方类型:" label-width="120px">
                    <el-radio-group v-model="ruleForm.split_type" @change="split_type_Change">
                        <el-radio :label="1">用户手机(外部对私)</el-radio>
                        <el-radio :label="2">岗位(内部对私)</el-radio>
                        <el-radio :label="3">组织结构(对公)</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="分成方名称:" v-if="ruleForm.split_type==1" label-width="120px">
                       <el-input v-model="ruleForm.split_target" placeholder="请输入分成方手机号码"></el-input>
                </el-form-item>
                <el-form-item label="分成方名称:" v-if="ruleForm.split_type==2" label-width="120px">
                    <el-select v-model="split_target_Uuid" filterable="" remote placeholder="请输入组织架构获取岗位" :remote-method="OrgMethod" :loading="loading" style="width:100%" @change="orgChang">
                        <el-option v-for="(item,index) in OrgPost" :key="index" :label="item.name" :value="item.uuid">
                        </el-option>
                    </el-select>
                    <el-radio-group v-if="jobSwitch" v-model="ruleForm.split_target" @change="JOBId">
                        <el-radio v-for="(item,index) in org_JobArray" :key="index" :label="item.aj_uuid" style="padding:10px 10px;">{{item.job_type}}({{item.realname}})</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="分成方名称:" v-if="ruleForm.split_type==3" label-width="120px">
                    <el-select v-model="split_target_Uuid" filterable="" remote placeholder="请输入组织架构名称" :remote-method="remoteMethod" :loading="loading" style="width:100%" @change="orgTwo_Chang">
                        <el-option v-for="(item,index) in organizational" :key="index" :label="item.name" :value="item.uuid">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="金额¥(单位:分):" v-if="modes==1" label-width="120px">
                        <el-input v-model="ruleForm.amount" placeholder="如104.38元，请输入10438, 只能是整数"></el-input>
                </el-form-item>
                <el-form-item label="比例（%）:" v-if="modes==2||modes==3"  label-width="120px">
                        <el-input v-model="ruleForm.percent" placeholder="请输入内容"></el-input>
                </el-form-item>
                <el-form-item label="金额上限:" v-if="modes==3" label-width="120px">
                        <el-input v-model="ruleForm.upper_limit" placeholder="请输入内容"></el-input>
                </el-form-item>
                <el-form-item label="金额下限:" v-if="modes==3" label-width="120px">
                        <el-input v-model="ruleForm.lower_limit" placeholder="请输入内容"></el-input>
                </el-form-item>
                <el-form-item label="取整方式:"  label-width="120px">
                    <el-select v-model="ruleForm.truncate" filterable placeholder="请选择取整方式" style="width:100%">
                        <el-option
                        v-for="(item,index) in RoundingS"
                        :key="index"
                        :label="item.label"
                        :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="状态:" label-width="120px">
                    <el-radio-group v-model="ruleForm.state">
                        <el-radio :label="1">正常</el-radio>
                        <el-radio :label="2">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="备注信息:"  label-width="120px">
                    <el-input type="textarea" v-model="ruleForm.remark" :rows="3"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="add_endForm('ruleForm')">新增</el-button>
                    <!--<el-button @click="resetForm('ruleForm')">重置</el-button>-->
                </el-form-item>
            </el-form>
            <!--<div slot="footer" class="dialog-footer">-->
                <!--<el-button type="primary" @click.native="add_end">添加完成</el-button>-->
            <!--</div>-->
        </el-dialog>
        <!--编辑分成方-->
        <el-dialog title="编辑分成方" :visible.sync="EditAccounts" :close-on-click-modal="false" width="50%">
            <el-form :model="EditForm" label-width="80px" :rules="EditFormRules" ref="EditForm">
                <!--<el-form-item label="分成方式:"  label-width="120px">-->
                    <!--<el-select v-model="SubjectName" filterable placeholder="请输入选择科目" @change="XZ_FCF" style="width:100%">-->
                        <!--<el-option-->
                                <!--v-for="item in SubjectNameS"-->
                                <!--:key="item.value"-->
                                <!--:label="item.label"-->
                                <!--:value="item.value">-->
                        <!--</el-option>-->
                    <!--</el-select>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="分成方类型:" label-width="120px">-->
                    <!--<el-radio-group v-model="radio">-->
                        <!--<el-radio :label="1">用户手机(外部对私)</el-radio>-->
                        <!--<el-radio :label="2">岗位(内部对私)</el-radio>-->
                        <!--<el-radio :label="3">组织结构(对公)</el-radio>-->
                    <!--</el-radio-group>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="分成方名称:" v-if="radio==1" label-width="120px">-->
                    <!--<el-input v-model="DividedS" placeholder="请输入分成方手机号码"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="分成方名称:" v-if="radio==2" label-width="120px">-->
                    <!--<el-autocomplete class="inline-input" v-model="DividedS" :fetch-suggestions="querySearch" placeholder="请输入组织架构获取岗位" :trigger-on-focus="false" @select="handleSelect" style="width:100%"></el-autocomplete>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="分成方名称:" v-if="radio==3" label-width="120px">-->
                    <!--<el-autocomplete class="inline-input" v-model="DividedS" :fetch-suggestions="querySearch" placeholder="请输入组织架构名称" :trigger-on-focus="false" @select="handleSelect" style="width:100%"></el-autocomplete>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="金额¥(单位:分):" v-if="modes==1" label-width="120px">-->
                    <!--<el-input v-model="Money" placeholder="如104.38元，请输入10438, 只能是整数"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="比例（%）:" v-if="modes==2||modes==3"  label-width="120px">-->
                    <!--<el-input v-model="Proportion" placeholder="请输入内容"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="金额上限:" v-if="modes==3" label-width="120px">-->
                    <!--<el-input v-model="MoneyUpper" placeholder="请输入内容"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="金额下限:" v-if="modes==3" label-width="120px">-->
                    <!--<el-input v-model="MoneyLower" placeholder="请输入内容"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item label="取整方式:"  label-width="120px">-->
                    <!--<el-select v-model="Rounding" filterable placeholder="请选择取整方式" style="width:100%">-->
                        <!--<el-option-->
                                <!--v-for="item in RoundingS"-->
                                <!--:key="item.value"-->
                                <!--:label="item.label"-->
                                <!--:value="item.value">-->
                        <!--</el-option>-->
                    <!--</el-select>-->
                <!--</el-form-item>-->
                <el-form-item label="状态:" label-width="120px">
                    <el-radio-group v-model="EditForm.state">
                        <el-radio :label="1">正常</el-radio>
                        <el-radio :label="2">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="备注信息:"  label-width="120px">
                    <el-input type="textarea" v-model="EditForm.remark" :rows="3"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click.native="Edit_end('EditForm')">编辑完成</el-button>
            </div>
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {SplitList,SplitAdd,OrgList,JobList,SplitEdit} from '../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                radio:1,
                modes:1,
                state:'',
                Money:'',
                restaurants: [],
                Subjects: '',
                IncomeSources: '',
                OrderNumbers: '',
                SubjectName:'',
                DividedS:'',
                Proportion:'',
                MoneyUpper:'',
                MoneyLower:'',
                mode:'',
                SubjectNameS: [{
                    value: '1',
                    label: '按订单奖励固定金额'
                }, {
                    value: '2',
                    label: '按交易金额百分比'
                }, {
                    value: '3',
                    label: '按交易金额百分比Pro'
                }, {
                    value: '4',
                    label: '剩余'
                }],
                Rounding:'',
                RoundingS: [{
                    value: '1',
                    label: '保留2位小数'
                },{
                    value: '2',
                    label: '向上取整'
                },{
                    value: '3',
                    label: '向下取整'
                }],
                status:'',//订单状态
                GE_DETAILS: [],
                //分成方
                personnelS:false,//界面是否显示
                addLoading: false,
                ruleForm: {
                    mode:'',//1:按订单奖励固定金额,2:按交易金额百分比,3:按交易金额百分比Pro，有上下限额，4:剩余分成
                    map_id:'',//规则id
                    split_type:1,//分账方类型，1：个人，2：职位，3：组织
                    split_target:'',//分成方账号，个人为手机号码，职位为职位uuid，组织为组织uuid
                    split_target_name:'',//分成方名称，可以为空
                    finance_cano:'',//用户金融平台货币账号，对应职位时可以为空
                    finance_cno:'',//用户金融平台唯一账号，对应职位时可以为空
                    amount:'',//固定金额
                    percent:'',//比例(%)
                    upper_limit:'',//金额上限
                    lower_limit: '',//金额下限
                    truncate:'',//1:保留2位小数,  2:向上取整, 3:向下取整
                    state:1,//状态，1：正常，2：禁用
                    remark:''//备注
                },
                ruleFormRules: {
                    mode: [
                        {required: true, message: '请选择分成方式', trigger: 'blur'}
                    ]
                },
                organizational:[],//组织架构数组
                loading: false,
                //组织架构获取岗位
                OrgPost:[],
                jobSwitch:false,
                org_JobArray:[],
                EditAccounts:false,
                split_target_Uuid:'',
                EditForm: {
                    split_id:'',//分成方id
                    state:'',//状态，1：正常，2：禁用
                    remark:''//备注
                },
                EditFormRules: {
                    state: [
                        {required: true, message: '请选择状态', trigger: 'change'}
                    ]
                },
                loadListing:true,//加载

            };
        },
        created: function () {
            this.DetailsStart();
        },
        methods: {
            handleSizeChange(val) {
//                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.DetailsStart();
            },
            handleCurrentChange(val) {
//                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.DetailsStart();
            },
            blackPage(){
                this.$router.go(-1);
            },
            DetailsStart(){
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let DetailsParams={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,map_id:this.$route.query.map_id,page:this.currentPage,page_size:this.page_size};
                SplitList(DetailsParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.GE_DETAILS=data.content.list;
                        this.total=data.content.total;
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
                        if(data.code=='402'){
                            sessionStorage.removeItem('user');
                            sessionStorage.removeItem('user_router');
                            _this.$router.push({ path: '/login' });

                        }
                    }
                })

            },
            add_endForm(addForm){//添加分成方
                    this.$refs[addForm].validate((valid) => {
                        if (valid) {
                            this.ruleForm.map_id=this.$route.query.map_id;
                            if(this.ruleForm.split_type==1){
                                this.ruleForm.split_target_name=this.ruleForm.split_target;
                            }else if(this.ruleForm.split_type==2){
                                for(var i=0;i<this.org_JobArray.length;i++){
                                    if(this.org_JobArray[i].uuid==this.ruleForm.split_target){
                                        this.ruleForm.split_target_name=this.org_JobArray[i].job_type+"("+this.org_JobArray[i].realname+")";
                                    }
                                }
                            }else if(this.ruleForm.split_type==3){
                                for(var i=0;i<this.organizational.length;i++){
                                    if(this.organizational[i].uuid==this.ruleForm.split_target){
                                        this.ruleForm.split_target_name=this.organizational[i].name;
                                    }
                                }
                            }
                            let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                            let DetailsParams={access_token:localRoutes.access_token};
                            let SplitAddParams=this.ruleForm;
                            let obj = Object.assign(DetailsParams, SplitAddParams);
                            this.function_add(obj)
                        } else {
                            console.log('error submit!!');
                            return false;
                        }
                    });

            },
            function_add(obj){//添加分成方函数
                SplitAdd(obj).then(data=>{
                    if(data.code==0){
                        this.ruleForm.mode='';
                        this.ruleForm.map_id='';
                        this.ruleForm.split_type=1;
                        this.ruleForm.split_target='';
                        this.ruleForm.split_target_name='';
                        this.ruleForm.finance_cano='';
                        this.ruleForm.finance_cno='';
                        this.ruleForm.amount='';
                        this.ruleForm.percent='';
                        this.ruleForm.upper_limit='';
                        this.ruleForm.lower_limit= '';
                        this.ruleForm.truncate='';
                        this.ruleForm.state='';
                        this.ruleForm.remark='';
                        this.DetailsStart();
                        this.personnelS = false;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
            details_Click() {
                var _this = this;
                _this.$router.push({ path: '/Merchant_details' });
            },
            AddPersonnel(){//新增
                this.personnelS = true;
            },
            XZ_FCF(value){
                this.modes=value;
            },
            EditPersonnel(index,row){//编辑分成方
               this.EditAccounts=true;
               this.EditForm.state=row.state;
               this.EditForm.remark=row.remark;
               this.EditForm.split_id=row.id;
            },
            Edit_end(EditForm){//编辑完成
                this.$refs[EditForm].validate((valid) => {
                    if (valid) {
                        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                        let DetailsParams={ access_token:localRoutes.access_token};
                        let SplitAddParams=this.EditForm;
                        let obj = Object.assign(DetailsParams, SplitAddParams);
                        SplitEdit(obj).then(data=>{
                            if(data.code==0){
                                this.DetailsStart();
                                this.EditAccounts=false;
                            }else{
                                this.$notify.error({
                                    title: '错误提示',
                                    message: data.message
                                });
                            }
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            remoteMethod(query) {//组织架构模糊搜索
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let orgarr={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,keyword:query};
                if (query !== '') {
                    this.loading = true;
                    OrgList(orgarr).then(data=>{
                        if(data.code==0){
                            this.loading = false;
                            this.organizational=data.content.list.list
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    });
                } else {
                    this.organizational = [];
                }
            },
            OrgMethod(query){//组织架构获取岗位
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let orgarr={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,keyword:query};
                if (query !== '') {
                    this.loading = true;
                    OrgList(orgarr).then(data=>{
                        if(data.code==0){
                            this.loading = false;
                            this.OrgPost=data.content.list.list
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    });
                } else {
                    this.organizational = [];
                }
            },
            orgChang(value){//组织架构获取岗位
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                var arrJob={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,org_uuid:value};
                JobList(arrJob).then(data=>{
                    if(data.code==0){
                        this.jobSwitch = true;
                        this.org_JobArray=data.content.list
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                });
            },
            orgTwo_Chang(value){//获取组织架构UUID
                this.ruleForm.split_target=value;
                for(var i=0;i<this.organizational.length;i++){
                    if(this.organizational[i].uuid=value){
                        this.ruleForm.split_target_name=this.organizational[i].name;
                        return;
                    }
                }
            },
            JOBId(value){//获取岗位uuid
                this.ruleForm.split_target=value;
                for(var i=0;i<this.org_JobArray.length;i++){
                    if(this.org_JobArray[i].aj_uuid==value){
                        this.ruleForm.split_target_name=this.org_JobArray[i].job_type+"("+this.org_JobArray[i].realname+")";
                        return;
                    }
                }
//                this.ruleForm.split_target_name=value;
            },
            split_type_Change(){//类型选择函数
                this.ruleForm.split_target='';
                this.split_target_Uuid='';
                this.jobSwitch=false;
                this.org_JobArray=[];
                this.organizational=[];
            }
        },
        mounted() {
//            this.restaurants = this.loadAll();
        }
    };
</script>

<style lang="css">
    .el-form-item_label{
        width:100px;
    }
    /*居中*/
    .cell{
        text-align: center;
    }
    a{text-decoration:none}
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
