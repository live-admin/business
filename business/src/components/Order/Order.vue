<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">组织架构</span>
                <!--<el-cascader :options="organizational" clearable change-on-select size="small" @change="structure" style="width: 500px"></el-cascader>-->
                <el-select
                  v-model="OrgName"
                  :multiple="false"
                  filterable
                  remote
                  reserve-keyword
                  placeholder="请输入组织名称"
                  :remote-method="orgMethod"
                  :focus="GetFocus"
                  :loading="orgloading"
                  size="small">
                  <el-option
                    v-for="item in OrgOptions"
                    :key="item.uuid"
                    :label="item.name"
                    :value="item.uuid">
                  </el-option>
                </el-select>
            </li>
            <li>
                <span class="demonstration">时间</span>
                <el-date-picker v-model="times" type="datetimerange" placeholder="选择时间范围" size="small"></el-date-picker>
            </li>
            <li>
                <span class="demonstration">彩之云订单号</span>
                <el-input
                        placeholder="请输入彩之云订单号"
                        v-model="CzyID" style="width:240px;" size="small">
                </el-input>
            </li>
            <li>
              <span class="demonstration">商户订单号</span>
              <el-input
                placeholder="请输入商户订单号"
                v-model="out_trade_no" style="width:240px;" size="small">
              </el-input>
            </li>
            <li>
                <span class="demonstration">手机号码</span>
                <el-input
                        placeholder="请输入手机号码"
                        v-model="Mobile" style="width:240px;" size="small">
                </el-input>
            </li>
            <li>
                <span class="demonstration">商户名称</span>
                <el-select
                        v-model="MerchantName"
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
                            :value="item.name">
                    </el-option>
                </el-select>
            </li>
            <li>
              <span class="demonstration">类目</span>
              <el-select v-model="MerchantList" clearable placeholder="请选择商户类目" size="small">
                <el-option
                  v-for="item in Merchantoptions"
                  :key="item.uuid"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </li>
            <li>
              <span class="demonstration">支付方式</span>
              <el-select v-model="payment_uuid" clearable placeholder="请选择支付方式" size="small">
                <el-option
                  v-for="item in paymentS"
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
            <li>
                <span class="demonstration">订单状态</span>
                <el-select v-model="Order_status" clearable placeholder="请选择订单状态" size="small">
                    <el-option v-for="item in Order" :key="item.id" :label="item.label" :value="item.id">
                    </el-option>
                </el-select>
            </li>
            <li>
                <el-button type="primary" icon="search" @click="Order_Search" size="small"></el-button>
                <el-button type="primary" icon="el-icon-download" @click="Export" size="small">导出</el-button>
                <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table :data="OrderTableData" v-loading="loadListing" border style="font-size: 0.7em;">
                <el-table-column prop="shop_name" label="商户名称" width="170">
                </el-table-column>
                <el-table-column prop="colour_sn" label="彩之云订单号" width="200">
                </el-table-column>
                <el-table-column prop="out_trade_no" label="商户订单号" width="150">
                </el-table-column>
                <el-table-column prop="community_name" label="小区" width="100">
                </el-table-column>
                <el-table-column label="分账状态" width="200">
                  <template slot-scope="scope">
                    <el-tag type="primary" v-if="scope.row.split_state==0">无需分账</el-tag>
                    <el-tag type="primary" v-if="scope.row.split_state==1">通知失败</el-tag>
                    <el-tag type="primary" v-if="scope.row.split_state==2">通知分账成功</el-tag>
                  </template>
                </el-table-column>
                <el-table-column prop="actual_pay_amount" label="支付金额" width="100">
                </el-table-column>
                <el-table-column prop="payment_name" label="支付方式" width="110">
                </el-table-column>
                <el-table-column label="订单状态"  width="130">
                    <template slot-scope="scope">
                        <el-tag type="primary" v-if="scope.row.trade_state==1">未支付</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==2">已付款</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==3">交易成功</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==4">已关闭</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==5">已撤销</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==6">用户支付中</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==7">转入退款</el-tag>
                        <el-tag type="primary" v-if="scope.row.trade_state==8">支付失败</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="mobile" label="手机号码" width="130">
                </el-table-column>
                <el-table-column label="创单时间" width="200">
                    <template slot-scope="scope">
                        <span>{{scope.row.time_create | time}}</span>
                        <!--二者选一都可以实现-->
                        <!--<span>{{new Date(scope.row.time_pay).toLocaleString()}}</span>-->
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="300">
                    <template slot-scope="scope">
                        <el-button type="primary" v-show="order_ddxq" @click="details_Click(scope.$index, scope.row)" size="mini">查看</el-button>
                        <el-button type="success" size="mini" v-show="order_fztz" v-if="scope.row.general_id!=25" @click="NoticeWithdrawal(scope.$index, scope.row)">分账通知</el-button>
                      <el-button type="success" size="mini" v-show="scope.row.general_id==25" @click="Check_econciliation(scope.$index, scope.row)">核对对账</el-button>
                        <el-button type="info" size="mini" v-show="order_ywtz" @click="BusinessNotifications(scope.$index, scope.row)">业务通知</el-button>
                      <el-button type="warning" v-if="order_ddtk" v-show="scope.row.trade_state==3||scope.row.trade_state==2" size="mini" @click="refund_click(scope.$index, scope.row)">退款</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <el-dialog title="订单详情" v-model="details_Visible" :close-on-click-modal="false" width="50%">
            <ul class="OneOrder">
                <li>
                    商户名称：{{OrderTableDataOne.business_name}}
                </li>
                <!--<li>-->
                    <!--应用名称：{{OrderTableDataOne.general_name}}-->
                <!--</li>-->
                <li>
                    彩之云订单号：{{OrderTableDataOne.colour_sn}}
                </li>
                <li>
                    商户订单号：{{OrderTableDataOne.out_trade_no}}
                </li>
                <!--<li>-->
                    <!--用户标识：{{OrderTableDataOne.open_id}}-->
                <!--</li>-->
                <li>
                    手机号码：{{OrderTableDataOne.mobile}}
                </li>
                <li>
                    订单金额：{{OrderTableDataOne.meal_total_fee}}
                </li>
                <li>
                    支付金额：{{OrderTableDataOne.actual_pay_amount}}
                </li>
                <li>
                    支付方式：{{OrderTableDataOne.payment_uuid}}
                </li>
                <li>
                    支付场景：{{OrderTableDataOne.trade_type}}
                </li>
                <li>
                    支付折扣率：{{OrderTableDataOne.discount}}
                </li>
                <li>
                    备注：{{OrderTableDataOne.detail}}
                </li>
                <!--<li>-->
                    <!--商品详情：{{OrderTableDataOne.detail}}-->
                <!--</li>-->
                <!--<li>终端IP:{{OrderTableDataOne.spbill_create_ip}}</li>-->
                <!--<li>交易开始时间：{{OrderTableDataOne.time_start}}</li>-->
                <li>
                    订单状态：
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==1">未支付</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==2">已付款</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==3">交易成功</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==4">已关闭</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==5">已撤销</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==6">用户支付中</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==7">转入退款</el-tag>
                    <el-tag type="primary" v-if="OrderTableDataOne.trade_state==8">支付失败</el-tag>
                </li>
                <li>创单时间：{{OrderTableDataOne.time_create}}</li>
                <li>支付时间：{{OrderTableDataOne.time_pay}}</li>
                <!--<li>附加信息：{{OrderTableDataOne.remark}}</li>-->
            </ul>
            <ul class="Store_information">
                <!--<li>小区UUID：{{OrderTableDataOne.community_uuid}}</li>-->
                <li>小区名称：{{OrderTableDataOne.e1_name}}</li>
                <li>事业部：{{OrderTableDataOne.e2_name}}</li>
                <li>大区：{{OrderTableDataOne.e3_name}}</li>
                <!--<li>门店名称：{{OrderTableDataOne.shop_name}}</li>-->
                <!--<li>店铺详细地址：{{OrderTableDataOne.address}}</li>-->
            </ul>
        </el-dialog>
      <!-- 退款-->
        <el-dialog  title="订单退款" v-model="refund_Visible" :close-on-click-modal="false" width="50%">
          <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
            <el-form-item label="商户名称:">
              <div>{{ruleForm.shop_name}}</div>
            </el-form-item>
            <el-form-item label="彩之云订单号:">
              <div>{{ruleForm.colour_sn}}</div>
            </el-form-item>
            <el-form-item label="支付金额:">
              <div>{{ruleForm.real_total_fee}} 元</div>
            </el-form-item>
            <el-form-item label="退款金额" prop="refund_fee">
              <el-input v-model="ruleForm.refund_fee" placeholder="单位（元）"></el-input>
            </el-form-item>
            <el-form-item label="退款原因" prop="refund_reason">
              <el-input type="textarea" v-model="ruleForm.refund_reason"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="refund_submitForm('ruleForm')">马上退款</el-button>
              <el-button @click="refund_resetForm('ruleForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-dialog>
        <el-alert
                title="备注：若没有筛选条件，默认导出全部订单数据。"
                type="info"
                :closable="false">
        </el-alert>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
        <!--下边-->
        <el-col :span="24" style="height:50px;text-align:center;margin-top: 10px;">
          <p style="font-size:0.8em;">双乾网络支付提供互联网支付服务</p>
        </el-col>
    </div>
