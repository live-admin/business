<template>
  <div>
    <div class="top_max_box">
       <div class="top_one_box">
         <ul>
           <li class='li_user'>
             账户信息：<span>{{user_inform.name}}</span>
           </li>
           <li class="li_meal">
             <div class="tx_meal_one">饭票余额：<span>{{user_inform.withdrawsMeal}}</span></div>
             <div class="tx_meal_two">
               <el-button type="primary" size="mini" @click="Withdrawals_all('1')" round>饭票提现</el-button>
             </div>
           </li>
           <li class="li_cash">
             <div class="tx_cash_one">现金余额：<span>{{user_inform.withdrawsCash}}</span></div>
             <div class="tx_cash_two">
               <el-button type="primary" size="mini" @click="Withdrawals_all('2')" round>现金提现</el-button>
             </div>
           </li>
         </ul>
       </div>
      <div class="top_two_box">
        <ul>
          <li class="title_top">
            <div>交易时间</div>
            <div>交易金额</div>
            <div style="border:0"></div>
          </li>
          <li class="title_bottom">
            <div class="list_one">{{one.time}}</div>
            <div class="list_one" style="border-right:1px solid #e7eaec">{{one.money}}</div>
            <div class="list_one" style="border:0"></div>
          </li>
          <li class="title_bottom">
            <div class="list_one">{{two.time}}</div>
            <div class="list_one" style="border-right:1px solid #e7eaec" >{{two.money}}</div>
            <div class="list_one" style="border:0" >
              <el-select v-model="type" style="width:58%" placeholder="请选择要导出账单日期" size="medium">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>


              <el-button type="primary" @click="Export_bill" round>导出账单</el-button>
            </div>
          </li>
          <li class="title_bottom">
            <div class="list_one" style="border:0">{{three.time}}</div>
            <div class="list_one" style="border-right:1px solid #e7eaec">{{three.money}}</div>
            <div class="list_one" style="border:0">

            </div>
          </li>
        </ul>
      </div>
      <!--<div class="top_min_box">-->
        <!--前天交易额:{{yesterdayBeforeMoney}}-->
      <!--</div>-->
    </div>
    <div class="bottom_max_box">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>最近余额流水信息</span>
        </div>
        <el-table v-loading="loadListing" :data="Balance_water" border style="font-size:0.8em;">
          <!--<el-table-column prop="payment_no" label="提现订单号">-->
          <!--</el-table-column>-->
          <el-table-column prop="crate_at" label="提现时间">
          </el-table-column>
          <el-table-column prop="amount" label="提现现金金额">
          </el-table-column>
          <el-table-column prop="fanpiao_amount" label="提现饭票金额">
          </el-table-column>
        </el-table>
      </el-card>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>账单流水信息</span>
          <!--<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>-->
        </div>
        <el-table v-loading="loadListing" :data="Bill_flow" border style="font-size:0.8em;">
          <!--<el-table-column prop="colour_sn" label="彩之云订单号">-->
          <!--</el-table-column>-->
          <el-table-column prop="time_pay" label="支付时间">
          </el-table-column>
          <el-table-column prop="real_total_fee" label="订单金额">
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button size="small" type="success" @click="View(scope.$index, scope.row)">查看</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-card>
    </div>
    <!--查看详情-->
    <el-dialog title="账单流水详情" v-model="Bill_flow_off" :close-on-click-modal="false" width="50%">
      <el-card class="box-card">
        <!--<div slot="header" class="clearfix">-->
          <!--<span>卡片名称</span>-->
          <!--<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>-->
        <!--</div>-->
        <div class="text item">商户名称：{{Order_details.business_name}} </div>
        <div class="text item">订单编号：{{Order_details.colour_sn}} </div>
        <div class="text item">订单金额：{{Order_details.total_fee}} </div>
        <div class="text item">支付方式：{{Order_details.payment_uuid}} </div>
        <div class="text item">商户手机号码：{{Order_details.mobile}} </div>
        <div class="text item">创单时间：{{Order_details.time_create}} </div>
        <div class="text item">支付时间：{{Order_details.time_pay}} </div>
      </el-card>
    </el-dialog>
  </div>
