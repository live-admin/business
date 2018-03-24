<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <!--<el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>-->
        <el-breadcrumb-item>操作日志</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <el-tabs type="border-card">
        <el-tab-pane label="操作记录">
          <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
            <el-form :inline="true" :model="filters">
              <el-form-item>
                <el-cascader
                  :options="organizational"
                  placeholder="请选择地区"
                  change-on-select
                  @change="structure"
                ></el-cascader>
              </el-form-item>
              <el-form-item>
                <el-cascader
                  :options="category"
                  placeholder="请选择入口"
                  change-on-select
                  @change="rkCategory"
                ></el-cascader>
              </el-form-item>
              <el-form-item>
                <el-date-picker
                  v-model="filters.QuickTime"
                  placeholder="选择时间间隔"
                  type="daterange"
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  @change="ScreeningDate">
                </el-date-picker>
              </el-form-item>
              <el-form-item>
                <el-input v-model="mobile" placeholder="电话号码"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" v-on:click="LogQuery">查询</el-button>
                <el-button type="primary" v-on:click="emptyFun">刷新</el-button>
              </el-form-item>
            </el-form>
          </el-col>
          <!--列表-->
          <el-table :data="logs1" highlight-current-row v-loading="listLoading" @selection-change="selsChange"
                    style="width: 100%;">
            <el-table-column prop="community_name" label="地区" ></el-table-column>
            <el-table-column prop="category_name" label="功能名称" ></el-table-column>
            <el-table-column prop="create_time" label="统计日期" ></el-table-column>
            <el-table-column prop="user_name" label="用户名称" ></el-table-column>
            <el-table-column prop="mobile" label="手机号码" ></el-table-column>
          </el-table>
          <el-col :span="24" class="toolbar">
            <el-button type="primary" @click="exportLog" size="small" style="margin-top: 5px;">导出</el-button>
            <el-pagination
              @size-change="handleSizeChange1"
              @current-change="handleCurrentChange1"
              :current-page.sync="currentPage_one"
              :page-size="page_size1"
              layout="prev, pager, next, jumper"
              :total="total1"
              style="float:right;margin:5px 30px 0 0;">
            </el-pagination>
          </el-col>
        </el-tab-pane>
        <!--<el-tab-pane label="小区统计">-->
          <!--<el-col :span="24" class="toolbar" style="padding-bottom: 0px;">-->
            <!--<el-form :inline="true" :model="filters">-->
              <!--<el-form-item>-->
                <!--<el-cascader-->
                  <!--:options="filters.region"-->
                  <!--placeholder="请选择地区"-->
                  <!--change-on-select-->
                <!--&gt;</el-cascader>-->
              <!--</el-form-item>-->
              <!--<el-form-item>-->
                <!--<el-button type="primary" v-on:click="">查询</el-button>-->
              <!--</el-form-item>-->
            <!--</el-form>-->
          <!--</el-col>-->
          <!--&lt;!&ndash;列表&ndash;&gt;-->
          <!--<el-table :data="Statistics" highlight-current-row v-loading="listLoading" @selection-change="selsChange"-->
                    <!--style="width: 100%;">-->
            <!--<el-table-column prop="Functional_name" label="功能名称" ></el-table-column>-->
            <!--<el-table-column prop="Statistical_date" label="统计日期" ></el-table-column>-->
            <!--<el-table-column prop="frequency" label="操作次数" ></el-table-column>-->
            <!--<el-table-column prop="user_Number" label="使用人次" ></el-table-column>-->
          <!--</el-table>-->
          <!--&lt;!&ndash;工具条&ndash;&gt;-->
          <!--<el-col :span="24" class="toolbar">-->
            <!--<el-button type="primary" size="small" @click="" style="margin-top: 5px;">导出</el-button>-->
            <!--<el-pagination-->
              <!--@size-change="handleSizeChange2"-->
              <!--@current-change="handleCurrentChange2"-->
              <!--:current-page.sync="currentPage_two"-->
              <!--:page-size="page_size2"-->
              <!--layout="prev, pager, next, jumper"-->
              <!--:total="total2"-->
              <!--style="float:right;margin:5px 30px 0 0;">-->
            <!--</el-pagination>-->
          <!--</el-col>-->
        <!--</el-tab-pane>-->
      </el-tabs>
    </el-col>
  </el-row>
