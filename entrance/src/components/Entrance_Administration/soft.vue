<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <!--<el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>-->
        <el-breadcrumb-item>入口管理</el-breadcrumb-item>
        <el-breadcrumb-item>软入口</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <!--工具条-->
      <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
        <el-form :inline="true" :model="filters">
          <el-form-item>
            <el-cascader
              :options="organizational"
              placeholder="请选择地区"
              change-on-select
              @change="structure">
            </el-cascader>
          </el-form-item>
          <el-form-item>
            <el-select v-model="category_id" clearable placeholder="请选择入口">
              <el-option
                v-for="item in options_hard"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-select v-model="filters.data_hard" placeholder="选择快捷日期">
              <el-option
                v-for="item in times"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
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
            <el-button type="primary" v-on:click="softQuery">查询</el-button>
            <el-button type="primary" v-on:click="emptyFun">刷新</el-button>
          </el-form-item>
        </el-form>
      </el-col>

      <!--列表-->
      <el-table :data="hardlist" highlight-current-row v-loading="listLoading" @selection-change="selsChange"
                style="width: 100%;">
        <el-table-column label="序号" >
          <template slot-scope="scope">
            {{scope.$index}}
          </template>
        </el-table-column>
        <el-table-column prop="org_name" label="地区" ></el-table-column>
        <el-table-column prop="all_always_num" label="常住户数" ></el-table-column>
        <el-table-column prop="average_num" label="平均计算人次" ></el-table-column>
        <el-table-column prop="average_activity" label="平均活跃度" ></el-table-column>
        <el-table-column label="日期" width="100">
          <template slot-scope="scope">
            {{hard_Date}}
          </template>
        </el-table-column>
        <el-table-column label="操作" width="150" v-if="Jurisdiction">
          <template slot-scope="scope">
            <el-button size="small" @click="showEditDialog(scope.$index,scope.row)">编辑</el-button>
          </template>
        </el-table-column>
        <el-table-column label="查看方式" width="150">
          <template slot-scope="scope">
            <div class="BrokenLine" @click="zxChart(scope.$index,scope.row)" style="width:35%;float: left;padding-top:5px;cursor: pointer;">
              <img src="./../../assets/images/zhexian_gray_icon.png" alt="">
              <!--<img src="./../../assets/images/zhu_gray_icon.png" alt="" style="position: absolute;margin-left:5px;">-->
            </div>
          </template>
        </el-table-column>
      </el-table>

      <!--工具条-->
      <el-col :span="24" class="toolbar">
        <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page.sync="currentPage"
          :page-size="page_size"
          layout="prev, pager, next, jumper"
          :total="total"
          style="float:right;margin:5px 30px 0 0;">
        </el-pagination>
      </el-col>

      <el-dialog title="编辑" v-model="editFormVisible" :close-on-click-modal="false">
        <el-form :model="editForm" label-width="100px" ref="editForm">
          <el-form-item label="是否需要考核">
            <el-radio-group v-model="editForm.radio_on">
              <el-radio :label="1">是</el-radio>
              <el-radio :label="2">否</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="日期筛选">
            <el-date-picker
              v-model="editForm.publishAt"
              type="daterange"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              @change="ScreeningDate">
            </el-date-picker>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click.native="editFormVisible = false">取消</el-button>
          <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
        </div>
      </el-dialog>
      <!--折线图-->
      <el-dialog title="活跃度展示图" :visible.sync="Broken_line" center="true">
        <el-select v-model="time_value" placeholder="请选择天数" @change="times_change">
          <el-option
            v-for="item in time_options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
        <div id="chartPie" style="width:100%; height:460px;"></div>
        <p style="text-align: center;margin:0;">活跃度展示图</p>
      </el-dialog>
    </el-col>
  </el-row>
