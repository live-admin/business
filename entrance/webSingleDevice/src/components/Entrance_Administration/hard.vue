<template>
  <el-row class="warp">
    <el-col :span="24" class="warp-breadcrum">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>
        <el-breadcrumb-item>入口管理</el-breadcrumb-item>
        <el-breadcrumb-item>软入口</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>

    <el-col :span="24" class="warp-main">
      <!--工具条-->
      <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
        <el-form :inline="true" :model="filters">
          <el-form-item>
              <!--<el-select v-model="filters.region" clearable placeholder="请选择地区">-->
                <!--<el-option-->
                  <!--v-for="item in options_region"-->
                  <!--:key="item.value"-->
                  <!--:label="item.label"-->
                  <!--:value="item.value">-->
                <!--</el-option>-->
              <!--</el-select>-->
            <el-cascader
              :options="filters.region"
              placeholder="请选择地区"
              change-on-select
            ></el-cascader>
          </el-form-item>
          <el-form-item>
              <el-select v-model="filters.hard" clearable placeholder="请选择入口">
                <el-option
                  v-for="item in options_hard"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
          </el-form-item>
          <el-form-item>
            <el-select v-model="filters.QuickTime" placeholder="选择快捷日期">
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
              v-model="filters.data_hard"
              type="date"
              placeholder="选择具体日期">
            </el-date-picker>
          </el-form-item>
          <!--<el-form-item>-->
            <!--<el-input v-model="filters.name" placeholder="书名"></el-input>-->
          <!--</el-form-item>-->
          <el-form-item>
            <el-button type="primary" v-on:click="getBooks">查询</el-button>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="showAddDialog">新增</el-button>
          </el-form-item>
        </el-form>
      </el-col>

      <!--列表-->
      <el-table :data="hardlist" highlight-current-row v-loading="listLoading" @selection-change="selsChange"
                style="width: 100%;">
        <el-table-column label="序号" >
          <template scope="scope">
            {{scope.$index}}
          </template>
        </el-table-column>
        <el-table-column prop="org_name" label="地区" ></el-table-column>
        <el-table-column prop="all_always_num" label="常住户数" ></el-table-column>
        <el-table-column prop="average_num" label="平均计算人次" ></el-table-column>
        <el-table-column prop="average_activity" label="平均活跃度" ></el-table-column>
        <el-table-column label="日期" width="100">
          <template scope="scope">
            {{hard_Date}}
          </template>
        </el-table-column>
        <el-table-column label="操作" width="150">
          <template scope="scope">
            <el-button size="small" @click="showEditDialog(scope.$index,scope.row)">编辑</el-button>
          </template>
        </el-table-column>
        <el-table-column label="查看方式" width="150">
          <template scope="scope">
            <div class="BrokenLine" style="width:35%;float: left;padding-top:5px;cursor: pointer;">
              <img src="./../../assets/images/zhexian_gray_icon.png" alt="">
            </div>
            <div class="Column" style="width:35%;float: left;padding-top:5px;cursor: pointer;">
              <img src="./../../assets/images/zhu_gray_icon.png" alt="">
            </div>

          </template>
        </el-table-column>
      </el-table>

      <!--工具条-->
      <el-col :span="24" class="toolbar">
        <!--<el-button type="danger" @click="batchDeleteBook" :disabled="this.sels.length===0">批量删除</el-button>-->
        <!--<el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="10" :total="total"-->
                       <!--style="float:right;">-->
        <!--</el-pagination>-->
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
        <el-form :model="editForm" label-width="100px" :rules="editFormRules" ref="editForm">
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
            <!--<el-date-picker type="date" placeholder="选择日期" v-model="editForm.publishAt"></el-date-picker>-->
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click.native="editFormVisible = false">取消</el-button>
          <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
        </div>
      </el-dialog>

      <!--新增界面-->
      <el-dialog title="新增" v-model="addFormVisible" :close-on-click-modal="false">
        <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
          <el-form-item label="书名" prop="name">
            <el-input v-model="addForm.name" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="作者" prop="author">
            <el-input v-model="addForm.author" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="出版日期">
            <el-date-picker type="date" placeholder="选择日期" v-model="addForm.publishAt"></el-date-picker>
          </el-form-item>
          <el-form-item label="简介" prop="description">
            <el-input type="textarea" v-model="addForm.description" :rows="8"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click.native="addFormVisible = false">取消</el-button>
          <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
        </div>
      </el-dialog>

    </el-col>
  </el-row>
