// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import ElementUI from 'element-ui'
import router from './router'
import './../static/css/normalize.css'
import './../static/css/style.css'
import './../static/js/flexible.js'
// import './../static/js/math.min.js'
import 'element-ui/lib/theme-chalk/index.css'


import MintUI from 'mint-ui';
import 'mint-ui/lib/style.css';

// import math from '/math.min.js'
Vue.use(MintUI);
Vue.use(ElementUI)

import VueClipboard from 'vue-clipboard2'
Vue.use(VueClipboard);


import base from './api/api.js'
import eft from './api/eft.js'
global.base = base;


// 添加全局js
// import $ from './../static/js/jquery-2.1.1.min.js'
// Vue.prototype.$ = $

// 全局自定义指令
// import focus from './components/directive/directive'
// 这样任何一个Vue文件只要这样v-focus(命令名)，就可以很方便的用到了
// <main class="middle"><input type="" name="" v-focus></main>

Vue.config.productionTip = false;

Vue.prototype.router = router;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
