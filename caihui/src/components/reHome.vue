<!-- 彩惠首页 -->
<template>
  <div class="wrap" @click.stop="addressFlag">

        <div style="position:fixed;top:0;left:0;width:100%;z-index:108;background:#f2f3f4;">
        
            <div class="index_header" style="width:100%">
                <div>
                    <div v-if="propertySn.is_property == 1" @click="addresslist">
                        <p>免物业费地址：<span v-text="propertySn.default_property"></span></p>
                    </div>
                    <div v-if="propertySn.is_property == 0">
                        <p>还未选择免物业费地址，赶紧去选吧~</p>
                    </div>
                    <em class="right_arrow  left"></em>
                </div>
                <em class="order_list" @click="Orderlist" v-if="propertySn.is_property == 1"></em>
            </div>

            <section class="progress_wrap">
                <div>

                    <div>
                        <p><span v-text="progressInfo.num">0</span>个月</p>
                        <p>免物业费</p>
                    </div>

                    <div>

                        <div class="full_progress_bar">
                            <p :style="{width:progressInfo.progress+'%'}"><span v-text="progressInfo.progress">0</span>%</p>
                            <div class="buy_progress_bar">
                                <span class="circle full_progress_circle"></span>
                                <div class="per_progress" :style="{width:progressInfo.progress+'%'}">
                                    <span class="per_progress_circle" v-if="progressInfo.progress > 6"></span>
                                </div>
                            </div>
                        </div>

                        <div class="per_progress_descript">
                            <div>
                                <p v-if="progressInfo.full && progressInfo.num == feelists.length">物业费已缴满</p>
                                <p v-if="progressInfo.totalamount == 0">快去添加心仪的商品吧~</p>
                                <p v-if="progressInfo.totalamount != 0 && progressInfo.progress != 100">还差<span class="orange" v-text="progressInfo.percent"></span>%就能免<span v-text="progressInfo.num+1"></span>个月的物业费~</p>
                                <p v-if="progressInfo.progress == 100 && progressInfo.full == false">已达到免<span v-text="progressInfo.num">1</span>个月物业费资格~</p>
                            </div>
                            <p>合计：<span class="orange">¥<em v-text="progressInfo.cashtotal.toFixed(2)"></em></span></p>
                        </div>

                    </div>

                    <div class="clearfix"></div>
                    <p @click="pay" v-if="progressInfo.num >= 1">去支付</p>

                </div>
            </section>
        </div>

        <div class="goods_list_wrap" :class="progressInfo.num >= 1 ? 'mt4' : 'mt3'">
            <ul>
                <li v-for="(item,index) in goodslist" :key=item.id>
                    <img src="../../static/images/has_buy.png" alt=""  v-if="item.amount != 0">
                    <div class="goods_info left" @click="goodsDetail(item.goods_id)">
                        <div>
                            <div class="mask_bg" v-if="item.amount != 0">
                                <span v-text="item.amount">0</span>
                            </div>
                            <img :src="item.goods_img" alt="">
                        </div>
                        <div class="goods_descript left">
                            <p v-text="item.name">暖风机</p>
                            <p v-text="item.decript">黑色</p>
                            <p>¥<span v-text="item.price">0.00</span></p>
                            <p><del>京东价：¥<span v-text="item.other_price">0.00</span></del></p>
                        </div>
                    </div>
                    <div class="cal right">
                        <p @click="plus(index,item)"></p>
                        <p @click="subtract(index,item)" v-if="item.amount > 0"></p>
                    </div>
                </li>
            </ul>
                <div class="loadwrap" v-if="loadmore==true">
                    <p class="loading"></p>
                </div>
                <div class="loadwrap" v-if="loadmore!=true">
                    <i>没有更多数据~</i>
                </div>
        </div>


        <div class="mask" v-show="addressListpop || waitPayPop || selectAddressPop" @click="hidepop"></div>

        <!-- 已经添加地址后弹窗 -->
        <!-- 物业缴费地址列表 -->
        <div class="select_address pop" v-show="addressListpop">
            <p>请选择免物业费地址</p>
            <ul>
                <li :class="{active:item.is_default == 1}" v-for="(item,index) in addressList" @click="selectAddress(item,index)">
                    <p v-text="item.address"></p><em></em>
                </li>
            </ul>
            <a href="javascript:;" @click="sureAddress">确定</a>
        </div>

        <!-- 未支付订单弹框 -->
        <div class="wait_pay pop" v-show="waitPayPop">
            <p>您有一笔未支付订单</p>
            <p>快去看看吧~</p>
            <div class="btn">
                <a href="javascript:;" @click="waitPayPop = false">取消</a>
                <a href="javascript:;" @click="jumpWaitPay">确定</a>
            </div>
        </div>

        <!-- 没有添加地址弹窗 -->
        <div class="wait_pay pop" v-show="selectAddressPop">
            <p style="margin-bottom:.6rem;">请先选择免物业费地址</p>
            <div class="btn">
                <a href="javascript:;" @click.stop="selectAddressPop = false">取消</a>
                <a href="javascript:;" @click="jumpAddress">选择</a>
            </div>
        </div>

    </div>