</template>
<script>
  import {operationLogList,backendOrgRegion,backendCategory,backendOperationLogExport} from '../../api/api';
  export default{
    data(){
      return {
        organizational:[],
        filters: {
          region:[{
            value: 'shejiyuanze',
            label: '设计原则',
            children: [{
              value: 'yizhi',
              label: '一致'
            }, {
              value: 'fankui',
              label: '反馈'
            }, {
              value: 'xiaolv',
              label: '效率'
            }, {
              value: 'kekong',
              label: '可控'
            }]
          }],
          Mobile: '',
          QuickTime:''
        },
        listLoading:false,
        logs1:[],
        Statistics:[{
          Functional_name:'软入口汇总',
          Statistical_date:'2017/8/25',
          frequency:'12113465',
          user_Number:'854645'
        },{
            Functional_name:'硬入口汇总',
            Statistical_date:'2017/8/25',
            frequency:'12113465',
            user_Number:'854645'
        }],
        options:[],
        category:[{
          value: '1',
          label: '软入口',
          children:[]
        },{
          value: '2',
          label: '硬入口',
          children:[]
        }],
        currentPage_two:1,
        currentPage_one:1,
        page:1,
        page_size1:15,
        page_size2:1,
        category_id:'',
        type:'',
        mobile:'',
        org_type:'',
        org_uuid:'',
        start_time:'',
        end_time:'',
        total1: 0,
        total2: 10,
        lastOrg_uuid:'',
      }
    },
    methods:{
      selsChange: function (sels) {
        this.sels = sels;
      },
      handleSizeChange2(val) {
        console.log(`每页 ${val} 条`);
        this.page = val;
        this.logList();
      },
      handleCurrentChange2(val) {
        console.log(`当前页: ${val}`);
        this.page = val;
        this.logList();
      },
      handleSizeChange1(val) {
        console.log(`每页 ${val} 条`);
        this.page = val;
        this.logList();
      },
      handleCurrentChange1(val) {
        console.log(`当前页: ${val}`);
        this.page = val;
        this.logList();
      },
      logList(){
        let para = {params:{
          page: this.page,
          page_size:this.page_size1,
          category_id:this.category_id,
          type:this.type,
          mobile:this.mobile,
          org_type:this.org_type,
          org_uuid:this.org_uuid,
          start_time:this.start_time,
          end_time:this.end_time
        }};
        this.listLoading = true;
        operationLogList(para).then((res) => {
          console.log(res);
          if(res.code==0){
            this.total1 = res.content.result.total;
            this.logs1 = res.content.result.data;
          }else{
            this.$notify.error({
              title: '错误提示',
              message: res.message
            });
          }
          this.listLoading = false;
        })
      },
      Residential(){
        let OrderListParams ={params:{org_uuid:''}};
        backendOrgRegion(OrderListParams).then(data => {
          if(data.code==0){
            let Array=[];
            for(let i=0;i<data.content.result.data.length;i++){
              let rpl={
                label:data.content.result.data[i].org_name,
                value:data.content.result.data[i].org_uuid,
                children:[]
              };
              Array.push(rpl);
            }
            this.organizational=Array;
          }else{
            this.$notify.error({
              title: '错误提示',
              message: data.message
            });
            if(data.code=='402'||data.code=='9001'){
              sessionStorage.removeItem('user_router');
              sessionStorage.removeItem('access-user');
              this.$router.push({ path: '/login' });
            }
          }
        });
      },
      structure(value){//选择组织架构
        this.org_type=value.length;
        console.log(value);
        for(let i=0;i<value.length;i++){
          this.lastOrg_uuid=value[value.length-1];
        }
        if(value.length==1){
          let OrderListParams ={params:{org_uuid:value[0]}
          };
          backendOrgRegion(OrderListParams).then(data => {
            console.log(data);
            if(data.code==0){
              for(let j=0;j<this.organizational.length;j++){
                if(value[0]==this.organizational[j].value){
                  let Array=[];
                  for(let i=0;i<data.content.result.data.length;i++){
                    let rpl={
                      label:data.content.result.data[i].org_name,
                      value:data.content.result.data[i].org_uuid,
                      children:[]
                    };
                    Array.push(rpl);
                  }
                  this.organizational[j].children=Array;
                }
              }
            }else{
              this.$notify.error({
                title: '错误提示',
                message: data.message
              });
              if(data.code=='402'){
                this.$router.push({ path: '/login' });
                sessionStorage.removeItem('access-user');
              }
            }
          });
        }else if(value.length==2){
          let OrderListParams ={params:{org_uuid:value[1]}};
          backendOrgRegion(OrderListParams).then(data => {
            console.log(data);
            if(data.code==0){
              for(let j=0;j<this.organizational.length;j++){
                if(value[0]==this.organizational[j].value){
                  for(let p=0;p<this.organizational[j].children.length;p++){
                    if(value[1]==this.organizational[j].children[p].value){
                      let Array=[];
                      for(let i=0;i<data.content.result.data.length;i++){
                        let rpl
                          if(data.content.result.org_type!=4){
                            rpl={
                              label:data.content.result.data[i].org_name,
                              value:data.content.result.data[i].org_uuid,
                              children:[]
                            };
                          }else{
                              rpl={
                              label:data.content.result.data[i].org_name,
                              value:data.content.result.data[i].org_uuid,
                            };
                          }

                        Array.push(rpl);
                      }
                      this.organizational[j].children[p].children=Array;
                    }
                  }
                }
              }
            }else{
              this.$notify.error({
                title: '错误提示',
                message: data.message
              });
              if(data.code=='402'){
                this.$router.push({ path: '/login' });
                sessionStorage.removeItem('access-user');
              }
            }
          });
        }
//        else if(value.length==3){
//          let OrderListParams ={params:{org_uuid:value[2]}};
//          backendOrgRegion(OrderListParams).then(data => {
//              console.log(data);
//            if(data.code==0){
//              for(let j=0;j<this.organizational.length;j++){
//                if(value[0]==this.organizational[j].value){
//                  for(let p=0;p<this.organizational[j].children.length;p++){
//                    if(value[1]==this.organizational[j].children[p].value){
//                      for(let m=0;m<this.organizational[j].children[p].children.length;m++){
//                        if(value[2]==this.organizational[j].children[p].children[m].value){
//                          let Array=[];
//                          for(let i=0;i<data.content.result.data.length;i++){
//                            let rpl={
//                              label:data.content.result.data[i].org_name,
//                              value:data.content.result.data[i].org_uuid
//                            };
//                            Array.push(rpl);
//                          }
//                          this.organizational[j].children[p].children[m].children=Array;
//                        }
//                      }
//                    }
//                  }
//                }
//              }
//            }else{
//              this.$notify.error({
//                title: '错误提示',
//                message: data.message
//              });
//              if(data.code=='402'){
//                this.$router.push({ path: '/login' });
//                sessionStorage.removeItem('access-user');
//              }
//            }
//          });
//        }
      },
      ScreeningDate(value){//日期间隔筛选
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
          console.log(strs[i]);
          this.start_time=strs[0];
          this.end_time=strs[1]
        }
      },
      rkCategory(value){
          console.log(value);
        this.type=value[0];
        this.category_id=value[1];
        if(value.length<2){
          let para = {params:{type:this.type}};
          backendCategory(para).then((res) => {
            console.log(res);
            if(res.code==0){
              for(var j=0;j<this.category.length;j++){
                  if(value[0]=this.category[j].value){
                    let Array=[];
                    for(let i=0;i<res.content.result.length;i++){
                      let rpl={
                        label:res.content.result[i].name,
                        value:res.content.result[i].category_id,
                      };
                      Array.push(rpl);
                    }
                    this.category[j].children=Array;
                  }
              }
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
            }
          })
        }
      },
      LogQuery(){
        if(this.lastOrg_uuid==''&&this.start_time==''&&this.type==""&&this.end_time==""&&this.mobile==''&&this.category_id==''){
          this.$notify({
            title: '警告',
            message: '请输入查询信息',
            type: 'warning'
          });
        }else{
          this.org_uuid=this.lastOrg_uuid;
          this.logList();
        }
      },
      emptyFun(){
        window.location.reload();
      },
      exportLog(){
//        let exportPara = {params:{
//            type:this.type,
//            category_id:this.category_id,
//            mobile:this.mobile,
//            org_type:this.org_type,
//            org_uuid:this.org_uuid,
//            start_time:this.start_time,
//            end_time:this.end_time,
//        }};
//        var newPage = window.open();
//        window.open('about:blank');
//        console.log(window.location.host);
//        console.log(document.domain);
//        let user=sessionStorage.getItem('access-user');
//        let access_token;
//        if (user){
//          user = JSON.parse(user);
//          access_token=user.access_token;
//        }else{
//          access_token='';
//        }
//        window.location.href='http://entrance.czytest.colourlife.com/backend/operation/log/export?access_token='+access_token+'&category_id='+this.category_id+'&type='+this.type+'&mobile='+this.mobile+'&org_type='+this.org_type+'&org_uuid='+this.org_uuid+'&start_time='+this.start_time+'&end_time='+this.end_time;

//        backendOperationLogExport(exportPara).then((res) => {
//          if(res.code==0){
//            newPage.location.href = 'url'
//          }else{
//            this.$notify.error({
//              title: '错误提示',
//              message: res.message
//            });
//          }
//        })
      }
    },
    mounted() {
      this.logList();
      this.Residential();
    }
  }
</script>
