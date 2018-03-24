
<!-- 修改收货地址 -->

<template>
    <div>
        <section>
            <form action="/cart/saveAddress" class="addAdress-form">
                <!-- <input type="hidden" name="aid" value="<?php echo $address_id; ?>"> -->
                <ul class="address-info">
                    <li>
                        收货人：<input type="text" name="name" id="name" value="" placeholder="请输入收货人姓名" v-model="addressInfo.real_name"> 
                        <!-- v-model="address" -->
                    </li>
                    <li>
                        联系方式：<input type="text" name="mobile" id="mobile" value="" placeholder="请输入收货人联系方式" v-model="addressInfo.mobile">
                    </li>
                    <li>
                        <select name="province" id="province" class="provice" v-model="addressInfo.province_id" >
                            <option value="0">选择省</option>
                            <option v-for="(item,index) in proviceList" v-bind:value="item.province_id"  v-text="item.province_name"></option>
                        </select>

                        <select name="city" id="city" class="city" v-model="addressInfo.city_id">
                            <option value="0">选择市</option>
                            <option v-for="(item,index) in cityList" :value='item.city_id' v-text="item.city_name"></option>
                        </select>

                        <select name="area" id="area" class="area" v-model="addressInfo.county_id">
                            <option value="0">选择县/区</option>
                            <option v-for="(item,index) in areaList" :value='item.county_id' v-text="item.county_name"></option>
                        </select>
                    </li>

                    <li>
                        <select name="town" id="town" class="town" v-model="addressInfo.town_id">
                           <option value="0">选择镇/街道(必填，除非没有选项)</option>
                           <option v-for="(item,index) in townList" :value='item.town_id' v-text="item.town_name"></option>
                        </select>
                    </li>
                    <li>
                        <input type="text" name="address" id="address" placeholder="请您按XX路(道)XX号形式填写收货地址" class="detail-address" value="" v-model="addressInfo.address">
                    </li>
                </ul>
            </form>
            <p class="add-address confirm_btn" @click="saveAddress">保存地址</p>
        </section>  
    </div> 
</template>

