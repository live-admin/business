<!-- 订单详情页 -->
<template>
  <div class="confirmation order_state">
        <!-- 物业费地址 -->
        <div class="free_info">
            <p>免物业费地址：
                <span v-text="detailInfo.property_info.property_address"></span>
            </p>
            <p>免物业费月份：
                <span v-text="freeServiceFeetxt"></span>
            </p>
        </div>

        <!-- 收货地址 -->
        <div class="consignee">
            <div>
                <p>
                    <img src="../../static/images/Pin.png" alt="">收货人：
                    <span v-text="detailInfo.address.real_name"></span>
                    <span style="margin-left:.45rem;" v-text="detailInfo.address.mobile"></span>
                </p>

                <p>收货地址：
                    <span v-text="detailInfo.address.address"></span>
                </p>
            </div>
        </div>

        <div class="goods_list merchant_item" v-for="(item,index) in detailInfo.sub_order_info">

            <p>
                <span v-text="item.goods_source"></span>
                <span :class="type" v-text="detailInfo.order_info.trade_state"></span>
            </p>

            <ul>
                <li class="signle" v-if="item.goods_arr.length == 1">

                   <div class="goods_show">
                       <img :src="item.goods_arr[0].goods_img" alt="">
                   </div>
                   <p class="goods_des" v-text="item.goods_arr[0].goods_name">Apple iPhone X (A1865) 64GB 深空灰色 移动联通电信4G手机</p>

                </li>

                <li v-if="item.goods_arr.length > 1" @click="goodslist(item.goods_sn)">

                    <div class="goods_show" v-for="(i,index) in item.goods_arr" v-if="index < 5">
                        <img :src="i.goods_img" alt="">
                    </div>

                </li>
            </ul>

            <p class="total">
                共<span v-text="item.goods_count"></span>
                件商品 实付款：¥ 
                <span v-text="item.goods_amount"></span>
            </p>

            <div class="consignee address_info" v-if="$route.query.state == 2 || $route.query.state == 3" @click="deliveringInfo(item.goods_sn)">
                <div>
                    <p>
                        <img src="../../static/images/truck.png" alt="">
                        <span>物流信息</span>
                    </p>

                    <p v-text="item.delivering_info"></p>
                </div>

                <img src="../../static/images/right_icon.png" alt="">

            </div>
        </div>

        <div class="order_info">
           <div class="referrer">
                <p>推荐人：<span v-text="detailInfo.order_info.recommend_mobile"></span></p><!-- <input type="text" placeholder="手机号码（选填）"> -->
               <!--  <img src="../../static/images/shaoyishao.png" alt=""> -->
            </div>
            <div class="order_id referrer">
                <p style="width:70%;float:left;">
                    <span style="width:70%;float:left;">订单编号：</span>
                    <span v-text="detailInfo.order_info.colour_sn"></span>
                </p>

                <p
                    v-clipboard:copy="detailInfo.order_info.colour_sn"
                    v-clipboard:success="onCopy"
                    v-clipboard:error="onError">
                    复制
                </p>

            </div>

            <div class="order_id referrer">
                <p>下单时间：
                    <span v-text="detailInfo.order_info.time_create">2018-02-05  10:25:56</span>
                </p>
                <p>
                    <a href="tel:4008-893-893">联系客服</a>
                </p>
            </div>

        </div>


        <div class="total_cal" v-if="state == 2 || state == 3">

            <p>合计：
                <span>¥ </span>
                <span v-text="detailInfo.order_info.trade_amount"></span>
            </p>

        </div>

        <div class="buy_btn del_order" v-if="$route.query.state == 4" @click="del">
            <div>
                <p>合计：
                    <span>¥ </span> 
                    <span v-text="detailInfo.order_info.trade_amount"></span>
                </p>
            </div>
            <div>
                删除订单
            </div>
        </div>

        <div class="wait_pay_btn" v-if="state == 1">
            <div>
                <p>合计：
                    <span>¥ </span> 
                    <span v-text="detailInfo.order_info.trade_amount"></span>
                </p>
            </div>
            <div>
                <p @click="cancelOrder">取消订单</p>
                <p @click="pay">去支付</p>
            </div>
        </div>

     </div>
</template>

