<template>
  <div class="tab-content">
    <ul class="block">
      <li>
        <span class="demonstration">商户类目</span>
        <el-select
          v-model="general_uuid"
          :multiple="false"
          filterable
          remote
          reserve-keyword
          placeholder="请输入商户类目关键词"
          :remote-method="remoteMethod"
          :focus="GetFocus"
          clearable
          :loading="loading"
          size="small">
          <el-option
            v-for="item in options"
            :key="item.general_uuid"
            :label="item.general_name"
            :value="item.general_uuid">
          </el-option>
        </el-select>
      </li>
      <li>
        <span class="demonstration">兑换单号</span>
        <el-input
          placeholder="请输入兑换单号"
          v-model="finance_no" style="width:200px;" size="small">
        </el-input>
      </li>
      <li>
        <span class="demonstration">兑换时间</span>
        <el-date-picker
          v-model="times"
          type="datetimerange"
          placeholder="选择时间间隔"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
          @change="ScreeningDate" size="small">
        </el-date-picker>
      </li>
      <li>
        <el-button type="primary" icon="search" @click="exchangeSearch" size="small"></el-button>
        <el-button type="primary" icon="el-icon-download" @click="exchangeExport" size="small">导出兑换记录</el-button>
        <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
      </li>
      <!--<li style="float: right;margin-right:100px;">-->
        <!--<el-button :plain="true" type="info" @click="blackPage" size="small">返回</el-button>-->
      <!--</li>-->
    </ul>
    <div class="box_table">
      <el-table v-loading="loadListing" :data="DistributionRecordS" border style="font-size:0.8em;">
        <el-table-column prop="id" label="序号" width="95">
        </el-table-column>
        <el-table-column prop="general_name" label="商户类目" width="105">
        </el-table-column>
        <el-table-column prop="finance_no" label="兑换单号" width="200">
        </el-table-column>
        <el-table-column label="分成方类型" width="100">
          <template slot-scope="scope">
              <el-tag color="#409EFF" size="small" v-if="scope.row.split_type==1">个人</el-tag>
              <el-tag color="#21F593" size="small" v-if="scope.row.split_type==2">岗位</el-tag>
              <el-tag color="#20A0FF" size="small" v-if="scope.row.split_type==3">组织架构</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="split_target" label="分成方账号" width="140">
        </el-table-column>
        <el-table-column prop="amount" label="兑换金额（元）" width="100">
        </el-table-column>
        <el-table-column label="兑换类型" width="100">
          <template slot-scope="scope">
              <el-tag v-if="scope.row.type==1" type="info">彩集饭票</el-tag>
              <el-tag v-if="scope.row.type==2" type="success">现金</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="create_at" label="兑换时间" width="180">
        </el-table-column>
        <el-table-column prop="result" label="兑换结果" width="200">
        </el-table-column>
        <el-table-column label="订单状态" width="240">
          <template slot-scope="scope">
              <el-tag v-if="scope.row.state==2" size="small" color="#409EFF">兑换成功</el-tag>
              <el-tag type="info" v-if="scope.row.state==1" size="small">未处理</el-tag>
              <el-tag v-if="scope.row.state==3" size="small" color="#F56C6C">兑换失败</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button @click="Remarks_Click(scope.$index, scope.row)" size="mini" type="primary">审核</el-button>
            <el-button @click="details_Click(scope.$index, scope.row)" size="mini" type="success">详情</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-alert
        title="备注：若没有筛选条件，默认导出全部兑换记录。"
        type="info"
        :closable="false">
      </el-alert>
    </div>
    <!--兑换详情-->
    <el-dialog
      title="兑换详情"
      :visible.sync="DetailedAccount"
      width="50%"
      :close-on-click-modal="false">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>商户类目：{{detailsList.general_name}}</span>
              <!--<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>-->
            </div>
            <div style="margin:5px 0;">
              序号：{{detailsList.id}}
            </div>
            <div style="margin:5px 0;">
              兑换单号：{{detailsList.finance_no}}
            </div>
            <div style="margin:5px 0;">
              分成方类型：
              <el-tag color="#409EFF" size="small" v-if="detailsList.split_type==1">个人</el-tag>
              <el-tag color="#21F593" size="small" v-if="detailsList.split_type==2">岗位</el-tag>
              <el-tag color="#20A0FF" size="small" v-if="detailsList.split_type==3">组织架构</el-tag>
            </div>
            <div style="margin:5px 0;">
              分成方账号：{{detailsList.split_target}}
            </div>
            <div style="margin:5px 0;">
              兑换金额：{{detailsList.amount}}
            </div>
            <div style="margin:5px 0;">
              兑换类型：
              <el-tag v-if="detailsList.type==1" type="info">彩集饭票</el-tag>
              <el-tag v-if="detailsList.type==2" type="success">现金</el-tag>
            </div>
            <div style="margin:5px 0;">
              商户类目uuid：{{detailsList.general_uuid}}
            </div>
            <div style="margin:5px 0;">
              提现的货币类型：{{detailsList.finance_pano}}
            </div>
            <div style="margin:5px 0;">
              提现的货币类型编号：{{detailsList.finance_atid}}
            </div>
            <div style="margin:5px 0;">
              提现的金融货币账号：{{detailsList.finance_cano}}
            </div>
            <div style="margin:5px 0;">
              创单时间：{{detailsList.create_at}}
            </div>
            <div style="margin:5px 0;">
              更新时间：{{detailsList.update_at}}
            </div>
            <div style="margin:5px 0;">
              订单状态：
              <el-tag v-if="detailsList.state==2" size="small" color="#409EFF">提现成功</el-tag>
              <el-tag type="info" v-if="detailsList.state==1" size="small">未处理</el-tag>
              <el-tag v-if="detailsList.state==3" size="small" color="#F56C6C">提现失败</el-tag>
            </div>
            <div style="margin:5px 0;">
              兑换结果：{{detailsList.result}}
            </div>
            <div style="margin:5px 0;">
              转入的货币类型：{{detailsList.arrival_pano}}
            </div>
            <div style="margin:5px 0;">
              转入的货币类型编号：{{detailsList.arrival_atid}}
            </div>
            <div style="margin:5px 0;">
              转入的金融货币账号：{{detailsList.arrival_cano}}
            </div>
            <div style="margin:5px 0;">
              转入的货币账号：{{detailsList.arrival_account}}
            </div>
            <div style="margin:5px 0;">
              订单号（内部记录）：{{detailsList.orderno}}
            </div>
          </el-card>
    </el-dialog>
    <!--兑换备注-->
    <el-dialog
      title="备注"
      :visible.sync="Remarks"
      width="50%"
      :close-on-click-modal="false">
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
          <el-form-item label="审核状态" prop="state">
            <el-radio-group v-model="ruleForm.state">
              <el-radio label="2">审核通过</el-radio>
              <el-radio label="3">审核不通过</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="失败原因">
            <el-input type="textarea" v-model="ruleForm.reason"></el-input>
          </el-form-item>
          <el-form-item label="备注">
            <el-input type="textarea" v-model="ruleForm.remark"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('ruleForm')">审核完成</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
          </el-form-item>
        </el-form>
    </el-dialog>
    <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
    </div>
  </div>
