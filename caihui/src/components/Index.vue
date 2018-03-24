<template>
  <div class="main-body" :style="{'-webkit-overflow-scrolling': scrollMode}">
    <v-loadmore :bottom-method="loadBottom" :bottom-all-loaded="allLoaded" :auto-fill="false" ref="loadmore">
      <ul class="list">

          <li v-for="(item, index) in proCopyright">
            <div>{{item.FZD_ZPMC}}</div>
          </li>

      </ul>

    </v-loadmore>

  </div>
</template>

<script>
import {Indicator,Loadmore} from 'mint-ui';
import { goodsList } from '../api/api';

export default {
    components:{
      'v-loadmore':Loadmore,
    },
  data () {
    return {
      pageNo:1,
      pageSize:50,
      proCopyright:[],
      allLoaded: false, //是否可以上拉属性，false可以上拉，true为禁止上拉，就是不让往上划加载数据了
      scrollMode:"auto", //移动端弹性滚动效果，touch为弹性滚动，auto是非弹性滚动
      totalpage:0,
      loading:false,
      bottomText: '',
    }
  },
  mounted(){
    this.loadPageList();  //初次访问查询列表
  },
  methods:{
    loadBottom:function() {
      // 上拉加载
      this.more();// 上拉触发的分页查询
      this.$refs.loadmore.onBottomLoaded();// 固定方法，查询完要调用一次，用于重新定位
    },
    loadPageList:function (){
      // 查询数据
      let _this = this;
        Indicator.open();
        let param = {
         access_token:sessionStorage.getItem("access_token"),
         page:this.page
        };
        goodsList(param).then(function (data) {
            Indicator.close();
            if(data.code == '0'){
                _this.proCopyrigh = data.content.data;
                _this.totalpage = Math.ceil(_this.proCopyrigh/this.pageSize);
                if(this.totalpage == 1){
                  this.allLoaded = true;
                }
                this.$nextTick(function () {
                  // 是否还有下一页，加个方法判断，没有下一页要禁止上拉
                  this.scrollMode = "touch";
                  this.isHaveMore();
                });

                // for(var i =0;i<_this.goodslist.length;i++){
                //     _this.goodslist[i].amount = 0;
                // }
            }else{
               Toast(data.message);
            }
        })
    },
    more:function (){
      // 分页查询
      if(this.totalpage == 1){
        this.pageNo = 1;
        this.allLoaded = true;
      }else{
        this.pageNo = parseInt(this.pageNo) + 1;
        this.allLoaded = false;
      }

      console.log(this.pageNo);
      this.axios.get('/copyright?key='+ encodeURIComponent('公司名称')+"&mask=001"+"&page="+this.pageNo+"&size="+this.pageSize).then(res=>{
        this.proCopyright = this.proCopyright.concat(res.data.result.PRODUCTCOPYRIGHT);
        console.log(this.proCopyright);
        this.isHaveMore();
      });
    },
    isHaveMore:function(){
      // 是否还有下一页，如果没有就禁止上拉刷新
      //this.allLoaded = false; //true是禁止上拉加载
      if(this.pageNo == this.totalpage){
        this.allLoaded = true;
      }
    }
  },
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    li{
      padding:30px 0;
      background-color: #ccc;
      margin-bottom:20px;
    }
</style>