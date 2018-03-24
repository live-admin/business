<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">商户名称</span>
                <el-input
                        placeholder="请输入商户名称"
                        v-model="MerchantName" style="width:240px;"  size="small">
                </el-input>
            </li>
            <li>
                <span class="demonstration">联系方式</span>
                <el-input
                        placeholder="请输入手机号码"
                        v-model="Mobile" style="width:240px;" size="small">
                </el-input>
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
                <span class="demonstration">状态</span>
                <el-select v-model="status" clearable placeholder="请选择商户状态" size="small">
                    <el-option v-for="item in DDStatus" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </li>
            <li>
                <el-button type="primary" icon="search" @click="MerchantSearch" size="small"></el-button>
                <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
            <!--<el-button type="info">新增</el-button>-->
        </ul>
        <div class="box_table">
            <el-table v-loading="loadListing" :data="BusinesstableData" border style="font-size:0.8em">
                <el-table-column prop="id" label="序号" width="100">
                </el-table-column>
                <el-table-column prop="uuid" label="商户uuid" width="190">
                </el-table-column>
                <el-table-column prop="name" label="商户名称" width="200">
                </el-table-column>
                <el-table-column prop="general_name" label="类目" width="150">
                </el-table-column>
                <el-table-column prop="state" label="状态" width="150">
                    <template slot-scope="scope">
                        <el-tag type="primary" v-if="scope.row.state==0">未审核</el-tag>
                        <el-tag type="warning" v-if="scope.row.state==1">启用</el-tag>
                        <el-tag type="danger"  v-if="scope.row.state==2">禁用</el-tag>
                        <el-tag type="danger"  v-if="scope.row.state==3">审核不通过</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="legal_person" label="法人" width="130">
                </el-table-column>
                <el-table-column prop="mobile" label="联系方式" width="140">
                </el-table-column>
                <el-table-column label="创建时间" width="220">
                    <template slot-scope="scope">
                        <span>{{scope.row.create_time | time}}</span>
                        <!--二者选一都可以实现-->
                        <!--<span>{{new Date(scope.row.create_time).toLocaleString()}}</span>-->
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="240">
                    <template slot-scope="scope">
                        <el-button type="info" size="mini" v-show="Merchant_XQ" @click="details_Click(scope.$index, scope.row)">详情</el-button>
                        <el-button type="success" size="mini" v-show="Merchant_TX" @click="Business_Edit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="warning" size="mini" v-show="scope.row.state==1&&Merchant_JY" @click="open2(scope.$index, scope.row)">禁用</el-button>
                        <el-button type="primary" size="mini" v-show="scope.row.state==2&&Merchant_QY" @click="open3(scope.$index, scope.row)">启用</el-button>
                        <el-button type="success" size="mini" v-show="Merchant_TX" @click="Business_owners(scope.$index, scope.row)">提现</el-button>
                        <!--<el-button type="text" size="small" @click="PaymentSettings(scope.$index, scope.row)">支付配置</el-button>-->
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--商户详情-->
        <el-dialog title="商户详情" :visible.sync="Merchant_Visible" :close-on-click-modal="false" width="50%">
            <ul class="Single_merchant">
                <li>
                    <h3>商户信息</h3>
                </li>
                <li>
                    <div>商户名称：</div>
                    <div>{{oneBusinesstableData.name}}</div>
                </li>
                <li>
                    <div>入驻平台：</div>
                    <div>{{oneBusinesstableData.general_name}}</div>
                </li>
                <!--<li>-->
                    <!--<div>商户行政区：</div>-->
                    <!--<div>广东省深圳市宝安区</div>-->
                <!--</li>-->
                <li>
                    <div>商户地址：</div>
                    <div>{{oneBusinesstableData.address}}</div>
                </li>
                <li id="logo_box">
                    <div class="logo_title">
                        <p>商户logo：</p>
                    </div>
                    <!--<div class="logo_picture"><img src="../../assets/logo.png" alt=""></div>-->
                </li>
                <li id="Merchant_introduction">
                    <div class="MI_title"><p>商户介绍：</p></div>
                    <div class="content">
                        {{oneBusinesstableData.desc}}
                    </div>
                </li>
                <li>
                    <div>联系电话：</div>
                    <div>{{oneBusinesstableData.mobile}}</div>
                </li>
                <li>
                    <div>商户状态：</div>
                    <div v-if="oneBusinesstableData.state==0">未审核</div>
                    <div v-if="oneBusinesstableData.state==1">启用</div>
                    <div v-if="oneBusinesstableData.state==2">禁用</div>
                    <div v-if="oneBusinesstableData.state==3">审核不通过</div>
                </li>
            </ul>
            <ul class="information">
                <li>
                    <h3>资质信息</h3>
                </li>
                <li>
                    <div>法人姓名：</div>
                    <div>{{oneBusinesstableData.legal_person}}</div>
                </li>
                <li id="PictureSize">
                    <div class="sfztp"><p>营业执照：</p></div>
                    <div class="pic">
                        <!--<img src="../../assets/营业执照.jpg" alt="">-->
                    </div>
                </li>
                <li id="PictureSize">
                    <div class="sfztp"><p>身份证正面照片：</p></div>
                    <div class="pic">
                        <!--<img src="../../assets/sfzbm.png" alt="">-->
                    </div>
                </li>
                <li id="PictureSize">
                    <div class="sfztp"><p>身份证反面照片：</p></div>
                    <!--<div class="pic"><img src="../../assets/sfzbm.png" alt=""></div>-->
                </li>
            </ul>
            <ul class="Collection">
                <li>
                    <h3>收款信息</h3>
                </li>
                <li>
                    <div>收款银行名称：</div>
                    <div>{{oneBusinesstableData.bank_name}}</div>
                </li>
                <li>
                    <div>收款银行卡号：</div>
                    <div>{{oneBusinesstableData.bank_card}}</div>
                </li>
            </ul>
            <ul v-show="oneBusinesstableData.state==0">
                <li><h3>审核状态</h3></li>
                <li>
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="状态：">
                            <el-radio-group v-model="form.resource">
                                <el-radio :label="1">通过</el-radio>
                                <el-radio :label="3">不通过</el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="原因：">
                            <el-input type="textarea" v-model="form.desc" style="width:75%"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="AuditPassed">确定</el-button>
                        </el-form-item>
                    </el-form>
                </li>
            </ul>
        </el-dialog>
        <!--支付配置-->
        <!--<el-dialog title="支付方式配置详情" v-model="Payment_settings" :close-on-click-modal="false">-->
            <!--<ul class="block">-->
                <!--<li>-->
                    <!--<span class="demonstration">商户名称</span>-->
                    <!--<el-input-->
                            <!--placeholder="请输入商户名称"-->
                            <!--v-model="MerchantName" style="width:240px;">-->
                    <!--</el-input>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<span class="demonstration">类型</span>-->
                    <!--<el-select v-model="type" placeholder="请选择类型">-->
                        <!--<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">-->
                        <!--</el-option>-->
                    <!--</el-select>-->
                <!--</li>-->
                <!--<el-button type="info" @click="open_add">新增</el-button>-->
            <!--</ul>-->
            <!--<div class="box_table">-->
                <!--<el-table :data="PaymentAllocation" border style="width: 100%;text-align: center">-->
                    <!--<el-table-column fixed prop="PaymentName" label="名称" width="120">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="logo" width="120">-->
                        <!--<template slot-scope="scope">-->
                            <!--<img src="../../assets/logo.png" alt="">-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="PaymentStyle" label="类型" width="120">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="PaymentRate" label="折扣率" width="100">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="PaymentSHName" label="商户名称" width="120">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="PaymentSHSATER" label="状态" width="100">-->
                        <!--<template slot-scope="scope">-->
                                <!--<el-tag type="primary" v-if="scope.row.state==0">未审核</el-tag>-->
                                <!--<el-tag type="warning" v-if="scope.row.state==1">启用</el-tag>-->
                                <!--<el-tag type="danger" v-if="scope.row.state==2">禁用</el-tag>-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="操作" width="110">-->
                        <!--<template slot-scope="scope">-->
                            <!--<el-button size="small" type="danger" @click="role_Delete(scope.$index, scope.row)">删除</el-button>-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                <!--</el-table>-->
            <!--</div>-->
        <!--</el-dialog>-->
        <!--支付新增-->
        <!--<el-dialog title="详情" v-model="Payment_Add" :close-on-click-modal="false">-->
            <!--<ul class="block">-->
                <!--<li>-->
                    <!--<span class="demonstration">商户名称</span>-->
                    <!--<el-input-->
                            <!--placeholder="请输入商户名称"-->
                            <!--v-model="MerchantName" style="width:240px;">-->
                    <!--</el-input>-->
                <!--</li>-->
                <!--<li>-->
                    <!--<span class="demonstration">类型</span>-->
                    <!--<el-select v-model="type" placeholder="请选择类型">-->
                        <!--<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">-->
                        <!--</el-option>-->
                    <!--</el-select>-->
                <!--</li>-->
            <!--</ul>-->
            <!--<div class="box_table">-->
                <!--<el-table :data="tableData" border style="width: 100%;text-align: center">-->
                    <!--<el-table-column fixed prop="province" label="名称" width="120">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="logo" width="120">-->
                        <!--<template slot-scope="scope">-->
                            <!--<img src="../../assets/logo.png" alt="">-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="zip" label="类型" width="120">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column prop="address" label="折扣率" width="300">-->
                    <!--</el-table-column>-->
                    <!--<el-table-column label="操作" width="110">-->
                        <!--<template slot-scope="scope">-->
                            <!--<el-button size="small" type="info" @click="role_Delete(scope.$index, scope.row)">新增</el-button>-->
                        <!--</template>-->
                    <!--</el-table-column>-->
                <!--</el-table>-->
            <!--</div>-->
        <!--</el-dialog>-->
      <!--商户编辑-->
      <el-dialog  title="商户编辑" v-model="Business_Edit_details" :close-on-click-modal="false" width="50%">
        <el-form :model="ruleForm_edit" :rules="rules_edit" ref="ruleForm_edit" label-width="100px" class="demo-ruleForm">
          <el-form-item label="商户名称:" label-width="100px" style="margin-bottom:0px;">
            <div>{{ruleForm_edit.business_name}}</div>
          </el-form-item>
          <el-form-item label="服务费" prop="service_rate">
            <el-input v-model="ruleForm_edit.service_rate" placeholder="例如10%"></el-input>
          </el-form-item>
          <el-form-item label="身份证号码" prop="identity_num">
            <el-input v-model="ruleForm_edit.identity_num" placeholder="请输入有效身份证号码"></el-input>
          </el-form-item>
          <el-form-item label="法人名称" prop="legal_person">
            <el-input v-model="ruleForm_edit.legal_person"></el-input>
          </el-form-item>
          <el-form-item label="银行卡号" prop="bank_card">
            <el-input v-model="ruleForm_edit.bank_card"></el-input>
          </el-form-item>
          <el-form-item label="银行名称" prop="bank_name">
            <el-input v-model="ruleForm_edit.bank_name"></el-input>
          </el-form-item>
          <el-form-item label="是否需要分账" prop="support_split" label-width="120px" >
            <el-radio-group v-model="ruleForm_edit.support_split">
              <el-radio label="1">是</el-radio>
              <el-radio label="0">否</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="提现类型" prop="withdrawals">
            <el-radio-group v-model="ruleForm_edit.withdrawals">
              <el-radio label="1">自动提现</el-radio>
              <el-radio label="2">手动提现</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="联系号码" prop="mobile">
            <el-input v-model="ruleForm_edit.mobile"></el-input>
          </el-form-item>
          <el-form-item label="商户地址" prop="address">
            <el-input type="textarea" v-model="ruleForm_edit.address"></el-input>
          </el-form-item>
          <el-form-item  label="修改小区" prop="resource">
            <el-select
                v-model="OrgName"
                :multiple="false"
                filterable
                remote
                reserve-keyword
                placeholder="请输入要修改的小区名称（非必填）"
                :remote-method="orgMethod"
                :focus="GetFocus"
                :loading="orgloading"
                size="small" style="width:100%;">
                      <el-option
                      v-for="item in OrgOptions"
                      :key="item.uuid"
                      :label="item.name"
                      :value="item.uuid">
                      </el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm_edit('ruleForm_edit')">确认编辑</el-button>
            <el-button @click="resetForm_edit('ruleForm_edit')">重置</el-button>
          </el-form-item>
        </el-form>
      </el-dialog>
        <!--商户提现-->
        <el-dialog  title="商户提现" v-model="Business_owners_Visible" :close-on-click-modal="false" width="50%">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
                <el-form-item label="商户名称:" label-width="120px" style="margin-bottom:0px;">
                    <div>{{ruleForm.name}}</div>
                    <!--fanpiao_balance   饭票余额-->
                    <!--fanpiao_total   饭票累计提现金额-->
                    <!--balance    现金余额-->
                    <!--total         现金累计提现金额-->
                </el-form-item>
                <el-form-item label="饭票余额:" label-width="120px" style="margin-bottom:0px;">
                    <div>{{fanpiao_balance}} 元</div>
                </el-form-item>
                <el-form-item label="饭票累计提现金额:" label-width="150px" style="margin-bottom:0px;">
                    <div>{{fanpiao_total}} 元</div>
                </el-form-item>
                <el-form-item label="现金余额:" label-width="120px" style="margin-bottom:0px;">
                    <div>{{RMB_balance}} 元</div>
                </el-form-item>
                <el-form-item label="现金累计提现金额:" label-width="150px" style="margin-bottom:0px;">
                    <div>{{RMB_total}} 元</div>
                </el-form-item>
                <el-form-item label="提现类型" prop="resource" label-width="120px">
                    <el-radio-group v-model="ruleForm.resource">
                        <el-radio label="1">现金提现</el-radio>
                        <el-radio label="2">饭票提现</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="owners_submitForm('ruleForm')">提现</el-button>
                    <el-button @click="owners_resetForm('ruleForm')">重置</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
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
    import {backendBusinessList,AdminBusinessView,adminBusinessStatus,AdminBusinessExamine,backendBusinessGeneralList,adminWithdrawsBalance,adminWithdrawsIndexindex,backendRolePrivilegeRole,BusinessEdit,backendRolePrivilegeOrg} from './../../api/api';
    export default {
        props: {
        },
        data(){
            return {
                type:'',//类型
                Mobile:'',//手机号码
                MerchantName:'',//商户名称
                Merchantoptions: [],
                MerchantList:'',//商户类目
                DDStatus: [{
                    value: '0',
                    label: '未审核'
                }, {
                    value: '1',
                    label: '启用'
                }, {
                    value: '2',
                    label: '禁用'
                }, {
                    value: '3',
                    label: '审核不通过'
                }],
                status:'',//订单状态
                BusinesstableData: [],//商户列表
                oneBusinesstableData:'',
                Merchant_Visible:false,//详情
                PaymentAllocation:[],
                tableData:[],
                form: {
                    resource: '',
                    desc: ''
                },
                addLoading: false,
                Payment_settings:false,//支付配置
                Payment_Add:false,
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                loadListing:true,//加载
                Business_owners_Visible:false,//商户提现
                fanpiao_balance:'',//饭票余额
                fanpiao_total:'',   //<!--饭票累计提现金额-->
                RMB_balance:'',    //<!--现金余额-->
                RMB_total:'',    //<!--现金累计提现金额-->
                ruleForm: {
                    name:'',
                    business_uuid:'',
                    resource:'',
                },
                rules: {
                    resource: [
                        { required: true, message: '请选择提现类型', trigger: 'blur' }
                    ]
                },
                privilege_id:'2',
                //权限
               Merchant_QY:false,
               Merchant_JY:false,
               Merchant_XQ:false,
               Merchant_SH:false,
               Merchant_TX:false,
              //商户编辑
              Business_Edit_details:false,
              ruleForm_edit: {
                business_name:'',//商户名称
                business_uuid:'',//商户UUid
                service_rate:'',//服务费
                identity_num:'',//身份证号码
                legal_person:'',//法人
                bank_card:'',//银行卡号
                bank_name:'',//银行名称
                support_split:'',//是否需要分账
                mobile:'',//手机号码
                address:'',//商户地址
                community_uuid:'',//小区UUID
                withdrawals:'',//提现类型
              },
              rules_edit: {
                service_rate: [
                  { required: true, message: '请输入服务费', trigger: 'blur' }
                ],
                identity_num: [
                  { required: true, message: '请输入身份证号码', trigger: 'change' },
                  { min: 15, max: 18, message: '长度在 15 到 18 个字符', trigger: 'blur' }
                ],
                legal_person: [
                  { required: true, message: '请输入法人', trigger: 'change' }
                ],
                bank_card: [
                  {  required: true, message: '请输入银行卡号', trigger: 'change' }
                ],
                bank_name: [
                  { required: true, message: '请输入银行名称', trigger: 'change' }
                ],
                support_split: [
                  { required: true, message: '请选择是否分账', trigger: 'change' }
                ],
                mobile: [
                  { required: true, message: '请输入手机号码', trigger: 'change' },
                  { min: 11, max: 11, message: '长度是11个字符', trigger: 'blur' }
                ],
                address: [
                  { required: true, message: '请输入商户地址', trigger: 'change' }
                ],
                withdrawals: [
                  { required: true, message: '请选择提现类型', trigger: 'change' }
                ]
              },
              //搜索小区
              loading: false,
              OrgName:'',
              OrgOptions:[],
              MerchantName:'',
              options:'',
              orgloading:'',
            };
        },
        created:function() {
            this.Start_authority();
            this.DetailsStart();
            this.Merchant_category();
        },
        methods: {
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
              }else{
                this.OrgOptions = [];
                this.OrgName='';
              }
            },
            GetFocus(){
              this.options = [];
              this.MerchantName='';
            },
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
                         if(data.content[i].name=='商户详情'){
                           this.Merchant_XQ=true;
                         }else if(data.content[i].name=='商户禁用'){
                           this.Merchant_JY=true;
                         }else if(data.content[i].name=='商户启用'){
                           this.Merchant_QY=true;
                         }else if(data.content[i].name=='商户提现'){
                           this.Merchant_TX=true;
                         }else if(data.content[i].name=='商户审核'){
                           this.Merchant_SH=true;
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
            DetailsStart(){
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinesParams={
                    access_token:localRoutes.access_token,
                    general_id:this.MerchantList,
                    state:this.status,
                    name:this.MerchantName,
                    mobile:this.Mobile,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                backendBusinessList(BusinesParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.BusinesstableData=data.content.data;
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
                        this.loadListing=false;
                        if(data.code=='402'){
                            sessionStorage.removeItem('user');
                            sessionStorage.removeItem('user_router');
                            _this.$router.push({ path: '/login' });

                        }
                    }
                })

            },
            details_Click(index,row) {//详情函数
                this.Merchant_Visible=true;
//                this.oneBusinesstableData=row;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinesParams={
                    access_token:localRoutes.access_token,
                    business_uuid :row.uuid,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                AdminBusinessView(BusinesParams).then(data=>{
                    if(data.code==0){
                        this.oneBusinesstableData=data.content;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                        this.loadListing=false;
                        if(data.code=='402'){
                            sessionStorage.removeItem('user');
                            sessionStorage.removeItem('user_router');
                            _this.$router.push({ path: '/login' });

                        }
                    }
                });
                this.form.resource='';
                this.form.desc='';
            },
            Business_Edit(index,row){//商户编辑
                var _this=this;
                _this.Business_Edit_details=true;
                _this.ruleForm_edit.business_name=row.name;
                _this.ruleForm_edit.business_uuid=row.uuid;
                _this.ruleForm_edit.community_uuid=row.community_uuid;
                _this.ruleForm_edit.legal_person=row.legal_person;
                _this.ruleForm_edit.mobile=row.mobile;
            },
            submitForm_edit(ruleForm_edit) {//编辑商户
              var _this=this;
              this.$refs[ruleForm_edit].validate((valid) => {
                  if (valid) {
                       if(_this.OrgName!=''){
                         _this.ruleForm_edit.community_uuid=_this.OrgName
                       }
                       console.log(_this.ruleForm_edit);
                        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                        let BusinesParams={
                          access_token:localRoutes.access_token,
                          business_name:_this.ruleForm_edit.business_name,//商户名称
                          business_uuid:_this.ruleForm_edit.business_uuid,//商户UUid
                          service_rate:_this.ruleForm_edit.service_rate,//服务费
                          identity_num:_this.ruleForm_edit.identity_num,//身份证号码
                          legal_person:_this.ruleForm_edit.legal_person,//法人
                          bank_card:_this.ruleForm_edit.bank_card,//银行卡号
                          bank_name:_this.ruleForm_edit.bank_name,//银行名称
                          support_split:_this.ruleForm_edit.support_split,//是否需要分账
                          mobile:_this.ruleForm_edit.mobile,//手机号码
                          address:_this.ruleForm_edit.address,//商户地址
                          community_uuid:_this.ruleForm_edit.community_uuid,//小区UUID
                          withdrawals:_this.ruleForm_edit.withdrawals,//提现类型
                        };
                        BusinessEdit(BusinesParams).then(data=>{
                          if(data.code==0){
                              _this.$message({
                                type: 'success',
                                message: '编辑成功!'
                              });
                              _this.Business_Edit_details=false;
                        }else{
                          _this.$notify.error({
                            title: '错误提示',
                            message: data.message
                          });
                        }
                      })
                }else{
                  console.log('error submit!!');
                  return false;
                }
              });
            },
            resetForm_edit(ruleForm_edit) {
              this.$refs[ruleForm_edit].resetFields();
            },
            PaymentSettings() {//支付配置
//              this.Payment_settings=true;
                var _this=this;
                _this.$router.push({ path: '/Merchant_details' });
            },
            MerchantSearch(){//搜索
                if(this.MerchantList==''&&this.status==''&&this.MerchantName==''&&this.Mobile==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入查询信息',
                        type: 'warning'
                    });
                }else{
                    this.DetailsStart();
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
            open_add(){
//                this.Payment_Add=true;
                const h = this.$createElement;
                this.$msgbox({
                    title: '新增支付管理',
                    message: h('table',{ style: 'border:1px solid #ccc' },
                        [
                        h('tr',{ style: 'border:1px solid #ccc' },[
                          h('th',null,'Month'),
                          h('th',null,'Savings'),
                          h('th',null,'Savings'),
                          h('th',null,'Savings'),
                          h('th',null,'Savings')
                        ]),
                        h('tr',{ style: 'border:1px solid #ccc' },[
                          h('td',null,'January'),
                          h('td',null,'$100'),
                          h('td',null,'$200'),
                          h('td',null,'$300'),
                          h('td',null,'$500')
                        ])
                        ]
                    ),
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = '执行中...';
                            setTimeout(() => {
                                done();
                                setTimeout(() => {
                                    instance.confirmButtonLoading = false;
                                }, 300);
                            }, 3000);
                        } else {
                            done();
                        }
                    }
                }).then(action => {
                    this.$message({
                        type: 'info',
                        message: 'action: ' + action
                    });
                });
            },
            open2(index,row){//禁用
                this.$confirm('此操作将禁用该商户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let BusParams={
                        access_token:localRoutes.access_token,
                        state:2,
                        business_uuid:row.uuid
                    };
                    adminBusinessStatus(BusParams).then(data=>{
                        if(data.code==0){
                            this.DetailsStart();
                            this.$message({
                                type: 'success',
                                message: '禁用成功!'
                            });
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消禁用'
                    })
                })
            },
            open3(index,row){//启用
                this.$confirm('此操作将启用该商户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let BusParams={
                        access_token:localRoutes.access_token,
                        state:1,
                        business_uuid:row.uuid
                    };
                    adminBusinessStatus(BusParams).then(data=>{
                        this.DetailsStart();
                        if(data.code==0){
                            this.$message({
                                type: 'success',
                                message: '启用成功!'
                            });
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消启用'
                    });
                });
            },
            modifyState(state,uuid){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusParams={
                    access_token:localRoutes.access_token,
                    state:state,
                    business_uuid:uuid
                };
                adminBusinessStatus(BusParams).then(data=>{
                    if(data.code==0){
                        this.$message({
                            type: 'success',
                            message: '修改成功!'
                        });
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
            AuditPassed(){//审核通过和不通过并说明原因
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                if(this.form.resource==''){
                    this.$notify({
                        title: '警告',
                        message: '请选择状态',
                        type: 'warning'
                    });
                }else if(this.form.resource==3){
                    if(this.form.desc==''){
                        this.$notify({
                            title: '警告',
                            message: '请输入原因',
                            type: 'warning'
                        })
                    }else{
                        let BusParams={
                            access_token:localRoutes.access_token,
                            state:3,
                            business_uuid:this.oneBusinesstableData.uuid
                        };
                        adminBusinessStatus(BusParams).then(data=>{
                            if(data.code==0){
                                this.BusinessExamine(this.form.desc);
                            }else{
                                this.$notify.error({
                                    title: '错误提示',
                                    message: data.message
                                });
                            }
                        });
                    }
                }else{
                    let BusParams={
                        access_token:localRoutes.access_token,
                        state:1,
                        business_uuid:this.oneBusinesstableData.uuid
                    };
                    adminBusinessStatus(BusParams).then(data=>{
                        if(data.code==0){
                            this.DetailsStart();
                            this.oneBusinesstableData.state=1;
                            this.$message({
                                type: 'success',
                                message: '审核成功!'
                            });
                            this.Merchant_Visible=false;
                        }else{
                            this.$notify.error({
                                title: '错误提示',
                                message: data.message
                            });
                        }
                    });
                }
            },
            BusinessExamine(state){
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusParams={
                    access_token:localRoutes.access_token,
                    examine:state,
                    business_uuid:this.oneBusinesstableData.uuid
                };
                AdminBusinessExamine(BusParams).then(data=>{
                    if(data.code==0){
                        this.oneBusinesstableData.state=3;
                        this.DetailsStart();
                        this.$message({
                            type: 'success',
                            message: '审核成功!'
                        });
                        this.Merchant_Visible=false;
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
//            Merchant_balance()
            Business_owners(index,row){//商户提现
                console.log(row);
                this.Business_owners_Visible=true;
                this.ruleForm.business_uuid=row.uuid;
                this.ruleForm.name=row.name;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let ownersParams={
                    access_token:localRoutes.access_token,
                    business_uuid:row.uuid
                };
                adminWithdrawsBalance(ownersParams).then(data=>{
                    console.log(data);
                    if(data.code==0){
                        this.fanpiao_balance=data.content.fanpiao_balance;//饭票余额
                        this.fanpiao_total=data.content.fanpiao_total;   //<!--饭票累计提现金额-->
                        this.RMB_balance=data.content.balance;    //<!--现金余额-->
                        this.RMB_total=data.content.total;        //<!--现金累计提现金额-->
                    }else{
                        this.$notify.error({
                            title: '错误提示',
                            message: data.message
                        });
                    }
                })
            },
            owners_submitForm(formName) {//调用退款接口
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                     var fanpiao_amount,amount;
                     if(this.ruleForm.resource=='1'){
                         fanpiao_amount=0;
                         amount=this.RMB_balance;
                     }else if(this.ruleForm.resource=='2'){
                         fanpiao_amount=this.fanpiao_balance;
                         amount=0;
                     }
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let ownersParams ={
                        access_token:localRoutes.access_token,
                        business_uuid:this.ruleForm.business_uuid,
                        amount:amount,
                        fanpiao_amount:fanpiao_amount,
                    };
                    adminWithdrawsIndexindex(ownersParams).then(data => {
                        if(data.code==0){
                        this.$message({
                            type: 'success',
                            message: '提现成功!'
                        });
                        this.Business_owners_Visible=false;
                        this.ruleForm.name='';
                        this.ruleForm.business_uuid='';
                        this.fanpiao_balance='';
                        this.fanpiao_total='';
                        this.RMB_balance='';
                        this.RMB_total='';
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
            owners_resetForm(formName) {//重置退款金额和退款说明
                this.$refs[formName].resetFields();
            },
        }
    };
</script>

<style lang="css">
    /*居中*/
    .cell{
        text-align: center;
    }
    /*图片*/
    #PictureSize div{
        height:100px;
        width:120px;
    }
    #PictureSize .pic img{
        width:150px;
        height:100px;
    }
    .sfztp p{
        line-height:100px;
        margin:0;
    }
    #logo_box .logo_picture img{
        height:50px;
    }
    #logo_box div{
        height:50px;
    }
    .logo_title{
        width:80px;
    }
    .logo_title p{
        line-height:50px;
        margin:0;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
        overflow: hidden;
        margin:0;
    }
    .block li{
        float: left;
        margin:5px 5px 10px 5px;
    }
    .block input{
        /*width:80%;*/
    }
    .box_table{
        text-align: center;
    }
    /*商户介绍*/
    #Merchant_introduction div{
        height:80px;
    }
    #Merchant_introduction .content{
        width:80%;
        /*overflow-y:scroll;*/
        overflow:auto;
        text-indent:2em;
    }
    .MI_title p{
        line-height:80px;
        margin:0;
    }
    /*商户信息样式*/
    h3{
        margin:10px 0;
    }
    .Single_merchant li{
        height: auto;
        overflow: hidden;
        margin: 10px 0;
    }
    .Single_merchant li div{
        height: 20px;
        float: left;
    }
    .information li{
        height: auto;
        overflow: hidden;
        margin: 10px 0;
    }
    .information li div{
        float: left;
        height: 20px;
    }
    .Collection li{
        height: auto;
        overflow: hidden;
        margin: 10px 0;
    }
    .Collection li div{
        float: left;
        height: 20px;
    }
</style>
