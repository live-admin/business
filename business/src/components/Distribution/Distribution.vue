<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <el-select
                        v-model="Subject_value"
                        :multiple="false"
                        filterable
                        remote
                        reserve-keyword
                        placeholder="请输入商户关键词"
                        :remote-method="remoteMethod"
                        :focus="GetFocus"
                        clearable
                        :loading="loading"
                        size="small">
                    <el-option
                            v-for="item in options"
                            :key="item.uuid"
                            :label="item.name"
                            :value="item.name">
                    </el-option>
                </el-select>
                <el-select v-model="business_state" clearable placeholder="请选择状态" size="small">
                    <el-option
                            v-for="item in busSTATE"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
                <el-select v-model="general_id" clearable placeholder="请选择商户类目" size="small">
                  <el-option
                    v-for="item in Merchantoptions"
                    :key="item.uuid"
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
                <el-date-picker
                  v-model="times"
                  type="datetimerange"
                  placeholder="选择时间间隔"
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  @change="ScreeningDate" size="small">
                </el-date-picker>
                <el-button type="primary" icon="search" @click="BUSHMerchant" size="small"></el-button>
                <el-button type="primary" icon="refresh" @click="emptyFun" size="small">刷新</el-button>
            </li>
        </ul>
        <div class="box_table">
            <el-table v-loading="loadListing" :data="tableData" border style="font-size:0.8em;">
                <el-table-column prop="id" label="序号" width="80">
                </el-table-column>
                <el-table-column prop="general_name" label="商户类目" width="120">
                </el-table-column>
                <el-table-column prop="name" label="商户名称">
                </el-table-column>
                <el-table-column prop="general_atid" label="金融账号Atid">
                </el-table-column>
                <el-table-column prop="general_pano" label="资金池金融账号" width="260">
                </el-table-column>
                <el-table-column label="创建时间">
                    <template slot-scope="scope">
                        <span>{{scope.row.create_time | time}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="是否可用" width="100">
                    <template slot-scope="scope">
                        <el-tag type="primary" v-if="scope.row.state==1">是</el-tag>
                        <el-tag type="danger" v-else="">否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="200">
                    <template slot-scope="scope">
                        <el-button size="mini" type="info" @click="RulesDetails_Click(scope.$index, scope.row)">规则详情</el-button>
                        <el-button size="mini" type="success" @click="DistributionDecord_Click(scope.$index, scope.row)">分配记录</el-button>
                        <!--<el-button size="mini" type="warning" @click="exchange_click(scope.$index, scope.row)">兑换记录</el-button>-->
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
    import {BusinessList,BackendBusinessStatusList,businessSearch,backendBusinessGeneralList} from './../../api/api';
    export default {
        props: {
        },
        data() {
            return {
                Subject_value:'',//商户
                options: [],
                list: [],
                loading: false,
                tableData: [],
                loadListing:true,
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                business_state:'',
                busSTATE:[{
                    value:'1',
                    label:'是'
                    },{
                    value:'0',
                    label:'否'
                }],
              general_id:'',//商户类目id
              Merchantoptions:[],//商户类目数组
              times:'',//时间
              create_time:'',//开始时间
              end_time:'',//结束时间
            };
        },
        watch: {
        },
        created: function () {
            this.BusinessStart();
            this.Merchant_category();
        },
        methods: {
              Merchant_category(){//获取类目
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
            ScreeningDate(value){//开始时间和结束时间
              let strs= new Array(); //定义一数组
              strs=value.split("至"); //字符分割
              for (let i=0;i<strs.length ;i++ ){
//                this.create_time=strs[0];
//                this.create_time=Date.parse(new Date(strs[0]));
                this.create_time=Math.round(new Date(strs[0]).getTime()/1000).toString();
                this.end_time=Math.round(new Date(strs[1]).getTime()/1000).toString();
//                this.end_time=strs[1]
//                this.end_time =Date.parse(new Date(strs[1]));
              }
            },
            emptyFun(){
              window.location.reload();
            },
            handleSizeChange(val) {
                this.currentPage=val;
                this.BusinessStart();
            },
            handleCurrentChange(val) {
                this.currentPage=val;
                this.BusinessStart();
            },
            BusinessStart(){//商户列表
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let BusinessParams = {
                    access_token:localRoutes.access_token,
                    name:this.Subject_value,
                    state:this.business_state,
                    mobile:'',
                    general_id:this.general_id,//商户类目id
                    create_time:this.create_time,//开始时间
                    end_time:this.end_time,//结束时间
                    page:this.currentPage,
                    page_size:this.page_size
                };
                BackendBusinessStatusList(BusinessParams).then(data=>{
                    if(data.code==0){
                        this.loadListing=false;
                        this.tableData=data.content.data;
                        this.total=data.content.total;
                        if(data.content.total>10){
                            this.PageDisplay=true;
                        }else{
                            this.PageDisplay=false;
                        }
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message:data.message
                        });
                        if(data.code=='402'){
                            sessionStorage.removeItem('user');
                            sessionStorage.removeItem('user_router');
                            _this.$router.push({ path: '/login' });
                        }
                    }
                })
            },
            RulesDetails_Click(index,row) {
                let _this = this;
                _this.$router.push({ path: '/RulesDetails', query:{business_uuid:row.uuid,businessName:row.name} });
            },
            DistributionDecord_Click(index,row){
                let _this = this;
                _this.$router.push({ path: '/total',query:{business_uuid:row.uuid} });
            },
            exchange_click(index,row){
//              this.$router.push({path: '/exchange', query:{map_id:row.id,business_uuid:row.uuid}});
            },
            remoteMethod(query) {//搜素商户
                if (query !== '') {
                    this.loading = true;
                    let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                    let MerchantSearch={
                        access_token:localRoutes.access_token,
                        name:query
                    };
                    businessSearch(MerchantSearch).then(data=>{
                        this.loading = false;
                        if(data.code==0){
                            this.options =data.content;
                            if(this.options.length==0){
                                this.Subject_value='';
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
                    this.Subject_value='';
                }
            },
            GetFocus(){
                this.options = [];
                this.Subject_value='';
            },
            BUSHMerchant(){//搜索
                if(this.options.length==0&&this.business_state==''&&this.general_id==''&&this.times==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入搜索信息',
                        type: 'warning'
                    });
                }else{
                    this.BusinessStart();
//                    this.options = [];
//                    this.Subject_value='';
//                    this.business_state='';
                }
            },
        }
    };
</script>

<style lang="css">
    /*居中*/
    .cell{
        text-align: center;
    }
    li{
        list-style-type:none;
    }
    .block{
        height:auto;
        overflow: hidden;
    }
    .block li{
        float: left;
        margin:5px 5px;
    }
    .box_table{
        text-align: center;
    }
</style>
