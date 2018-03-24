<!-- 彩惠首页重做 -->

<template>
    <div class="wrap">

        <div  style="position:fixed;top:0;left:0;width:100%;z-index:108;background:#f2f3f4;">

            <div class="index_header" style="width:100%">
                <div>
                    <div v-if="propertySn.is_property == 1" @click="addresslist">
                        <p v-text="propertySn.default_property"></p>
                    </div>
                    <div v-else @click="addressFlag">
                        <p>还未选择免物业费地址，赶紧去选吧~</p>
                    </div>
                    <em class="right_arrow  left"></em>
                </div>
                <em class="order_list" @click="goOrderList" v-if="propertySn.is_property == 1"></em>
            </div>

            <!-- 欠费信息展示 -->
            <section class="fee_wrap" v-if="feeList.length > 0">

                <div class="process_item" v-for = "(item,index) in feeList" :key = "index">

                    <p class="year" :class="index > 0 && item.year == feeList[index-1].year ? 'hide_font' : 'aa' ">
                        <span>{{item.year}}</span>
                    </p>

                    <div style="height:1.82rem; padding:.3rem 0rem">
                        <!-- 0-未交物业费 -->
                        <div class="circleProgress"  v-if="item.type == 0 || item.type == 4">
                            <el-progress class="processCircle" type="circle"  :stroke-width="4" :percentage="item.percentage"></el-progress>
                        </div>

                        <!-- 1-欠费 -->
                        <div class="circle" v-else-if="item.type == 1" :class="item.type == 1 ? 'red_border' : 'aa' ">
                            <div class="circle_s " :class="item.type == 1 ? 'red_circle' : 'aa' ">
                                    欠费
                            </div>
                        </div>

                        <!-- 2-减免 -->
                        <div class="circle" v-else-if="item.type == 2" :class="item.type == 2 ? 'blue_border' : 'aa' ">
                            <div class="circle_s " :class="item.type == 2 ? 'blue_circle' : 'aa' ">
                                减免
                            </div>
                        </div>

                        <!-- 3-预缴 -->
                        <div class="circle" v-else-if="item.type == 3" :class="item.type == 3 ? 'blue_border blue' : 'aa' ">
                            预缴
                        </div>

                        <p class="month">
                            {{item.money | numFormat}}
                        </p>

                        <!-- <p class="month">
                            {{item.month}}月
                        </p>
 -->
                    </div>

                </div>

            </section>

        </div>

        <!-- 商品列表 -->
        <div class="goods_wrap " :class="{hide_fee : feeList.length <= 0}">

            <div class="list" v-for = "(item,index) in goodsList" :key="index">

                <div class="good_image" @click="goToCompany(item.company_url)">

                    <img :src="item.company_img" alt="">

                </div>

                <div class="good_descript">

                    <div @click="goGoodsDetail(item.goods_id,index,item.type)">

                        <p>{{item.goods_name}}</p>
                        <p>{{item.goods_info}}</p>
                        <p>
                            <span class="red">￥{{item.market_price}}</span>
                            &nbsp;&nbsp;&nbsp;
                            送{{item.property_fee | numFormat}}元物业费
                        </p>

                        <p>
                            已有
                            <span class="red">{{item.buy_number}}</span>
                            人购买&nbsp;&nbsp;&nbsp;

                            剩余<span class="red"> {{item.last_number}} </span>件

                        </p>
                    </div>

                    <!-- 跳转原生 -->
                    <div v-if="item.type == 2">

                        <span class="see_btn" @click="goOtherPage(item.caifu_url)">去看看</span>

                        <p v-if="item.limit_number != 0">
                            限购<span> {{item.limit_number}} </span>件
                        </p>
                    </div>

                    <div v-else>
                        <img :class="{fack_hide : item.curBuyNumber <= 0}" @click="mimusGoods(index)" src="../../static/images/minus_icon.png" alt="">
                        <span :class="{fack_hide : item.curBuyNumber <= 0}">{{item.curBuyNumber}}</span>
                        <img @click="addGoods(index)" src="../../static/images/plus_icon.png" alt="">
                        <p v-if="item.limit_number != 0">
                            限购<span> {{item.limit_number}} </span>件
                        </p>
                    </div>

                </div>

            </div>

            <div class="loadwrap" v-if="loadmore==true">
                <p class="loading"></p>
            </div>
            <div class="loadwrap" v-else>
                <i>没有更多数据~</i>
            </div>

        </div>

        <!-- 购物车图标 -->
        <div class="shopping_car" @click="showOrderList" v-if="!isShowOrderList">

            <div>
                <img src="../../static/images/shopping_car.png" />
                <span v-if="carInfo.totalAmount > 0">{{carInfo.totalAmount}}</span>
            </div>

            <span v-if="carInfo.totalAmount > 0">￥{{carInfo.totalFee | numFormat}}</span>

        </div>

        <!-- 购物车 -->
        <div class="car_list" v-if="isShowOrderList">

            <div class="shopping_car_hide" @click="showOrderList">

                <div>
                    <img src="../../static/images/shopping_car.png" />
                    <span v-if="carInfo.totalAmount > 0">{{carInfo.totalAmount}}</span>
                </div>

                <span v-if="carInfo.totalAmount > 0">￥{{carInfo.totalFee | numFormat}}</span>

            </div>

            <p class="header">
                可送<span>{{carInfo.propertyMounth}}</span>个月物业费: <span> {{carInfo.propertyFee |numFormat}} </span>
            </p>

            <p class="note">
                已选商品
            </p>

            <div class="good_list_wrap">

                <div class="good_list" v-for="(item,index) in carInfo.goodsList">

                    <div class="descript">
                        {{item.goods_name}}
                    </div>

                    <div class="price">
                        <span>￥{{item.market_price | numFormat}}</span>
                        <img class="mimus" @click="mimusCarGoods(index)"  src="../../static/images/minus_icon.png" alt="">
                        <span class="amount">{{item.curBuyNumber}}</span>
                        <img class="add" @click="addCarGoods(index)" src="../../static/images/plus_icon.png" alt="">
                    </div>

                </div>

            </div>

            <div class="total">
                <span>￥{{carInfo.totalFee | numFormat}}</span>
                <span @click="goToPay">去结算</span>
            </div>

        </div>

        <!-- 弹框后面的遮罩 -->
        <div class="mask" v-show="addressListpop || waitPayPop || selectAddressPop || isShowOrderList" @click="hidepop"></div>

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

        <!-- 提示添加地址弹框 -->
        <div class="wait_pay pop" v-show="selectAddressPop">
            <p style="margin-bottom:.6rem;">请先选择免物业费地址</p>
            <div class="btn">
                <a href="javascript:;" @click.stop="selectAddressPop = false">取消</a>
                <a href="javascript:;" @click="jumpAddress">选择</a>
            </div>
        </div>

        <!-- 存在未支付订单弹框 -->
        <div class="wait_pay pop" v-show="waitPayPop">
            <p>您有一笔未支付订单</p>
            <p>快去看看吧~</p>
            <div class="btn">
                <a href="javascript:;" @click="waitPayPop = false">取消</a>
                <a href="javascript:;" @click="jumpWaitPay">确定</a>
            </div>
        </div>

    </div>
