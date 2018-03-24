<!-- 支付成功页面 -->

<template>
    <div class="pay">
        <div>
          <img src="../../static/images/pay_succeed.png" alt="" v-if="payInfo.trade_state == '成功'">
          <p>支付<span v-text="payInfo.trade_state"></span></p>
        </div>
        <div>
          <p>¥<span v-text="payInfo.real_fee"></span></p>
          <p>已免<span v-text="payInfo.month_count">0</span>个月物业费</p>
        </div>
        <a href="javascript:;" @click="home">返回首页</a>
    </div>    
</template>

<script>
import { orderCheck } from '../api/api';
import { Toast, Indicator } from 'mint-ui';
export default {
  name: 'Paysucceed',
  data () {
    return {
        payInfo:{
          trade_state:'',
          real_fee:'',
          month_count:'',
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
        colour_sn:this.$route.query.colour_sn.split("?")[0]
      };

      orderCheck(param).then(function (data) {
          if(data.code == '0'){
              _this.payInfo = data.content;
             
          }else{
             Toast(data.message);
          }
      })
    },
    home:function(){
      // alert(sessionStorage.getItem("access_token"));
        // this.$router.push({path:'/home',query:{access_token:sessionStorage.getItem("access_token"),state:3},name:'Home'})
        window.location.href=base+"/dist/index.html"+'?access_token='+sessionStorage.getItem("access_token");
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
