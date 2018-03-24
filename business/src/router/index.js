// import Vue from 'vue'
// import Router from 'vue-router'
// import Dashboard from '@/components/Dashboard'
// import Login from './../components/Login.vue'
// import Home from './../components/Home.vue'
// import notFound from './../components/404.vue'

import Vue from "vue"
import VueRouter from "vue-router"
// 引入组件
import login from './../components/login.vue'
import Welcome from './../components/Welcome.vue'
import home from './../components/Home.vue'
import notFound from './../components/404.vue'
import empty from './../components/empty.vue'
//提现
// 要告诉 vue 使用 vueRouter
Vue.use(VueRouter);
var router = new VueRouter({
  routes:[{
    path: '/login',
    component: login,
    name: '',
    hidden: true
  },{
    path: '/404',
    component: notFound,
    name: '',
    hidden: true
  },{
    path: '/empty',
    component: empty,
    name: '',
    hidden: true
  }]
});
export default router;

// 懒加载方式，当路由被访问的时候才加载对应组件
// const Login = resolve => require(['@/components/Login'], resolve);

// Vue.use(Router);
//
// let router = new Router({
// // mode: 'history',
//   routes: [{
//       path: '/login',
//       component:Login,
//       name: '',
//       hidden: true
//     },{
//       path: '/404',
//       component: notFound,
//       name: '',
//       hidden: true
//     },
//   ]
// })
//
// router.beforeEach((to, from, next) => {
//   // console.log('to:' + to.path)
//   if (to.path.startsWith('/login')) {
//     window.sessionStorage.removeItem('access-user')
//     next()
//   } else {
//     let user = JSON.parse(window.sessionStorage.getItem('access-user'))
//     if (!user) {
//       next({path: '/login'})
//     } else {
//       next()
//     }
//   }
// })
//
// export default router