</template>

<script>
import { goodsList , homeInfo , addresslist , propertyChoose , goodsDetail , feesInfo , order ,receivAddress, getFeeList, getGoodsList } from '../api/api';
import { Toast, Indicator } from 'mint-ui';
import {eft} from './../api/eft.js';
import $ from 'jquery';
import Vue from 'vue'
export default {
    name: 'Home',
    data () {
        return {
            //缴费地址详情
            propertySn:{
                is_property:0, //是否存在物业费地址 0---不存在，1---存在
                default_property:'', //默认缴费地址
                is_old_order:0, //是否有未支付订单 0---没有未支付订单， 1---有未支付订单
                old_sn:'', //历史未支付订单
                flag:0, //第一次进入弹窗 0----没有弹过窗， 1---已经弹窗
                room_uuid:'', //楼栋房间UUID
            },

            //当前缴费地址各个月份欠费信息
            //type: 0--未交, 1--欠费, 2--减免, 3--预缴
            feeList:[
                // {
                //     "bill_id":"123456,7777",
                //     "year":2017,
                //     "month":8,
                //     "money":1,
                //     "type":1,
                // },
            ],
            feeIndex:-1, //当前减免的月份是第几个月

            // 商品列表
            curPage:1, //当前是第几页
            pageSize:3, //一页几条数据
            total:'', //当前总页数
            loadmore:false, //是否有更多数据加载
            idAddMore:false, //现在是否在执行加载操作,
            goodsList:[
                // {
                //     "company_img":"http://img20.360buyimg.com/n0/jfs/t3967/294/2536213007/193439/72a3a399/58abf71bN92dddf35.jpg", //广告图片
                //     "company_url":"http://www.baidu.com", //广告详情跳转地址
                //     "goods_name":"天天特价", //商品名称
                //     "goods_info":"商品介绍", //商品介绍
                //     "market_price":500, //商品价格
                //     "property_fee":50, //赠送的物业费
                //     "buy_number": 100, //购买人数
                //     "last_number":12, //剩余件数
                //     "limit_number":0, //限制数量
                //     "goods_id":1, //商品ID
                //     "type":1, //商品类别 1--商品， 2--第三方商品
                // },
            ],

            // 购物车订单列表
            isShowOrderList:false, //是否显示订单列表
            carInfo:{ //已经选购的商品信息
                totalAmount: 0, //现在购买商品的总件数
                totalFee:0,//购买商品的总费用
                propertyMounth:0,//可送物业的月数
                propertyFee:0, //当前可送的物业费
                propertyFeeTotal:0,//可送物业的总金额
                goodsList:[], //所购买的商品列表
            },

            // 提示添加地址弹框
            selectAddressPop:false, //提示添加地址弹框是否出现

            //选择缴费地址弹框
            addressListpop: false, //选择缴费地址弹框
            addressList:[], //缴费地址列表
            defaultAddress:'', //
            room_uuid:'', //当前选择缴费地址的楼栋房间号

            // 是否有未支付订单
            waitPayPop:false,

        }
    },

    created() {

        var _this = this;

        // 进入到彩惠人生首页，存储token
         sessionStorage.setItem('access_token',this.$route.query.access_token); //电脑版
//        sessionStorage.setItem('access_token',this.getQueryString("access_token"));//手机版

        if(!sessionStorage.getItem("goodsList")){

            // 获取到缴费地址信息
            _this.homeInfo();

            // 获取当前缴费地址各个月份欠费信息
            _this.getFeeList();

            //获取到当前列表
            _this.getGoodsList();

        }else{

            _this.propertySn = JSON.parse(sessionStorage.getItem("propertyAddress"));
            _this.feeList = JSON.parse(sessionStorage.getItem("feeList"));
            _this.goodsList = JSON.parse(sessionStorage.getItem("goodsList"));
            _this.carInfo = JSON.parse(sessionStorage.getItem("carInfo"));
            _this.total = JSON.parse(sessionStorage.getItem("totalPage"));

            // console.log(JSON.parse(sessionStorage.getItem("isAddGoods")));

            // 判断是否点击添加
            if(JSON.parse(sessionStorage.getItem("isAddGoods"))){

                var googsIndex = JSON.parse(sessionStorage.getItem("curGoodsIndex"));

                // console.log(_this.goodsList[googsIndex].curBuyNumber);

                _this.addGoods(JSON.parse(sessionStorage.getItem("curGoodsIndex")));

            }

            sessionStorage.removeItem('propertyAddress');
            sessionStorage.removeItem('feeList');
            sessionStorage.removeItem('goodsList');
            sessionStorage.removeItem('carInfo');
            sessionStorage.removeItem('isAddGoods');
            sessionStorage.removeItem('curGoodsIndex');
            sessionStorage.removeItem('totalPage');

        }

    },
    filters:{

        // 数字转换成两位小数点
        numFormat(value){

            return parseFloat(value).toFixed(2);

        },

    },
    methods:{

        // 离开页面时需要缓存的东西
        doCache(){

            var _this = this;

            sessionStorage.setItem('propertyAddress',JSON.stringify(_this.propertySn)); //当前缓存缴费地址
            sessionStorage.setItem('feeList',JSON.stringify(_this.feeList)); //缴费信息
            sessionStorage.setItem('goodsList',JSON.stringify(_this.goodsList)); //商品列表信息
            sessionStorage.setItem('carInfo',JSON.stringify(_this.carInfo)); //购物车信息
            sessionStorage.setItem('totalPage',JSON.stringify(_this.total)); //总共的页数

        },

        //获取到缴费地址详情 ---完成
        homeInfo(){

            var _this = this;

            Indicator.open(); //loading图标开启

            var param = {

                access_token:sessionStorage.getItem("access_token"),

            };

            homeInfo(param).then(function (data) {

                Indicator.close(); //loading图标关闭

                if(data.code == 0){

                    _this.propertySn = data.content;
                    _this.room_uuid = _this.propertySn.room_uuid;

                    // 如果没有弹窗过而且有未支付订单弹出未支付订单弹框
                    if(_this.propertySn.flag == 0 && _this.propertySn.is_old_order == 1){

                        _this.waitPayPop = true;

                    }

                }else{

                    Toast(data.message);

                }
            });
        },

        //当前缴费地址各个月份欠费信息
        getFeeList(){

            var _this = this;

            // Indicator.open(); //loading图标开启

            var param = {

                access_token:sessionStorage.getItem("access_token"),

            };

            getFeeList(param).then(function (data) {

                // Indicator.close(); //loading图标关闭

                if(data.code == 0){

                    if(data.content.bill_arr){

                        _this.feeList = data.content.bill_arr;

                    }


                    // 给每一个费用加一个进度

                    if(_this.feeList.length){

                        for(var i = 0; i < _this.feeList.length; i++){

                            _this.feeList[i].money = parseFloat(_this.feeList[i].money);

                            Vue.set(_this.feeList[i], 'percentage', 0);

                        }

                    }

                }else{

                    Toast(data.message);

                }

            });

        },

        //获取商品列表--完成
        getGoodsList(){

            var _this = this;

            Indicator.open(); //loading图标开启

            var param = {

                access_token:sessionStorage.getItem("access_token"),
                page: _this.curPage,
                page_size: _this.pageSize,

            };

            getGoodsList(param).then(function (data) {

                Indicator.close(); //loading图标关闭

                if(data.code == 0){

                    _this.total = data.content.total;

                    if(_this.goodsList.length > 0){

                        _this.goodsList = _this.goodsList.concat(data.content.goods_arr);

                    }else{

                        _this.goodsList = data.content.goods_arr;

                    }

                    _this.idAddMore = false;

                    // 循环添加当前用户购买的数量
                    for(var i = 0; i < _this.goodsList.length; i++){

                        _this.goodsList[i].market_price = parseFloat(_this.goodsList[i].market_price);
                        _this.goodsList[i].property_fee = parseFloat(_this.goodsList[i].property_fee);

                        if(!_this.goodsList[i].curBuyNumber){

                            Vue.set(_this.goodsList[i], 'curBuyNumber', 0);

                        }



                    }

                }else{

                    Toast(data.message);

                }
            });

        },

        //获取到缴费地址列表 ---完成
        addresslist(){

            var _this = this;

            Indicator.open();

            var param = {

                access_token:sessionStorage.getItem("access_token"),

            };

            addresslist(param).then(function (data) {

                Indicator.close();

                if(data.code == 0){

                    _this.addressListpop = true;
                    _this.addressList = data.content;

                }else{

                    Toast(data.message,1000);

                }

            })

        },

        // 列表商品加----完成
        // 当前购买的是第几个商品
        addGoods(index){

            var _this = this;

            // 判断是否已经存在缴费地址，没有提示添加地址
            if(_this.addressFlag()) return;

            // 判断是否已经超出了购买的数量----完成
            _this.goodsList[index].curBuyNumber++;

            // 判断是否超过限购
            if(_this.goodsList[index].curBuyNumber > _this.goodsList[index].limit_number){

                if(_this.goodsList[index].limit_number > 0){

                    _this.goodsList[index].curBuyNumber--;
                    Toast("您购买的数量太多啦~"); return;

                }

            }

            // 判断是否超过剩余数量
            if(_this.goodsList[index].curBuyNumber > _this.goodsList[index].last_number){

                _this.goodsList[index].curBuyNumber--;
                Toast("您购买的数量太多啦~"); return;

            }

            // 购物车值的变化
            _this.carInfo.totalAmount++;
            _this.carInfo.totalFee = _this.carInfo.totalFee + _this.goodsList[index].market_price;
            _this.carInfo.propertyFeeTotal = _this.carInfo.propertyFeeTotal + _this.goodsList[index].property_fee;

            // console.log(_this.carInfo.propertyFeeTotal);

            // 判断当前商品是否已经是被添加过
            // 执行添加操作
            if(_this.carInfo.goodsList.length > 0){

                var flag = false;  //假设商品没有被添加过

                for(var i = 0; i < _this.carInfo.goodsList.length; i++){

                    if(_this.goodsList[index].goods_id == _this.carInfo.goodsList[i].goods_id){

                        if(JSON.parse(sessionStorage.getItem("isAddGoods"))){

                            _this.carInfo.goodsList[i] = _this.goodsList[index];

                        };

                        flag = true;

                    }

                }

                // 如果购物车里面不存在执行添加操作
                if(!flag){

                    _this.carInfo.goodsList.push(_this.goodsList[index]);

                }

            }else{

                _this.carInfo.goodsList.push(_this.goodsList[index]);

            }

            _this.processRule("add");

        },

        // 列表商品减---完成
        // 当商品数量为0的时候相应的操作自然消失，所以不需要做判断
        // 当前删减的是第几个商品
        mimusGoods(index){

            var _this = this;

            // 变化当前商品选取的数量
            _this.goodsList[index].curBuyNumber--;

            // 购物车值的变化
            _this.carInfo.totalAmount--;
            _this.carInfo.totalFee = _this.carInfo.totalFee - _this.goodsList[index].market_price;
            _this.carInfo.propertyFeeTotal = _this.carInfo.propertyFeeTotal - _this.goodsList[index].property_fee;

            // 删除减掉的商品
            for(var i = 0; i < _this.carInfo.goodsList.length; i++){

                if( _this.carInfo.goodsList[i].curBuyNumber == 0){

                    _this.carInfo.goodsList.splice(i, 1)

                }
            }

            _this.processRule("minus");

        },

        //进度条规则-----完成
        //type:加减标志
        processRule(type){

            var _this = this;

            if(_this.carInfo.totalAmount == 0){

                _this.getFeeList(); return;

            };

            var totalPropertyFee = 0; //预计要免的费用
            var leftPropertyFee = 0; //多出来不可以免的费用

            if(!_this.feeList.length){

                return;

            }

            for(var i = 0; i < _this.feeList.length; i++){

                totalPropertyFee = totalPropertyFee + _this.feeList[i].money;

                //总的免的物业费大于预计要免的物业费----完成
                if(_this.carInfo.propertyFeeTotal >= totalPropertyFee){

                    if(_this.feeList[i].type == 1 || _this.feeList[i].type == 4){

                        _this.feeList[i].type = 2;

                    }else if(_this.feeList[i].type == 0){

                        _this.feeList[i].type = 3;

                    }

                    _this.feeList[i].percentage = 0;

                }else{ //总的免的物业费小于预计要免的物业费

                    leftPropertyFee = _this.carInfo.propertyFeeTotal - (totalPropertyFee - _this.feeList[i].money);

                    _this.feeList[i].percentage = parseInt((leftPropertyFee/_this.feeList[i].money * 100).toFixed(2));

                    if(type == 'add'){

                        if(_this.feeList[i].type == 1){

                            _this.feeList[i].type = 4;
                        }

                    }else{

                        if(_this.feeList[i].type == 2){

                            _this.feeList[i].type = 4;

                        }else if(_this.feeList[i].type == 3){

                            _this.feeList[i].type = 0;

                        }
                    }


                    _this.feeIndex = i;

                    if(type == "minus"){

                        for(var j = _this.feeList.length-1; j > _this.feeIndex; j--){

                            if(j > _this.feeIndex){

                                if(_this.feeList[j].type == 2 || _this.feeList[j].type == 4){

                                    _this.feeList[j].type = 1;

                                }else if(_this.feeList[j].type == 3){

                                    _this.feeList[j].type = 0;

                                }

                                _this.feeList[j].percentage = 0;
                            }

                        }

                    };

                    break;
                }

            };



            _this.countMouth();

        },

        //判断减免了多少个月和物业费是多少
        countMouth(){

            var _this = this;

            _this.carInfo.propertyFee = 0;

            for(var i = 0; i < _this.feeList.length; i++){

                if(_this.feeList[i].type == 2 || _this.feeList[i].type == 3){

                    _this.carInfo.propertyFee = _this.carInfo.propertyFee + _this.feeList[i].money;

                }else{

                    _this.carInfo.propertyMounth = i;
                    break;
                }

            }

            if(_this.feeList[_this.feeList.length - 1].type == 2 || _this.feeList[_this.feeList.length - 1].type == 3){

                _this.carInfo.propertyMounth = _this.feeList.length;

            }

        },

        // 是否显示购物车---完成
        showOrderList(){

            var _this = this;

            if( _this.carInfo.totalAmount <= 0){

                Toast("购物车暂无商品哦~"); return;

            }

            _this.isShowOrderList = true;

        },

        // 购物车商品加-----完成
        // 当前添加的商品索引
        addCarGoods(index){

            var _this = this;

            _this.carInfo.goodsList[index].curBuyNumber++;

            // 判断是否超过限购
            if(_this.carInfo.goodsList[index].curBuyNumber > _this.carInfo.goodsList[index].limit_number){

                if(_this.carInfo.goodsList[index].limit_number > 0){

                    _this.carInfo.goodsList[index].curBuyNumber--;
                    Toast("您购买的数量太多啦~"); return;

                }

            }

            // 判断是否超过剩余数量
            if(_this.carInfo.goodsList[index].curBuyNumber > _this.carInfo.goodsList[index].last_number){

                _this.carInfo.goodsList[index].curBuyNumber--;
                Toast("您购买的数量太多啦~"); return;
            }

            _this.carInfo.totalAmount++;
            _this.carInfo.totalFee = _this.carInfo.totalFee + _this.carInfo.goodsList[index].market_price;
            _this.carInfo.propertyFeeTotal = _this.carInfo.propertyFeeTotal + _this.carInfo.goodsList[index].property_fee;


            _this.processRule("add");

        },

        // 购物车商品减----完成
        // 当前删减的产品索引
        mimusCarGoods(index){

            var _this = this;

            _this.carInfo.totalAmount--;
            _this.carInfo.totalFee = _this.carInfo.totalFee - _this.carInfo.goodsList[index].market_price;
            _this.carInfo.propertyFeeTotal = _this.carInfo.propertyFeeTotal - _this.carInfo.goodsList[index].property_fee;
            _this.carInfo.goodsList[index].curBuyNumber--;

            _this.processRule("minus");

            if(_this.carInfo.goodsList[index].curBuyNumber == 0){

                _this.carInfo.goodsList.splice(index,1);

                if(_this.carInfo.goodsList.length == 0){

                    _this.isShowOrderList = false;

                }

            }

        },

        // 跳转到公司介绍页
        // url:跳转链接
        goToCompany(url){

            var _this = this;
            _this.doCache();

            var fdStart = url.indexOf("http");

            if(fdStart == 0){
                window.location.href = url;
            }else if(fdStart == -1){

                _this.mobileJump(url);
            }

        },

        // 跳转到商品详情页面
        // goodsId:商品ID
        // index:当前的商品的索引值
        // type:商品类别
        goGoodsDetail(goodsId,index,type){

            var _this = this;

            // 若是财富不给跳转
            if(type == 2){
                return;
            }

            _this.doCache();
            sessionStorage.setItem('curGoodsIndex',JSON.stringify(index)); //当前查看详情的商品索引

            if(!_this.addressFlag()){

                _this.$router.push({path:'/home/goodsdetail',query:{'goods_id':goodsId},name:'Goodsdetail'});

            }

        },

        // 跳转到第三方页面
        //url:第三方地址
        goOtherPage(url){

            var _this = this;

            _this.doCache();

            var fdStart = url.indexOf("http");

            if(fdStart == 0){
                window.location.href = url;
            }else if(fdStart == -1){

                _this.mobileJump(url);
            }

        },


        //跳转到支付页面
        goToPay(){

            var _this = this;

            // 按钮改成灰色
            if(_this.carInfo.propertyMounth < 1){

                Toast("暂时还不可以免物业费哦");return;

            }

            //需要缓存的东西
            _this.doCache();

            this.$router.push({path:'/home/confirmation',query:{freeAddress:_this.propertySn.default_property,freeAddressuuid:_this.propertySn.room_uuid,total:parseFloat(_this.carInfo.totalFee.toFixed(2))},name:'Confirmation'});

        },


        //上拉加载---完成
        addMore(){

            var _this = this;

            if($(document).scrollTop() + window.screen.availHeight >= $(document).height()){

                if(_this.goodsList.length < _this.total){

                    _this.idAddMore = true;
                    _this.loadmore = true;
                    _this.curPage++;

                    setTimeout(function() {

                        _this.getGoodsList();return;

                    }, 1000);

                }else{

                    _this.loadmore = false;

                }
            }
        },

        // 选择缴费地址----完成
        // item:当前是属于哪一条地址
        // index:地址的索引值
        selectAddress(item,index){

            var _this = this;

            for(var i=0;i<_this.addressList.length;i++){

                _this.addressList[i].is_default = 0;

            }

            _this.addressList[index].is_default = 1;
            _this.room_uuid = item.room_uuid;
            _this.defaultAddress = _this.addressList[index].address;

        },

        // 确认所选择的缴费地址
        sureAddress(){

            var _this = this;

            Indicator.open();

            var param = {
                access_token:sessionStorage.getItem("access_token"),
                room_uuid:this.room_uuid
            };

            propertyChoose(param).then(function (data) {

                Indicator.close();

                if(data.code == 0){

                    _this.addressListpop = false;

                    if(data.content == 1){

                        Toast("缴费地址修改成功",1000);
                        localStorage.clear();

                        //重新加载页面
                        window.location.reload();

                    }else{

                        Toast("缴费地址修改失败",1000);

                    }

                }else{

                    Toast(data.message);

                }
            });

        },

        // 跳转到订单列表页----完成
        goOrderList(){

            var _this = this;

            _this.$router.push({path:'/home/orderlist',name:'Orderlist'});

        },

        // 跳转到添加地址页面
        jumpAddress(){

            var _this = this;

            window.location.href = eft;

        },

        // 跳转到未支付订单页面
        jumpWaitPay(){

            var _this = this;

            _this.$router.push({path:'/home/orderdetail',query:{'colour_sn':_this.propertySn.old_sn,state:1},name:'Orderdetail'});

        },

        // 提示添加地址弹框
        addressFlag(){

            var _this = this;

            if(_this.propertySn.is_property == 0){

                _this.selectAddressPop = true;
                return true;

            }else{
                return false;
            }

        },

        // 点击关闭弹框和遮罩----完成
        hidepop(){

            var _this = this;

            _this.addressListpop = false;
            _this.waitPayPop = false;
            _this.selectAddressPop =false;
            _this.isShowOrderList = false;

        },

        //字符串截取
        getQueryString:function(name) {

            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;

        },

        // 跳原生方法
        //cmd:跳转地址
        mobileJump(cmd) {

            if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {

                var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
                document.location = _cmd;

            } else if (/(Android)/i.test(navigator.userAgent)) {

                var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
                eval(_cmd);

            } else {}

        },

    },

    // 滚动监听---完成
    mounted:function() {

        var _this = this;

        window.addEventListener('scroll',function(){

            if(!_this.idAddMore){

                _this.addMore();

            };

        });

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

.circleProgress{
    /*margin-top:0.3rem;*/
}

.el-progress-circle{
    width:.84rem !important;
    height:.84rem !important;
}

.el-progress-circle + div{
    color:#BBC0CB;
    font-size:.28rem !important;
}

.processCircle svg path:last-child{
  stroke: #FE6262 !important;
}

.fack_hide{
    visibility: hidden;
}



</style>