<script>
import { region ,editAddress , addAddress , receivAddressdetail} from '../api/api';
import { Toast, Indicator } from 'mint-ui';
export default {
  name: 'Addaddress',
  data () {
    return {
        proviceList:[],
        cityList:[],
        areaList:[],
        townList:[],
        
        addressInfo:{
          address_id:'',
          real_name: '',
          mobile:'',
          address:'',
          is_default: '',
          province_id: '0',
          province_name: '选择省',
          city_id: '0',
          city_name: '选择市',
          county_id: '0',
          county_name: '选择县/区',
          town_id: '0',
          town_name:'选择镇/街道(必填，除非没有选项)',
        },

        type:1,
    }
  },
  created() {
    // alert(this.addressInfo.address_id)
    if(this.$route.query.address_id != undefined){
        this.addressDetail();//详情
    }else{
      this.regionlist(1);
    }
     
  },
  computed:{
      provinceid:function(){
          return this.addressInfo.province_id;
      },
      cityid:function(){
          return this.addressInfo.city_id;
      },
      countyid:function(){
          return this.addressInfo.county_id;
      },
      townid:function(){
          return this.addressInfo.town_id;
      }
  },
  methods:{
    addAddressInfo:function(){
      let _this = this;
      let param = {//新增
        access_token:sessionStorage.getItem("access_token"),
        real_name:this.addressInfo.real_name,
        mobile:this.addressInfo.mobile,
        province_id:this.addressInfo.province_id,
        city_id:this.addressInfo.city_id,
        county_id:this.addressInfo.county_id,
        town_id:this.addressInfo.town_id,
        address:this.addressInfo.address,
      };

      Indicator.open();
      addAddress(param).then(function (data) {
        Indicator.close();
        if(data.code == '0' && data.content != 0){
            Toast("地址保存成功",1000);
            sessionStorage.setItem("address_id",data.content);
            window.setTimeout(function(){
              _this.$router.push({path:'/home/confirmation',query:{mobile:_this.addressInfo.mobile,real_name:_this.addressInfo.real_name,address:_this.addressInfo.address,address_id:data.content},name:'Confirmation'});
            },1000)  
        }else{
             Toast(data.message);
        }
      }) 
    },
    editAddressInfo:function(){
      let _this = this;
      let param = {//编辑
        access_token:sessionStorage.getItem("access_token"),
        address_id:this.$route.query.address_id,
        real_name:this.addressInfo.real_name,
        mobile:this.addressInfo.mobile,
        province_id:this.addressInfo.province_id,
        city_id:this.addressInfo.city_id,
        county_id:this.addressInfo.county_id,
        town_id:this.addressInfo.town_id,
        address:this.addressInfo.address,
        is_default:this.addressInfo.is_default,
      }
      Indicator.open();
      editAddress(param).then(function (data) {
        Indicator.close();
        if(data.code == '0'){
            Toast("地址修改成功",1000);
            _this.$router.push({path:'/addresslist',query:{},name:'Addresslist'});
             
        }else{
             Toast(data.message);
        }
      })

    },
    addressDetail:function(){
      let _this = this;
      let param = {
          access_token:sessionStorage.getItem("access_token"),
          address_id:this.$route.query.address_id
      };
      receivAddressdetail(param).then(function (data) {
          if(data.code == '0'){
              _this.addressInfo = data.content;
                  _this.regionlist(1);
                  _this.regionlist(2);
                  _this.regionlist(3);
          }else{
               Toast(data.message);
          }
      })
    },
    regionlist:function(i){
      let _this = this;      
        let param = {
            access_token:sessionStorage.getItem("access_token"),
            type:i,
            province_id:this.addressInfo.province_id,
            city_id:this.addressInfo.city_id,
            county_id:this.addressInfo.county_id,
        };

        // console.log(this.addressInfo.province_id);
        region(param).then(function (data) {
            if(data.code == '0'){
              if(i == 1){
                _this.proviceList = data.content;
              }else if(i == 2){
                _this.cityList = data.content;
              }else if(i == 3){
                _this.areaList = data.content;
              }else if(i == 4){
                _this.townList = data.content;
              }
            }else{
                 Toast(data.message);
            }
        })
    },
    saveAddress:function(){
      let _this = this;
      var reg=/^1\d{10}$/;
      if (this.addressInfo.real_name=="") {
          Toast("请输入收件人姓名",1000);
          return false;
      }
      if (this.addressInfo.mobile=="" || !reg.test(this.addressInfo.mobile)) {
          Toast("请输入正确的手机号码",1000);
          return false;
      }
      if (this.addressInfo.address=="") {
          Toast("请输入详细收货地址",1000);
          return false;
      }
      if (this.addressInfo.province_id=="" || this.addressInfo.city_id=="" || this.addressInfo.county_id=="" ||this.addressInfo.province_id == 0 || this.addressInfo.city_id==0 || this.addressInfo.county_id==0 ) {
          Toast("请先选择省/城市",1000);
          return false;
      }
      // else if(jiedao==1 && town==""){
      //         Toast("请先选择镇/街道",1000);
      //         return false;
      // }
      else{
          if(this.$route.query.address_id){
            this.editAddressInfo();
          }else{
            this.addAddressInfo();
          }
      }        
          // var color=$(".confirm_btn").css("background-color");
          // console.log(color);
          // if(color=='rgb(254, 98, 99)'){
          //     $(".confirm_btn").css('background-color','#ccc');
          //     $('form').submit();
          // }        
    }
  },
  watch:{
    provinceid:function(v,o){
      console.log(v,o);
      if(o != ''){
        this.regionlist(2);
        this.addressInfo.city_id = '0';
        this.addressInfo.county_id = '0';
        this.addressInfo.town_id = '0';
      }
    },
    cityid:function(v,o){
      if(o != ''){
        this.regionlist(3);
        this.addressInfo.county_id ='0';
        this.addressInfo.town_id = '0';
      }
    },
    countyid:function(v,o){
      if(o != ''){
        this.regionlist(4);
        // this.addressInfo.town_id = '0';
     }
    },
    townid:function(){

    },
    type:function(){

    }
  },
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
  background: url(../../static/images/arrow.png) no-repeat scroll 90% center transparent;
}
.address-info li:nth-child(4)>select{
  width: 70%;
  height: 100%;
  -webkit-appearance: none;
  border: solid 1px #fff;
  padding-left: 2%;
  background: url(../../static/images/arrow.png) no-repeat scroll 100% center transparent;
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
  background-color:#7CAFF5;
  z-index: 999;
}
</style>