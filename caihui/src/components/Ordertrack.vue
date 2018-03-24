
<!-- 物流跟踪 -->

<template>
  <div class="order_track">
        <div class="order_info_num">
            <p>订单编号：<span v-text="deliverlist.order.colour_sn"></span></p>
            <p>京东订单号：<span v-text="deliverlist.order.deliver_sn"></span></p>
        </div>
        <div class="order_path">
            <ul>
                <li :class="{active:index == 0}" v-for="(item,index) in deliverlist.deliver">
                    <p v-text="item.operator +' '+item.content"></p>
                    <p v-text="item.msgTime"></p>
                    <p><span></span></p>
                </li>
            </ul>
        </div>
    </div>     
</template>

<script>
import { deliverDetail } from '../api/api';
import { Toast, Indicator } from 'mint-ui';
export default {
  name: 'Ordertrack',
  data () {
    return {
        deliverlist:{
          order: {
            colour_sn:'',
            deliver_sn: '',
          },
          deliver: []
        }
    }
  },
  created() {
    this.initPage();
  },
  methods:{
    initPage:function(){
        let _this = this;
        let param = {
             access_token:sessionStorage.getItem("access_token"),
             goods_sn:this.$route.query.goods_sn
            };
            deliverDetail(param).then(function (data) {
                if(data.code == '0'){
                    _this.deliverlist = data.content;
                }else{
                   Toast(data.message);
                }
            })
        }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
