<template>
    <div class="tab-content">
        <ul class="block">
            <li>
                <span class="demonstration">支付名称</span>
                <el-input
                        placeholder="请输入支付名称"
                        v-model="PaymentName" style="width:240px;">
                </el-input>
            </li>
            <!--<li>-->
                <!--<span class="demonstration">支付类型</span>-->
                <!--<el-select v-model="type" placeholder="请选择支付类型">-->
                    <!--<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</li>-->
            <li>
                <el-button type="primary" icon="search" @click="Paymentsearch"></el-button>
            </li>
            <li  style="float: right;margin-right:30px;">
                <!--<el-button type="info" @click="details_Click">新增</el-button>-->
            </li>
        </ul>
        <div class="box_table">
            <el-table :data="paymentTableData" border style="width: 100%;text-align: center">
                <el-table-column prop="id" label="ID">
                </el-table-column>
                <el-table-column prop="uuid" label="uuid">
                </el-table-column>
                <el-table-column prop="name" label="支付名称">
                </el-table-column>
                <el-table-column label="logo">
                    <template slot-scope="scope">
                        <img src="../../assets/logo.png" alt="">
                    </template>
                </el-table-column>
                <el-table-column label="类型">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.type==1">饭票</el-tag>
                        <el-tag type="info" v-if="scope.row.type==2">现金</el-tag>
                        <!--<el-tag type="warning">00000</el-tag>-->
                    </template>
                </el-table-column>
                <el-table-column prop="discount" label="折扣率">
                </el-table-column>
                <el-table-column prop="state" label="状态">
                </el-table-column>
                <el-table-column prop="creater" label="创建人">
                </el-table-column>
                <el-table-column prop="parent_id" label="父级ID">
                </el-table-column>
                <el-table-column prop="update_at" label="更新时间">
                </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <!--<el-button type="text" size="small" @click="open2">禁用</el-button>-->
                        <!--<el-button type="text" size="small" @click="details_xiugai">修改</el-button>-->
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!--新增界面-->
        <el-dialog title="新增支付方式" v-model="Merchant_Visible" :close-on-click-modal="false" width="50%">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="支付名称" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="父级ID" prop="author">
                    <!--<el-input v-model="addForm.name" auto-complete="off"></el-input>-->
                    <el-select v-model="ParentID_s" placeholder="请选择" style="width:100%">
                        <el-option
                                v-for="item in ParentIDs"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="折扣率">
                    <el-input v-model="addForm.Rate" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="状态" prop="author">
                    <!--<el-input v-model="addForm.name" auto-complete="off"></el-input>-->
                    <el-select v-model="state_new" placeholder="请选择" style="width:100%">
                        <el-option
                                v-for="item in state_news"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="LOGO" prop="description">
                    <el-button type="primary">上传<i class="el-icon-upload el-icon--right"></i></el-button>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addSubmit">取消</el-button>
                <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>
        <!--修改界面-->
        <el-dialog title="修改支付方式" v-model="Merchant_xiugai" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="支付名称" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="父级ID" prop="author">
                    <!--<el-input v-model="addForm.name" auto-complete="off"></el-input>-->
                    <el-select v-model="ParentID_s" placeholder="请选择" style="width:100%">
                        <el-option
                                v-for="item in ParentIDs"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="折扣率">
                    <el-input v-model="addForm.Rate" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="状态" prop="author">
                    <!--<el-input v-model="addForm.name" auto-complete="off"></el-input>-->
                    <el-select v-model="state_new" placeholder="请选择" style="width:100%">
                        <el-option
                                v-for="item in state_news"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="LOGO" prop="description">
                    <el-button type="primary">上传<i class="el-icon-upload el-icon--right"></i></el-button>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addSubmit">取消</el-button>
                <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>
        <div style="float:right;margin:10px 35px;" v-show="PageDisplay">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size="page_size" layout="prev, pager, next, jumper" :total="total"></el-pagination>
        </div>
    </div>
</template>
<script>
    import {backendPaymentList,paymentAdd,paymentEdit} from '../../api/api';
    export default {
        data() {
            return {
                currentPage:1,//显示页数
                page_size:10,//每页数量
                total:1,//总页数
                PageDisplay:false,
                type:'',//类型
                Mobile:'',//手机号码
                PaymentName:'',//商户名称
                options: [{
                    value: '01',
                    label: '支付宝'
                }, {
                    value: '02',
                    label: '微信'
                }, {
                    value: '03',
                    label: '彩饭票'
                }, {
                    value: '04',
                    label: '现金'
                }],
                ParentIDs: [{
                    value: '01',
                    label: '01'
                }, {
                    value: '02',
                    label: '02'
                }, {
                    value: '03',
                    label: '03'
                }, {
                    value: '04',
                    label: '04'
                }],
                state_news: [{
                    value: '01',
                    label: '正常'
                }, {
                    value: '02',
                    label: '禁止'
                }],
                state_new:'',//状态
                ParentID_s:'',
                status:'',//订单状态
                paymentTableData: [],
                Merchant_Visible:false,
                addLoading: false,
                Merchant_xiugai:false,
                addFormRules: {
                    name: [
                        {required: true, message: '请输入支付名称', trigger: 'blur'}
                    ]
                },
                addForm: {
                    name: ''
                }
            };
        },
        created: function () {
            this.paymentList();
        },
        methods: {
            Paymentsearch(){
                if(this.PaymentName==''){
                    this.$notify({
                        title: '警告',
                        message: '请输入查询信息',
                        type: 'warning'
                    });
                }else{
                    this.paymentList();
//                    this.PaymentName='';
                }
            },
            handleSizeChange(val) {
//                console.log(`每页 ${val} 条`);
                this.currentPage=val;
                this.paymentList();
            },
            handleCurrentChange(val) {
//                console.log(`当前页: ${val}`);
                this.currentPage=val;
                this.paymentList();
            },
            paymentList(){//支付列表
                let _this=this;
                let localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
                let RoleParams={
                    access_token:localRoutes.access_token,
                    name:this.PaymentName,
                    page:this.currentPage,
                    page_size:this.page_size
                };
                backendPaymentList(RoleParams).then(data=>{
                    if(data.code==0){
                        this.paymentTableData=data.content.list.data;
                        this.total=data.content.list.total;
                        if(data.content.list.total>10){
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
                            _this.$router.push({ path: '/login' });

                        }
                    }
                })
            },
            details_Click() {
                this.Merchant_Visible=true;
//                var _this = this;
//                _this.$router.push({ path:'/Merchant_details' });
            },
            details_xiugai(){
                this.Merchant_xiugai=true;
            },
            addSubmit(){
                this.Merchant_Visible=false;
                this.Merchant_xiugai=false;
            },
            open2(){
                this.$confirm('此操作将禁用该支付方式, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$message({
                        type: 'success',
                        message: '禁用成功!'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消禁用'
                    });
                });
            }
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
        margin:0;
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
</style>
