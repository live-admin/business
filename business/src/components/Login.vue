<template>
  <div class="login-page-container">
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-position="left" label-width="0px" class="demo-ruleForm login-container">
      <!--<h3 class="title">彩之云运营平台</h3>-->
      <div class="logo_titles">
        <div class="logo_box">
          <img src="./../assets/images/ic_launcher.png" alt=""/>
        </div>
        <div class="title_box">
          <h3 class="">彩之云运营平台</h3>
        </div>
      </div>
      <el-form-item prop="employee_account">
        <el-input type="text" v-model="ruleForm.employee_account" auto-complete="off" placeholder="账号"></el-input>
      </el-form-item>
      <el-form-item prop="password">
        <el-input type="password" v-model="ruleForm.password" auto-complete="off" placeholder="密码"></el-input>
      </el-form-item>
      <el-checkbox v-model="checked" checked class="remember">记住密码</el-checkbox>
      <el-form-item style="width:100%;">
        <el-button type="primary" style="width:100%;" @click="handleSubmit('ruleForm')" :loading="logining">登录</el-button>
      </el-form-item>
      <div style="text-align: center;font-size:0.8em;">
        双乾网络支付提供互联网支付服务
     </div>
    </el-form>
  </div>
</template>
<style>
  .logo_titles{
    width:230px;
    height:60px;
    padding:0 60px;
  }
  .logo_titles div{
    float: left;
  }
  .logo_box{
    width:60px;
    height:60px;
    margin-right:20px;
  }
  .logo_box img{
    height:60px;
  }
  .title_box h3{
    line-height:60px;
    margin:0;
    text-align: center;
  }
</style>

