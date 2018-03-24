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
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/invite/hongbao.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/iscroll.js'); ?>"></script> 

</head>

<body style="background:#fcf0d1;">
<div class="hongbao" id="wrapper">
  <div class="hongbao_content">
  <div class="like_h3"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/h3.gif');?>" class="lotteryimg" /></div>
  <h3 class="indexh3 red">我的邀请记录</h3>
  <div class="page_content">
    <p style="text-align:left;">您邀请的好友注册详情如下：</p>
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
                <td>
                      <?php if($_v->status == 1 && $_v->effective == 1){ ?>
                             已注册
                      <?php }else if($_v->status == 0 && (time() <= $_v->valid_time)){ ?>
                             注册中
                      <?php }else if($_v->status == 0 && (time() > $_v->valid_time)){ ?>
                             已失效
                      <?php }else{ ?>
                             邀请无效
                      <?php } ?>
              </td>
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
    <dl>
      <dt>注册状态说明</dt>
      <dd>注册中：手机号码受邀请后尚未注册成功的</dd>
      <dd>已注册：手机号码受邀注册成功的</dd>
      <dd>已失效：手机号码受邀注册已经失效，需要重新注册</dd>
      <dd>邀请无效：未按照活动说明注册的用户</dd>
    </dl>
    <a href="javascript:history.back();" class="btnlist goback">返  回</a>
  </div>
  <div class="bot_p" style="padding-bottom:50px;">
    <p>★注：活动最终解释权归彩生活所有</p>
  </div>

  </div>
</div>

<script type="text/javascript">
            //var username = "<?php //echo $username;  ?>"; 
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
                        url : "/ingInvite/getMyInviteRecordByAjax",
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
                                        tr.innerHTML = "<td>"+col_i+"</td><td>"+result.records[i]['create_time']+"</td><td>"+result.records[i]['mobile']+"</td><td>"+result.records[i]['status']+"</td>";
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
          
          // function reinvite(mobile){
          //     $.post(
          //       '/invite/inviteFriend',
          //       {'mobile':mobile},
          //       function (data){
          //          if(data.code == "success"){
          //              $('.textinfo p').text("您已经成功邀请了好友，短信发送内容为：您的好友"+username+"邀请您注册彩之云,下载地址http://dwz.cn/8YPIv。");
          //              $('.opacity').show();
          //              center($('.tips_contairn'));
          //              $('.send_number').show(); 
          //          }else{
          //              $('.textinfo p').text(data.code);
          //              $('.opacity').show();
          //              center($('.tips_contairn'));
          //              $('.send_number').show();
          //          } 
          //       }
          //       ,'json');
          // }
          
         //  $('.closeOpacity').click(function(){
         //        $('.opacity').hide();   
         // }); 
         
  //        function center(obj) {
  //   var screenWidth = $(window).width();
  //   var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
  //   var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
  //   var objLeft = (screenWidth - obj.width())/2 ;
  //   var objTop = (screenHeight - obj.height())/2 + scrolltop;
  //   obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
  //   //浏览器窗口大小改变时
  //   $(window).resize(function() {
  //   screenWidth = $(window).width();
  //   screenHeight = $(window).height();
  //   scrolltop = $(document).scrollTop();
  //   objLeft = (screenWidth - obj.width())/2 ;
  //   objTop = (screenHeight - obj.height())/2 + scrolltop;
  //   obj.css({left: objLeft + 'px', top: objTop + 'px'});
  // });
  //        }
         
         // $('.invite_it').live('click',function (){
         //     var mobile = $(this).parent().prev().html();
         //     $(this).parent().html("已重新邀请")
         //     $.post(
         //        '/invite/inviteFriend',
         //        {'mobile':mobile},
         //        function (data){
         //           if(data.code == "success"){
         //               $('.textinfo p').text("您已经成功邀请了好友，短信发送内容为：您的好友"+username+"邀请您注册彩之云,下载地址http://dwz.cn/8YPIv。");
         //               $('.opacity').show();
         //               center($('.tips_contairn'));
         //               $('.send_number').show(); 
         //           }else{
         //               $('.textinfo p').text(data.code);
         //               $('.opacity').show();
         //               center($('.tips_contairn'));
         //               $('.send_number').show();
         //           } 
         //        }
         //        ,'json');
         // });
    </script>
</body>
</html>
