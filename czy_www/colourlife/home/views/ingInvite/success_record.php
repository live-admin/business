<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>上市感恩季</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/invite/hongbao.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/iscroll.js'); ?>"></script> 

</head>

<body style="background:#fcf0d1;">
<div class="hongbao" id="wrapper" data-role="content">
  <div class="hongbao_content">
  <div class="like_h3"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/h3.gif');?>" class="lotteryimg" /></div>
  <h3 class="indexh3 red">我的成功邀请</h3>
  <div class="page_content">
    <p style="text-align:left;">您已邀请<?php echo $mycount; ?>位好友成功注册彩之云，已领取<?php echo $mysum?$mysum:0; ?>元红包，待领取<?php echo $diff; ?>元红包。</p>
    <p style="text-align:left;">再邀请<?php echo $lack; ?>为好友可以再领取50元红包，加油哦！</p>
    <p style="text-align:left; margin-top:20px;">您的成功邀请注册详情如下：</p>
    <div class="table_box">
      <table id="add">
        <thead>
          <tr>
            <th>序号</th>
            <th>邀请时间</th>
            <th>手机号码</th>
            <th>注册状态</th>
          </tr>
        </thead>
        <tbody>
        <?php $i=1; ?>
          <?php if(!empty($records)){ ?>  
              <?php $i=1; ?>  
              <?php foreach($records as $_v){ ?>   
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$_v->create_time); ?></td>
                <td><?php echo $_v->mobile; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$_v->getRegisterTime()); ?></td>
              </tr>
                <?php $i++; ?>
              <?php } ?>  
          <?php }else{ ?>  
              <tr><td colspan="4">您还没有邀请好友注册，活动期间，邀请好友成功注册彩之云APP可获得红包及抽奖机会。赶紧去邀请好友注册吧！</td></tr>
          <?php } ?>    
        </tbody>
      </table>
    </div>
    <div id="pullUp">
      <span class="pullUpIcon"></span>                
      <span class="pullUpLabel" style="<?php if($mycount > 3){ ?>display:block;<?php }else{ ?>display:none;<?php } ?>">显示更多...</span>
    </div>
    <a href="javascript:history.back();" class="btnlist goback">返  回</a>
  </div>
  <div class="bot_p" style="padding-bottom:50px;">
    <p>★注：体验区用户不能参与注册大礼包的领取，活动最终解释权归彩生活所有</p>
  </div>

  </div>
</div>
<script type="text/javascript">
            var col_i = <?php echo $i; ?>;
            var pageIndex=1;
            var myScroll;
            var pullUpEl;
            var pullUpOffset;
            var count = 0;
            
            function pullUpAction() {//下拉事件
                    var el, tr;
                    el = document.getElementById('add');
                    pageIndex++;                    
                    $.ajax({
                        type : "POST",
                        url : "/ingInvite/getSuccessListByAjax",
                        data : {'pageIndex':pageIndex},
                        dataType:'json',
                        async : false,
                        success : function(result){
                            if(result.code == "error"){
                                $('#pullUp').hide();
                            }else{
                                if(result.records.length < 3){
                                    $('#pullUp').hide();
                                }                            
                                if(result.records.length > 0){
                                    for (var i=0; i < result.records.length; i++ ) {
                                                                                
                                        tr = document.createElement('tr');
                                        tr.innerHTML = "<td>"+col_i+"</td><td>"+result.records[i]['create_time']+"</td><td>"+result.records[i]['mobile']+"</td><td>"+result.records[i]['registerTime']+"</td>";
                                        el.appendChild(tr, el.childNodes[0]);
                                        col_i++;
                                    }
                                }
                            }
                        }
                });
                    myScroll.refresh();
            }
            
            
          function loaded() {
         
     pullUpEl = document.getElementById('pullUp');
     pullUpOffset = pullUpEl.offsetHeight;
     myScroll = new iScroll('wrapper', {
      scrollbarClass: 'myScrollbar', /* 重要样式 */
      useTransition: false,
      onRefresh: function () {
       if (pullUpEl.className.match('loading')) {
        pullUpEl.className = '';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
       }
      },
      onScrollMove: function () {
       if (this.y < (this.maxScrollY - 50) && !pullUpEl.className.match('flip')) {
        pullUpEl.className = 'flip';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
        this.maxScrollY = this.maxScrollY;
       } else if (this.y > (this.maxScrollY + 50) && pullUpEl.className.match('flip')) {
        pullUpEl.className = '';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
        this.maxScrollY = pullUpOffset;
       }
      },
      onScrollEnd: function () {
       if (pullUpEl.className.match('flip')) {
        pullUpEl.className = 'loading';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
        pullUpAction(); // Execute custom function (ajax call?)
       }
      }
     });
     setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
    }
    //初始化绑定iScroll控件
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', loaded, false);
            
    </script>  

</body>
</html>
