import Vue from 'vue'
import Router from 'vue-router'

import Home from '@/components/Home'
import reHome from '@/components/reHome'
import Confirmation from '@/components/Confirmation'
import Goodslist from '@/components/Goodslist'
import Fixaddress from '@/components/Fixaddress'
import Orderlist from '@/components/Orderlist'
import Orderdetail from '@/components/Orderdetail'
import Goodsdetail from '@/components/Goodsdetail'
import Paysucceed from '@/components/Paysucceed'
import Ordertrack from '@/components/Ordertrack'

// import Addaddress from '@/components/Addaddress'
import Addresslist from '@/components/Addresslist'
import Index from '@/components/Index'


Vue.use(Router)
var router = new Router({
    routes: [

        {
            path: '/',
            component: Home,
            redirect:'/home',
        },

        // 彩惠首页
        {
            path: '/home',
            name: 'Home',
            component: Home,
            meta:{
                keepAlive:true
            }
        },

        // 彩惠首页----重做
        {
            path: '/reHome',
            name: 'reHome',
            component: reHome,
            meta:{
                keepAlive:true
            }
        },


        // 商品清单
        {
            path: '/goodslist',
            name: 'Goodslist',
            component: Goodslist
        },

        // 订单确认页面
        {
            path: '/home/confirmation',
            name: 'Confirmation',
            component: Confirmation,
        },
        
        // 修改收货地址
        {
            path: '/fixaddress',
            name: 'Fixaddress',
            component: Fixaddress
        },

        // 订单列表记录
        {
            path: '/home/orderlist',
            name: 'Orderlist',
            component: Orderlist
        },

        // 订单详情
        {
            path: '/home/orderdetail',
            name: 'Orderdetail',
            component: Orderdetail
        },

        // 商品详情
        {
            path: '/home/goodsdetail',
            name: 'Goodsdetail',
            component: Goodsdetail
        },

        // 支付成功页面
        {
            path: '/paysucceed',
            name: 'Paysucceed',
            component: Paysucceed
        },

        // 物流跟踪
        {
            path: '/ordertrack',
            name: 'Ordertrack',
            component: Ordertrack
        }, 

        // {
        //     path: '/addaddress',
        //     name: 'Addaddress',
        //     component: Addaddress
        // },

        {
            path: '/addresslist',
            name: 'Addresslist',
            component: Addresslist
        },

        {
            path: '/index',
            name: 'Index',
            component: Index
        },
    ]
})


// 懒加载方式，当路由被访问的时候才加载对应组件
// const Login = resolve => require(['@/components/Login'], resolve)
router.beforeEach((to, from, next) => {

    const toDepth = to.path.split('/').length;
    const fromDepth = from.path.split('/').length;
 
    if (toDepth < fromDepth && from.path== '/home/confirmation') {

        from.meta.keepAlive = false;
        to.meta.keepAlive = true;

    }else{

 
    }

    next()
})

export default router
