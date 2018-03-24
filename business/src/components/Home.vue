<template>
  <div class="home-container">
    <el-row class="container">
      <!--头部-->
      <el-col :span="24" class="topbar-wrap">
        <div class="topbar-logo topbar-btn" v-if="collapsed" transiton="mytran">
          <img src="../assets/logo_lj02.png" style="padding-left:6px;">
        </div>
        <div class="topbar-logos" v-if="!collapsed" transiton="mytran222">
          <img src="../assets/logo13.png">
        </div>
        <div class="topbar-title">
          <!--展开折叠开关-->
          <div class="menu-toggle" @click.prevent="collapse">
            <i class="iconfont icon-mianbaoxie" v-show="!collapsed"></i>
            <i class="iconfont icon-mianbaoxie" v-show="collapsed"></i>
          </div>
          <div style="float: left;">
            <span style="font-size: 18px;color:#000;font-weight:600;margin-left:12px;">彩之云运营平台</span>
          </div>
        </div>
        <div class="topbar-account topbar-btn">
          <el-dropdown trigger="click">
          <span class="el-dropdown-link userinfo-inner"><i class="iconfont icon-yonghu"></i> {{sysUserName}}  <i
            class="iconfont icon-down"></i></span>
            <el-dropdown-menu slot="dropdown">
              <!--<el-dropdown-item>-->
                <!--<router-link to="/user/profile"><span style="color: #555;font-size: 14px;">个人信息</span></router-link>-->
              <!--</el-dropdown-item>-->
              <!--<el-dropdown-item>-->
                <!--<router-link :to="'/user/changepwd'"><span style="color: #555;font-size: 14px;">修改密码</span></router-link>-->
              <!--</el-dropdown-item>-->
              <el-dropdown-item divided @click.native="logoutFun">退出登录</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
      </el-col>
      <!--中间-->
      <el-col :span="24" class="main">
        <!--左侧导航-->
        <aside :class="{showSidebar:!collapsed}">
          <!--导航菜单-->
          <el-menu default-active="0" router :collapse="collapsed">
            <template v-for="(item,index) in nodes" v-if="item.menuShow">
              <el-submenu v-if="!item.leaf" :index="index+''">
                <template slot="title"><i :class="item.iconCls"></i><span slot="title">{{item.name}}</span></template>
                <el-menu-item v-for="term in item.children" :key="term.path" :index="term.path" v-if="term.menuShow"
                              :class="$route.path==term.path?'is-active':''">
                  <i :class="term.iconCls"></i><span slot="title">{{term.name}}</span>
                </el-menu-item>
              </el-submenu>
              <el-menu-item v-else-if="item.leaf&&item.children&&item.children.length" :index="item.children[0].path"
                            :class="$route.path==item.children[0].path?'is-active':''">
                <i :class="item.iconCls"></i><span slot="title">{{item.children[0].name}}</span>
              </el-menu-item>
            </template>
          </el-menu>
        </aside>

        <!--右侧内容区-->
        <section class="content-container">
          <div class="grid-content bg-purple-light">
            <el-col :span="24" class="breadcrumb-container">
               <strong class="title">{{$route.name}}</strong>
                <el-breadcrumb separator="/" class="breadcrumb-inner" style="float: right">
                   <el-breadcrumb-item v-for="item in $route.matched" :key="item.path">{{ item.name }}</el-breadcrumb-item>
                 </el-breadcrumb>
            </el-col>
            <el-col :span="24" class="content-wrapper">
              <transition name="fade" mode="out-in">
                <router-view></router-view>
              </transition>
            </el-col>
            <!--下边-->
            <!--<el-col :span="24" style="height:50px;text-align:center;margin-top: 10px;">-->
              <!--<p style="font-size:0.8em;">双乾网络支付提供互联网支付服务</p>-->
            <!--</el-col>-->
          </div>
        </section>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import router from "./../router/index.js"
  export default {
    data() {
      return {
        nodes:this.$router.options.routes,
        sysName: '商户管理后台',
        sysUserName: '',
        collapsed: false,
        routes:[],
        CurrentName:''
      }
    },
    watch:{
      '$route': function (route) {
        this.CurrentName = route.name;
      },
    },
    created: function (){
      let isLoadNodes = sessionStorage.getItem('isLoadNodes');
      if (!isLoadNodes) {
        let data = JSON.parse(window.sessionStorage.getItem('user_router'));
        this.nodes.push(...data);
        sessionStorage.setItem('isLoadNodes','true');
      }
    },
    methods: {
      collapse: function () {
        this.collapsed = !this.collapsed;
      },
      //退出登录
      logoutFun: function() {
        let _this = this;
        this.$confirm('确认退出吗?', '提示', {
          //type: 'warning'
        }).then(() => {
          sessionStorage.removeItem('user');
          sessionStorage.removeItem('user_router');
          _this.$router.push('/login');
        }).catch(() => {

        });
      },
      flushCom:function(){
        if(this.$route.matched[1].name==this.CurrentName) {
//          this.$router.go(0);
//          let router = new Router({});
//            router.go(0);
//          this.CurrentName='';
        }else{
          this.CurrentName=this.$route.matched[1].name
        }
      }
        //router是路由实例,例如:
        // var router = new Router({})
        //router.go(n)是路由的一个方法，意思是在history记录中前进或者后退多少步，0就表示还是当前，类似window.history.go(n)
//        this.$router.go(0);
//        router.beforeEach((to, from, next) => {
//            console.log(to);
//          if (to.path == '/login') {
//            sessionStorage.removeItem('user_router');
//            sessionStorage.removeItem('user');
//          }
//          let user = JSON.parse(sessionStorage.getItem('user'));
//          if (!user && to.path != '/login') {
//            next({
//              path: '/login'
//            });
//          } else {
//            next();
//          }
//        });
//      }
    },
    mounted() {
      let user = sessionStorage.getItem('user');
      if (user) {
        user = JSON.parse(user);
        this.sysUserName = user.realname || '';
      }
    }
  }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  .container {
    position: absolute;
    top: 0px;
    bottom: 0px;
    width: 100%;

    .topbar-wrap {
      height: 50px;
      line-height: 50px;
      /*background: #373d41;*/
      border-bottom:3px solid #E7EAEC;
      padding: 0px;

      .topbar-btn {
        color: #fff;
      }
      /*.topbar-btn:hover {*/
      /*background-color: #4A5064;*/
      /*}*/
      .topbar-logo {
        float: left;
        width: 60px;
        line-height: 26px;
        height:50px;
        background:#333744;
      }
      .topbar-logos {
        float: left;
        width: 180px;
        line-height: 26px;
        background:#333744;
      }
      .topbar-logos img {
        height:40px;
        margin-top: 5px;
        margin-left: 25px;
      }
      .topbar-logo img{
        height:26px;
        margin-top:12px;
      }
      .topbar-title {
        position: relative;
        float: left;
        text-align: left;
        width:350px;
        padding-left: 10px;
        /*border-left: 1px solid #000;*/
      }
      .topbar-account {
        float: right;
        padding-right: 12px;
      }
      .userinfo-inner {
        cursor: pointer;
        color: #000;
        font-weight:600;
        padding-left: 10px;
      }
    }
    .main {
      display: -webkit-box;
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      position: absolute;
      top: 50px;
      bottom: 0px;
      overflow: hidden;
    }

    aside {
      min-width: 50px;
      background: #333744;
      &::-webkit-scrollbar {
        display: none;
      }

      &.showSidebar {
        overflow-x: hidden;
        overflow-y: auto;
      }

      .el-menu {
        height: 100%; /*写给不支持calc()的浏览器*/
        height: -moz-calc(100% - 80px);
        height: -webkit-calc(100% - 80px);
        height: calc(100% - 80px);
        border-radius: 0px;
        background-color: #333744;
      }

      .el-submenu .el-menu-item {
        min-width: 60px;
      }
      .el-menu {
        width: 180px;
      }
      .el-menu--collapse {
        width: 60px;
      }

      .el-menu .el-menu-item, .el-submenu .el-submenu__title {
        height: 46px;
        line-height: 46px;
      }

      .el-menu-item:hover, .el-submenu .el-menu-item:hover, .el-submenu__title:hover {
        /*background-color: #7ed2df;*/
      }
    }

    .menu-toggle {
      float: left;
      background: #4E97D9;
      border-radius:6px;
      text-align: center;
      color: white;
      height: 30px;
      line-height: 30px;
      width: 30px;
      margin: 10px 0;
    }

    .content-container {
      background: #fff;
      flex: 1;
      overflow-y: auto;
      padding: 10px;
      padding-bottom: 1px;

      .content-wrapper {
        background-color: #fff;
        box-sizing: border-box;
      }
    }
    /*这个定义动画情况，以及存在时的样式，这个样式会覆盖class里的样式*/
    .mytran-transition {
      transition: all 3s ease;
    }

    /* .mytran-enter 定义进入的开始状态 */
    /* .mytran-leave 定义离开的结束状态 */
    .mytran-enter,{
      width: 100%;
      opacity:1;
    }
    .mytran-leave {
      width: 0;
      opacity:0;
    }
  }
</style>