</template>
<script>
  import {TransactionBill,TransactionBalance,AdminOrderView,backendWithdrawsMeal,backendWithdrawsCash} from './../../api/api';
  export default {
    data(){
      return {
        loadListing:true,
        one:'',
        two:'',
        three:'',
        Bill_flow:[],
        Balance_water:[],
        Bill_flow_off:false,
        Order_details:'',
        user_inform:'',
        options: [{
          value: '1',
          label: '1天'
        }, {
          value: '2',
          label: '2天'
        }, {
          value: '3',
          label: '3天'
        }, {
          value: '4',
          label: '4天'
        }, {
          value: '5',
          label: '5天'
        }],
        type: '',
        fanpiao_amount:'',
        amount:'',
        business_uuid:'',
      }
    },
    created:function(){
       this.bill();
       this.Tbalance();
    },
    methods:{
        bill(){
              let localRoutes = JSON.parse(window.sessionStorage.getItem('user'));
              let Params = {
                access_token: localRoutes.access_token
              };
              TransactionBill(Params).then(data=>{
                console.log(data);
                if(data.code == 0){
                    this.user_inform=data.content.employee;
                    this.one=data.content.oneDay;
                    this.three=data.content.threeDay;
                    this.two=data.content.twoDay;
                }else{
                  this.$notify.error({
                    title: '错误提示',
                    message: data.message
                  });
                }
              })
        },
        Tbalance(){
              let localRoutes = JSON.parse(window.sessionStorage.getItem('user'));
              let Params = {
                access_token: localRoutes.access_token
              };
              TransactionBalance(Params).then(data=>{
                this.loadListing=false;
                console.log(data);
                  if(data.code == 0){
                  this.Bill_flow = data.content.order;
                  this.Balance_water = data.content.withdraws;
                }else{
                  this.$notify.error({
                    title: '错误提示',
                    message: data.message
                  });
                }
              })
        },
        View(index,row){
              var _this=this;
              console.log(row);
                let localRoutes = JSON.parse(window.sessionStorage.getItem('user'));
                let Params = {
                  access_token: localRoutes.access_token,
                  colour_sn:row.colour_sn
                };
                AdminOrderView(Params).then(data=>{
                  _this.Bill_flow_off=true;
                console.log(data);
                if(data.code == 0){
                  _this.Order_details=data.content;
                }else{
                  this.$notify.error({
                    title: '错误提示',
                    message: data.message
                  });
                }
              })
        },
        Export_bill(){//导出账单
              let sh_url;
              let url=document.domain;
              if(url=="check.account.czytest.colourlife.com"){
                sh_url='http://check.account.czytest.colourlife.com';
              }else if(url=="check.account.colourlife.com"){
                sh_url ='http://check.account.colourlife.com';
              }else if(url=="localhost"){
                sh_url='http://check.account.czytest.colourlife.com';
              }
              let _this=this;
              let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
              window.location.href=sh_url+'/backend/order/excel?access_token='+localRoutes.access_token+'&type='+_this.type;
        },
        Withdrawals_all(value){//饭票提现
              var _this=this;
              let localRoutes = JSON.parse(window.sessionStorage.getItem('user'));
              let Params = {
                access_token: localRoutes.access_token,
              };
              if(value==1){
                backendWithdrawsMeal(Params).then(data=>{
                  console.log(data);
                if(data.code == 0){
                  this.$notify({
                    title: '成功',
                    message: '现金提现申请提交成功，请耐心等待！',
                    type: 'success'
                  });
                }else{
                  this.$notify.error({
                    title: '错误提示',
                    message: data.message
                  });
                }
              })
              }else if(value==2){
                backendWithdrawsCash(Params).then(data=>{
                  if(data.code == 0){
                    this.$notify({
                      title: '成功',
                      message: '现金提现申请提交成功，请耐心等待！',
                      type: 'success'
                    });
                  }else{
                    this.$notify.error({
                      title: '错误提示',
                      message: data.message
                    });
                  }
                })
              }

    console.log(Params);

        },
      }
    };
</script>

<style lang="css">
  .top_max_box{
    width:100%;
    height:200px;
    margin:20px 0;
  }
  .top_one_box{
    width:30%;
    height:100%;
    float: left;
    border:2px solid #E7EAEC;
    border-radius:20px;
    /*line-height:200px;*/
    /*text-align: center;*/
  }
  .top_one_box ul{
    width:100%;
    height:100%;
  }
  .li_user{
    width:100%;
    height:50px;
    text-align: center;
    line-height:50px;
    border-bottom:1px solid #e7eaec;
  }
  .li_meal{
    width:100%;
    height:60px;
    padding-top:15px;
    line-height:60px;
  }
  .li_meal .tx_meal_one{
    width:70%;
    height:100%;
    float: left;
    text-align: center;
  }
  .li_meal .tx_meal_two{
    width:30%;
    height:100%;
    float: right;
  }
  .li_cash{
    width:100%;
    height:60px;
    padding-bottom:15px;
    line-height:60px;
  }

  .li_cash .tx_cash_one{
    width:70%;
    height:100%;
    float: left;
    text-align: center;
  }
  .li_cash .tx_cash_two{
    width:30%;
    height:100%;
    float: right;
  }
  .top_two_box{
    width:67%;
    height:100%;
    margin-left:2%;
    float: left;
    border:2px solid #E7EAEC;
    border-radius:20px;
    line-height:200px;
    text-align: center;
  }
  .top_two_box ul{
    width:100%;
    height: 100%;
  }
  .title_top{
    width:100%;
    height:50px;
    border-bottom:1px solid #e7eaec;
  }
  .title_top div{
    width:33%;
    height:100%;
    text-align: center;
    line-height:50px;
    float: left;
    /*border-right:1px solid #e7eaec;*/
  }
  .title_bottom{
    width:100%;
    height:50px;
    /*border-bottom:1px solid #e7eaec;*/
  }
  .title_bottom .list_one{
    width:33%;
    height: 48px;;
    text-align: center;
    line-height:48px;
    float: left;
    border-bottom:1px solid #e7eaec;
  }
  .bottom_max_box{
    width:100%;
    height:auto;
  }
  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .box-card {
    width:100%;
  }
</style>
