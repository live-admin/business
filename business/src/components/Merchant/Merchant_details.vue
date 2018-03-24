<template>
   <div class="tab-content">
      <ul class="block">
         <li>
            <span class="demonstration">商户名称</span>
            <el-input
                    placeholder="请输入商户名称"
                    v-model="MerchantName" style="width:240px;">
            </el-input>
         </li>
         <li>
            <span class="demonstration">类型</span>
            <el-select v-model="type" placeholder="请选择类型">
               <el-option v-for="item in DDStatus" :key="item.value" :label="item.label" :value="item.value">
               </el-option>
            </el-select>
         </li>
         <el-button type="info" @click="open_add">新增</el-button>
      </ul>
      <div class="box_table">
         <el-table :data="PaymentAllocation" border style="width: 100%;text-align: center">
            <el-table-column prop="PaymentName" label="支付名称">
            </el-table-column>
            <el-table-column label="logo">
               <template slot-scope="scope">
                  <!--<img src="../../assets/logo.png" alt="">-->
               </template>
            </el-table-column>
            <el-table-column prop="PaymentStyle" label="类型">
            </el-table-column>
            <el-table-column prop="PaymentRate" label="折扣率">
            </el-table-column>
            <el-table-column prop="PaymentSHName" label="商户名称">
            </el-table-column>
            <el-table-column prop="PaymentSHSATER" label="状态">
            </el-table-column>
            <el-table-column label="操作">
               <template slot-scope="scope">
                  <el-button size="small" type="danger" @click="role_Delete(scope.$index, scope.row)">删除</el-button>
               </template>
            </el-table-column>
         </el-table>
      </div>
      <!--支付配置-->
      <el-dialog title="支付方式配置详情" v-model="Payment_settings" :close-on-click-modal="false" width="50%">
         <ul class="block">
            <li>
               <span class="demonstration">商户名称</span>
               <el-input
                       placeholder="请输入商户名称"
                       v-model="MerchantName" style="width:240px;">
               </el-input>
            </li>
            <li>
               <span class="demonstration">类型</span>
               <el-select v-model="type" placeholder="请选择类型">
                  <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                  </el-option>
               </el-select>
            </li>
            <el-button type="info" @click="open_add">新增</el-button>
         </ul>
         <div class="box_table">
            <el-table :data="PaymentAllocation" border style="width: 100%;text-align: center">
               <el-table-column fixed prop="PaymentName" label="名称" width="120">
               </el-table-column>
               <el-table-column label="logo" width="120">
                  <template slot-scope="scope">
                     <!--<img src="../../assets/logo.png" alt="">-->
                  </template>
               </el-table-column>
               <el-table-column prop="PaymentStyle" label="类型" width="120">
               </el-table-column>
               <el-table-column prop="PaymentRate" label="折扣率" width="100">
               </el-table-column>
               <el-table-column prop="PaymentSHName" label="商户名称" width="120">
               </el-table-column>
               <el-table-column prop="PaymentSHSATER" label="状态" width="100">
               </el-table-column>
               <el-table-column label="操作" width="110">
                  <template slot-scope="scope">
                     <el-button size="small" type="danger" @click="role_Delete(scope.$index, scope.row)">删除</el-button>
                  </template>
               </el-table-column>
            </el-table>
         </div>
      </el-dialog>
      <!--支付新增-->
      <el-dialog title="添加支付配置详情" v-model="Payment_Add" :close-on-click-modal="false" width="50%">
         <ul class="block">
            <!--<li>-->
               <!--<span class="demonstration">支付名称</span>-->
               <!--<el-input-->
                       <!--placeholder="请输入支付名称"-->
                       <!--v-model="PaymentName" style="width:240px;">-->
               <!--</el-input>-->
            <!--</li>-->
            <li>
               <span class="demonstration">支付名称</span>
               <el-select v-model="PaymentType" placeholder="请选择支付名称">
                  <el-option v-for="item in Payments" :key="item.value" :label="item.label" :value="item.value">
                  </el-option>
               </el-select>
            </li>
         </ul>
         <!--<div class="box_table">-->
            <!--<el-table :data="tableData" border style="width: 100%;text-align: center">-->
               <!--<el-table-column fixed prop="province" label="名称" width="120">-->
               <!--</el-table-column>-->
               <!--<el-table-column label="logo" width="120">-->
                  <!--<template scope="scope">-->
                     <!--<img src="../../assets/logo.png" alt="">-->
                  <!--</template>-->
               <!--</el-table-column>-->
               <!--<el-table-column prop="zip" label="类型" width="120">-->
               <!--</el-table-column>-->
               <!--<el-table-column prop="address" label="折扣率" width="300">-->
               <!--</el-table-column>-->
               <!--<el-table-column label="操作" width="110">-->
                  <!--<template scope="scope">-->
                     <!--<el-button size="small" type="info" @click="role_Delete(scope.$index, scope.row)">新增</el-button>-->
                  <!--</template>-->
               <!--</el-table-column>-->
            <!--</el-table>-->
         <!--</div>-->
         <div slot="footer" class="dialog-footer">
            <el-button @click.native="addSubmit">取消</el-button>
            <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
         </div>
      </el-dialog>
   </div>