<script>
import { orderDetail , cancelOrder , delOrder} from '../api/api';
import { Toast, Indicator } from 'mint-ui';
export default {
  name: 'Orderdetail',
  data () {
    return {

        // 商品详情页
        detailInfo:{

            //物业费地址信息
            property_info:{
                property_address:'',
                property_month:[],
            },

            // 收货地址
            address:{
                real_name:'',
                mobile:'',
                address:'',
            },

            sub_order_info:[],
            order_info:{
                recommend_mobile:'',
                colour_sn:'',
                time_create:'',
                trade_state:'',
            },
         },

        type:'', //没有用上
        state:'', //当前订单的支付状态
        sn:'', //支付订单号
        month_count:'', //总共免了几个月
        freeServiceFeetxt:'', //免物业费月份

       }
    },
    created() {

        var _this = this;

        _this.orderDetail();

        this.state = this.$route.query.state; //当前订单的支付状态

        this.sn = this.$route.query.colour_sn;
    },
    methods:{

        //订单详情列表
        orderDetail:function(){

            let _this = this;

            Indicator.open();

            let param = {

                access_token:sessionStorage.getItem("access_token"),
                colour_sn:this.$route.query.colour_sn, //支付订单号

            };

            orderDetail(param).then(function (data) {

                Indicator.close();

                if(data.code == '0'){

                    _this.detailInfo = data.content;

                    console.log(_this.detailInfo );

                    _this.month_count = _this.detailInfo.property_info.property_month.length;

                    if(_this.month_count > 0){

                        if(_this.month_count == 1){

                            _this.freeServiceFeetxt = _this.detailInfo.property_info.property_month[0];

                        }else{

                            _this.freeServiceFeetxt = _this.detailInfo.property_info.property_month[0]+'-'+_this.detailInfo.property_info.property_month[_this.month_count-1];
                        }

                    }else{

                        _this.freeServiceFeetxt = '无';

                    }

                    switch(_this.$route.query.state){

                        case 1:
                            _this.type = 'wait';
                            return;
                        case 2:
                            _this.type = 'succeed';
                            return;
                        case 3:
                            _this.type = 'succeed';
                            return;
                        case 4:
                            _this.type = 'fail';
                            return;
                    }

                    // if(property_info.property_month)
                }else{

                   Toast(data.message);

                }

            });
        },

        // 跳转到商品子页面
        goodslist:function(sn){

            var _this = this;

            sessionStorage.setItem('subGoodsList',JSON.stringify(_this.detailInfo.sub_order_info[0].goods_arr));

            this.$router.push({path:'/goodslist',query:{goods_sn:sn},name:'Goodslist'});
        },

        // 取消订单
        cancelOrder:function(){

            let _this = this;

            Indicator.open();

            let param = {

                access_token:sessionStorage.getItem("access_token"),
                colour_sn:this.$route.query.colour_sn
            };

            cancelOrder(param).then(function (data) {

                Indicator.close();

                if(data.code == '0'){

                    _this.detailInfo = data.content;

                    Toast("订单取消成功！",1000);

                    _this.$router.push({path:'/home/orderdetail',query:{state:4,colour_sn:_this.sn},name:'Orderdetail'});

                    window.location.reload();

                }else{

                   Toast(data.message);

                }

            })

        },

        // 跳转到支付页面
        pay:function(){

            var u = navigator.userAgent, app = navigator.appVersion;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

            let _this = this;

            if(isiOS){
                _this.iosLink(_this.sn,base+'/dist/#/Paysucceed?access_token='+sessionStorage.getItem("access_token")+'&colour_sn='+_this.sn);//苹果调用代码
            }else{
                _this.androidLink(_this.sn,base+'/dist/#/Paysucceed?access_token='+sessionStorage.getItem("access_token")+'&colour_sn='+_this.sn);//android调用代码
            }

        },

        iosLink:function(sn,notifyUrl){

            window.location.href="*payFromHtml5*"+sn+"*"+notifyUrl;

        },

        androidLink:function(sn,notifyUrl){

            jsObject.payFromHtml(sn,notifyUrl);

        },

        deliveringInfo:function(sn){

            this.$router.push({path:'/ordertrack',query:{goods_sn:sn},name:'Ordertrack'});

        },

        // 复制订单号
        onCopy:function(e){

            Toast('订单号复制成功',1000);

        },
        onError:function(e){

            Toast('订单号复制失败');

        },

        // 删除订单
        del:function(sn){

            let _this = this;

            Indicator.open();

            let param = {

                access_token:sessionStorage.getItem("access_token"),
                colour_sn:this.$route.query.colour_sn, //支付订单号

            };

            delOrder(param).then(function (data) {

                Indicator.close();

                if(data.code == '0' && data.content == '1'){

                    Toast("订单删除成功！");

                    _this.$router.push({path:'/home',query:{access_token:sessionStorage.getItem("access_token")},name:'home'});
                    window.location.reload();

                }else{

                   Toast(data.message);

                }

            });

        },

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
