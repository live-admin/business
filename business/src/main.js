// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import '@/assets/iconfont.css'
import '@/assets/styles/main.scss'
import Router from 'vue-router';
// import 'font-awesome/css/font-awesome.min.css'

import Mock from './mock'
Mock.init()

Vue.config.productionTip = false

Vue.use(ElementUI);
Vue.use(Router);

router.beforeEach((to, from, next) => {
  if (to.path == '/login') {
    sessionStorage.removeItem('user_router');
    sessionStorage.removeItem('user');
  }
  let user = JSON.parse(sessionStorage.getItem('user'));
  if (!user && to.path != '/login') {
    next({
      path: '/login'
    });
  } else {
    next();
  }
});
import {backendPrivilegeList} from './api/api';
let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
if(localRoutes){
  var leftParams = {
    access_token:localRoutes.access_token
  };
  backendPrivilegeList(leftParams).then(data=>{
    if(data.code==0){
      var list=[
        {
          path:'/',
          component:require('./components/Home.vue'),
          name: '',
          menuShow: true,
          iconCls: 'iconfont icon-caidankanzhaopian',
          leaf: true,
          children:[
            { path: '/Welcome', component:require('./components/Welcome.vue'),iconCls: '', name: '首页'}]
        }
      ];
      for(var i=0;i<data.content.length;i++){
        if(data.content[i].name=="商户管理"){
          list.push({
            path:'/',
            component:require('./components/Home.vue'),
            menuShow: true,
            name: '商户管理',
            iconCls: 'iconfont icon-yonghu1',
            //leaf: true,
            children:[
            {path:'/Merchant',name: '商户列表',menuShow:true,iconCls: 'iconfont icon-yonghu1', component: (resolve) => require(['./components/Merchant/Merchant.vue'], resolve)},
              //{ path: '/Merchant', component:require('./components/Merchant/Merchant.vue'),iconCls: '', name: '商户列表'},
              { path: '/Merchant_details', component:require('./components/Merchant/Merchant_details.vue'), name: '支付配置'}]
          });
        }else if(data.content[i].name=="订单管理"){
          list.push({
            path: '/',
            component:require('./components/Home.vue'),
            menuShow: true,
            name: '交易记录',
            iconCls: 'iconfont icon-dingdan',
            //leaf: true,
            children:[
            {path:'/Reconciliation',name: '商户对账',menuShow:true,iconCls: 'iconfont icon-qianmoney113', component: (resolve) => require(['./components/Merchant/Reconciliation.vue'], resolve)},
          {path:'/Order',name: '订单管理',menuShow:true,iconCls: 'iconfont icon-icondd1', component: (resolve) => require(['./components/Order/Order.vue'], resolve)},
          {path:'/OrderRefund',name:'退款记录',menuShow:true,iconCls:'iconfont icon-tuikuanguanli',component: (resolve) => require(['./components/Order/OrderRefund.vue'], resolve)},
              ]});
        }else if(data.content[i].name=="利益分配"){
          list.push({
            path: '/',
            component:require('./components/Home.vue'),
            name: '利益分配',
            menuShow: true,
            iconCls: 'iconfont icon-web-icon-',
            children:[
              {path:'/Distribution',name: '商户利益分配',menuShow: true,iconCls: 'iconfont icon-qianmoney113', component: (resolve) => require(['./components/Distribution/Distribution.vue'], resolve)},
              {path:'/record',name: '分配记录（接口）',menuShow: true,iconCls: 'iconfont icon-duihuan',component: (resolve) => require(['./components/Distribution/Distribution_record.vue'], resolve)},
              {path:'/exchange',name: '兑换记录（对私）',menuShow: true,iconCls: 'iconfont icon-duihuan',component: (resolve) => require(['./components/Distribution/exchange.vue'], resolve)},
              {path:'/next_list',name: '科目子订单分派列表',component: (resolve) => require(['./components/Distribution/next_list.vue'], resolve)},
              {path:'/DistributionDecord',name: '分配记录',component: (resolve) => require(['./components/Distribution/DistributionDecord.vue'], resolve)},
              {path:'/total',name: '分配记录(总)',component: (resolve) => require(['./components/Distribution/DistributionDecord_total.vue'], resolve)},
              {path:'/RulesDetails',name: '规则列表',component: (resolve) => require(['./components/Distribution/RulesDetails.vue'], resolve)},
              {path:'/record_log',name: '对私订单的分配记录',component: (resolve) => require(['./components/Distribution/Distribution_record_log.vue'], resolve)},
              {path:'/details',name: '规则详情',component: (resolve) => require(['./components/Distribution/details.vue'], resolve)}]});
        }else if(data.content[i].name=="提现管理"){
          list.push({
            path: '/',
            component:require('./components/Home.vue'),
            name: '',
            menuShow: true,
            iconCls: 'iconfont icon-tixian',
            leaf: true,
            children:[
              {path:'/Withdrawals',name: '提现管理',iconCls: '', component: (resolve) => require(['./components/Withdrawals/Withdrawals.vue'], resolve)}
              ]});
        }else if(data.content[i].name=="支付渠道"){
          // list.push({path: '/',component:require('./components/Home.vue'),name: '',iconCls: '',leaf: true,children:[{path:'/payment',name: '支付渠道管理',component: (resolve) => require(['./components/payment/payment.vue'], resolve)}]})
        }else if(data.content[i].name=="权限管理"){
          list.push({
            path:'/',
            component:require('./components/Home.vue'),
            name: '',
            menuShow: true,
            iconCls: 'iconfont icon-permissions-user',
            leaf: true,
            children:[
              { path: '/role', component:require('./components/role/role.vue'),iconCls: '', name: '权限管理'},
              { path: '/roleAuthorization', component:require('./components/role/roleAuthorization.vue'), name: '角色权限配置'}
            ]});
        }else if(data.content[i].name=="GMV看板"){
            list.push({
              path:'/',
              component:require('./components/Home.vue'),
              name: '',
              menuShow: true,
              iconCls: 'iconfont icon-permissions-user',
              leaf: true,
              children:[
                { path: '/Kanban', component:require('./components/Kanban/Kanban.vue'),iconCls: '', name: 'GMV看板'}
              ]});
          }
      }
      router.addRoutes(list);
      window.sessionStorage.removeItem('isLoadNodes')
    }else{
      if(data.code=='402'&&data.code=='9001'){
        router.beforeEach((to, from, next) => {
          next({
            path: '/login'
          });
        });
        sessionStorage.removeItem('user_router');
        sessionStorage.removeItem('user');
      }
    }
  });
}
//vue选择器
Vue.filter('time', function (value) {
  return new Date(parseInt(value) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
});
// 引入路由
import router from "./router/index.js"
/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    render: h => h(App)
}).$mount('#app');
// new Vue({
//   el: '#app',
//   router,
//   template: '<App/>',
//   components: { App }
// })
