<template>
  <div class="tab-content">
    <ul class="block">
      <li>
        <span class="demonstration">时间</span>
        <el-date-picker v-model="times" type="datetimerange" placeholder="选择时间范围" size="small" style="width: 300px;"></el-date-picker>
      </li>
      <li>
        <span class="demonstration">商户名称</span>
        <el-input
          placeholder="请输入商户名称"
          v-model="MerchantName" style="width:240px;"  size="small">
        </el-input>
      </li>
      <li>
        <span class="demonstration">支付方式</span>
        <el-select v-model="payment_uuid" clearable placeholder="请选择支付方式" size="small">
          <el-option
            v-for="item in payment_uuidArray"
            :key="item.uuid"
            :label="item.name"
            :value="item.uuid">
          </el-option>
        </el-select>
      </li>
      <li>
        <span class="demonstration">类目</span>
        <el-select v-model="MerchantList" clearable placeholder="请选择商户类目" size="small">
          <el-option
            v-for="item in Merchantoptions"
            :key="item.uuid"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </li>
      <li>
        <span class="demonstration">组织架构</span>
        <el-select
          v-model="org_uuid"
          :multiple="false"
          filterable
          clearable
          remote
          reserve-keyword
          placeholder="请输入组织名称"
          :remote-method="orgMethod"
          :focus="GetFocus"
          :loading="orgloading"
          size="small">
          <el-option
            v-for="item in OrgOptions"
            :key="item.uuid"
            :label="item.name"
            :value="item.uuid">
          </el-option>
        </el-select>
      </li>
      <li>
        <el-button type="primary" icon="search" @click="MerchantSearch" size="small"></el-button>
        <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
      </li>
    </ul>
    <el-col :span="24">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>总交易额</span>
          <!--<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>-->
        </div>
        <div class="text item">{{search_times}}</div>
        <div class="text item">{{search_Category}}</div>
        <div class="text item">{{search_orgUuid}}</div>
        <div class="text item">{{search_MerchantName}}</div>
        <div class="text item">{{search_payment}}</div>
        <div class="text item">{{Total_volume}}</div>
      </el-card>
    </el-col>
  </div>