<script>
  import {requestLogin,RecordList,SH_Login,backendPrivilegeList} from './../api/api';
  import md5 from 'js-md5';
  import ElCol from "element-ui/packages/col/src/col";
  export default {
    components: {ElCol}, data() {
      return {
        logining: false,
        ruleForm: {
          employee_account: '',
          password: ''
        },
        rules: {
          employee_account: [{
            required: true,
            message: '请输入账号',
            trigger: 'blur'
          }],
          password: [{
            required: true,
            message: '请输入密码',
            trigger: 'blur'
          }]
        },
        checked: true,
      };
    },
    beforeCreate:function () {
      if(this.$route.query.access_token!=undefined){
        let loginParams = {
          access_token:this.$route.query.access_token
        };
        SH_Login(loginParams).then(data=>{
          if(data.code==0){
            sessionStorage.setItem('user', JSON.stringify(data.content));
            this.Token(data.content.access_token);
          }else{
            this.$alert('授权失败！', '提示信息', {
              confirmButtonText: '确定'
            });
            this.$router.push({ path: '/login' });
          }
        });
      }else{
        this.$router.push({ path: '/login' });
      }
    },
    created:function(){
    },
    methods: {
      Token(token){
        var leftParams = {
          access_token:token
        };
        backendPrivilegeList(leftParams).then(data=>{
          let _this=this;
          if(data.code==0){
            let list=[
              {
                path:'/',
                component:require('./Home.vue'),
                name: '',
                menuShow: true,
                iconCls: 'iconfont icon-caidankanzhaopian',
                leaf: true,
                children:[
                  { path: '/Welcome', component:require('./Welcome.vue'),iconCls: '', name: '首页'}]
              }];
            for(var i=0;i<data.content.length;i++){
             if(data.content[i].name=="商户管理"){
                list.push({
                  path:'/',
                  component:require('./Home.vue'),
                  name: '商户管理',
                  menuShow: true,
                  iconCls: 'iconfont icon-yonghu1',
//                  leaf: true,
                  children:[
                  {path:'/Merchant',name: '商户列表',menuShow: true,iconCls: 'iconfont icon-yonghu1', component: (resolve) => require(['./Merchant/Merchant.vue'], resolve)},
//                    { path: '/Merchant', component:require('./Merchant/Merchant.vue'),iconCls: '', name: '商户管理'},
                    { path: '/Merchant_details', component:require('./Merchant/Merchant_details.vue'), name: '支付配置'}]
                });
              }else if(data.content[i].name=="订单管理"){
                list.push({
                  path: '/',
                  component:require('./Home.vue'),
                  name: '交易记录',
                  menuShow: true,
                  iconCls: 'iconfont icon-dingdan',
//                  leaf: true,
                  children:[
                      {path:'/Reconciliation',name: '商户对账',menuShow: true,iconCls: 'iconfont icon-qianmoney113', component: (resolve) => require(['./Merchant/Reconciliation.vue'], resolve)},
                      {path:'/Order',name: '订单管理',menuShow:true,iconCls: 'iconfont icon-icondd1', component: (resolve) => require(['./Order/Order.vue'], resolve)},
                      {path:'/OrderRefund',name: '退款记录',menuShow:true,iconCls:'iconfont icon-tuikuanguanli',component: (resolve) => require(['./Order/OrderRefund.vue'], resolve)}
             ]});
              }else if(data.content[i].name=="利益分配"){
               list.push({
                 path: '/',
                 component:require('./Home.vue'),
                 name:'利益分配',
                 menuShow: true,
                 iconCls: 'iconfont icon-web-icon-',
                 children:[
                   {path:'/Distribution',name: '商户利益分配',menuShow: true,iconCls: 'iconfont icon-qianmoney113', component: (resolve) => require(['./Distribution/Distribution.vue'], resolve)},
                 {path:'/record',name: '分配记录（接口）',menuShow:true,iconCls: 'iconfont icon-duihuan',component: (resolve) => require(['./Distribution/Distribution_record.vue'], resolve)},
                   {path:'/exchange',name: '兑换记录（对私）',menuShow:true,iconCls: 'iconfont icon-duihuan',component: (resolve) => require(['./Distribution/exchange.vue'], resolve)},
                   {path:'/DistributionDecord',name: '分配记录',component: (resolve) => require(['./Distribution/DistributionDecord.vue'], resolve)},
                    {path:'/next_list',name: '科目子订单分派列表',component: (resolve) => require(['./Distribution/next_list.vue'], resolve)},
                   {path:'/total',name: '分配记录(总)',component: (resolve) => require(['./Distribution/DistributionDecord_total.vue'],resolve),
                     },
                   {path:'/RulesDetails',name: '规则列表',component: (resolve) => require(['./Distribution/RulesDetails.vue'], resolve)},
                    {path:'/record_log',name: '对私订单对应的分配记录',component: (resolve) => require(['./Distribution/Distribution_record_log.vue'], resolve)},
                   {path:'/details',name: '规则详情',component: (resolve) => require(['./Distribution/details.vue'], resolve)}]});
             }else if(data.content[i].name=="提现管理"){
                list.push({
                  path: '/',
                  component:require('./Home.vue'),
                  name: '',
                  menuShow: true,
                  iconCls: 'iconfont icon-tixian',
                  leaf: true,
                  children:[
                      {path:'/Withdrawals',name: '提现管理',iconCls: '', component: (resolve) => require(['./Withdrawals/Withdrawals.vue'], resolve)}]});
              }else if(data.content[i].name=="支付渠道"){
//                                list.push({path: '/',component:require('./Home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/payment',name: '支付渠道管理',component: (resolve) => require(['./payment/payment.vue'], resolve)}]})
              }else if(data.content[i].name=="权限管理"){
               list.push({
                 path:'/',
                 component:require('./Home.vue'),
                 name: '',
                 menuShow: true,
                 iconCls: 'iconfont icon-permissions-user',
                 leaf: true,
                 children:[
                   { path: '/role', component:require('./role/role.vue'),iconCls: '', name: '权限管理'},
                   { path: '/roleAuthorization', component:require('./role/roleAuthorization.vue'), name: '角色权限配置'}]
               });
             }else if(data.content[i].name=="GMV看板"){
                list.push({
                  path:'/',
                  component:require('./Home.vue'),
                  name: '',
                  menuShow: true,
                  iconCls: 'iconfont icon-ditukanban',
                  leaf: true,
                  children:[
                    { path: '/Kanban', component:require('./Kanban/Kanban.vue'),iconCls: '', name: 'GMV看板'}]
                });
              }
            }
            window.sessionStorage.setItem('user_router',JSON.stringify(list));
            _this.$router.addRoutes(list);
            _this.$router.push({ path: '/Welcome' });
            _this.$router.options.routes=[
                {path:'/login',component:require('./login.vue'),name: '',iconCls: '',hidden: true},
                {path:'/404',component:require('./404.vue'),name: '',iconCls: '',hidden: true},
                {path:'/empty',component:require('./empty.vue'),name: '',iconCls: '',hidden: true}
                ];
            window.sessionStorage.removeItem('isLoadNodes');
          }else{
            this.$notify.error({
              title: '错误提示',
              message: data.message
            });
          }
        });
      },
      login(data){
        window.sessionStorage.setItem('user_router',JSON.stringify(data));
      },
      handleSubmit(ruleForm) {
        sessionStorage.removeItem('user');
        sessionStorage.removeItem('user_router');
        let _this = this;
        _this.$refs[ruleForm].validate((valid) => {
          if (valid) {
            _this.logining = true;
            //验证
            var loginParams = {
              employee_account: this.ruleForm.employee_account,
              password:md5(this.ruleForm.password)
            };
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('user_router');
            SH_Login(loginParams).then(data=>{
              if(data.code==0){
                _this.logining = false;
                sessionStorage.setItem('user', JSON.stringify(data.content));
                this.Token(data.content.access_token);
              }else{
                _this.logining = false;
                _this.$alert('用户名或密码错误！', '提示信息', {
                  confirmButtonText: '确定'
                });
              }
            });
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      }
    }
  }
</script>
<style lang="scss" scoped>
  .login-container {
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    margin: 0 auto;
    margin-top:160px;
    width: 350px;
    padding: 35px 35px 15px 35px;
    background: #fff;
    border: 1px solid #eaeaea;
    box-shadow: 0 0 25px #cac6c6;

    background: -ms-linear-gradient(top, #fff, #6495ed);        /* IE 10 */
    background:-moz-linear-gradient(top,#b8c4cb,#f6f6f8);/*火狐*/
    background:-webkit-gradient(linear, 0% 0%, 0% 100%,from(#b8c4cb), to(#f6f6f8));/*谷歌*/
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#fff), to(#6495ed));      /* Safari 4-5, Chrome 1-9*/
    background: -webkit-linear-gradient(top, #fff, #6495ed,#fff);   /*Safari5.1 Chrome 10+*/
    background: -o-linear-gradient(top, #fff, #6495ed);  /*Opera 11.10+*/

    .title {
      margin: 0px auto 40px auto;
      text-align: center;
      color: #505458;
    }
    .remember {
      margin: 0px 0px 35px 0px;
    }
  }
</style>
