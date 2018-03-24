<template>
  <section>
    <ul class="address-info-wrap">
        <li v-for="(item,index) in addressListArr">
            <div class="editAdress-top"  @click="AddressXueZhe(item)">
                <p>
                    <span v-text="item.real_name"></span>
                    <span v-text="item.mobile"></span>
                </p>
                <p v-text="item.address"></p>
            </div>
            <div class="editAdress-bottom">
                <p class="defaultAddress" @click="defaultAddress(item.address_id,index)"><em class="default-address" :class="{active:item.is_default == 1}"> </em><span>设为默认地址</span></p>
                <p>
                    <a href="javascript:void(0);" class="edit" @click.stop="editRess(item.address_id)">编辑</a>
                    <a href="javascript:void(0);" @click.stop="delRess(item.address_id)">删除</a>
                </p>
            </div>
        </li>
    </ul>
    <router-link :to="{path:'/fixaddress',query:{}}" class="add-address" tag="p">+ 添加新地址</router-link>   
    <!-- <p ></p> -->
</section>  
</template>

<script>
import { receivAddress,editAddress,delAddress} from '../api/api';
import { Toast, Indicator } from 'mint-ui';
export default {
  name: 'addresslist',
  data () {
    return {
        addressListArr:[]
    }
  },
  created() {
    this.initPage();
  },  
  methods:{
    initPage:function(){
      Indicator.open();
      let _this = this;
      let param = {
       access_token:sessionStorage.getItem("access_token"),
      };
      receivAddress(param).then(function (data) {
        Indicator.close();
          if(data.code == '0'){
              _this.addressListArr = data.content;
          }else{
             Toast(data.message);
          }
      })
    },
    delRess:function(address_id){
        console.log(address_id);
      let _this = this;
      Indicator.open();
      let param = {
          access_token:sessionStorage.getItem("access_token"),
          address_id:address_id,
      };
        delAddress(param).then(function (data) {
            console.log(data);
          Indicator.close();
          if(data.code == '0'){
              for(var l=0;l<_this.addressListArr.length;l++){
                  console.log(_this.addressListArr[l].address_id)
                  if(_this.addressListArr[l].address_id==address_id){
                      _this.addressListArr.splice(l,1);
                      Toast("删除成功",500);
                      return;
                  }
              }
          }else{
             Toast(data.message);
          }
      })
    },
    defaultAddress:function(id,index){
      for(var i = 0;i<this.addressListArr.length;i++){
          this.addressListArr[i].is_default = 0;
      }
      this.addressListArr[index].is_default = 1;
      Indicator.open();
      let param = {//编辑
        access_token:sessionStorage.getItem("access_token"),
        address_id:id,
        is_default:1,
      }
      editAddress(param).then(function (data) {
        Indicator.close();
        if(data.code == '0'){
            // Toast("默认地址设置成功");
        }else{
             Toast(data.message);
        }
      })
    },
    // setRecAddress:function(item){
    //   console.log(item);
    //     this.$router.push({path:'/home/confirmation',query:{},name:'Confirmation'});
    // },
    AddressXueZhe:function(item){
        sessionStorage.setItem('Select_address',JSON.stringify(item));
        this.$router.push({path:'/home/confirmation',query:{},name:'Confirmation'});
    },
    editRess:function(item){
      console.log(JSON.stringify(item));
      this.$router.push({path:'/fixaddress',query:{'address_id':item},name:'Fixaddress'});
      return;
    }

  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
        .address-info-wrap{
          min-height:10.8rem;
        }
        .address-info-wrap{
  margin-bottom: 1.1rem;
}
.address-info-wrap li{
  padding:0 .27rem;
  margin-bottom:.1rem;
  background-color: #fff;
  overflow: hidden;
}
.editAdress-top{
  border-bottom:1px solid #e5e9ea;
  margin-top:.3rem;
}
.editAdress-top p:nth-child(1){
  color:#333b46;
  font-size:.32rem;
  overflow: hidden;
}
.editAdress-top p:nth-child(1) span:nth-child(1){
  float:left;
}
.editAdress-top p:nth-child(1) span:nth-child(2){
  float:right;
}
.editAdress-top p:nth-child(2){
  margin-top:.1rem;
  margin-bottom: .2rem;
}
.editAdress-bottom p:nth-child(1){
  display: inline-block;
  height:.72rem;
  line-height: .72rem;

}
.editAdress-bottom p:nth-child(1) em{
  width:.33rem;
  height: .33rem;
 /* margin:.34rem .1rem 0 .3rem ;*/
  margin-top:.2rem;
  margin-right:.1rem;
  float:left;
  background: url(../../static/images/check.png) no-repeat;
  background-size: 100%;
}
.editAdress-bottom p:nth-child(1) em.active{
  background: url(../../static/images/check_active.png) no-repeat;
  background-size: 100%;
}
.editAdress-bottom p:nth-child(1) span{
  padding-top:.2rem;
}
.editAdress-bottom p:nth-child(2){
  float:right;
  display: inline-block;

}
.editAdress-bottom p:nth-child(2) a{
  width:1rem;
  height:.5rem;
  line-height: .5rem;
  border:1px solid #bfc7cc;
  border-radius: 3px;
  display: inline-block;
  text-align: center;
  margin-top: .15rem;
  color:#333b46;
  font-size: .26rem;
  margin-left:.2rem;
}
.address-info-wrap{
  min-height:10.8rem;
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
