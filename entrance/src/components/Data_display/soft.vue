<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <!--<el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>-->
        <el-breadcrumb-item>数据展示</el-breadcrumb-item>
        <el-breadcrumb-item>软入口</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <section class="chart-container">
        <el-row>
          <el-col :span="24" style="padding:0 20px;">
            <el-form :inline="true" :model="filters">
              <!--<el-form-item>-->
              <!--<el-cascader-->
              <!--:options="filters.region"-->
              <!--placeholder="请选择地区"-->
              <!--change-on-select-->
              <!--&gt;</el-cascader>-->
              <!--</el-form-item>-->
              <el-form-item>
                <el-select v-model="filters.RankingHard" clearable placeholder="请选择要展示数据" @change="FunDisplay">
                  <el-option
                    v-for="item in display_hard"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <el-select v-model="filters.org_type" clearable placeholder="要展示的架构" @change="funOrg">
                  <el-option
                    v-for="item in display_Framework"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <el-select v-model="filters.QuickTime" clearable placeholder="选择时间类型" @change="funQuickTime">
                  <el-option
                    v-for="item in times"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <!--<el-date-picker-->
                <!--v-model="filters.data_hard"-->
                <!--type="date"-->
                <!--placeholder="选择具体日期" >-->
                <!--</el-date-picker>-->
                <el-date-picker
                  v-model="filters.data_hard"
                  type="daterange"
                  placeholder="选择具体时间间隔"
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  @focus="TimesFocus"
                  @change="TimesChange">
                </el-date-picker>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" v-on:click="StatisticsQuery">查询</el-button>
                <el-button type="primary" v-on:click="emptyFun">刷新</el-button>
              </el-form-item>
            </el-form>
          </el-col>
          <el-col :span="24" style="padding:0 20px;" v-show="Graph_state==1">
            <el-col :span="12">
              <el-table
                :data="LargeAreaData"
                border
                style="width: 100%">
                <el-table-column :label="Large_type+'活跃度排行榜'">
                  <el-table-column
                    prop="org_name"
                    :label='Large_type'
                    width="180">
                  </el-table-column>
                  <el-table-column
                    prop="average_activity"
                    label="活跃度（%）"
                    width="180">
                  </el-table-column>
                  <el-table-column label="日期">
                    <template slot-scope="scope">
                      {{Time_date}}
                    </template>
                  </el-table-column>
                </el-table-column>
              </el-table>
            </el-col>
            <el-col :span="12">
              <div id="chartColumn" style="width:40rem; height:500px;"></div>
            </el-col>
          </el-col>
          <el-col :span="24" style="padding:0 20px;" v-show="Graph_state==9">
            <el-col :span="12">
              <div id="chartBar" style="width:40rem; height:400px;"></div>
            </el-col>
          </el-col>
          <el-col :span="24" style="padding:0 20px;" v-show="Graph_state==3">
            <el-col :span="12">
              <div id="chartLine" style="width:40rem; height:400px;"></div>
            </el-col>
          </el-col>
          <el-col :span="24" style="padding:0 20px;" v-show="Graph_state==2">
            <el-col :span="12">
              <el-table
                :data="PieData"
                border
                style="width: 100%">
                <el-table-column :label="Large_type+'活跃度占比'">
                  <el-table-column
                    prop="rate"
                    :label="Large_type+'活跃度区间'"
                    width="180">
                  </el-table-column>
                  <el-table-column
                    prop="num"
                    :label="'区间的'+Large_type+'个数'"
                    width="180">
                  </el-table-column>
                  <el-table-column label="日期">
                    <template slot-scope="scope">
                      {{pitTime_date}}
                    </template>
                  </el-table-column>
                </el-table-column>
              </el-table>
            </el-col>
            <el-col :span="12">
              <div id="chartPie" style="width:40rem; height:400px;"></div>
            </el-col>
          </el-col>
          <el-col :span="24">
            <!--<a href="http://echarts.baidu.com/examples.html" target="_blank" style="float: right;">more>></a>-->
          </el-col>
        </el-row>
      </section>

    </el-col>
  </el-row>
