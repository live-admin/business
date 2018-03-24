<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<title>抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/july/lottery.css?time=76543210'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.js?datetime=123456'); ?>"></script>
</head>
<body>
<div class="lottery_topic">
   <div class="lottery">
     <p class="changebox">
         <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
         您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span> 次机会，已有<span id=><?php echo $allJoin; ?></span>人次参加
     </p>
     <div class="lotteryList" style="position:relative;">
       <dl id="ticker">
         <?php foreach($listResutl as $result){ ?>  
            <dt><?php echo $result['msg']; ?></dt>
         <?php } ?>
       </dl>
     </div>
     
     <div class="lottery_box">
       <div class="roulette">
         <div id="startbtn">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/lotterypoint.png'); ?>" class="cup_start lotteryimg"/>
         </div>
         
       </div>
       <div class="lotteryrule">
         <a href="/luckyApp/lotteryrule" class="rulebtn">活动规则</a>
         <a href="/luckyApp/mylottery" class="rulebtn">中奖情况</a>
         <a href="/luckyApp/lookAllResult" class="resultbtn">世界杯竞猜成绩</a>
         <a href="/luckyApp" class="resultbtn">返回</a>
       </div>
     </div>
     
     <div class="adver" style="width:94%; height:54px; margin-bottom:10px;">
      <a href="http://sz189.cn/mini/colourlife/index.html">
          <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/578x100.jpg'); ?>" class="lotteryimg" style="margin:0;"/>
      </a>
     </div>
     
   </div>
   

  <!--弹出框 start-->
 <div class="opacity" style="display:none;">
   <!--手气用完 start-->
   <div class="alertcontairn" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">亲，你的抽奖次数用光了。</p>
         <p>
           邀请邻居注册成功可获得5次抽奖机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/lotteryrule"><span>查看活动规则</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--手气用完 end-->
   <!--每天5次 start-->
   <div class="alertcontairn0" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">亲，每天只能抽奖5次哦，</p>
         <p class="redfont">明天再来抽大奖吧！</p>
         <p>
           邀请邻居注册成功可获得5次抽奖机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/lotteryrule"><span>查看活动规则</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--每天5次 end-->
   <!--谢谢参与 start-->
   <div class="alertcontairn1" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">谢谢您的参与，彩生活有您更加精彩！</p>
         <p>
           邀请邻居注册成功可获得5次抽奖机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/lotteryrule"><span>查看活动规则</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--谢谢参与 end-->
   
   <!--中红包 start-->
   <div class="alertcontairn6" style="display: none">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <div class="redpack_box">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/redpack.jpg'); ?>"/>
           <span id="bonus_amount">0.18</span>
         </div>
         <p class="redfont" style="margin-bottom:0;">恭喜您获得了<span id="bonus_amount_small">0.18</span>元红包！</p>
         <p>
           邀请邻居注册成功可获得5次抽奖机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery"><span>查看中奖情况</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--中红包 end-->
  
   
 </div>
<!--弹出框 end-->
  
</div>

<script type="text/javascript"> 


$(function(){
  $("body").scrollTop(10);  //操作滚动条
  if($("body").scrollTop() > 0){
	  $('.opacity').css('height',$('.lottery').height()+'px');    //判断是否有滚动
	  
  }
  
  $("body").scrollTop(0);   //滚动条返回顶部
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
	  $('.opacity > div').hide();
	})
})
</script> 
</body>
</html>