</template>

<script>
    import { goodsList , homeInfo , addresslist , propertyChoose , goodsDetail , feesInfo , order ,receivAddress} from '../api/api';
    import { Toast, Indicator } from 'mint-ui';
    import {eft} from './../api/eft.js';
    import $ from 'jquery';
    export default {
        name: 'Home',
        data () {
            return {

                goodslist:[], //商品列表

                // 缴费地址详情
                propertySn:{
                    is_property:'', //是否存在物业费地址 0---不存在，1---存在
                    default_property:'', //默认缴费地址
                    is_old_order:'', //是否有未支付订单 0---没有未支付订单， 1---有未支付订单
                    old_sn:'', //历史未支付订单
                    flag:'', //第一次进入弹窗 0----没有弹过窗， 1---已经弹窗
                    room_uuid:'', //楼栋房间UUID
                },

                addressListpop:false, //是否弹出物业缴费地址列表弹框
                addressList:[], //物业缴费地址列表
                room_uuid:'', //当前选择的缴费地址的楼栋房间号
                defaultAddress:'', //当前的默认的地址

                feelists:[], //当前缴费地址各个月欠费信息

                waitPayPop:false, //控制未支付订单弹框
                selectAddressPop:false, //是否弹出请选择免物业费地址弹框

                progressInfo:{

                    num:0,//免物业费月数
                    progress:0,
                    percent:0,
                    remaintotal_service:0,//减去上个月物业费所剩金额
                    buytotal_service:0,//已选购总物业费
                    prevBuytotal:0,//单个商品服务费
                    totalamount:0,//商品总数量
                    totalfee:0,//总物业费
                    full:false,//物业费是否已缴满
                    cashtotal:0,//商品现金总价
                    remain_service:0,

                },

                defaultreciveAddress:'', //默认的收货地址

                //分页
                current_page:1, //当前是第几页
                total:'',//总页数
                per_page:10,//单页数量
                scroll: '',
                loadmore:true,
                arr:[],
                tip:''

            }
        },
        created() {

            sessionStorage.setItem('access_token',this.$route.query.access_token);

            //判断是否储存分页和总页数
            if(sessionStorage.getItem('total')!=null){

                this.total=sessionStorage.getItem('total');
                this.current_page=sessionStorage.getItem('current_page');
                sessionStorage.removeItem('total');
                sessionStorage.removeItem('current_page');

            }else{

                this.total='';
                this.current_page=1;

            }

            //判断是否存储总的物业费
            if(sessionStorage.getItem('feelists')!=null){

                this.feelists = JSON.parse(sessionStorage.getItem('feelists'));
                sessionStorage.removeItem('feelists');

            }else{

                this.feelists=[];
              
            }

            // 是否有存储数据,有则获取存储，没有则重新获取
            if(sessionStorage.getItem('goodslist') || sessionStorage.getItem('progressInfo')){

                this.goodslist = JSON.parse(sessionStorage.getItem('goodslist'));
                this.progressInfo =JSON.parse(sessionStorage.getItem('progressInfo'));
                sessionStorage.removeItem('goodslist');
                sessionStorage.removeItem('progressInfo');
                sessionStorage.removeItem('feelists');

            }else{

                this.goodslists();

            }

            if(sessionStorage.getItem("scrollTop") != undefined){

                $(document).scrollTop(sessionStorage.getItem("scrollTop"));

            }


            if(sessionStorage.getItem("goods_id") != undefined){

                for(var i = 0;i<this.goodslist.length;i++){

                    if(this.goodslist[i].goods_id == sessionStorage.getItem("goods_id")){

                        this.plus(i,this.goodslist[i]);

                    }
                }
            }

            this.homeInfo();
            this.feelist();
            this.receivAdd();
        },
        methods:{

            //缴费地址信息----完成
            homeInfo:function(){

                let _this = this;

                Indicator.open(); //loading图标开启

                let param = {

                    access_token:sessionStorage.getItem("access_token"),

                };

                homeInfo(param).then(function (data) {

                    Indicator.close(); //loading图标关闭

                    if(data.code == '0'){

                        _this.propertySn = data.content;
                        _this.room_uuid = _this.propertySn.room_uuid;

                        // 如果没有弹窗过而且有未支付订单弹出未支付订单弹框
                        if(_this.propertySn.flag == 0 && _this.propertySn.is_old_order == 1){

                            _this.waitPayPop = true;

                        }

                    }else{

                       Toast(data.message);

                    }
                })
            },

            //商品列表----完成
            goodslists:function(){

                let _this = this;
                Indicator.open();

                let param = {
                    access_token:sessionStorage.getItem("access_token"),
                    page:this.current_page
                };

                goodsList(param).then(function (data) {

                    Indicator.close();

                    if(data.code == '0'){

                        _this.current_page = data.content.now_page;
                        _this.total = data.content.total;
                        _this.arr = data.content.data;

                        for(var i =0;i<_this.arr.length;i++){

                            //当前选择的数量
                            _this.arr[i].amount = 0;

                        }

                        _this.goodslist = _this.goodslist.concat(_this.arr);

                        //存储总页数
                        sessionStorage.setItem('total', Math.ceil(_this.total));

                    }else{

                        Toast(data.message);

                    }
                })
            },

            // 当前缴费地址各个月缴费信息---完成
            feelist:function(){

                let _this = this;

                Indicator.open();

                let param = {
                    access_token:sessionStorage.getItem("access_token"),
                };

                feesInfo(param).then(function (data) {

                    Indicator.close();

                    if(data.code == '0'){

                        _this.feelists = data.content;

                        //存储总应该交的物业费
                        sessionStorage.setItem('feelists',JSON.stringify(_this.feelists));

                    }else{

                       Toast(data.message);

                    }
                })

            },

            // 收货地址列表----完成
            // 获取到默认的收货地址
            receivAdd:function(){

                let _this = this;

                Indicator.open();

                let param = {

                    access_token:sessionStorage.getItem("access_token"),

                };

                receivAddress(param).then(function (data) {

                    Indicator.close();

                    if(data.code == '0'){

                        _this.addlist = data.content;

                        for(var i = 0;i<_this.addlist.length;i++){

                            if(_this.addlist[i].is_default == 1){
                                _this.defaultreciveAddress = _this.addlist[i];
                            }

                        }

                    }else{

                         Toast(data.message,1000);

                    }
                })
            },

            //获取物业缴费地址列表---完成
            addresslist:function(){

                let _this = this;

                Indicator.open();

                let param = {

                    access_token:sessionStorage.getItem("access_token"),
                };

                addresslist(param).then(function (data) {

                    Indicator.close();

                    if(data.code == '0'){

                        _this.addressListpop = true;
                        _this.addressList = data.content;

                    }else{

                       Toast(data.message,1000);

                    }
                })
            },

            //选择当前的默认物业缴费地址---完成
            selectAddress:function(item,index){

                for(var i=0;i<this.addressList.length;i++){

                    this.addressList[i].is_default = 0;

                }

                this.addressList[index].is_default = 1;
                this.room_uuid = item.room_uuid;
                this.defaultAddress = this.addressList[index].address;

            },

            // 确定当前的默认缴费地址 ---完成
            sureAddress:function(){

                let _this = this;
                Indicator.open();

                let param = {
                    access_token:sessionStorage.getItem("access_token"),
                    room_uuid:this.room_uuid
                };

                propertyChoose(param).then(function (data) {

                    Indicator.close();

                    if(data.code == '0'){

                        _this.addressListpop = false;

                        if(data.content == 1){

                            Toast("缴费地址修改成功",1000);
                            localStorage.clear();
                            window.location.reload();

                        }else{

                            Toast("缴费地址修改失败",1000);

                        }

                    }else{

                       Toast(data.message);

                    }
                });

                if(this.defaultAddress != ''){

                    this.propertySn.default_property = this.defaultAddress;
                    sessionStorage.removeItem('goodslist');
                    sessionStorage.removeItem('progressInfo');

                }
            },


            // 支付按钮，跳转订单确认页面---完成
            pay:function(){

                sessionStorage.setItem('defaultAddress',JSON.stringify(this.propertySn));
                sessionStorage.setItem('cashtotal',JSON.stringify(this.progressInfo.cashtotal));
                this.$router.push({path:'/home/confirmation',query:{freeAddress:this.propertySn.default_property,freeAddressuuid:this.propertySn.room_uuid,total:this.progressInfo.cashtotal.toFixed(2)},name:'Confirmation'});

            },

            // 跳转到订单列表页面---完成
            Orderlist:function(){

                this.$router.push({path:'/home/orderlist',name:'Orderlist'});
                sessionStorage.setItem("scrollTop",$(document).scrollTop());
                sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                sessionStorage.setItem('feelists',JSON.stringify(this.feelists));

            },

            // 跳转到商品详情页面----完成
            goodsDetail:function(id){

                if(this.propertySn.is_property == 0){
                    this.selectAddressPop = true;
                    return false;
                }
        
                sessionStorage.setItem("scrollTop",$(document).scrollTop());
                sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                sessionStorage.setItem('feelists',JSON.stringify(this.feelists));
                this.$router.push({path:'/home/goodsdetail',query:{'goods_id':id},name:'Goodsdetail'});
            },

            //跳转到添加地址页面----完成
            jumpAddress:function(){
                window.location.href = eft;
            },

            //点击地址弹框选择地址 
            addressFlag:function(){

                if(this.propertySn.is_property == 0){
                    this.selectAddressPop = true;
                    return false;
                }
            },

            // 商品加------完成
            plus:function(i,item){
                // 判断默认地址是否存在
                if(this.propertySn.is_property == 0 && this.propertySn.is_property != ''){
                    this.selectAddressPop = true;
                    return;
                }

                this.progressInfo.cashtotal += parseFloat(item.price);

                //amount购买每个商品的数量,小于库存存在，进行计算
                if(item.amount < item.stock){
                    item.amount++;
                    this.progressInfo.totalamount++;

                    // 选购商品置顶
                    this.goodslist.splice(i,1);
                    this.goodslist.splice(0,0,item);

                    // 选购总金额
                    this.progressInfo.buytotal_service += parseFloat(item.service_amount);
                    //加上上个月物业费
                    this.progressInfo.remaintotal_service = this.progressInfo.buytotal_service;

                    this.progressInfo.totalfee = 0;
                    for(var i = 0;i<this.feelists.length;i++){
                        this.progressInfo.totalfee += parseFloat(this.feelists[i].actual_fee);
                    }

                    for(var k = 0;k<this.feelists.length;k++){

                        this.progressInfo.remain_service = this.progressInfo.remaintotal_service - this.feelists[k].actual_fee;

                        if(this.progressInfo.remain_service >= 0){

                            this.progressInfo.remaintotal_service -= this.feelists[k].actual_fee;
                            this.progressInfo.num = k+1;
                            sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                            sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                            sessionStorage.setItem('feelists',JSON.stringify(this.feelists));

                        }else{

                            this.progressInfo.progress = (parseFloat(this.progressInfo.remaintotal_service)/parseFloat(this.feelists[k].actual_fee))*100;
                            this.progressInfo.progress = (this.progressInfo.progress).toFixed(2);
                            this.progressInfo.percent = (100 - this.progressInfo.progress).toFixed(2);
                            this.progressInfo.num = k;
                            sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                            sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                            sessionStorage.setItem('feelists',JSON.stringify(this.feelists));
                            return;

                        }
                    }


                    if(parseFloat(this.progressInfo.buytotal_service) >= parseFloat(this.progressInfo.totalfee)){
                        this.progressInfo.progress = 100;
                        this.progressInfo.percent = 100;
                        this.progressInfo.full = true;
                        this.progressInfo.num = this.feelists.length;
                        return;
                    }

                }else{

                    Toast("没有足够的商品",1000);

                }
            },

            // 商品减
            subtract:function(i,item){
                this.progressInfo.cashtotal -= parseFloat(item.price);

                //amount购买每个商品的数量,小于库存存在，进行计算
                item.amount--;
                this.progressInfo.totalamount--;

                if(this.progressInfo.totalamount == 0){
                    this.progressInfo.progress = Math.abs(0.00);
                }

                // 选购商品置顶
                // this.goodslist.splice(i,1);
                // this.goodslist.splice(0,0,item);

                // 选购总金额
                this.progressInfo.buytotal_service -= parseFloat(item.service_amount);
                //加上上个月物业费
                this.progressInfo.remaintotal_service = this.progressInfo.buytotal_service;

                this.progressInfo.totalfee = 0;

                for(var i = 0;i<this.feelists.length;i++){

                    this.progressInfo.totalfee += parseFloat(this.feelists[i].actual_fee);

                }

                if(parseFloat(this.progressInfo.buytotal_service) >= parseFloat(this.progressInfo.totalfee)){

                    this.progressInfo.progress = 100;
                    this.progressInfo.percent = 100;
                    this.progressInfo.full = true;
                    this.progressInfo.num = this.feelists.length;
                    return;

                }

                for(var k = 0;k<this.feelists.length;k++){

                    this.progressInfo.remain_service = this.progressInfo.remaintotal_service - this.feelists[k].actual_fee;
                    if(this.progressInfo.remain_service >= 0){

                        this.progressInfo.remaintotal_service -= this.feelists[k].actual_fee;
                        this.progressInfo.num = k+1;
                        sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                        sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                        sessionStorage.setItem('feelists',JSON.stringify(this.feelists));

                    }else{

                        this.progressInfo.progress = (parseFloat(this.progressInfo.remaintotal_service)/parseFloat(this.feelists[k].actual_fee))*100;
                        this.progressInfo.progress = (this.progressInfo.progress).toFixed(2)
                        this.progressInfo.percent = (100 - this.progressInfo.progress).toFixed(2);
                        this.progressInfo.num = k;
                        sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                        sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                        sessionStorage.setItem('feelists',JSON.stringify(this.feelists));
                        return;

                    }
                }

                sessionStorage.setItem('goodslist',JSON.stringify(this.goodslist));
                sessionStorage.setItem('progressInfo',JSON.stringify(this.progressInfo));
                sessionStorage.setItem('feelists',JSON.stringify(this.feelists));
            },

            // 跳转到待支付订单页面
            jumpWaitPay:function(){

                this.$router.push({path:'/home/orderdetail',query:{'colour_sn':this.propertySn.old_sn,state:1},name:'Orderdetail'});

            },

            // 
            menu:function() {

                let _this = this;
                if($(document).scrollTop() + window.screen.availHeight >= $(document).height()){

                    if(this.current_page < Math.ceil(this.total/this.per_page)){

                        _this.loadmore = true;

                        setTimeout(function() {
                            _this.goodslists();
                        }, 1000);

                        _this.current_page = parseInt(_this.current_page) + 1;
                            //储存当前页数
                            sessionStorage.setItem('current_page',_this.current_page);
                    }else{

                        _this.loadmore = false;

                    }
                }

            },

            // 字符串截取
            getQueryString:function(name) {

                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
                var r = window.location.search.substr(1).match(reg);
                if (r != null) return unescape(r[2]); return null;

            },

            formatFloat:function(f, digit){

                var m = Math.pow(10, digit);
                return parseInt(f * m, 10) / m;

            },

            // 点击关闭弹窗
            hidepop:function(){

                this.addressListpop = false;
                this.waitPayPop = false; 
                this.selectAddressPop =false;

            },

        },

        // 滚动监听
        mounted:function() {

            window.addEventListener('scroll', this.menu);

        },

    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
.mint-indicator .mint-indicator-wrapper{
    z-index: 110 !important;
}
.loading{
  margin-left: 48% !important;
}
.mt3{
    margin-top:3.4rem !important;
}
.mt4{
    margin-top:4.6rem !important;
}
</style>