</template>
<script>
  import {backendActivityTop,backendActivityRate} from '../../api/api'
  import echarts from 'echarts'

  export default {
    data() {
      return {
        currentDate: new Date(),
        chartColumn: null,
        chartBar: null,
        chartLine: null,
        chartPie: null,
        LargeAreaData:[],//活跃度数据
        PieData:[],//占比数据
        pitTime_date:'',
        // 选择
        Graph_state:1,
        filters:{
          RankingHard:'',
          data_hard:'',
          QuickTime:'',
          org_type:''
        },
        display_hard:[{
          value: '1',
          label: '活跃度排行'
        },{
          value: '2',
          label: '活跃度占比'
        }],
        display_Framework:[],
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
        Time_date:'',
        Large_type:'',
        org_type:'',
        time_type:'',
        start_time:'',
        end_time:'',
      };
    },
    mounted: function () {
    },
    methods:{
      emptyFun(){
        window.location.reload();
      },
      FunDisplay(value){
        this.Graph_state=value;
        if(this.Graph_state==2){
            let ResidentialQuarters=[{
              value: '1',
              label: '大区'
            },{
              value: '2',
              label: '事业部'
            },{
              value: '3',
              label: '小区'
            }];
          this.display_Framework=ResidentialQuarters;
        }else{
          let ResidentialQuarters=[{
            value: '1',
            label: '大区'
          },{
            value: '2',
            label: '事业部'
          }];
          this.display_Framework=ResidentialQuarters;
        }
      },
      funOrg(value){
        console.log(value);
        this.org_type=value;
      },
      funQuickTime(value){
        console.log(value);
        this.time_type=value;
        this.filters.data_hard='';
        this.start_time='';
        this.end_time=''
      },
      TimesFocus(){//日期选择获取焦点时候触发
        if(this.time_type!=5){
          this.$notify.error({
            title: '错误提示',
            message:'请先选择时间类型'
          });
        }
      },
      TimesChange(value){//具体日期选择
        console.log(value);
        if(this.time_type!=5){
          this.$notify.error({
            title: '错误提示',
            message:'请先选择时间类型'
          });
          this.filters.data_hard=''
        }else{
          let strs= new Array(); //定义一数组
          strs=value.split("至"); //字符分割
          for (let i=0;i<strs.length ;i++ ){
            console.log(strs[i]);
            this.start_time=strs[0];
            this.end_time=strs[1]
          }
        }
      },
      StatisticsQuery(){//统计查询
        if(this.org_type==''||this.time_type==''){
          this.$notify.error({
            title: '错误提示',
            message:'请选择要搜索的条件'
          });
        }else{
          if(this.time_type==5){
            if(this.start_time==''||this.end_time==''){
              this.$notify.error({
                title: '错误提示',
                message:'请选择时间间隔'
              });
            }else{
              this.GraphicalData();
            }
          }
          this.GraphicalData();
        }
      },
      GraphicalData(){
        if(this.org_type==1){
          this.Large_type='大区'
        }else if(this.org_type==2){
          this.Large_type='事业部'
        }else{
          this.Large_type=''
        }
        if(this.Graph_state==1){
          let TopPara = {params:{
            org_type:this.org_type,
            time_type:this.time_type,
            start_time:this.start_time,
            end_time:this.end_time,
            type:1
          }};
          backendActivityTop(TopPara).then((res) => {
            console.log(res);
            if(res.code==0){
              this.LargeAreaData=res.content.result.data;
              this.Time_date=res.content.result.date;
              let dataArray=[];
              let orgArray=[];
              for(let p=0;p<this.LargeAreaData.length;p++){
                dataArray.push(this.LargeAreaData[p].average_activity);
                orgArray.push(this.LargeAreaData[p].org_name);
              }
              this.funBarGraph(dataArray,orgArray);
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
            }
          })
        }else if(this.Graph_state==2){
          let RatePara = {params:{
            org_type:this.org_type,
            time_type:this.time_type,
            start_time:this.start_time,
            end_time:this.end_time,
            type:1
          }};
          backendActivityRate(RatePara).then((res) => {
            console.log(res);
            if(res.code==0){
              this.PieData=res.content.result.data;
              this.pitTime_date=res.content.result.date;
              let numArray=[];
              let rateArray=[];
              for(let p=0;p<this.PieData.length-1;p++){
                let oneArray={value:this.PieData[p].num,name:this.PieData[p].rate};
                numArray.push(oneArray);
                rateArray.push(this.PieData[p].rate);
              }
              this.funPieChart(numArray,rateArray);
            }else{
              this.$notify.error({
                title: '错误提示',
                message: res.message
              });
            }
          })
        }
      },
      funBarGraph(dataArray,orgArray){
        this.chartColumn = echarts.init(document.getElementById('chartColumn'));
        this.chartColumn.setOption({
          title: { text: this.Large_type+'排行榜',subtext: '活跃度（%）', },
          tooltip: {},
          xAxis: {
            data: orgArray,
          },
          yAxis: {},
          series: [{
            name: '活跃度',
            type: 'bar',
            data: dataArray
          }]
        });
      },
      funPieChart(numArray,rateArray){
        this.chartPie = echarts.init(document.getElementById('chartPie'));
        this.chartPie.setOption({
          title: {
            text: this.Large_type+'活跃度占比',
            subtext: '单位（%）',
            x: 'center'
          },
          tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient: 'vertical',
            left: 'left',
            data: rateArray
          },
          series: [
            {
              name: '访问来源',
              type: 'pie',
              radius: '55%',
              center: ['50%', '60%'],
              data: numArray,
              itemStyle: {
                emphasis: {
                  shadowBlur: 10,
                  shadowOffsetX: 0,
                  shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
              }
            }
          ]
        });
      }
    },
    mounted(){
      this.org_type=1;
      this.time_type=4;
      console.log(1213);
      this.GraphicalData();
    }
  }
</script>
