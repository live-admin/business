<template>
  <div class="box-card">
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-position="left" label-width="0px" class="demo-ruleForm login-container">
      <!--<h3 class="title">彩之云运营平台</h3>-->
      <div class="logo_titles">
        <div class="logo_box">
          <img src="./../assets/images/ic_launcher.png" alt=""/>
        </div>
        <div class="title_box">
          <h3 class="">e家访后端管理系统</h3>
        </div>
      </div>
      <el-form-item prop="oa_username">
        <el-input type="text" v-model="ruleForm.oa_username" auto-complete="off" placeholder="账号"></el-input>
      </el-form-item>
      <el-form-item prop="password">
        <el-input type="password" v-model="ruleForm.password" auto-complete="off" placeholder="密码"></el-input>
      </el-form-item>
      <el-checkbox v-model="checked" checked class="remember">记住密码</el-checkbox>
      <el-form-item style="width:100%;">
        <el-button type="primary" style="width:100%;" @click="handleSubmit('ruleForm')" :loading="logining">登录</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
  import {eJF_Login} from './../api/api';
  import md5 from 'js-md5';
  export default {
      data() {
        return {
          logining: false,
          ruleForm: {
            oa_username: '',
            password: '',
            access_token:'',
          },
          rules: {
            oa_username: [{
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
          password_md5:'',
          checked: true,
          }
      },
      methods:{
              handleSubmit(ruleForm) {
                sessionStorage.removeItem('user');
                sessionStorage.removeItem('user_router');
                let _this = this;
                _this.$refs[ruleForm].validate((valid) => {
                  if (valid) {
                  this.password_md5=md5(this.ruleForm.password);
                  this.ruleForm.access_token='';
                  this.login();
              } else {
                console.log('error submit!!');
                return false;
              }
            });
        },
        login(){
                sessionStorage.removeItem('user');
                this.logining = true;
                var loginParams = {
                  oa_username: this.ruleForm.oa_username,
                  password:this.password_md5,
                  access_token:this.ruleForm.access_token
                };
                eJF_Login(loginParams).then(data=>{
                  console.log(data);
                  if(data.code==0){
                  this.logining = false;
                  sessionStorage.setItem('user', JSON.stringify(data.content));
                    this.$router.push({ path: '/bindpropermanage'});
                }else{
                  this.logining = false;
                  this.$alert(data.message, '提示信息', {
                    confirmButtonText: '确定'
                  });
                }
              });
           }
      },
      computed:{
      },
      watch:{
      },
      created() {
        console.log(this.$route.query.access_token);
        if(this.$route.query.access_token!=''&&this.$route.query.access_token!=undefined){
          this.ruleForm.access_token=this.$route.query.access_token;
          this.login();
        }
      }
  }
</script>
<style>
  .box-card {
    width: 350px;
    padding: 35px 35px 15px;
    background: #fff;
    border: 1px solid #eaeaea;
    position:absolute;
    left:50%;    /* 定位父级的50% */
    top:50%;
    transform: translate(-50%,-50%); /*自己的50% */
  }
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
    /*margin-right:20px;*/
  }
  .logo_box img{
    height:60px;
  }
  .title_box h3{
    line-height:60px;
    margin:0;
    text-align: center;
  }
  .remember{
    margin: 0 0 35px;
  }
</style>
