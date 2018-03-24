<template>
  <div class="tab-content">
    <ul class="block">
      <!--<li>-->
        <!--<span class="demonstration">类目</span>-->
        <!--<el-select v-model="general_id" clearable placeholder="请选择商户类目" size="small">-->
          <!--<el-option-->
            <!--v-for="item in Merchantoptions"-->
            <!--:key="item.uuid"-->
            <!--:label="item.name"-->
            <!--:value="item.id">-->
          <!--</el-option>-->
        <!--</el-select>-->
      <!--</li>-->
      <!--<li>-->
        <!--<span class="demonstration">兑换时间</span>-->
        <!--<el-date-picker-->
          <!--v-model="times"-->
          <!--type="datetimerange"-->
          <!--placeholder="选择时间间隔"-->
          <!--range-separator="至"-->
          <!--start-placeholder="开始日期"-->
          <!--end-placeholder="结束日期"-->
          <!--@change="ScreeningDate" size="small">-->
        <!--</el-date-picker>-->
      <!--</li>-->
      <!--<li>-->
        <!--&lt;!&ndash;<el-button type="primary" icon="search" @click="exchangeSearch" size="small"></el-button>&ndash;&gt;-->
        <!--<el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>-->
      <!--</li>-->
      <li style="float: right;margin-right:100px;">
        <el-button :plain="true" type="info" @click="blackPage" size="small">返回</el-button>
      </li>
    </ul>
    <div class="box_table">
      <el-table v-loading="loadListing" :data="DistributionRecordS" border style="font-size:0.8em;">
        <el-table-column prop="id" label="记录编号" width="100">
        </el-table-column>
        <el-table-column prop="tag_order_id" label="对私订单记录编号" width="100">
        </el-table-column>
        <el-table-column prop="split_target" label="分成方" width="120">
        </el-table-column>
        <el-table-column prop="split_account_amount" label="分配金额" width="120">
        </el-table-column>
        <el-table-column prop="split_finance_pano" label="分成方金融平台账号" width="200">
        </el-table-column>
        <el-table-column prop="finance_no" label="金融平台交易流水号" width="200">
        </el-table-column>
        <el-table-column prop="split_result" label="分配结果" width="200">
        </el-table-column>
        <el-table-column prop="create_at" label="创建时间" width="160">
        </el-table-column>
        <el-table-column prop="update_at" label="更新时间" width="160">
        </el-table-column>
        <el-table-column prop="orderno" label="内部记录号" width="200">
        </el-table-column>
        <el-table-column prop="business_uuid" label="商户uuid" width="200">
        </el-table-column>
        <el-table-column prop="out_trade_no" label="商户订单号" width="200">
        </el-table-column>
        <el-table-column label="订单状态" width="160">
          <template slot-scope="scope">
            <el-tag color="#409EFF" size="small" v-if="scope.row.state==1">未处理</el-tag>
            <el-tag color="#21F593" size="small" v-if="scope.row.state==2">请求金融平台成功</el-tag>
            <el-tag color="#20A0FF" size="small" v-if="scope.row.state==3">请求金融平台失败</el-tag>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
    </div>
  </div>
</template>
<script>
  import {privateLog} from './../../api/api';
  export default {
    props: {
    },
    data() {
      return {
        PageDisplay:false,
        DistributionRecordS: [],
        currentPage:1,//显示页数
        page_size:10,//每页数量
        total:1,//总页数
        loadListing:true,
        loading:false,
        fz_url:'',//分账url
        attach:'',
      };
    },
    created: function () {
      this.privateLogStart();
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
//      exchangeSearch(){//搜索
//        if(this.general_id==''&&this.times==''){
//          this.$message('请输入搜索条件');
//        }else{
//          this.privateLogStart();
//        }
//      },
//      ScreeningDate(value){
//        let strs= new Array(); //定义一数组
//        strs=value.split("至"); //字符分割
//        for (let i=0;i<strs.length ;i++ ){
//          this.start_time=strs[0];
//          this.end_time=strs[1]
//        }
//      },
      blackPage(){
        this.$router.go(-1);
      },
      handleSizeChange(val) {
        this.currentPage=val;
        this.privateLogStart();
      },
      handleCurrentChange(val) {
        this.currentPage=val;
        this.privateLogStart();
      },
      privateLogStart(){
        var _this=this;
          let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
          let SplitLogParams = {params:{
            access_token:localRoutes.access_token,
            attach:_this.$route.query.attach
          }};
          privateLog(SplitLogParams).then(data=>{
            console.log(data);
            if(data.code==0){
            this.loadListing=false;
            this.DistributionRecordS=data.content;
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
