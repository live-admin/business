<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">时间</span>
                <el-date-picker v-model="times" type="datetimerange" placeholder="选择时间范围" size="small" style="width: 300px;"></el-date-picker>
            </li>
            <li>
                <span class="demonstration">提现状态</span>
                <el-select v-model="WithdrawalsStart" clearable placeholder="请选择" size="small">
                    <el-option
                            v-for="item in options01"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </li>
            <li>
                <span class="demonstration">提现单号</span>
                <el-input
                        placeholder="请输入商户订单号"
                        v-model="WithdrawalsID" style="width:240px;" size="small">
                </el-input>
            </li>
            <li>
                <span class="demonstration">商户名称</span>
                <el-select
                        v-model="MerchantUUID"
                        :multiple="false"
                        filterable
                        remote
                        reserve-keyword
                        placeholder="请输入商户关键词"
                        :remote-method="remoteMethod"
                        :focus="GetFocus"
                        :loading="loading"
                        size="small">
                    <el-option
                            v-for="item in options"
                            :key="item.uuid"
                            :label="item.name"
                            :value="item.uuid">
                    </el-option>
                </el-select>
            </li>
            <!--<li>-->
                <!--<span class="demonstration">应用名称</span>-->
                <!--<el-select v-model="applicationName" placeholder="请搜索选择应用名称">-->
                    <!--<el-option v-for="item in YY_NAME" :key="item.value" :label="item.label" :value="item.value">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</li>-->
            <!--<li>-->
                <!--<span class="demonstration">订单状态</span>-->
                <!--<el-select v-model="Order_status" placeholder="请选择订单状态">-->
                    <!--<el-option v-for="item in Order" :key="item.value" :label="item.label" :value="item.value">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</li>-->
            <li>
                <el-button type="primary" icon="search" @click="WithdrawalsSearch" size="small"></el-button>
                <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table :data="WithdrawsTableData" v-loading="loadListing" border style="width: 100%">
                <el-table-column prop="name" label="商户名称">
                </el-table-column>
                <el-table-column prop="fanpiao_amount" label="饭票金额">
                </el-table-column>
                <el-table-column prop="amount" label="现金金额">
                </el-table-column>
                <el-table-column prop="payment_no" label="提现单号">
                </el-table-column>
                <el-table-column label="提现状态值">
                    <template slot-scope="scope">
                        <el-tag color="#4395FF" v-if="scope.row.status=='ALL_SUCCESS'">提现成功</el-tag>
                        <el-tag v-else-if="scope.row.status=='UN_REVIEW'">待审核</el-tag>
                        <el-tag v-else-if="scope.row.status=='REVIEWED'">已审核</el-tag>
                        <el-tag v-else-if="scope.row.status=='PROCESSING'">处理中</el-tag>
                        <el-tag v-else-if="scope.row.status=='SUCCESS'">交易成功</el-tag>
                        <el-tag v-else-if="scope.row.status=='REVIEW_FAIL'">审核不通过</el-tag>
                        <el-tag v-else-if="scope.row.status=='PAYMENT_FAIL'">代发失败</el-tag>
                        <el-tag v-else-if="scope.row.status=='1'">申请中</el-tag>
                        <el-tag v-else-if="scope.row.status=='2'">审核完成</el-tag>

                    </template>
                </el-table-column>
                <el-table-column label="通知双乾状态">
                    <template slot-scope="scope">
                        <el-tag color="#4395FF" v-if="scope.row.sq_status==1">通知成功</el-tag>
                        <el-tag type="danger" v-if="scope.row.sq_status==0">通知失败</el-tag>
                    </template>
                </el-table-column>
                <!--<el-table-column prop="payment_uuid" label="支付方式">-->
                <!--</el-table-column>-->
                <!--<el-table-column label="订单状态">-->
                    <!--<template scope="scope">-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==1">未支付</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==2">已付款</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==3">交易成功</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==4">已关闭</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==5">已撤销</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==6">用户支付中</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==7">转入退款</el-tag>-->
                        <!--<el-tag type="primary" v-if="scope.row.trade_state==8">支付失败</el-tag>-->
                    <!--</template>-->
                <!--</el-table-column>-->
                <!--<el-table-column prop="mobile" label="手机号码">-->
                <!--</el-table-column>-->
                <el-table-column label="创单时间">
                  <template slot-scope="scope">
                    <span>{{scope.row.crate_at | time}}</span>
                  </template>
                </el-table-column>
                <el-table-column label="操作" width="240">
                    <template slot-scope="scope">
                        <el-button @click="details_Click(scope.$index, scope.row)" type="primary" v-show="Withdrawals_txxq" size="mini">查看</el-button>
                        <el-button size="mini" type="success" v-if="Withdrawals_txtz" v-show="scope.row.status=='UN_REVIEW'||scope.row.status=='REVIEWED'||scope.row.status=='PROCESSING'||scope.row.status=='SUCCESS'||scope.row.status=='REVIEW_FAIL'||scope.row.status=='PAYMENT_FAIL'" @click="NoticeWithdrawal(scope.$index, scope.row)">提现通知</el-button>
                      <el-button size="mini" type="warning" v-show="scope.row.state==1" @click="Present_audit_review(scope.$index, scope.row)">提现审核</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <el-dialog title="提现详情" v-model="details_Visible" :close-on-click-modal="false" width="50%">
             <div>
                 <ul class="OneOrder">
                     <li>
                         提现订单ID：{{WithdrawsTableDataOne.id}}
                    </li>
                     <li>
                         商户名称：{{WithdrawsTableDataOne.name}}
                    </li>
                     <li>
                         订单现金金额：{{WithdrawsTableDataOne.amount}}
                    </li>
                     <li>
                         商户UUid：{{WithdrawsTableDataOne.business_uuid}}
                    </li>
                     <li>
                         商家提现订单号码：{{WithdrawsTableDataOne.payment_no}}
                    </li>
                     <li>
                         订单双乾通知状态：
                         <el-tag color="#4395FF" v-if="WithdrawsTableDataOne.sq_status==1">通知成功</el-tag>
                         <el-tag type="danger" v-if="WithdrawsTableDataOne.sq_status==0">通知失败</el-tag>
                    </li>
                     <li>
                         饭票提现状态：
                         <el-tag color="#4395FF" v-if="WithdrawsTableDataOne.fp_status==1">提现成功</el-tag>
                         <el-tag type="danger" v-if="WithdrawsTableDataOne.fp_status==0">提现失败</el-tag>
                     </li>
                     <li>
                         饭票金额：{{WithdrawsTableDataOne.fanpiao_amount}}
                    </li>
                     <li>
                         创建时间：
                         {{WithdrawsTableDataOne.crate_at | time}}
                    </li>
                     <li>
                         更新时间:
                         {{WithdrawsTableDataOne.update_at | time}}
                    </li>
                     <li>
                         订单状态：
                         <el-tag color="#4395FF" v-if="WithdrawsTableDataOne.status=='ALL_SUCCESS'">提现成功</el-tag>
                         <el-tag type="danger" v-else="">通知失败</el-tag>
                    </li>
                     <li>
                         是否有备付金账号：
                         <el-tag color="#4395FF" v-if="WithdrawsTableDataOne.is_provision==1">有</el-tag>
                         <el-tag type="danger" v-else="">没有</el-tag>
                     </li>
                     <li>
                         回调地址：{{WithdrawsTableDataOne.notify_url}}
                     </li>
                </ul>
             </div>
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
        <!--下边-->
        <el-col :span="24" style="height:50px;text-align:center;margin-top: 10px;">
          <p style="font-size:0.8em;">双乾网络支付提供互联网支付服务</p>
        </el-col>
      <!--提现审核-->
      <el-dialog  title="提现审核" v-model="Present_audit_start" :close-on-click-modal="false" width="50%">
        <el-form :model="Present_audit_ruleForm" :rules="Present_audit_rules" ref="Present_audit_ruleForm" label-width="100px" class="demo-ruleForm">
          <el-form-item label="提现单号:">
            <div>{{Present_audit_ruleForm.payment_no}}</div>
          </el-form-item>
          <el-form-item label="审核状态" prop="state">
            <el-radio-group v-model="Present_audit_ruleForm.state">
              <el-radio label="2">审核通过</el-radio>
              <el-radio label="3">审核不通过</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="Present_audit_submitForm('Present_audit_ruleForm')">立即创建</el-button>
            <el-button @click="Present_audit_resetForm('Present_audit_ruleForm')">重置</el-button>
          </el-form-item>
        </el-form>
      </el-dialog>
    </div>
