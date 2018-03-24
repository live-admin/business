import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
//登录页面
import login from '@/components/login.vue'
// import Dashboard from '@/components/Dashboard'

import Oabindcolourlife from '@/components/Oabindcolourlife'
import Bindpropermanage from '@/components/Bindpropermanage'
import Communitycomments from '@/components/Communitycomments'
import LimitManage from '@/components/LimitManage'
import Viewdetail from '@/components/Viewdetail'

Vue.use(Router);

export default new Router({
  // mode:'history',
  routes: [{
      path: '/login',
      name: '',
      component: login,
      hidden: true,
    },{
      path: '/',
      name: 'home',
      component: Home,
      //redirect: '/oabindcolourlife',
      leaf: true, // 只有一个节点
      // menuShow: true,
      iconCls: 'iconfont icon-home', // 图标样式class
      children: [
        {path: '/oabindcolourlife', component: Oabindcolourlife, name: '首页', menuShow: false}
      ]
    },
    {
      path: '/',
      component: Home,
      name: 'OA绑定彩之云账号管理',
      menuShow: true,
      leaf: true, // 只有一个节点
      iconCls: 'iconfont icon-users', // 图标样式class
      children: [
        {path: '/oabindcolourlife', component: Oabindcolourlife, name: 'OA绑定彩之云账号', menuShow: true,
        children:[{path:'/viewdetail', component: Viewdetail, name: 'OA绑定彩之云账号查看详情'}]}
      ]
    },
    {
      path: '/',
      component: Home,
      name: '绑定专属客户经理1',
      menuShow: true,
      leaf: true, // 只有一个节点
      iconCls: 'iconfont icon-books',
      children: [
        {path: '/bindpropermanage', component: Bindpropermanage, name: '绑定专属客户经理', menuShow: true,
        children:[{path:'/viewdetail', component: Viewdetail, name: '绑定专属客户经理查看详情'}]},
      ]
    },
    {
      path: '/',
      component: Home,
      name: '小区业主评价',
      menuShow: true,
      leaf: true, // 只有一个节点
      iconCls: 'iconfont icon-books',
      children: [
          {path: '/communitycomments', component: Communitycomments,name: '小区业主评价记录', menuShow: true,
          children:[{path:'/viewdetail', component: Viewdetail, name: '小区业主评价详情查看详情'}]},
      ]
    },
    {
      path: '/',
      component: Home,
      name: '权限管理',
      menuShow: true,
      leaf: false,
      // hidden: true,
      iconCls: 'iconfont icon-users',
      children: [
        {path: '/limitManage', component: LimitManage, name: '员工列表', menuShow: true},
      ]
    },

    // {
    //   path: '/',
    //   component: Home,
    //   name: '设置',
    //   menuShow: true,
    //   iconCls: 'iconfont icon-setting1',
    //   children: [
    //     {path: '/user/profile', component: UserProfile, name: '个人信息', menuShow: true},
    //     {path: '/user/changepwd', component: UserChangePwd, name: '修改密码', menuShow: true}
    //   ]
    // }


  ]
})