</template>
<script>
    import {OrderList,AdminOrderView,businessSearch,backendOrderOrg,backendOrderExcel,adminOrderNotify,backendBusinessGeneralList,backendRolePrivilegeOrg,backendPayList,adminOrderCallback,adminRefund,backendRolePrivilegeRole} from '../../api/api';
    export default {
        data() {
            return {
                times:'',//时间间隔
                CzyID:'',//彩之云ID
                out_trade_no:'',//商户订单号码
                Mobile:'',//手机号码
                MerchantName:'',//商户名称
                payment_uuid:'',//支付方式UUID
                paymentS:[],//支付数组
                options: [],
                list: [],
                loading: false,
                applicationName:'',//应用名称
                MerchantList:'',//商户类目
                Merchantoptions:[],
                Order:[{
                    id:1,
                    label:"未支付"
                },{
                    id:2,
                    label:"已付款"
                },{
                    id:3,
                    label:"交易成功"
                },{
                    id:4,
                    label:"已关闭"
                },{
                    id:5,
                    label:"已撤销"
                },{
                    id:6,
                    label:"用户支付中"
                },{
                    id:7,
                    label:"转入退款"
                },{
                    id:8,
                    label:"支付失败"
                }],
                Order_status:'',//订单状态
                OrderTableData: [],//订单列表
                //详情展示数据
                details_Visible:false,//界面是否显示
                OrderTableDataOne:'',//订单详情
                Detail_data:{//展示数据
                },
                refund_Visible:false,//退款
                ruleForm: {
                  shop_name:'',
                  colour_sn: '',
                  real_total_fee:'',
                  refund_fee: '',
                  refund_reason: '',
                },
                rules: {
                  refund_fee: [
                    { required: true, message: '输入退款金额', trigger: 'blur' },
//                    { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
                  ],
                  refund_reason: [
                    { required: true, message: '请填写退款原因', trigger: 'blur' }
                  ]
                },
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                loadListing:true,//加载
                startTime:'',//开始时间
                endTime:'',//结束时间
                /////组织架构
                parent_uuid:'',
                organizational: [],
                ORG_Uuid:'',
                sh_url:'',
                OrgName:'',
                OrgOptions:[],
                orgloading: false,
                privilege_id:'3',
                order_ddxq:false,
                order_ywtz:false,
                order_fztz:false,
                order_ddtk:false,
                order_ttjl:false,
            };
        },
        created: function () {
            this.Start_authority();
            this.OrderStart();
            this.Payment_method();
            this.Merchant_category();
            let url=document.domain;
            if(url=="check.account.czytest.colourlife.com"){
              this.sh_url='http://check.account.czytest.colourlife.com';
            }else if(url=="check.account.colourlife.com"){
              this.sh_url='http://check.account.colourlife.com';
            }else if(url=="localhost"){
              this.sh_url='http://check.account.czytest.colourlife.com';
            }
        },
        watch:{
            options:{
//                handler:function(newval,oldval){console.log('...........',newval,oldval)}
            }
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
                  if(data.content[i].name=='订单详情'){
                    this.order_ddxq=true;
                  }else if(data.content[i].name=='业务通知'){
                    this.order_ywtz=true;
                  }else if(data.content[i].name=='分账通知'){
                    this.order_fztz=true;
                  }else if(data.content[i].name=='订单退款'){
                    this.order_ddtk=true;
                  }else if(data.content[i].name=='退款记录'){
                    this.order_ttjl=true;
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
            orgMethod(query){//搜索组织架构
              if (query !== '') {
                this.orgloading = true;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let orgMerchantSearch={
                  access_token:localRoutes.access_token,
                  keyword:query
                };
                backendRolePrivilegeOrg(orgMerchantSearch).then(data=>{
                  this.orgloading = false;
                  if(data.code==0){
                    this.OrgOptions =data.content.list;
                    if(this.OrgOptions.length==0){
                      this.OrgName='';
                    }
                  }else{
                    this.$notify.error({
                      title: '错误提示',
                      message: data.message
                    });
                    if(data.code=='402'||data.code=='9001'){
                      sessionStorage.removeItem('user');
                      sessionStorage.removeItem('user_router');
                      _this.$router.push({ path: '/login' });

                    }
                  }
                });
              } else {
                this.OrgOptions = [];
                this.OrgName='';
              }
            },
            Merchant_category(){
              let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
              let BusinesParams={
                access_token:localRoutes.access_token
              };
              backendBusinessGeneralList(BusinesParams).then(data=>{
                if(data.code==0){
                  this.Merchantoptions=data.content;
                }else{
                  this.$notify.error({
                    title: '错误提示',
                    message: data.message
                  });
                }
              })
            },
            details_Click(index,row) {
                this.details_Visible = true;
                this.OrderTableDataOne='';
//                this.OrderTableDataOne=row;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                var OrderListParams ={
                    access_token:localRoutes.access_token,
                    business_uuid:row.business_uuid,
                    colour_sn:row.colour_sn
                };
                AdminOrderView(OrderListParams).then(data => {
                    if(data.code==0){
                        this.OrderTableDataOne=data.content;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                      if(data.code=='402'||data.code=='1006'||data.code=='9001'){
                        sessionStorage.removeItem('user');
                        sessionStorage.removeItem('user_router');
                        _this.$router.push({ path: '/login' });

                      }
                    }
                });
            },
            handleSizeChange(val) {
//                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.OrderStart();
            },
            handleCurrentChange(val) {
//                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.OrderStart();
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
                          if(data.code=='402'||data.code=='9001'){
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
            Order_Search(){//搜索
                ///////////////////////////////////////
                let datdString=this.times[0];
                this.format(this.times[0], 'yyyy-MM-dd HH:mm:ss');
//                datdString = datdString.replace("GMT", "").replaceAll("\\(.*\\)", "");
//                //将字符串转化为date类型，格式2016-10-12
//                SimpleDateFormat format =  new SimpleDateFormat("EEE MMM dd yyyy hh:mm:ss z",Locale.ENGLISH);
//                Date dateTrans = format.parse(datdString);
//                System.out.println(new SimpleDateFormat("yyyy-MM-dd").format(dateTrans))
                ///////////////////////////////////////
                if(this.OrgName==''&&this.CzyID==''&&this.out_trade_no==''&&this.Mobile==""&&this.MerchantName==''&&this.Order_status==''&&this.times==''&&this.MerchantList==''&&this.payment_uuid==''){
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
//                    this.CzyID='';
//                    this.Mobile="";
//                    this.MerchantName='';
//                    this.Order_status='';
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
            get_unix_time(dateStr){
                let newstr = dateStr.replace(/-/g,'/');
                let date =  new Date(newstr);
                let time_str = date.getTime().toString();
                return time_str.substr(0, 10);
            },
            OrderStart(){
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let OrderListParams ={
                    access_token:localRoutes.access_token,
                    colour_sn:this.CzyID,
                    out_trade_no:this.out_trade_no,
                    payment_uuid:this.payment_uuid,
                    org_uuid:this.OrgName,
                    mobile:this.Mobile,
                    trade_state:this.Order_status,
                    shop_name:this.MerchantName,
                    create_time:this.startTime,
                    end_time:this.endTime,
                    page:this.currentPage,
                    page_size:this.page_size,
                    general_id:this.MerchantList
                };
                OrderList(OrderListParams).then(data => {
                    if(data.code==0){
                        this.loadListing=false;
                        this.OrderTableData=data.content.data;
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
                        if(data.code=='402'||data.code=='9001'){
                            sessionStorage.removeItem('user');
                            sessionStorage.removeItem('user_router');
                            _this.$router.push({ path: '/login' });

                        }
                    }
                });

            },
          //支付方式列表
            Payment_method(){
              let _this=this;
              let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
              let PaymentParams ={
                access_token:localRoutes.access_token,
              };
              backendPayList(PaymentParams).then(data => {
                  console.log(data);
                if(data.code==0){
                  this.paymentS=data.content;
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
            Export(){//导出
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let OrderListParams ={params:{
                        access_token:localRoutes.access_token,
                        colour_sn:this.CzyID,
                        org_uuid:this.ORG_Uuid,
                        mobile:this.Mobile,
                        trade_state:this.Order_status,
                        shop_name:this.MerchantName,
                        create_time:this.startTime,
                        end_time:this.endTime,
                        page:this.currentPage,
                        page_size:this.page_size
                    }
                };
                window.location.href=this.sh_url+'/backend/order/excel?access_token='+localRoutes.access_token+'&colour_sn='+this.CzyID+'&org_uuid='+this.ORG_Uuid+'&mobile='+this.Mobile+'&trade_state='+this.Order_status+'&shop_name='+this.MerchantName+'&create_time='+this.startTime+'&general_id='+this.MerchantList+'&end_time='+this.endTime+'&page='+this.currentPage+'&page_size='+this.page_size;
            },
            NoticeWithdrawal(index,row){//分账通知
                var _this=this;
                this.$confirm('给【'+row.shop_name+'】发送分账通知, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let WthdrawsParams ={
                        access_token:localRoutes.access_token,
                        business_uuid:row.business_uuid,
                        colour_sn:row.colour_sn
                    };
                    adminOrderNotify(WthdrawsParams).then(data => {
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
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消发送'
                    });
                });
            },
            Check_econciliation(index,row){//核对对账
                var _this=this;
                this.$confirm('给【'+row.shop_name+'】发送对账通知, 是否继续?', '提示', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  type: 'warning'
                }).then(() => {
                  let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let WthdrawsParams ={
                  access_token:localRoutes.access_token,
                  business_uuid:row.business_uuid,
                  colour_sn:row.colour_sn
                };
                adminOrderNotify(WthdrawsParams).then(data => {
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
                }
              });
              }).catch(() => {
                this.$message({
                  type: 'info',
                  message: '已取消发送'
                });
              });
            },
            refund_click(index,row){//点击列表退款按钮
      console.log(row);
              this.refund_Visible=true;
              this.ruleForm.shop_name=row.shop_name;
              this.ruleForm.colour_sn=row.colour_sn;
              this.ruleForm.real_total_fee=row.real_total_fee;
            },
            refund_submitForm(formName) {//调用退款接口
              this.$refs[formName].validate((valid) => {
                if (valid) {
                  let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                  let refundParams ={
                    access_token:localRoutes.access_token,
                    colour_sn:this.ruleForm.colour_sn,
                    refund_fee:this.ruleForm.refund_fee,
                    refund_reason:this.ruleForm.refund_reason,
                  };
                  adminRefund(refundParams).then(data => {
                    if(data.code==0){
                      this.$message({
                        type: 'success',
                        message: '退款成功!'
                      });
                      this.refund_Visible=false;
                      this.ruleForm.shop_name='';
                      this.ruleForm.colour_sn='';
                      this.ruleForm.real_total_fee='';
                      this.ruleForm.refund_fee='';
                      this.ruleForm.refund_reason='';
                    }else{
                      this.$notify.error({
                        title: '错误提示',
                        message: data.message
                      });
                    }
                });
              }else{
                console.log('error submit!!');
                return false;
              }
            });
            },
            refund_resetForm(formName) {//重置退款金额和退款说明
              this.$refs[formName].resetFields();
            },
            BusinessNotifications(index,row){//业务通知
              var _this=this;
              _this.$confirm('给【'+row.shop_name+'】发送业务通知, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
              }).then(() => {
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let WthdrawsParams ={
                  access_token:localRoutes.access_token,
                  business_uuid:row.business_uuid,
                  colour_sn:row.colour_sn
                };
                adminOrderCallback(WthdrawsParams).then(data => {
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
                  }
                });
              }).catch(() => {
                this.$message({
                  type: 'info',
                  message: '已取消发送'
                });
              });
            }
        }
    };
</script>

<style lang="css">
    .tab-content{
      font-size:0.9em;
    }
    /*居中*/
    .cell{
        text-align: center;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
        /*overflow: hidden;*/
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
    .OneOrder li{
        height:30px;
    }
    .Store_information li{
        height:30px;
    }
</style>