</template>
<script>
    export default {
        props: {
        },
        data() {
            return {
                type:'',//类型
                PaymentType:'',//支付类型
                Mobile:'',//手机号码
                MerchantName:'',//商户名称
                PaymentName:'',
                options: [{
                    value: '选项1',
                    label: 'e停车微信'
                }, {
                    value: '选项2',
                    label: 'e费通支付宝'
                }, {
                    value: '选项3',
                    label: '彩实惠现金'
                }, {
                    value: '选项4',
                    label: '集团总部规定'
                }, {
                    value: '选项5',
                    label: '彩饭票'
                }],
                Payments: [{
                    value: '选项1',
                    label: 'e费通'
                }, {
                    value: '选项2',
                    label: 'e安全'
                }, {
                    value: '选项3',
                    label: '饭票商城'
                }, {
                    value: '选项4',
                    label: '微商圈'
                }, {
                    value: '选项5',
                    label: '粮票商城'
                }],
                DDStatus: [{
                    value: '选项1',
                    label: '正常'
                }, {
                    value: '选项2',
                    label: '异常'
                }, {
                    value: '选项3',
                    label: '支付失败'
                }],
                PaymentAllocation:[{
                    PaymentName:'微信支付',
                    PaymentStyle:'现金',
                    PaymentRate:'100%',
                    PaymentSHName:'多彩便利店',
                    PaymentSHSATER:'正常'
                },{
                    PaymentName:'微信支付',
                    PaymentStyle:'现金',
                    PaymentRate:'100%',
                    PaymentSHName:'多彩便利店',
                    PaymentSHSATER:'正常'
                },{
                    PaymentName:'微信支付',
                    PaymentStyle:'现金',
                    PaymentRate:'100%',
                    PaymentSHName:'多彩便利店',
                    PaymentSHSATER:'正常'
                },{
                    PaymentName:'微信支付',
                    PaymentStyle:'现金',
                    PaymentRate:'100%',
                    PaymentSHName:'多彩便利店',
                    PaymentSHSATER:'正常'
                }],
                Merchant_Visible:false,//详情
                form: {
                    resource: '',
                    desc: ''
                },
                addLoading: false,
                addFormRules: {
                    name: [
                        {required: true, message: '请输入角色', trigger: 'blur'}
                    ],
//                    author: [
//                        {required: true, message: '请输入作者', trigger: 'blur'}
//                    ],
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
                Payment_settings:false,//支付配置
                Payment_Add:false
            };
        },
        methods: {
            addSubmit(){
                this.Payment_Add=false;
            },
            details_Click() {//详情函数
                this.Merchant_Visible=true;
//              _this.$router.push({ path: '/Merchant_details' });
            },
            PaymentSettings() {//支付配置
//              this.Payment_settings=true;
                var _this=this;
                _this.$router.push({ path: '/Merchant_details' });
            },
            open_add(){
                this.Payment_Add=true;
//                const h = this.$createElement;
//                this.$msgbox({
//                    title: '新增支付管理',
//                    message: h('table',{ style: 'border:1px solid #ccc' },
//                        [
//                            h('tr',{ style: 'border:1px solid #ccc' },[
//                                h('th',null,'Month'),
//                                h('th',null,'Savings'),
//                                h('th',null,'Savings'),
//                                h('th',null,'Savings'),
//                                h('th',null,'Savings')
//                            ]),
//                            h('tr',{ style: 'border:1px solid #ccc' },[
//                                h('td',null,'January'),
//                                h('td',null,'$100'),
//                                h('td',null,'$200'),
//                                h('td',null,'$300'),
//                                h('td',null,'$500')
//                            ])
//                        ]
//                    ),
//                    showCancelButton: true,
//                    confirmButtonText: '确定',
//                    cancelButtonText: '取消',
//                    beforeClose: (action, instance, done) => {
//                        if (action === 'confirm') {
//                            instance.confirmButtonLoading = true;
//                            instance.confirmButtonText = '执行中...';
//                            setTimeout(() => {
//                                done();
//                                setTimeout(() => {
//                                    instance.confirmButtonLoading = false;
//                                }, 300);
//                            }, 3000);
//                        } else {
//                            done();
//                        }
//                    }
//                }).then(action => {
//                    this.$message({
//                        type: 'info',
//                        message: 'action: ' + action
//                    });
//                });
            },
            open2(){
                this.$confirm('此操作将禁用该商户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$message({
                        type: 'success',
                        message: '禁用成功!'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消禁用'
                    });
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
