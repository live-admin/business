<template>
  <el-row class="warp">
    <div v-if="$route.name == 'OA绑定彩之云账号查看详情'">
      <el-col :span="24" class="warp-breadcrum">
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>
          <el-breadcrumb-item  :to="{ path: '/oabindcolourlife' }">OA绑定彩之云</el-breadcrumb-item>
          <el-breadcrumb-item>查看</el-breadcrumb-item>
        </el-breadcrumb>
      </el-col>
      <el-col :span="24" class="warp-main">
          <ul>
            <li>
              <span>客户经理姓名</span>
              <span  v-text="userInfo.name">--</span>
            </li>
            <li>
              <span>客户经理手机号</span>
              <span v-text="userInfo.mobile">--</span>
            </li>
            <li>
              <span>管辖部门</span>
              <span v-text="userInfo.family_name">--</span>
            </li>
            <li>
              <span>彩之云账户</span>
              <span v-text="userInfo.user_id">--</span>
            </li>
            <li>
              <span>绑定状态</span>
              <span v-text="userInfo.bind_state">--</span>
            </li>
            <li>
              <span>绑定时间</span>
              <span v-text="userInfo.time_bind">--</span>
            </li>
            <li>
              <span>解绑时间</span>
              <span v-text="userInfo.time_unbind">--</span>
            </li>
          </ul>
      </el-col>
    </div>

    <div v-if="$route.name == '绑定专属客户经理查看详情'">
      <el-col :span="24" class="warp-breadcrum">
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>
          <el-breadcrumb-item  :to="{ path: '/bindpropermanage' }">绑定专属客户经理</el-breadcrumb-item>
          <el-breadcrumb-item>查看</el-breadcrumb-item>
        </el-breadcrumb>
      </el-col>
      <el-col :span="24" class="warp-main">
          <ul>
            <li>
              <span>业主姓名</span>
              <span  v-text="userInfo.user_name">--</span>
            </li>
            <li>
              <span>业主手机号</span>
              <span v-text="userInfo.user_mobile">--</span>
            </li>
            <li>
              <span>业主所在小区</span>
              <span v-text="userInfo.community_name">--</span>
            </li>
            <li>
              <span>管辖部门</span>
              <span v-text="userInfo.family_name">--</span>
            </li>
            <li>
              <span>客户经理姓名</span>
              <span v-text="userInfo.manager_name">--</span>
            </li>
            <li>
              <span>客户经理手机号</span>
              <span v-text="userInfo.manager_mobile">--</span>
            </li>
            <li>
              <span>客户经理oa</span>
              <span v-text="userInfo.manager_oa">--</span>
            </li>
            <li>
              <span>绑定状态</span>
              <span v-text="userInfo.bind_state">--</span>
            </li>
            <li>
              <span>绑定时间</span>
              <span v-text="userInfo.time_bind">--</span>
            </li>
            <li>
              <span>解绑时间</span>
              <span v-text="userInfo.time_unbind">--</span>
            </li>
          </ul>
      </el-col>
    </div>

    <div v-if="$route.name == '小区业主评价详情查看详情'">
      <el-col :span="24" class="warp-breadcrum">
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{ path: '/' }"><b>首页</b></el-breadcrumb-item>
          <el-breadcrumb-item  :to="{ path: '/communitycomments' }">小区业主评价记录</el-breadcrumb-item>
          <el-breadcrumb-item>查看</el-breadcrumb-item>
        </el-breadcrumb>
      </el-col>
      <el-col :span="24" class="warp-main">
          <ul>
            <li>
              <span>业主姓名</span>
              <span  v-text="userInfo.user_name">--</span>
            </li>
            <li>
              <span>业主手机号</span>
              <span v-text="userInfo.user_mobile">--</span>
            </li>
            <li>
              <span>业主小区</span>
              <span v-text="userInfo.community_name">--</span>
            </li>
            <li>
              <span>管辖部门</span>
              <span v-text="userInfo.family_name">--</span>
            </li>
            <li>
              <span>客户经理姓名</span>
              <span v-text="userInfo.manager_name">--</span>
            </li>
            <li>
              <span>客户经理手机号</span>
              <span v-text="userInfo.manager_mobile">--</span>
            </li>
            <li>
              <span>评价时间</span>
              <span v-text="userInfo.time_create">--</span>
            </li>
            <li>
              <span>满意度</span>
              <span v-text="userInfo.level_value">--</span>
            </li>
            <li>
              <span>评价内容</span>
              <span v-text="userInfo.content">--</span>
            </li>
            <li class="imageList">
              <span>评价图片</span>
              <img :src="item" v-for="(item,index) in userInfo.image" @click="enlargePic(item)" :key="index">
            </li>

          </ul>
      </el-col>
    </div>
    <el-dialog
      :visible.sync="dialogVisible"
      width="30%"
      :before-close="handleClose">
      <img :src="img" style="width:100%;height:100%;">
    </el-dialog>
  </el-row>
</template>
<style type="text/css" scoped>
.warp-main ul{
  list-style-type: none;
  border-bottom: 1px solid #DDD;
}
.warp-main ul li{
  border-top: 1px solid #DDD;
}
.warp-main ul li span:nth-child(1){
  display: inline-block;
  width:20%;
  text-align: right;
  padding:12px 0;
  margin-right:100px;
  font-size: 16px;
  font-weight: bold;
}
.warp-main ul li:nth-of-type(odd) {
  background:#F9F9F9;
}
.warp-main ul li:nth-of-type(even) {
  background:#fff;
}
.imageList img{
  width:100px;
  height:100px;
  margin-top:20px;
}
</style>
<script>
  import { oaBindDetail , managerDetail , commentDetail} from '../api/api';
  export default {
   data() {
      return {
        userInfo:{},
        dialogVisible:false,
        img:'',
      }
    },
    methods:{
      initPage(){
        var localRoutes =JSON.parse(window.sessionStorage.getItem('user'));
        let param = {
          access_token:localRoutes.access_token,
          id:this.$route.query.id,
        };
        console.log(this.$route.query.type);
        switch(this.$route.query.type)
        {
          case "1":
            oaBindDetail(param).then((res) => {
              this.userInfo = res.content.result;
            });
            return;
          case "2":
            managerDetail(param).then((res) => {
              this.userInfo = res.content.result;
            });
            return;
          case "3":
            commentDetail(param).then((res) => {
              this.userInfo = res.content;
            });
            return;
        }
      },
      handleClose(){
        this.dialogVisible = false;
      },
      enlargePic(item){
        this.dialogVisible = true;
        this.img = item;
      }
    },
    created() {
      this.initPage();
    }

  }

</script>
