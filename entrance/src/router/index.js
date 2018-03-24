import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Dashboard from '@/components/Dashboard'
//软硬入口管理
import EntranceBase from  '@/components/Entrance_base/base'
import EntranceAdminHard from  '@/components/Entrance_Administration/hard'
import EntranceAdminSoft from  '@/components/Entrance_Administration/soft'
import DataHard from  '@/components/Data_display/hard'
import DataSoft from  '@/components/Data_display/soft'
import OperationLog from '@/components/Operation_log/log'
import PermissionSet from '@/components/Permission_setting/setting'
import EntranceAdmincate from  '@/components/Entrance_Administration/entryCategory'

// 懒加载方式，当路由被访问的时候才加载对应组件
const Login = resolve => require(['@/components/Login'], resolve)

Vue.use(Router)

let router = new Router({
// mode: 'history',
  routes: [
    {
      path: '/login',
      name: '登录',
      component: Login
    },
    // {
    //   path: '/',
    //   name: 'home',
    //   component: Home,
    //   redirect: '/dashboard',
    //   leaf: true, // 只有一个节点
    //   menuShow: true,
    //   iconCls: 'iconfont icon-home', // 图标样式class
    //   children: [
    //     {path: '/dashboard', component: Dashboard, name: '首页',meta:{requireAuth:true}, menuShow: true}
    //   ]
    // },
    {
      path: '/',
      component: Home,
      name: '入口管理',
      menuShow: true,
      iconCls: 'el-icon-menu',
      children: [
        {path: '/entranceAdmin/hard', component: EntranceAdminHard, name: '硬入口', menuShow: true},
        {path: '/entranceAdmin/soft', component: EntranceAdminSoft, name: '软入口', menuShow: true},
        {path: '/entranceAdmin/category', component: EntranceAdmincate, name: '入口类别管理', menuShow: true}
      ]
    },
    // {
    //   path: '/',
    //   component: Home,
    //   name: '',
    //   menuShow: true,
    //   leaf: true, // 只有一个节点
    //   iconCls: 'el-icon-edit', // 图标样式class
    //   children: [
    //     {path: '/entrance/base', component: EntranceBase, name: '入口考核基数', menuShow: true}
    //   ]
    // },
    {
      path: '/',
      component: Home,
      name: '数据展示',
      menuShow: true,
      iconCls: 'el-icon-document',
      children: [
        {path: '/dataDisplay/hard', component: DataHard, name: '硬入口',meta:{requireAuth:true}, menuShow: true},
        {path: '/dataDisplay/soft', component: DataSoft, name: '软入口',meta:{requireAuth:true}, menuShow: true}
      ]
    },{
      path: '/',
      component: Home,
      name: '',
      menuShow: true,
      leaf: true, // 只有一个节点
      iconCls: 'el-icon-edit', // 图标样式class
      children: [
        {path: '/Operation/log', component: OperationLog, name: '操作日志',meta:{requireAuth:true}, menuShow: true}
      ]
    },
    // {
    //   path: '/',
    //   component: Home,
    //   name: '',
    //   menuShow: true,
    //   leaf: true, // 只有一个节点
    //   iconCls: 'iconfont icon-setting', // 图标样式class
    //   children: [
    //     {path: '/permission/set', component: PermissionSet, name: '权限设置', menuShow: true}
    //   ]
    // }
    ]
})

router.beforeEach((to, from, next) => {
  // console.log('to:' + to.path)
  if (to.path.startsWith('/login')) {
    window.sessionStorage.removeItem('access-user');
    next()
  } else {
    let user = JSON.parse(window.sessionStorage.getItem('access-user'));
    if (!user) {
      next({path: '/login'})
    } else {
      next()
    }
  }
});
// router.beforeEach((to, from, next) => {
//   if (to.meta.requireAuth) {  // 判断该路由是否需要登录权限
//     let user = JSON.parse(window.sessionStorage.getItem('access-user'));
//     if (user) {  // 通过vuex state获取当前的token是否存在
//       next();
//     }else {
//       next({
//         path: '/login',
//         query: {redirect: to.fullPath}  // 将跳转的路由path作为参数，登录成功后跳转到该路由
//       })
//     }
//   }else{
//     next();
//   }
// });
export default router
