<!-- 商品详情 -->
<template>
    <div class="goods_detail">

        <div>
            <img :src="info.banner_img" alt="">
            <p v-text="info.goods_name">V字碎花连衣裙2018夏季新款女装大码韩版修身长袖性感长款裙子  图片色 M</p>
            <p>¥&nbsp;<span v-text="info.price"></span></p>
        </div>

        <div class="goods_detail_show">
            <p>商品详情</p>
            <div v-html="info.goods_detail">
                
            </div>
        </div>

        <div class="add_buy" @click="addGoods">
            <p>选购</p>
        </div>

    </div>    
</template>

<script>
import { goodsDetail } from '../api/api';
import { Toast, Indicator , Loadmore} from 'mint-ui';
export default {
    name: 'Goodsdetail',
    data () {
        return {

            info:{

                goods_id:'',
                banner_img:'',
                goods_name:'',
                price:'',
                other_price:'',
                service_amount:'',   
                stock:'',
                goods_detail:'',
            }
        }
    },
    created(){

        var _this = this;

        _this.initPage();

    },
    methods:{

        initPage:function(){

            let _this = this;

            let param = {

                access_token:sessionStorage.getItem("access_token"),
                goods_id:this.$route.query.goods_id,

            };

            Indicator.open();

            goodsDetail(param).then(function (data) {

                if(data.code == '0'){

                    Indicator.close();

                    _this.info = data.content;

                }else{

                   Toast(data.message);

                }
            })
        },

        addGoods:function(){

            var _this = this;

            sessionStorage.setItem('isAddGoods',JSON.stringify("true")); //是否点击添加了商品

            this.$router.push({path:'/home',query:{access_token:sessionStorage.getItem("access_token")},name:'Home'});

        },
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