</template>
<script>
  import {backActivityList,backendOrgRegion,backendCategory,backendActivityOrg,backendActivityEdit} from '../../api/api';
  import echarts from 'echarts'
  export default{
    data(){
      return {
        Jurisdiction:false,//权限管理
        organizational:[],
        filters: {
          hard: '',
          data_hard:'',
          QuickTime:''
        },
        options_hard: [],
        hardlist: [],
        times:[{
          value: '1',
          label: '昨天'
        },{
          value: '2',
          label: '本月'
        },{
          value: '3',
          label: '上月'
        },{
          value: '4',
          label: '年度'
        },{
          value: '5',
          label: '具体时间区间'
        }],
        total: 0,
        currentPage:1,
        page: 1,
        listLoading: false,
        page_size:10,
        org_type:'',
        org_uuid:'',
        type:1,
        category_id:'',
        time_type:4,
        start_time:'',
        end_time:'',
        hard_Date:'',
        sels: [], //列表选中列
        editTimes:'',

        //编辑相关数据
        editFormVisible: false,//编辑界面是否显示
        editLoading: false,
        // radio_on:1,
        editForm: {
          publishAt: '',
          radio_on:1,
        },
        //折线图
        Broken_line:false,
        chartPie: null,
        tuOrg_uuid:'',
        tuTime_type:'',
        time_value:'',
        time_options:[{
          value:1,
          label:'7天'
        },{
          value:2,
          label:'30天'
        },]
      }
    },
    methods: {
      emptyFun(){
        window.location.reload();
      },
      zxChart(index,row){//折线图
        console.log(row);
        this.Broken_line = true;
        this.$nextTick(function () {
          this.chartPie = echarts.init(document.getElementById('chartPie'));
        });
        this.tuOrg_uuid=row.org_uuid;
        this.time_value=this.tuTime_type=1;
        this.zxChartFun()
      },
      times_change(){
        this.tuTime_type=this.time_value;
        this.zxChartFun()
      },
      zxChartFun(){
        let para = {params:{
          org_uuid: this.tuOrg_uuid,
          type:this.type,
          time_type:this.tuTime_type
        }};
        backendActivityOrg(para).then((res) => {
          console.log(res);
          if(res.code==0){
            let times=[];
            let datas=[];
            for(let i=0;i<res.content.result.length;i++){
              times.push(res.content.result[i].date);
              datas.push(res.content.result[i].data);
            }
            this.zxChartLl(times,datas);
          }else{
            this.$notify.error({
              title: '错误提示',
              message: res.message
            });
            if(res.code=='9001'||res.code=='402'||res.code=='1006'){
              sessionStorage.removeItem('user_router');
              sessionStorage.removeItem('access-user');
              this.$router.push({ path: '/login' });
            }
          }
        });
      },
      zxChartLl(times,datas){
        console.log(datas);
        this.chartPie.setOption({
          title: {
            text: '',
            subtext: '%'
          },
          tooltip: {
            trigger: ''
          },
          legend: {
            data:['活跃度']
          },
          toolbox: {
            show: true,
            feature: {
              dataZoom: {
                yAxisIndex: 'none'
              },
              dataView: {readOnly: false},
              magicType: {type: ['line', 'bar']},
              restore: {},
              saveAsImage: {}
            }
          },
          xAxis:  {
            type: 'category',
            boundaryGap: false,
            data:times
          },
          yAxis: {
            type: 'value',
            axisLabel: {
              formatter: '{value}'
            }
          },
          series: [
            {
              name:'最高活跃度',
              type:'line',
              data:datas,
              markPoint: {
                data: [
                  {type: 'max', name: '最大值'},
                  {type: 'min', name: '最小值'}
                ]
              },
              markLine: {
                data: [
                  {type: 'average', name: '平均值'}
                ]
              }
            }
          ]
        })
      },
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
        this.page = val;
        this.ActivityList();
      },
      handleCurrentChange(val) {
        this.page = val;
        this.ActivityList();
      },
      selsChange: function (sels) {
        this.sels = sels;
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
      //显示编辑界面
      showEditDialog: function (index, row) {
        this.editFormVisible = true;
        this.org_uuid=row.org_uuid;
//        this.editForm = Object.assign({}, row);
      },
      editSubmit(){
        console.log(this.editForm.radio_on);
        console.log(this.editTimes);
        if(this.editForm.radio_on!='' && this.editTimes!=''){
          let Editpara = {
            is_check:this.editForm.radio_on,
            org_uuid:this.org_uuid,
            type:this.type,
            category_id:this.category_id,
            time_type:this.time_type,
            start_time:this.start_time,
            end_time:this.end_time
          };
          backendActivityEdit(Editpara).then((res) => {
            console.log(res);
            if(res.code==0){
              this.$notify({
                title: '成功',
                message: '编辑修改完成！',
                type: 'success'
              });
              this.editFormVisible = false;
              this.org_uuid='';
              this.category_id='';
              this.time_type='';
              this.start_time='';
              this.end_time='';
              this.ActivityList();
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
            }
          })
        }else{
          this.$notify.error({
            title: '错误提示',
            message: '请选择日期和考核状况'
          });
        }
      },
      //软入口列表
      ActivityList(){
        let para = {params:{
          page: this.page,
          page_size:this.page_size,
          org_type:this.org_type,
          org_uuid:this.org_uuid,
          type:this.type,
          category_id:this.category_id,
          time_type:this.time_type,
          start_time:this.start_time,
          end_time:this.end_time
        }};
        this.listLoading = true;
        backActivityList(para).then((res) => {
          console.log(res);
          if(res.code==0){
            this.total = res.content.result.total;
            this.hardlist = res.content.result.data;
            this.hard_Date=res.content.result.date;
          }else{
            this.$notify.error({
              title: '错误提示',
              message: res.message
            });
          }
          this.listLoading = false;
        })
      },
      ScreeningDate(value){//日期筛选
        console.log(value);
        this.editTimes=value;
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
          //document.write(strs[i]+"<br/>"); //分割后的字符输出
          this.start_time=strs[0];
          this.end_time=strs[1]
        }
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
            if(data.code=='402'){
              this.$router.push({ path: '/login' });
              sessionStorage.removeItem('access-user');
            }
          }
        });
      },
      structure(value){//选择组织架构
        this.org_type=value.length;
        console.log(value);
        for(let i=0;i<value.length;i++){
          this.org_uuid=value[value.length-1];
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
                        let rpl={
                          label:data.content.result.data[i].org_name,
                          value:data.content.result.data[i].org_uuid,
                        };
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
//        else if(value.length==3) {
//          let OrderListParams ={params:{org_uuid:value[2]}};
//          backendOrgRegion(OrderListParams).then(data => {
//            console.log(data);
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
      rkCategory(){
        let para = {params:{type:1}};
        backendCategory(para).then((res) => {
          console.log(res);
          if(res.code==0){
            let Array=[];
            for(let i=0;i<res.content.result.length;i++){
              let rpl={
                label:res.content.result[i].name,
                value:res.content.result[i].category_id,
              };
              Array.push(rpl);
            }
            this.options_hard=Array;
          }else{
            this.$notify.error({
              title: '错误提示',
              message: res.message
            });
          }
        })
      },
      softQuery(){
        this.time_type=this.filters.data_hard;
        if(this.org_uuid==''&&this.org_type==""&&this.time_type==""&&this.category_id==''){
          this.$notify({
            title: '警告',
            message: '请输入查询信息',
            type: 'warning'
          });
        }else{
          if(this.time_type==5){
            if(this.start_time==''&&this.end_time==""){
              this.$notify({
                title: '警告',
                message: '请选择时间间隔',
                type: 'warning'
              });
            }else{
              this.ActivityList();
            }
          }else{
            this.ActivityList();
          }
        }
      },
      Permissions_settings(){
        let user=sessionStorage.getItem('access-user');
        let admin=JSON.parse(user).admin;
        console.log(admin);
        if(admin==1||admin==2){
          this.Jurisdiction=true;
        }else if(admin==3){
          this.Jurisdiction=false;
        }
      }
    },
    mounted() {
      this.Permissions_settings();
      this.rkCategory();
      this.ActivityList();
      this.Residential();
    }
  }
</script>

<style>
  .demo-table-expand label {
    font-weight: bold;
  }
</style>
