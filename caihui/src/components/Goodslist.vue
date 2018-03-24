<!-- 子订单商品数据-->
<template>
  <div class="goods_list_detail">
        <p>
            共
            <span>{{carInfo.totalAmount}}</span>
            种商品
        </p>

        <ul>
            <li v-for="(item,index) in carInfo.goodsList">
                <div>
                   <img :src="item.goods_img" alt="">
                   <div>
                       <p v-text="item.goods_name">养生壶</p>
                       <p></p>
                       <p v-text="item.market_price">¥100.00</p>
                   </div> 
                   <p>x<span v-text="item.curBuyNumber">1</span></p>
                </div>
            </li>
        </ul>
    </div>  
</template>

<script>
import { subList } from '../api/api';
import { Toast, Indicator } from 'mint-ui';
import Vue from 'vue'
export default {
    name: 'Goodslist',
    data () {
        return {
        //     list:{
        //         goods_count:'',
        //         goods_arr:[]
        //     },

            carInfo:{ //已经选购的商品信息
                totalAmount: 0, //现在购买商品的总件数
                totalFee:0,//购买商品的总费用
                propertyMounth:0,//可送物业的月数
                propertyFee:0,//可送物业的总金额
                goodsList:[], //所购买的商品列表
            }, 
        }
    },

    created() {

        var _this = this;

        if(this.$route.query.goods_sn){

            var goodsList = JSON.parse(sessionStorage.getItem('subGoodsList'));

            console.log(goodsList);

            // Vue.set(_this.carInfo, 'totalAmount', goodsList.length);
            // Vue.set(_this.carInfo, 'goodsList', goodsList);

            _this.carInfo.totalAmount = goodsList.length;

            _this.carInfo.goodsList = goodsList;

            for(var i = 0; i < _this.carInfo.goodsList.length; i++){

                _this.carInfo.goodsList[i].curBuyNumber = _this.carInfo.goodsList[i].count;

            }


        }else{

            _this.carInfo = JSON.parse(sessionStorage.getItem('carInfo'));

        }

        

        // if(sessionStorage.getItem('goodslist')!=null){

        //     var goodslist= JSON.parse(sessionStorage.getItem('goodslist'));

        //     console.log(goodslist);

        //     for(var i=0;i<goodslist.length;i++){

        //         if(goodslist[i].amount>0){

        //             this.list.goods_arr.push(goodslist[i]);

        //         }
        //     }

        //     this.list.goods_count=this.list.goods_arr.length;

        //     console.log(this.list.goods_arr);
        // }
    },  

    methods:{
        
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