</template>
<script>
  import {backendWithdrawalsList,backendHandlewithdrawals,backendAppdetail,backendWithdrawals,backendWithdrawalsRemark} from './../../api/api';
  export default {
    props: {
    },
    data() {
      return {
        state:'',//状态
        split_target:'',//分成方账号
        split_type:'',//分成方类型
        times:'',
        start_time:'',//搜索开始时间
        end_time:'',//搜索结束时间
        general_uuid:'',//商户类目
        PageDisplay:false,
        finance_no: '',//兑换单号
        options: [],
        DistributionRecordS: [],
        //新增
        DetailedAccount:false,//分账详情
        Remarks:false,//备注
        currentPage:1,//显示页数
        page_size:10,//每页数量
        total:1,//总页数
        loadListing:true,
        loadDetailsing:true,
        loading:false,
        detailsList:'',//详情
        Memo_editorId:'',
        ruleForm: {//备注认证
          state: '',
          reason: '',
          remark:''
        },
        rules: {
          state: [
            { required: true, message: '请选择审核状态', trigger: 'change' }
          ],
        },
        fz_url:'',//分账url
      };
    },
    created: function () {
      this.exchangeStart();
      let url=document.domain;
      if(url=="check.account.czytest.colourlife.com"){
        this.fz_url="http://fzsvr-czytest.colourlife.com";
      }else if(url=="check.account.colourlife.com"){
        this.fz_url='http://account.allot.colourlife.com';
      }else if(url=="localhost"){
        this.fz_url="http://fzsvr-czytest.colourlife.com";
      }
    },
    methods: {
      emptyFun(){
        window.location.reload();
      },
      exchangeExport(){//导出
        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        window.location.href=this.fz_url+'/backend/withdrawals/export?access_token='+localRoutes.access_token+'&general_uuid='+this.general_uuid+'&finance_no='+this.finance_no+'&split_type='+this.split_type+'&split_target='+this.split_target+'&state='+this.state+'&start_time='+this.start_time+'&end_time='+this.end_time+'&page='+this.currentPage+'&page_size='+this.page_size;
      },
      exchangeSearch(){//搜索
        if(this.finance_no==''&&this.times==''&&this.general_uuid==''){
          this.$message('请输入搜索条件');
        }else{
            this.exchangeStart();
        }
      },
      ScreeningDate(value){
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
          this.start_time=strs[0];
          this.end_time=strs[1]
        }
      },
      Remarks_Click(index,row){//备注
        this.Remarks=true;
        this.Memo_editorId=row.id;
        this.ruleForm.state='';
        this.ruleForm.reason='';
        this.ruleForm.remark='';
      },
      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
              this.To_examine();
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },
      To_examine(){
        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let examineParams = {
          access_token:localRoutes.access_token,
          withdrawals_id:this.Memo_editorId,
          state:this.ruleForm.state,
          reason:this.ruleForm.reason
        };
        backendHandlewithdrawals(examineParams).then(data=>{
          if(data.code==0){
            //判断是否有备注
            if(this.ruleForm.remark!=''){
                let examineParams = {
                  access_token:localRoutes.access_token,
                  withdrawals_id:this.Memo_editorId,
                  remark:this.ruleForm.remark
                };
                backendHandlewithdrawals(examineParams).then(data=>{
                  if(data.code==0){
                    this.$notify({
                      title: '成功',
                      message: '审核备注添加成功',
                      type: 'success'
                    });
                    this.Remarks=false;
                    this.ruleForm.state='';
                    this.ruleForm.reason='';
                    this.ruleForm.remark='';
                  }else{
                    this.$notify.error({
                      title: '错误提示',
                      message: data.message
                    });
                    this.resetForm('ruleForm');
                    if(data.code=='402'){
                      sessionStorage.removeItem('user');
                      sessionStorage.removeItem('user_router');
                      this.$router.push({ path: '/login' });

                    }
                  }
                });
            }else{
              this.$notify({
                title: '成功',
                message: '审核成功',
                type: 'success'
              });
              this.Remarks=false;
              this.ruleForm.state='';
              this.ruleForm.reason='';
              this.ruleForm.remark='';
            }
          }else{
            this.$notify.error({
              title: '错误提示',
              message: data.message
            });
//            this.resetForm('ruleForm');
            if(data.code=='402'){
              sessionStorage.removeItem('user');
              sessionStorage.removeItem('user_router');
              this.$router.push({ path: '/login' });

            }
          }
        });
      },
      resetForm(formName) {
//        this.$refs[formName].resetFields();
        this.ruleForm.state='';
        this.ruleForm.reason='';
        this.ruleForm.remark='';
      },
      details_Click(index,row) {//详情
        this.DetailedAccount=true;
        this.detailsList=row;
//        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
//        let DetailsParams = {params:{
//          access_token:localRoutes.access_token,
//          withdrawals_id:row.id
//        }};
//        backendWithdrawals(DetailsParams).then(data=>{
//          if(data.code==0){
//            this.detailsList=data.content.result;
//          }else{
//            this.$notify.error({
//              title: '错误提示',
//              message: data.message
//            });
//            if(data.code=='402'){
//              sessionStorage.removeItem('user');
//              sessionStorage.removeItem('user_router');
//              this.$router.push({ path: '/login' });
//
//            }
//          }
//        });
      },
      blackPage(){
        this.$router.go(-1);
      },
      remoteMethod(query) {//搜素商户类目
        if (query !== '') {
          this.loading = true;
          let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
          let MerchantSearch={params:{
            access_token:localRoutes.access_token,
            pano:'',
            general_name:query
          }};
          backendAppdetail(MerchantSearch).then(data=>{
            this.loading = false;
            if(data.code==0){
              this.options =data.content.result;
              if(this.options.length==0){
                this.general_uuid='';
              }
            }else{
              this.$notify.error({
                title: '错误提示',
                message: data.message
              });
            }
          });
        } else {
          this.options = [];
          this.general_uuid='';
        }
      },
      GetFocus(){//获取焦点清空
        this.options = [];
        this.general_uuid='';
      },
      handleSizeChange(val) {
        this.currentPage=val;
        this.exchangeStart();
      },
      handleCurrentChange(val) {
        this.currentPage=val;
        this.exchangeStart();
      },
      exchangeStart(){
        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let SplitLogParams = {params:{
            access_token:localRoutes.access_token,
            general_uuid:this.general_uuid,
            finance_no:this.finance_no,
            split_type:this.split_type,//分账方类型
            split_target:this.split_target,//分成方账号
            state:this.state,//状态
            start_time:this.start_time,
            end_time:this.end_time,
            page:this.currentPage,
            page_size:this.page_size
        }};
        backendWithdrawalsList(SplitLogParams).then(data=>{
          if(data.code==0){
            this.loadListing=false;
            this.DistributionRecordS=data.content.result.data;
            this.total=data.content.result.total;
            if(data.content.result.total>10){
              this.PageDisplay=true;
            }else{
              this.PageDisplay=false;
            }
          }else{
            this.$notify.error({
              title: '错误提示',
              message: data.message
            });
            if(data.code=='402'){
              sessionStorage.removeItem('user');
              sessionStorage.removeItem('user_router');
              this.$router.push({ path: '/login' });

            }
          }
        });
      },
    }
  };
</script>
<style lang="css">
  /*居中*/
  .cell{
    text-align: center;
  }
  a{text-decoration:none}
  li{
    list-style-type:none;
  }
  .block{
    height:auto;
  }
  .block li{
    float: left;
    margin:5px 10px;
  }
  .block input{
    /*width:80%;*/
  }
  .box_table{
    text-align: center;
  }
</style>