</template>
<script>
  import util from '../../common/util'
  import {reqGetBookListPage, reqDeleteBook, reqEditBook, reqBatchDeleteBook, reqAddBook,backActivityList} from '../../api/api';

  export default{
    data(){
      return {
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
          name: '',
          hard: '',
          data_hard:'',
          QuickTime:''
        },
        options_hard: [{
          value: '选项1',
          label: 'e租房'
        }, {
          value: '选项2',
          label: 'e费通'
        }, {
          value: '选项3',
          label: 'e投诉'
        }, {
          value: '选项4',
          label: 'e评价'
        }, {
          value: '选项5',
          label: 'e装修'
        }, {
          value: '选项6',
          label: 'e能源'
        }, {
          value: '选项7',
          label: 'e入伙'
        }, {
          value: '选项8',
          label: 'e绿化'
        }, {
          value: '选项9',
          label: 'e维修'
        }, {
          value: '选项10',
          label: 'e家纺'
        }, {
          value: '选项11',
          label: '彩富人生'
        }, {
          value: '选项12',
          label: '彩住宅'
        }, {
          value: '选项13',
          label: '邻里'
        }, {
          value: '选项14',
          label: '花易借'
        }, {
          value: '选项15',
          label: '花样保险'
        }, {
          value: '选项16',
          label: '荣信汇理财'
        }, {
          value: '选项17',
          label: '太平洋保险'
        }, {
          value: '选项18',
          label: '钱生花'
        }, {
          value: '选项19',
          label: '彩生活特供'
        }],
        times:[{
          value: '选项1',
          label: '昨天'
        },{
          value: '选项1',
          label: '本月'
        },{
          value: '选项1',
          label: '上月'
        },{
          value: '选项1',
          label: '年度'
        },],

        hardlist: [],
        total: 0,
        currentPage:1,
        page: 1,
        listLoading: false,
        page_size:1,
        org_type:'',
        org_uuid:'',
        type:1,
        category_id:'',
        time_type:4,
        start_time:'',
        end_time:'',
        hard_Date:'',
        sels: [], //列表选中列

        //编辑相关数据
        editFormVisible: false,//编辑界面是否显示
        editLoading: false,
        editFormRules: {
          name: [
            {required: true, message: '请输入书名', trigger: 'blur'}
          ],
          author: [
            {required: true, message: '请输入作者', trigger: 'blur'}
          ],
          description: [
            {required: true, message: '请输入简介', trigger: 'blur'}
          ]
        },
        radio_on:1,
        editForm: {
          id: 0,
          name: '',
          author: '',
          publishAt: '',
          description: '',
          radio_on:1,
        },

        //新增相关数据
        addFormVisible: false,//新增界面是否显示
        addLoading: false,
        addFormRules: {
          name: [
            {required: true, message: '请输入书名', trigger: 'blur'}
          ],
          author: [
            {required: true, message: '请输入作者', trigger: 'blur'}
          ],
          description: [
            {required: true, message: '请输入简介', trigger: 'blur'}
          ]
        },
        addForm: {
          name: '',
          author: '',
          publishAt: '',
          description: ''
        }
      }
    },
    methods: {
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
        this.page = val;
//        this.getBooks();
        this.ActivityList();
      },
      handleCurrentChange(val) {
        this.page = val;
//        this.getBooks();
        this.ActivityList();
      },
      //获取用户列表
      getBooks() {
        let para = {
          page: this.page,
          name: this.filters.name
        };
        this.listLoading = true;
        //NProgress.start();
        reqGetBookListPage(para).then((res) => {
            console.log(res);
          this.total = res.data.total;
          this.hardlist = res.data.books;
          this.listLoading = false;
          //NProgress.done();
        })
      },
      selsChange: function (sels) {
        this.sels = sels;
      },
      //删除
      delBook: function (index, row) {
        this.$confirm('确认删除该记录吗?', '提示', {type: 'warning'}).then(() => {
          this.listLoading = true;
          //NProgress.start();
          let para = {id: row.id};
          reqDeleteBook(para).then((res) => {
            this.listLoading = false;
            //NProgress.done();
            this.$message({
              message: '删除成功',
              type: 'success'
            });
            this.getBooks();
          });
        }).catch(() => {
        });
      },
      //显示编辑界面
      showEditDialog: function (index, row) {
        this.editFormVisible = true;
//        this.editForm = Object.assign({}, row);
      },
      //编辑
      editSubmit: function () {
        this.$refs.editForm.validate((valid) => {
          if (valid) {
            this.$confirm('确认提交吗？', '提示', {}).then(() => {
              this.editLoading = true;
              //NProgress.start();
              let para = Object.assign({}, this.editForm);
              para.publishAt = (!para.publishAt || para.publishAt == '') ? '' : util.formatDate.format(new Date(para.publishAt), 'yyyy-MM-dd');
              reqEditBook(para).then((res) => {
                this.editLoading = false;
                //NProgress.done();
                this.$message({
                  message: '提交成功',
                  type: 'success'
                });
                this.$refs['editForm'].resetFields();
                this.editFormVisible = false;
                this.getBooks();
              });
            });
          }
        });
      },
      showAddDialog: function () {
        this.addFormVisible = true;
        this.addForm = {
          name: '',
          author: '',
          publishAt: '',
          description: ''
        };
      },
      //新增
      addSubmit: function () {
        this.$refs.addForm.validate((valid) => {
          if (valid) {
            this.addLoading = true;
            //NProgress.start();
            let para = Object.assign({}, this.addForm);
            para.publishAt = (!para.publishAt || para.publishAt == '') ? '' : util.formatDate.format(new Date(para.publishAt), 'yyyy-MM-dd');
            reqAddBook(para).then((res) => {
              this.addLoading = false;
              //NProgress.done();
              this.$message({
                message: '提交成功',
                type: 'success'
              });
              this.$refs['addForm'].resetFields();
              this.addFormVisible = false;
              this.getBooks();
            });
          }
        });
      },
      //批量删除
      batchDeleteBook: function () {
        var ids = this.sels.map(item => item.id).toString();
        this.$confirm('确认删除选中记录吗？', '提示', {
          type: 'warning'
        }).then(() => {
          this.listLoading = true;
          //NProgress.start();
          let para = {ids: ids};
          reqBatchDeleteBook(para).then((res) => {
            this.listLoading = false;
            //NProgress.done();
            this.$message({
              message: '删除成功',
              type: 'success'
            });
            this.getBooks();
          });
        }).catch(() => {

        });
      },
//      软入口列表
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
        let strs= new Array(); //定义一数组
        strs=value.split("至"); //字符分割
        for (let i=0;i<strs.length ;i++ ){
//          document.write(strs[i]+"<br/>"); //分割后的字符输出
          console.log(strs[i])
        }
      }
    },
    mounted() {
//      this.getBooks();
      this.ActivityList();
    }
  }
</script>

<style>
  .demo-table-expand label {
    font-weight: bold;
  }
</style>
