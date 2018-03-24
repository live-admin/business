// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import '@/assets/iconfont.css'
import '@/assets/styles/main.scss'
let list=[];
let user=sessionStorage.getItem('access-user');
if(user){
  let admin=JSON.parse(user).admin;
  if(admin==1){
    list.push(
      {
        path:'/',
        component:require('./components/Home.vue'),
        name: '',
        menuShow: true,
        iconCls: 'el-icon-edit',
        leaf: true,
        children:[
          { path: '/entrance/base', component:require('./components/Entrance_base/base.vue'),iconCls: '', name: '入口考核基数'}]
      },
      {
        path:'/',
        component:require('./components/Home.vue'),
        name: '',
        menuShow: true,
        iconCls: 'iconfont icon-setting',
        leaf: true,
        children:[{ path: '/permission/set', component:require('./components/Permission_setting/setting.vue'),iconCls: '', name: '权限设置'}]
      });
  }else if(admin==2){
    list.push(
      {
        path:'/',
        component:require('./components/Home.vue'),
        name: '',
        menuShow: true,
        iconCls: 'el-icon-edit',
        leaf: true,
        children:[
          { path: '/entrance/base', component:require('./components/Entrance_base/base.vue'),iconCls: '', name: '入口考核基数'}]
      });
  }
}
router.addRoutes(list);
window.sessionStorage.removeItem('isLoadNodes');
Vue.config.productionTip = false;
Vue.use(ElementUI);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
});