</template>
<script>
    import {backendWithdrawsList,adminWthdrawsView,businessSearch,adminNotify,backendRolePrivilegeRole,WithdrawsAuditing} from '../../api/api';
    export default {
        data() {
            return {
                times:'',//时间间隔
                WithdrawalsStart:'',
                options01:[{
                            value: 'ALL_SUCCESS',
                        label: '提现成功'
                    },{
                    value: 'SUCCESS',
                    label: '提现中'
                    },{
                        value: '选项1',
                        label: '提现失败'
                    }],
                WithdrawalsID:'',//提现订单号码
                options: [],
                list: [],
                loading: false,
                startTime:'',
                endTime:'',
                MerchantUUID:'',//商户名称
                WithdrawsTableData: [],//订单列表
                //详情展示数据
                details_Visible:false,//界面是否显示
                WithdrawsTableDataOne:'',//订单详情
                Detail_data:{//展示数据
                },
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                loadListing:true,//加载
                privilege_id:'4',
                Withdrawals_txxq:false,
              Withdrawals_txtz:false,
              //提现审核
              Present_audit_start:false,
              Present_audit_ruleForm: {
                payment_no : '',
                state:'',//状态
              },
              Present_audit_rules: {
                state: [
                  { required: true, message: '请选择审核状态', trigger: 'change' }
                ]
              }
            };
        },
        created: function () {
            this.Start_authority();
            this.OrderStart();
        },
        methods: {
              Start_authority(){
                var _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinesParams={
                  access_token:localRoutes.access_token,
                  privilege_id:this.privilege_id,
                };
                backendRolePrivilegeRole(BusinesParams).then(data=>{
                  console.log(data);
                if(data.code==0){
                  for(var i=0; i<data.content.length;i++){
                    if(data.content[i].name=='提现详情'){
                      this.Withdrawals_txxq=true;
                    }else if(data.content[i].name=='提现通知'){
                      this.Withdrawals_txtz=true;
                    }
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
            emptyFun(){
              window.location.reload();
            },
            details_Click(index,row) {
                var _this = this;
                this.details_Visible = true;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                var WthdrawsParams ={
                    access_token:localRoutes.access_token,
                    business_uuid:row.business_uuid,
                    pay_no:row.payment_no
                };
                adminWthdrawsView(WthdrawsParams).then(data => {
                    if(data.code==0){
                        this.WithdrawsTableDataOne=data.content;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                        if(data.code=='402'){
                            sessionStorage.removeItem('user_router');
                            sessionStorage.removeItem('user');
                            _this.$router.push({ path: '/login' });

                        }
                    }
                });
            },
            NoticeWithdrawal(index,row){//提现通知
                var _this=this;
                this.$confirm('给【'+row.name+'】发送提现通知, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                        let WthdrawsParams ={
                            access_token:localRoutes.access_token,
                            business_uuid:row.business_uuid,
                            pay_no:row.payment_no
                        };
                        adminNotify(WthdrawsParams).then(data => {
                            if(data.code==0){
                                this.$message({
                                    type: 'success',
                                    message: '发送成功!'
                                });
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
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消发送'
                    });
                });
            },
            remoteMethod(query) {//搜素商户
                if (query !== '') {
                    this.loading = true;
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let MerchantSearch={
                        access_token:localRoutes.access_token,
                        name:query
                    };
                    businessSearch(MerchantSearch).then(data=>{
                        this.loading = false;
                        if(data.code==0){
                            this.options =data.content;
                            if(this.options.length==0){
                                this.MerchantName='';
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
                    });
                } else {
                    this.options = [];
                    this.MerchantName='';
                }
            },
            GetFocus(){
                this.options = [];
                this.MerchantName='';
            },
            WithdrawalsSearch(){//搜索
                if(this.WithdrawalsStart==''&&this.MerchantUUID==''&&this.WithdrawalsID==''&&this.times==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入查询信息',
                        type: 'warning'
                    });
                }else{
                    if(this.times!=''){
                        this.startTime=this.get_unix_time(this.format(this.times[0], 'yyyy-MM-dd HH:mm:ss'));
                        this.endTime=this.get_unix_time(this.format(this.times[1], 'yyyy-MM-dd HH:mm:ss'));
                        this.OrderStart();
                    }else{
                        this.OrderStart();
                    }
//                    this.WithdrawalsStart='';
//                    this.MerchantUUID='';
//                    this.WithdrawalsID='';
//                    this.times='';
                }
            },
            format(time,format){//转换为日期
                var t = new Date(time);
                var tf = function(i){return (i < 10 ? '0' :'') + i};
                return format.replace(/yyyy|MM|dd|HH|mm|ss/g,function(a){
                    switch(a){
                        case 'yyyy':
                            return tf(t.getFullYear());
                            break;
                        case 'MM':
                            return tf(t.getMonth() + 1);
                            break;
                        case 'mm':
                            return tf(t.getMinutes());
                            break;
                        case 'dd':
                            return tf(t.getDate());
                            break;
                        case 'HH':
                            return tf(t.getHours());
                            break;
                        case 'ss':
                            return tf(t.getSeconds());
                            break;
                    }
                })
            },
            get_unix_time(dateStr){//转换为时间戳
                let newstr = dateStr.replace(/-/g,'/');
                let date =  new Date(newstr);
                let time_str = date.getTime().toString();
                return time_str.substr(0, 10);
            },
            handleSizeChange(val) {
                this.currentPage=val;
                this.OrderStart();
            },
            handleCurrentChange(val) {
//                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.OrderStart();
            },
            OrderStart(){
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let WithdrawsListParams ={
                    access_token:localRoutes.access_token,
                    business_uuid:this.MerchantUUID,
                    payment_no:this.WithdrawalsID,
                    status:this.WithdrawalsStart,
                    create_time:this.startTime,
                    end_time:this.startTime,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                backendWithdrawsList(WithdrawsListParams).then(data => {
                    if(data.code==0){
                        this.loadListing=false;
                        this.WithdrawsTableData=data.content.data;
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
                });

            },
            Present_audit_review(index,row){//提现审核
              var _this=this;
              _this.Present_audit_start=true;
              console.log(row);
              _this.Present_audit_ruleForm.payment_no=row.payment_no;
            },
            Present_audit_submitForm(formName) {
              var _this=this;
              this.$refs[formName].validate((valid) => {
                if (valid) {
                var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                var queryParams ={
                  access_token:localRoutes.access_token,
                  payment_no:_this.Present_audit_ruleForm.payment_no,
                  state:_this.Present_audit_ruleForm.state
                };
                WithdrawsAuditing(queryParams).then(data => {
                  if(data.code==0){
                  _this.$message({
                    message:data.message,
                    type: 'success'
                  });
                  _this.Refund_audit_start=false;
                }else{
                  _this.$message({
                    message:data.message,
                    type: 'warning'
                  });
        //                    if(data.code=='402'||data.code=='9001'){
        //                      sessionStorage.removeItem('user');
        //                      sessionStorage.removeItem('user_router');
        //                      _this.$router.push({ path: '/login' });
        //                    }
                }
              });
            } else {
              console.log('error submit!!');
              return false;
            }
            });
            },
            Present_audit_resetForm(formName) {
              this.$refs[formName].resetFields();
            }
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
    .box_table{
        text-align: center;
    }
    .OneOrder li{
        height:30px;
    }
    .Store_information li{
        height:30px;
    }
</style>
