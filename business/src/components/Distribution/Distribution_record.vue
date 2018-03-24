<template>
  <div class="tab-content">
    <ul class="block">
      <li>
        <!--<span class="demonstration">商户类目</span>-->
        <el-select v-model="general_id" clearable placeholder="请选择商户类目" size="small">
          <el-option
            v-for="item in Merchantoptions"
            :key="item.uuid"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </li>
      <li>
        <!--<span class="demonstration">商户订单号</span>-->
        <el-input
          placeholder="请输入商户订单号"
          v-model="attach" style="width:200px;" size="small">
        </el-input>
      </li>
      <li>
        <!--<span class="demonstration">兑换时间</span>-->
        <el-date-picker
          v-model="times"
          type="datetimerange"
          placeholder="选择记录时间间隔"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
          @change="ScreeningDate" size="small">
        </el-date-picker>
      </li>
      <li>
        <!--<span class="demonstration">订单状态</span>-->
        <el-select v-model="state" clearable placeholder="请选择订单状态" size="small">
          <el-option
            v-for="item in Order_status"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </li>
      <li>
        <el-button type="primary" icon="search" @click="exchangeSearch" size="small"></el-button>
        <el-button type="primary" icon="el-icon-download" @click="recordExport" size="small">导出分配记录</el-button>
        <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
      </li>
      <!--<li style="float: right;margin-right:100px;">-->
        <!--<el-button :plain="true" type="info" @click="blackPage" size="small">返回</el-button>-->
      <!--</li>-->
    </ul>
    <div class="box_table">
      <el-table v-loading="loadListing" :data="DistributionRecordS" border style="font-size:0.8em;">
        <el-table-column prop="id" label="记录编号" width="120">
        </el-table-column>
        <el-table-column prop="attach" label="商户订单号">
        </el-table-column>
        <el-table-column prop="create_at" label="创建时间">
        </el-table-column>
        <el-table-column prop="create_at" label="更新时间">
        </el-table-column>
        <el-table-column label="订单状态">
          <template slot-scope="scope">
            <el-popover trigger="hover" placement="top">
              <p>共8种状态</p>
              <p>1.未处理</p>
              <p>2.部分已处理分账到具体用户</p>
              <p>3.全部已处理分账到具体账户</p>
              <p>4.全部都处理失败了</p>
              <p>5.回滚未处理</p>
              <p>6.部分回滚</p>
              <p>7.全部回滚</p>
              <p>8.回滚全部失败了</p>
              <div slot="reference" class="name-wrapper">
                <el-tag color="#409EFF" size="small" v-if="scope.row.state==1">未处理</el-tag>
                <el-tag color="#21F593" size="small" v-if="scope.row.state==2">部分已处理分账到具体用户</el-tag>
                <el-tag color="#20A0FF" size="small" v-if="scope.row.state==3">全部已处理分账到具体账户</el-tag>
                <el-tag color="#20A0FF" size="small" v-if="scope.row.state==4">全部都处理失败了</el-tag>
                <el-tag color="#20A0FF" size="small" v-if="scope.row.state==5">回滚未处理</el-tag>
                <el-tag color="#20A0FF" size="small" v-if="scope.row.state==6">部分回滚</el-tag>
                <el-tag color="#20A0FF" size="small" v-if="scope.row.state==7">全部回滚</el-tag>
                <el-tag color="#20A0FF" size="small" v-if="scope.row.state==8">回滚全部失败了</el-tag>
              </div>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="150">
          <template slot-scope="scope">
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
    <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
    </div>
  </div>
</template>
<script>
  import {privateList,privateLog,backendBusinessGeneralList} from './../../api/api';
  export default {
    props: {
    },
    data() {
      return {
        state:'',//状态
        attach:'',//商户订单号
        business_uuid:'',//商户uuid
        general_id:'',//商户类目ID
        times:'',
        start_time:'',//搜索开始时间
        end_time:'',//搜索结束时间
        PageDisplay:false,
        DistributionRecordS: [],
        currentPage:1,//显示页数
        page_size:10,//每页数量
        total:1,//总页数
        loadListing:true,
        loading:false,
        fz_url:'',//分账url
        Merchantoptions:[],
        Order_status:[
          {
           name:'未处理',
           id:1
          },
          {
            name:'部分已处理分账到具体用户',
            id:2
          },
          {
            name:'全部已处理分账到具体账户',
            id:3
          },
          {
            name:'全部都处理失败了',
            id:4
          },
          {
            name:'回滚未处理',
            id:5
          },
          {
            name:'部分回滚',
            id:6
          },
          {
            name:'全部回滚',
            id:7
          },
          {
            name:'回滚全部失败了',
            id:8
          }
        ],
      };
    },
    created: function () {
      this.privateStart();
      this.Merchant_category();
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
      Merchant_category(){//获取类目
          var _this=this;
          let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
          let BusinesParams={
            access_token:localRoutes.access_token
          };
          backendBusinessGeneralList(BusinesParams).then(data=>{
          if(data.code==0){
            _this.Merchantoptions=data.content;
          }else{
            _this.$notify.error({
                title: '错误提示',
                message: data.message
              });
          }
        })
      },
      exchangeSearch(){//搜索
        var _this=this;
        if(_this.general_id==''&&_this.times==''&&_this.state==''&&_this.attach==''){
          _this.$message('请输入搜索条件');
        }else{
          _this.privateStart();
        }
      },
      ScreeningDate(value){
        var _this=this;
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
          _this.start_time=strs[0];
          _this.end_time=strs[1]
        }
      },
      blackPage(){
        this.$router.go(-1);
      },
      details_Click(index,row){
          this.$router.push({path: '/record_log', query:{attach:row.attach}});
//    console.log(row)
      },
      handleSizeChange(val) {
        this.currentPage=val;
        this.privateStart();
      },
      handleCurrentChange(val) {
        this.currentPage=val;
        this.privateStart();
      },
      recordExport(){
        var _this=this;
        let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        window.location.href=_this.fz_url+'/backend/private/export?access_token='+localRoutes.access_token+'&state='+_this.state+'&attach='+_this.attach+'&start_time='+_this.start_time+'&end_time='+_this.end_time+'&business_uuid='+_this.business_uuid+'&general_id='+_this.general_id;
      },
      privateStart(){
          let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
          let SplitLogParams = {params:{
            access_token:localRoutes.access_token,
            state:this.state,//状态
            attach:this.attach,//商户订单号
            business_uuid:this.business_uuid,//商户uuid
            general_id:this.general_id,//商户类目ID
            start_time:this.start_time,
            end_time:this.end_time,
            page:this.currentPage,
            page_size:this.page_size
          }};
          privateList(SplitLogParams).then(data=>{
            console.log(data);
            if(data.code==0){
            this.loadListing=false;
            this.DistributionRecordS=data.content.data;
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
