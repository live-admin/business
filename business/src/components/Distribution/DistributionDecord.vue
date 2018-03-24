<template>
    <div class="tab-content">
        <ul class="block">
            <!--<li>-->
                <!--<span class="demonstration">收入源</span>-->
                <!--<el-input-->
                        <!--placeholder="请输入收入源"-->
                        <!--v-model="IncomeSources" style="width:160px;">-->
                <!--</el-input>-->
            <!--</li>-->
            <li>
                <span class="demonstration">业务订单</span>
                <el-input
                        placeholder="请输入订单号"
                        v-model="OrderNumbers" style="width:160px;" size="small">
                </el-input>
            </li>
            <!--<li>-->
                <!--<span class="demonstration">选择科目</span>-->
                <!--<el-select v-model="SubjectsID" placeholder="请选择科目" size="small">-->
                    <!--<el-option v-for="item in options" :key="item.id" :label="item.name" :value="item.id">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</li>-->
            <li>
              <el-button type="primary" icon="search" @click="SubjectSearch" size="small"></el-button>
              <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
            <li style="float: right;margin-right:100px;">
              <el-button type="primary" @click="rule_details" size="small">规则详情</el-button>
              <el-button :plain="true" type="info" @click="blackPage" size="small">返回</el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table v-loading="loadListing" :data="DistributionRecordS" border style="font-size:0.8em;">
                <el-table-column prop="id" label="记录编号" width="95">
                </el-table-column>
                <el-table-column prop="general_name" label="商户类型" width="105">
                </el-table-column>
                <el-table-column prop="tag_name" label="科目" width="95">
                </el-table-column>
                <el-table-column prop="business_name" label="商户名称" width="200">
                </el-table-column>
                <el-table-column prop="out_trade_no" label="业务订单号" width="180">
                </el-table-column>
                <el-table-column prop="split_amount" label="业务订单金额（元）" width="100">
                </el-table-column>
                <el-table-column prop="split_amount" label="应分总金额（元）" width="100">
                </el-table-column>
                <el-table-column prop="split_amount" label="实分总金额（元）" width="100">
                </el-table-column>
                <el-table-column prop="update_at" label="分成时间" width="200">
                </el-table-column>
                <el-table-column label="订单状态" width="200">
                  <template slot-scope="scope">
                    <el-popover trigger="hover" placement="top">
                      <p>共6种状态</p>
                      <p>1.未处理</p>
                      <p>2.部分已处理分账到具体用户</p>
                      <p>3.全部已处理分账到具体账户</p>
                      <p>4.全部都处理失败</p>
                      <p>5.部分回滚</p>
                      <p>6.全部回滚</p>
                      <div slot="reference" class="name-wrapper">
                        <el-tag color="#F56C6C" size="small" v-if="scope.row.state==1">未处理</el-tag>
                        <el-tag color="#EDE879" size="small" v-if="scope.row.state==2">部分已处理分账到具体用户</el-tag>
                        <el-tag color="#20A0FF" size="small" v-if="scope.row.state==3">全部已处理分账到具体账户</el-tag>
                        <el-tag color="#93031C" size="small" v-if="scope.row.state==4">全部都处理失败</el-tag>
                        <el-tag color="#3BF579" size="small" v-if="scope.row.state==5">部分回滚</el-tag>
                        <el-tag color="#29CBE3" size="small" v-if="scope.row.state==6">全部回滚</el-tag>
                      </div>
                    </el-popover>
                  </template>
                </el-table-column>
                <el-table-column prop="msg" label="失败原因"  width="200">
                </el-table-column>
                <el-table-column label="操作" width="100">
                    <template slot-scope="scope">
                        <el-button @click="details_Click(scope.$index, scope.row)" type="text" size="small">详情</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <!--分账详情-->
            <el-dialog title="分账详情" :visible.sync="DetailedAccount" size="large">
                <el-table v-loading="loadDetailsing" :data="usersArray" border style="width:100%;float-size:0.8em;">
                    <el-table-column fixed prop="split_target_name" label="分成方" width="150">
                    </el-table-column>
                    <el-table-column label="分账方类型" width="120">
                        <template slot-scope="scope">
                            <el-tag type="primary" v-if="scope.row.split_type==1">个人</el-tag>
                            <el-tag type="primary" v-if="scope.row.split_type==2">岗位</el-tag>
                            <el-tag type="primary" v-if="scope.row.split_type==3">组织</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="split_account_amount" label="分配到账户上的金额(元)" width="120">
                    </el-table-column>
                    <el-table-column prop="split_finance_pano" label="分配的金融平台货币账户" width="120">
                    </el-table-column>
                    <el-table-column prop="split_result" label="分账结果" width="120">
                    </el-table-column>
                    <el-table-column prop="create_at" label="创建时间" width="120">
                    </el-table-column>
                    <el-table-column prop="update_at" label="更新时间" width="120">
                    </el-table-column>
                    <el-table-column prop="orderno" label="订单号（内部记录）" width="120">
                    </el-table-column>
                    <el-table-column prop="out_trade_no" label="商户订单号" width="120">
                    </el-table-column>
                    <el-table-column prop="tag_id" label="科目uuid" width="120">
                    </el-table-column>
                    <el-table-column prop="order_source_id" label="来源订单id" width="120">
                    </el-table-column>
                    <el-table-column prop="general_uuid" label="商户类目uuid" width="120">
                    </el-table-column>
                    <el-table-column prop="general_name" label="商户类目名称" width="120">
                    </el-table-column>
                    <el-table-column prop="community_name" label="小区名称" width="120">
                    </el-table-column>
                    <el-table-column label="状态" width="120">
                        <template slot-scope="scope">
                            <el-tag type="primary" v-if="scope.row.state==1">未处理</el-tag>
                            <el-tag type="primary" v-if="scope.row.state==2">请求金融平台成功</el-tag>
                            <el-tag type="primary" v-if="scope.row.state==3">请求金融平台失败</el-tag>
                            <el-tag type="primary" v-if="scope.row.state==4">金额不足</el-tag>
                        </template>
                    </el-table-column>
                </el-table>
                <!--<div slot="footer" class="dialog-footer">-->
                    <!--<el-button @click.native="addFormVisible = false">取消</el-button>-->
                    <!--<el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>-->
                <!--</div>-->
            </el-dialog>
        </div>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {TagOrderList,SplitLogList,TagList} from './../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                PageDisplay:false,
                SubjectsID: '',//开始科目ID
                IncomeSources: '',
                OrderNumbers: '',
                options: [],
                status:'',//订单状态
                DistributionRecordS: [],
                //新增
                DetailedAccount:false,//分账详情
                addLoading: false,
                addFormRules: {
                    name: [
                        {required: true, message: '请输入角色', trigger: 'blur'}
                    ],
                    description: [
                        {required: true, message: '请输入角色权限说明', trigger: 'blur'}
                    ]
                },
                addForm: {
                    name: '',
                    author: '',
                    publishAt: '',
                    description: ''
                },
                usersArray:[],
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                loadListing:true,
                loadDetailsing:true,
            };
        },
        created: function () {
            this.RecordStart();
            this.tagList();
        },
        methods: {
            emptyFun(){
              window.location.reload();
            },
            rule_details(){
              this.$router.push({path: '/details', query:{map_id:this.$route.query.map_id,business_uuid:this.$route.query.business_uuid}});
            },
            tagList(){//科目列表
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let Params = {access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid};
                TagList(Params).then(data=>{
                    if(data.code==0){
                        this.options=data.content.list;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }

                })
            },
            SubjectSearch(){//科目搜索
                if(this.SubjectsID==''&&this.OrderNumbers==''){
                    this.$message('请输入搜索条件');
                }else{
                    this.RecordStart();
//                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
//                    let SplitLogParams = {
//                        access_token:localRoutes.access_token,
//                        business_uuid:this.$route.query.business_uuid,
//                        tag_uuid:this.SubjectsID,
//                        general_uuid:this.$route.query.general_uuid,
//                        map_id:this.$route.query.map_id,
//                        out_trade_no:this.OrderNumbers,
//                        page:this.currentPage,
//                        page_size:this.page_size
//                    };
//                    TagOrderList(SplitLogParams).then(data=>{
//                        if(data.code==0){
//                            this.DistributionRecordS=data.content.list;
//                            this.total=data.content.total;
//                            if(data.content.total>10){
//                                this.PageDisplay=true;
//                            }
//                        }else{
//                            this.$notify.error({
//                                title: '错误提示',
//                                message: data.message
//                            });
//                        }
//
//                    });
                }

            },
            details_Click(index,row) {
                this.DetailedAccount=true;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                var logParams={access_token:localRoutes.access_token,business_uuid:this.$route.query.business_uuid,tag_order_id:row.id,business_uuid:row.business_uuid};
                SplitLogList(logParams).then(data=>{
                    if(data.code==0){
                        this.loadDetailsing=false;
                        this.usersArray=data.content.list;
//                        this.total=data.content.total;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }

                });
            },
            NewAdd(){//新增
                this.Add_Visible = true;
            },
            blackPage(){
                this.$router.go(-1);
            },
            handleSizeChange(val) {
//                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.RecordStart();
            },
            handleCurrentChange(val) {
//                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.RecordStart();
            },
            RecordStart(){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let SplitLogParams = {
                    access_token:localRoutes.access_token,
                    business_uuid:this.$route.query.business_uuid,
                    general_uuid:this.$route.query.general_uuid,
                    tag_uuid:this.$route.query.tag_uuid,
                    map_id:this.$route.query.map_id,
                    out_trade_no:'',
                    page:this.currentPage,
                    page_size:this.page_size
                };
                TagOrderList(SplitLogParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.DistributionRecordS=data.content.list;
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
                            this.$router.push({ path: '/login' });

                        }
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
    a{text-decoration:none}
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
</style>
