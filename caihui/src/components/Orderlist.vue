<!-- 订单列表 -->
<!-- 没有问题 -->
<template>
    <div class="confirmation">
        <div class="goods_list order_list_state">
            <ul>
                <li @click="orderDetail(item)" v-for="(item,index) in goodslist" :class="{signle:item.goods_arr.length == 1 }">
                    <div class="list_state_info">
                        <p><span v-text="item.time_create"></span><span :class="item.type" v-text="item.trade_state"></span></p>
                        <p>已免<span v-text="item.month_count">0</span>个月物业费</p>
                    </div> 
                    <div v-if="item.goods_arr.length == 1">
                        <div class="goods_show">
                            <img :src="item.goods_arr[0].goods_img" alt="">
                        </div> 
                        <p class="goods_des" v-text="item.goods_arr[0].goods_name"></p>
                    </div>
                    <div  v-if="item.goods_arr.length > 1">
                        <div class="goods_show more_show" v-for="(i,index) in item.goods_arr" v-if="index < 5">
                            <img :src="i.goods_img" alt="">
                        </div>
                    </div>
                    <p>共<span v-text="item.goods_count"></span>件商品 实付款：¥<span v-text="item.trade_amount"></span></p>
                </li>
                    
            </ul>
        </div>
        <div class="loadwrap" v-show="loadmore">
            <p class="loading"></p>
            <!--  <i>正在加载</i> -->
        </div>
        <div class="loadwrap" v-show="!loadmore">
            <i>没有更多数据~</i>
         </div>
    </div> 
</template>

<script>
import { orderList } from '../api/api';
import { Toast, Indicator } from 'mint-ui';
import $ from 'jquery'
export default {
  name: 'Orderlist',
  data () {
    return {

        goodslist:[],

        //分页
        current_page:1,
        total:'',
        per_page:10,
        scroll: '',
        loadmore:false,
        arr:[],
    }
  },
  created() {

    var _this = this;

    _this.orderLists();

  },

  methods:{

    orderLists:function(){

        let _this = this;

        let param = {

            access_token:sessionStorage.getItem("access_token"),
            page:this.current_page,

        };

        Indicator.open();

        orderList(param).then(function (data) {

            Indicator.close();

            if(data.code == '0'){

                _this.current_page = data.content.now_page;
                _this.total = data.content.total;
                _this.arr = data.content.data;
                _this.goodslist = _this.goodslist.concat(_this.arr);

                for(var i =0;i<_this.goodslist.length;i++){

                    if(_this.goodslist[i].trade_code == '1'){

                        _this.goodslist[i].type = "wait";

                    }else if(_this.goodslist[i].trade_code == '2'){

                        _this.goodslist[i].type = "succeed";

                    }else if(_this.goodslist[i].trade_code == '3'){

                        _this.goodslist[i].type = "succeed";

                    }else if(_this.goodslist[i].trade_code == '4'){

                        _this.goodslist[i].type = "fail";

                    }
                }

            }else{

               Toast(data.message);

            }

        })
    },
    orderDetail:function(item){
        // let type;
        // if(state == '1'){//代付款
        //     type = 1;
        // }else if(state == '2'){//已付款
        //     type = 2;
        // }else if(state == '3'){//成功
        //     type = 3;
        // }else if(state == '4'){//已取消
        //     type = 4;
        // }item.trade_code,item.colour_sn
        this.$router.push({path:'/orderdetail',query:{state:item.trade_code,colour_sn:item.colour_sn},name:'Orderdetail'});

    },
    menu:function() {
        
        let _this = this;
            if($(document).scrollTop() + window.screen.availHeight >= $(document).height()){
                if(this.current_page < Math.ceil(this.total/this.per_page)){
                    _this.loadmore = true;
                    setTimeout(function() {
                         _this.orderLists();
                    }, 1000);
                   _this.current_page = parseInt(_this.current_page) + 1; 
                }
            }else{
                _this.loadmore = false;
            }
        },
    },
    // 滚动监听
    mounted:function() {
         
        window.addEventListener('scroll', this.menu);

    },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
