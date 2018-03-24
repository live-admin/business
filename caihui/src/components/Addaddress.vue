<!-- 没有用到 -->
<template>
<div>
  <section>
    <form action="/cart/saveAddress" class="addAdress-form">
        <input type="hidden" name="aid" value="<?php echo $address_id; ?>">
        <ul class="address-info">
            <li>
                收货人：<input type="text" name="name" id="name" value="梁晓珠" placeholder="请输入收货人姓名">
            </li>
            <li>
                联系方式：<input type="text" name="mobile" id="mobile" value="132434545465" placeholder="请输入收货人联系方式">
            </li>
            <li>
                <select name="province" id="province" class="provice">
                    <?php
                    if(empty($provinceId)){
                        echo '<option value="">选择省</option>';
                    }

                    if ($provinceArray){
                        foreach($provinceArray as $k=>$v){
                            if($v['id'] == $provinceId){
                                echo "<option selected='selected' value='".$v['id']."'>".$v['name']."</option>";
                            }else{
                                echo "<option value='".$v['id']."'>".$v['name']."</option>";
                            }

                        }
                    }
                    ?>
                </select>
                <select name="city" id="city" class="city">
                    <?php
                    if(empty($cityId)){
                        echo '<option value="">选择市</option>';
                    }

                    if ($cityArray){
                        foreach($cityArray as $k=>$v){
                            if($v['id'] == $cityId){
                                echo "<option selected='selected' value='".$v['id']."'>".$v['name']."</option>";
                            }else{
                                echo "<option value='".$v['id']."'>".$v['name']."</option>";
                            }
                        }
                    }
                    ?>
                </select>
                <select name="area" id="area" class="area">
                    <?php
                    if(empty($countyId)){
                        echo '<option value="">选择县/区</option>';
                    }
                    if ($countyArray){
                        foreach($countyArray as $k=>$v){
                            if($v['id'] == $countyId){
                                echo "<option selected='selected' value='".$v['id']."'>".$v['name']."</option>";
                            }else{
                                echo "<option value='".$v['id']."'>".$v['name']."</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </li>
            <li>
                <select name="town" id="town" class="town">
                    <?php
                    if(empty($townId)){
                        echo '<option value="">选择镇/街道(必填，除非没有选项)</option>';
                    }

                    if ($townArray){
                        foreach($townArray as $k=>$v){
                            if($v['id'] == $townId){
                                echo "<option selected='selected' value='".$v['id']."'>".$v['name']."</option>";
                            }else{
                                echo "<option value='".$v['id']."'>".$v['name']."</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </li>
            <li>
                <input type="text" name="address" id="address" placeholder="请您按XX路(道)XX号形式填写收货地址" class="detail-address" value="塘水围二区一栋8080">
            </li>
        </ul>
    </form>
    <p class="add-address confirm_btn">保存地址</p>
</section>  
</div> 
</template>

<script>
import { subList } from '../api/api';
import { Toast, Indicator } from 'mint-ui';
export default {
  name: 'Addaddress',
  data () {
    return {
  
    }
  },
  created() {
    this.initPage();
  },  
  methods:{
    initPage:function(){
      // let param = {
      //  access_token:sessionStorage.getItem("access_token"),
      //  goods_sn:this.$route.query.goods_sn
      // };
      // subList(param).then(function (data) {
      //     if(data.code == '0'){
                  // this.list = data.content.result;
      //     }else{
      //        Toast(data.message);
      //     }
      // })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .address-info{
  background-color: #fff;
}
.address-info li{
  height:.9rem;
  line-height: .9rem;
  border-bottom:1.5px solid #f2f3f4;
  font-size:.28rem;
  padding:0 .26rem;
  color:#333b46;
}
.address-info li input{
  border:0;
  margin-left:.1rem;
  width:4.5rem;
}
#address{
  width:100%;
}
.address-info li:nth-child(3)>select{
  width: 30%;
  height: 100%;
  appearance: none;
  -moz-appearance: none;
  -webkit-appearance: none;
  border: solid 1px #fff;
  padding-left: 2%;
  background: url(http://ourjs.github.io/static/2015/arrow.png) no-repeat scroll 90% center transparent;
}
.address-info li:nth-child(4)>select{
  width: 70%;
  height: 100%;
  -webkit-appearance: none;
  border: solid 1px #fff;
  padding-left: 2%;
  background: url(http://ourjs.github.io/static/2015/arrow.png) no-repeat scroll 100% center transparent;
}
.add-address{
  width:7.5rem;
  height: 1rem;
  line-height: 1rem;
  font-size:.36rem;
  color:#fff;
  text-align: center;
  position:fixed;
  bottom:0;
  left:0;
  background-color:#fe6263;
  z-index: 999;
}
</style>