</template>
<script>
  import {backendBusinessGeneralList,backendOrderSum,backendPayList,backendRolePrivilegeOrg} from './../../api/api';
  export default {
    props: {},
    data(){
    return {
      MerchantName: '',//商户名称
      payment_uuidArray:[],//支付方式数组
      payment_uuid: '',//支付方式
      OrgName:'',
      OrgOptions:[],
      orgloading: false,
      org_uuid: '',//组织架构UUID
      Total_volume:'',//总交易额
      Merchantoptions: [],
      MerchantList: '',//商户类目
      total_money:'总金额（单位:元）',
      search_Category:'',
      search_orgUuid:'',
      search_MerchantName:'',
      search_payment:'',
      search_times:'',
      //看板新增时间
      times:'',//时间间隔
      create_time:'',
      end_time:'',
    };
  },
  created:function() {
    this.DetailsStart();
    this.Merchant_category();
    this.payment_function();
  },
  methods: {
    emptyFun(){
      window.location.reload();
    },
    DetailsStart(){
      let _this = this;
      let localRoutes = JSON.parse(window.sessionStorage.getItem('user'));
      let BusinesParams = {
        access_token: localRoutes.access_token,
        general_id: this.MerchantList,//商户类目ID
        payment_uuid: this.payment_uuid,
        shop_name: this.MerchantName,//商户名称
        org_uuid: this.org_uuid,
        create_time:this.create_time,//开始时间
        end_time:this.end_time//结束时间
      };
      backendOrderSum(BusinesParams).then(data =>{
        if(data.code == 0){
            if(data.content==''){
              this.Total_volume=0;
            }else{
              this.Total_volume=data.content;
            }
        }else{
          this.$notify.error({
            title: '错误提示',
            message: data.message
          });
          this.loadListing = false;
          if (data.code == '402') {
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('user_router');
            this.$router.push({path: '/login'});
          }
        }
    }
  )},
  MerchantSearch(){//搜索
    var _this=this;
    if(_this.MerchantList==''&&_this.payment_uuid==''&&_this.MerchantName==''&&_this.org_uuid==''&&_this.times==''){
      _this.$notify({
        title: '警告',
        message: '请输入查询信息',
        type: 'warning'
      });
    }else{
      _this.search_Category='';
      _this.search_orgUuid='';
      _this.search_MerchantName='';
      _this.search_payment='';
      _this.search_times='';
      if(_this.MerchantList!=''){
        for(var i=0;i<_this.Merchantoptions.length;i++){
          if(_this.MerchantList==_this.Merchantoptions[i].id){
            _this.search_Category='商户类目：'+_this.Merchantoptions[i].name;
          }
        }
      };
      if(_this.MerchantName!=''){
        _this.search_MerchantName='商户名称:'+_this.MerchantName;
      }
      if(_this.org_uuid!=''){
        for(var i=0;i<_this.OrgOptions.length;i++){
          if(_this.org_uuid==_this.OrgOptions[i].uuid){
            _this.search_orgUuid='组织架构:'+_this.OrgOptions[i].name;
          }
        }
      };
      if(_this.payment_uuid!=''){
        for(var i=0;i<_this.payment_uuidArray.length;i++){
          if(_this.payment_uuid==_this.payment_uuidArray[i].uuid){
            _this.search_payment='支付方式:'+_this.payment_uuidArray[i].name;
          }
        }
      };
      if(_this.times!=''){
        _this.create_time=_this.get_unix_time(_this.format(_this.times[0], 'yyyy-MM-dd HH:mm:ss'));
        _this.end_time=_this.get_unix_time(_this.format(_this.times[1], 'yyyy-MM-dd HH:mm:ss'));
        _this.search_times='开始时间：'+_this.format(_this.times[0], 'yyyy-MM-dd HH:mm:ss')+' 结束时间:'+_this.format(_this.times[1], 'yyyy-MM-dd HH:mm:ss');
      }
      _this.DetailsStart();
    }
  },
  get_unix_time(dateStr){//转换为时间戳
    let newstr = dateStr.replace(/-/g,'/');
    let date =  new Date(newstr);
    let time_str = date.getTime().toString();
    return time_str.substr(0, 10);
  },
  format(time,format){//转换为日期
    var t = new Date(time);
    var tf = function(i){return (i < 10 ? '0' :'') + i};
    return format.replace(/yyyy|MM|dd|HH|mm|ss/g,function(a){
      switch(a){
        case 'yyyy':
          return tf(t.getFullYear());
          break;
        case 'MM':
          return tf(t.getMonth() + 1);
          break;
        case 'mm':
          return tf(t.getMinutes());
          break;
        case 'dd':
          return tf(t.getDate());
          break;
        case 'HH':
          return tf(t.getHours());
          break;
        case 'ss':
          return tf(t.getSeconds());
          break;
      }
    })
  },
  Merchant_category(){//商户类目
    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
    let BusinesParams={
      access_token:localRoutes.access_token
    };
    backendBusinessGeneralList(BusinesParams).then(data=>{
        if(data.code==0){
        this.Merchantoptions=data.content;
      }else{
        this.$notify.error({
          title: '错误提示',
          message: data.message
        });
      }
    })
  },
  payment_function(){//支付方式
    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
    let BusinesParams={
      access_token:localRoutes.access_token
    };
    backendPayList(BusinesParams).then(data=>{
          if(data.code==0){
          this.payment_uuidArray=data.content;
        }else{
          this.$notify.error({
            title: '错误提示',
            message: data.message
          });
        }
      })
    },
    orgMethod(query){//搜索组织架构
        if (query !== '') {
          this.orgloading = true;
          let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
          let orgMerchantSearch={
            access_token:localRoutes.access_token,
            keyword:query
          };
          backendRolePrivilegeOrg(orgMerchantSearch).then(data=>{
            this.orgloading = false;
          if(data.code==0){
            this.OrgOptions =data.content.list;
            if(this.OrgOptions.length==0){
              this.OrgName='';
            }
          }else{
            this.$notify.error({
              title: '错误提示',
              message: data.message
            });
            if(data.code=='402'||data.code=='9001'){
              sessionStorage.removeItem('user');
              sessionStorage.removeItem('user_router');
              _this.$router.push({ path: '/login' });

            }
          }
        });
      } else {
        this.OrgOptions = [];
        this.OrgName='';
      }
    },
    GetFocus(){
      this.options = [];
      this.MerchantName='';
    },
  }
  };
</script>

<style lang="css">
  .text{
    margin:5px 0;
  }
</style>
