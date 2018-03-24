<template>
  <el-form ref="AccountFrom" :model="account" :rules="rules" label-position="left" label-width="0px"
           class="demo-ruleForm login-container">
    <h3 class="title">软硬入口后台登录系统</h3>
    <el-form-item prop="username">
      <el-input type="text" v-model="account.username" auto-complete="off" placeholder="账号"></el-input>
    </el-form-item>
    <el-form-item prop="pwd">
      <el-input type="password" v-model="account.pwd" auto-complete="off" placeholder="密码"></el-input>
    </el-form-item>
    <el-checkbox v-model="checked" checked class="remember">记住密码</el-checkbox>
    <el-form-item style="width:100%;">
      <el-button type="primary" style="width:100%;" @click.native.prevent="handleLogin" :loading="logining">登录</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
  import md5 from 'js-md5'
  import {backLogin} from '../api/api';
  export default {
    data() {
      return {
        logining: false,
        account: {
          username: '',
          pwd: ''
        },
        rules: {
          username: [
            {required: true, message: '请输入账号', trigger: 'blur'},
          ],
          pwd: [
            {required: true, message: '请输入密码', trigger: 'blur'},
          ]
        },
        checked: true
      };
    },
    methods: {
      handleLogin(){
        this.$refs.AccountFrom.validate((valid) => {
          if (valid){
            this.logining = true;
            var loginParams = { employee_account: this.account.username, password:md5(this.account.pwd)};
            console.log(loginParams);
            backLogin(loginParams).then(data => {
              this.logining = false;
              let { message, code, content } = data;
              console.log(data);
              console.log(message);
              console.log(content);
              console.log(code);
              if (code !== 0) {
                this.$message({
                  message: message,
                  type: 'error'
                });
              } else {
//                alert(content.employee.access_token);
                sessionStorage.setItem('access-user', JSON.stringify(content.employee));
                let list=[];
                //判断权限
                if(content.employee.admin==1){
                  list.push(
                    {
                      path:'/',
                      component:require('./Home.vue'),
                      name: '',
                      menuShow: true,
                      iconCls: 'iconfont icon-books1',
                      leaf: true,
                      children:[
                        { path: '/entrance/base', component:require('./Entrance_base/base.vue'),iconCls: '',meta:{requireAuth:true}, name: '入口考核基数'}]},
                    {
                      path:'/',
                      component:require('./Home.vue'),
                      name: '',
                      menuShow: true,
                      iconCls: 'iconfont icon-setting',
                      leaf: true,
                      children:[{ path: '/permission/set', component:require('./Permission_setting/setting.vue'),meta:{requireAuth:true},iconCls: '', name: '权限设置'}]});
                }else if(content.employee.admin==2){
                  list.push(
                    {
                      path:'/',
                      component:require('./Home.vue'),
                      name: '',
                      menuShow: true,
                      iconCls: 'iconfont icon-books1',
                      leaf: true,
                      children:[
                        { path: '/entrance/base', component:require('./Entrance_base/base.vue'),iconCls: '',meta:{requireAuth:true}, name: '入口考核基数'}]});
                }
                sessionStorage.removeItem('user_router');
                sessionStorage.removeItem('access-user');
                window.sessionStorage.setItem('user_router',JSON.stringify(list));
                window.sessionStorage.setItem('access-user',JSON.stringify(content.employee));
                this.$router.addRoutes(list);
                this.$router.push({ path: '/entranceAdmin/hard' });
                this.$router.options.routes=[
                  {
                   path: '/login',
                   name: '登录',
                   component:require('./Login.vue')
                  },
//                  {
//                    path: '/',
//                    name: 'home',
//                    component: require('./Home.vue'),
////                    redirect: '/dashboard',
//                    leaf: true, // 只有一个节点
//                    menuShow: true,
//                    iconCls: 'iconfont icon-home', // 图标样式class
//                    children: [
//                      {path: '/dashboard', component:require('./Dashboard.vue'), name: '首页', menuShow: true}
//                    ]
//                  },
                  {
                    path: '/',
                    component:require('./Home.vue'),
                    name: '入口管理',
                    menuShow: true,
                    iconCls: 'el-icon-menu',
                    children: [
                      {path: '/entranceAdmin/hard', component:require('./Entrance_Administration/hard.vue'),meta:{requireAuth:true}, name: '硬入口', menuShow: true},
                      {path: '/entranceAdmin/soft', component:require('./Entrance_Administration/soft.vue'),meta:{requireAuth:true}, name: '软入口', menuShow: true},
                      {path: '/entranceAdmin/category', component:require('./Entrance_Administration/entryCategory.vue'), name: '入口类别管理',meta:{requireAuth:true}, menuShow: true}
                    ]
                  },{
                    path: '/',
                    component: require('./Home.vue'),
                    name: '数据展示',
                    menuShow: true,
                    iconCls: 'el-icon-document',
                    children: [
                      {path: '/dataDisplay/hard', component: require('./Data_display/hard.vue'), name: '硬入口',meta:{requireAuth:true}, menuShow: true},
                      {path: '/dataDisplay/soft', component: require('./Data_display/hard.vue'), name: '软入口',meta:{requireAuth:true}, menuShow: true}
                    ]
                  },{
                    path: '/',
                    component: require('./Home.vue'),
                    name: '',
                    menuShow: true,
                    leaf: true, // 只有一个节点
                    iconCls: 'el-icon-edit', // 图标样式class
                    children: [
                      {path: '/Operation/log', component: require('./Operation_log/log.vue') , name: '操作日志',meta:{requireAuth:true}, menuShow: true}
                    ]
                  }];
                window.sessionStorage.removeItem('isLoadNodes');
              }
            });
          }else{
            console.log('error submit!!');
            return false;
          }
        });
      }
    }
  }

</script>
<style>
  body{
    background: #DFE9FB;
  }
</style>
<style lang="scss" scoped>
  .login-container {
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    margin: 160px auto;
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
