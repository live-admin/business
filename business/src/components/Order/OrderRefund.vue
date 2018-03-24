<template>
  <div class="tab-content">
    <ul class="block">
      <li>
        <span class="demonstration">时间</span>
        <el-date-picker v-model="times" type="datetimerange" placeholder="选择时间范围" size="small"></el-date-picker>
      </li>
      <li>
        <span class="demonstration">商户平台订单号</span>
        <el-input
          placeholder="请输入商户平台订单号"
          v-model="colour_sn" style="width:240px;" size="small">
        </el-input>
      </li>
      <li>
        <span class="demonstration">退款订单号</span>
        <el-input
          placeholder="请输入商户订单号"
          v-model="out_refund_no" style="width:240px;" size="small">
        </el-input>
      </li>
      <li>
        <span class="demonstration">订单状态</span>
        <el-select v-model="Order_status" clearable placeholder="请选择订单状态" size="small">
          <el-option v-for="item in Order_refund" :key="item.id" :label="item.label" :value="item.id">
          </el-option>
        </el-select>
      </li>
      <li>
        <el-button type="primary" icon="search" @click="Order_Search" size="small"></el-button>
        <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
      </li>
    </ul>
    <div class="box_table">
      <el-table :data="OrderTableData" v-loading="loadListing" border style="font-size: 0.7em;">
        <el-table-column prop="colour_sn" label="商户平台订单号" width="200">
        </el-table-column>
        <el-table-column prop="out_refund_no" label="退款订单号" width="180">
        </el-table-column>
        <el-table-column prop="refund_fee" label="退款金额" width="120">
        </el-table-column>
        <el-table-column prop="refund_reason" label="退款原因" width="120">
        </el-table-column>
        <el-table-column label="退款状态" width="180">
          <template slot-scope="scope">
            <el-tag type="primary" v-if="scope.row.state==1">退款申请中</el-tag>
            <el-tag type="primary" v-if="scope.row.state==2">退款中</el-tag>
            <el-tag type="primary" v-if="scope.row.state==3">退款成功</el-tag>
            <el-tag type="primary" v-if="scope.row.state==4">退款失败</el-tag>
          </template>
        </el-table-column>
        <!--<el-table-column prop="refund_notify_msg" label="退款通知业务系统返回信息" width="110">-->
        <!--</el-table-column>-->
        <!--<el-table-column prop="refund_wallet_msg" label="退款申请双乾返回信息" width="130">-->
        <!--</el-table-column>-->
        <!--<el-table-column prop="application_id" label="操作应用ID" width="100">-->
        <!--</el-table-column>-->
        <el-table-column prop="operation" label="后台退款操作人" width="130">
        </el-table-column>
        <el-table-column prop="wallet_trade_no" label="双乾返回退款流水号" width="180">
        </el-table-column>
        <el-table-column prop="retund_notify_url" label="退款回调地址" width="180">
        </el-table-column>
        <el-table-column label="退款时间" width="180">
          <template slot-scope="scope">
            <span>{{scope.row.time_create | time}}</span>
            <!--二者选一都可以实现-->
            <!--<span>{{new Date(scope.row.time_pay).toLocaleString()}}</span>-->
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200">
          <template slot-scope="scope">
            <el-button type="primary" @click="Refund_Inquiry(scope.$index, scope.row)" size="mini">退款查询</el-button>
            <!--<el-button type="success" size="mini" @click="NoticeWithdrawal(scope.$index, scope.row)">分账通知</el-button>-->
            <!--<el-button type="info" size="mini" @click="BusinessNotifications(scope.$index, scope.row)">业务通知</el-button>-->
            <el-button type="warning" v-show="scope.row.state==1" size="mini" @click="Refund_audit(scope.$index, scope.row)">退款审核</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
    </div>
    <!--下边-->
    <el-col :span="24" style="height:50px;text-align:center;margin-top: 10px;">
      <p style="font-size:0.8em;">双乾网络支付提供互联网支付服务</p>
    </el-col>
    <!--退款审核-->
    <el-dialog  title="退款审核" v-model="Refund_audit_start" :close-on-click-modal="false" width="50%">
      <el-form :model="Refund_audit_ruleForm" :rules="Refund_audit_rules" ref="Refund_audit_ruleForm" label-width="100px" class="demo-ruleForm">
        <el-form-item label="退款单号:">
          <div>{{Refund_audit_ruleForm.out_refund_no}}</div>
        </el-form-item>
        <el-form-item label="审核状态" prop="state">
          <el-radio-group v-model="Refund_audit_ruleForm.state">
            <el-radio label="3">审核通过</el-radio>
            <el-radio label="4">审核不通过</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="Refund_audit_submitForm('Refund_audit_ruleForm')">立即创建</el-button>
          <el-button @click="Refund_audit_resetForm('Refund_audit_ruleForm')">重置</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
  import {backendRefundListd,adminQuery,RefundAuditing} from '../../api/api';
  export default {
      data() {
        return {
          times:'',//时间间隔
          colour_sn:'',
          out_refund_no:'',//退款订单号码
          create_time:'',
          end_time:'',
          Order_refund:[{
            id:1,
            label:"退款中"
          },{
            id:2,
            label:"退款成功"
          },{
            id:3,
            label:"退款失败"
          }],
          Order_status:'',//订单状态
          OrderTableData: [],//订单列表
          currentPage:1,//显示页数
          page_size:10,//每页数量
          total:1,//总页数
          PageDisplay:false,
          loadListing:false,
          //退款审核
          Refund_audit_start:false,
          Refund_audit_ruleForm:{
            out_refund_no : '',
            state:'',//状态
          },
          Refund_audit_rules: {
            state: [
              { required: true, message: '请选择审核状态', trigger: 'change' }
            ]
          }
        };
      },
      created: function () {
        this.OrderRefundStart();
        let url=document.domain;
        if(url=="check.account.czytest.colourlife.com"){
          this.sh_url='http://check.account.czytest.colourlife.com';
        }else if(url=="check.account.colourlife.com"){
          this.sh_url='http://check.account.colourlife.com';
        }else if(url=="localhost"){
          this.sh_url='http://check.account.czytest.colourlife.com';
        }
      },
      watch:{
      },
      methods: {
        emptyFun(){
          window.location.reload();
        },
        handleSizeChange(val){
          this.currentPage=val;
          this.OrderRefundStart();
        },
        handleCurrentChange(val){
          this.currentPage=val;
          this.OrderRefundStart();
        },
        Order_Search(){//搜索
          let datdString=this.times[0];
          this.format(this.times[0], 'yyyy-MM-dd HH:mm:ss');
          if(this.colour_sn==''&&this.out_refund_no==''&&this.times==''&&this.Order_status==''){
            this.$notify({
              title: '警告',
              message: '请输入查询信息',
              type: 'warning'
            });
          }else{
            if(this.times!=''){
              this.create_time=this.get_unix_time(this.format(this.times[0], 'yyyy-MM-dd HH:mm:ss'));
              this.end_time=this.get_unix_time(this.format(this.times[1], 'yyyy-MM-dd HH:mm:ss'));
              this.OrderRefundStart();
            }else{
              this.OrderRefundStart();
            }
      //                    this.CzyID='';
      //                    this.Mobile="";
      //                    this.MerchantName='';
      //                    this.Order_status='';
      //                    this.times='';
          }
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
        get_unix_time(dateStr){
          let newstr = dateStr.replace(/-/g,'/');
          let date =  new Date(newstr);
          let time_str = date.getTime().toString();
          return time_str.substr(0, 10);
        },
        OrderRefundStart(){
            let _this=this;
            let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
            let OrderListParams ={
              access_token:localRoutes.access_token,
              colour_sn:this.colour_sn,
              out_refund_no:this.out_refund_no,//退款订单号码
              state:this.Order_status,//退款状态
              create_time:this.create_time,
              end_time:this.end_time,
              page:this.currentPage,
              page_size:this.page_size,
            };
          backendRefundListd(OrderListParams).then(data => {
              if(data.code==0){
              this.loadListing=false;
              this.OrderTableData=data.content.data;
              this.total=data.content.total;
              if(data.content.total>10){
                this.PageDisplay=true;
              }else{
                this.PageDisplay=false;
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
        },
        Refund_Inquiry(index,row){
    console.log(row);
              let _this=this;
              let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
              let queryParams ={
                access_token:localRoutes.access_token,
                out_refund_no:row.out_refund_no,
              };
              adminQuery(queryParams).then(data => {
                if(data.code==0){
                    _this.$message({
                      message:data.message,
                      type: 'success'
                    });
                }else{
                    _this.$message({
                      message:data.message,
                      type: 'warning'
                    });
                  if(data.code=='402'||data.code=='9001'){
                    sessionStorage.removeItem('user');
                    sessionStorage.removeItem('user_router');
                    _this.$router.push({ path: '/login' });
                  }
                }
            });
        },
        Refund_audit(index,row){//退款审核
          var _this=this;
          _this.Refund_audit_start=true;
            console.log(row);
          _this.Refund_audit_ruleForm.out_refund_no=row.out_refund_no;
        },
        Refund_audit_submitForm(formName) {
          var _this=this;
          this.$refs[formName].validate((valid) => {
            if (valid) {
                  var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                  var queryParams ={
                    access_token:localRoutes.access_token,
                    out_refund_no:_this.Refund_audit_ruleForm.out_refund_no,
                    state:_this.Refund_audit_ruleForm.state
                  };
                  RefundAuditing(queryParams).then(data => {
                    if(data.code==0){
                    _this.$message({
                      message:data.message,
                      type: 'success'
                    });
                  _this.Refund_audit_start=false;
                  }else{
                    _this.$message({
                      message:data.message,
                      type: 'warning'
                    });
//                    if(data.code=='402'||data.code=='9001'){
//                      sessionStorage.removeItem('user');
//                      sessionStorage.removeItem('user_router');
//                      _this.$router.push({ path: '/login' });
//                    }
                  }
                });
          } else {
            console.log('error submit!!');
            return false;
          }
        });
        },
        Refund_audit_resetForm(formName) {
          this.$refs[formName].resetFields();
        }
      }
  }
</script>

<style lang="css">
  .tab-content{
    font-size:0.9em;
  }
  /*居中*/
  .cell{
    text-align: center;
  }
  li{
    list-style-type:none;
  }
  .block{
    height:auto;
    /*overflow: hidden;*/
  }
  .block li{
    float: left;
    margin:5px 5px;
  }
  .block input{
    /*width:80%;*/
  }
  .box_table{
    text-align: center;
  }
  .OneOrder li{
    height:30px;
  }
  .Store_information li{
    height:30px;
  }
</style>
