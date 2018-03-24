// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css';
import '@/assets/iconfont.css'
import '@/assets/styles/main.scss'

import base from './api/api.js'
global.base = base;

Vue.config.productionTip = false;
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
Vue.use(ElementUI)
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
