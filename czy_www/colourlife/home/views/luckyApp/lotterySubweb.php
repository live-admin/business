<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>汽车大奖</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/luckyCar/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
</head>

<body>
<div class="lottery_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/lotteryhead1.jpg');?>" class="lotteryimg"/></div>
<div class="lottery_topic lotterys_page">
   <img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/subback1.png');?>" class="lotteryimg subback"/>
   <div class="lottery_subpage">
     <div class="lottery_subpage_content">
       <p>强大源自内心，锋芒亦是。</p>
       <p style="white-space:nowrap;">俊朗外观，澎湃动力,型于外，逸于内，你的锋芒淋漓尽现。</p>
       <div class="lottery_details_link">
         <a href="javascript:void(0);" class="acitverule" style="margin:4% auto;">领取抽奖码</a> 
       </div>
       <p class="subbtn">
         <a href="/luckyApp/carMyCode" class="subbtn_l">查看我的抽奖码</a>
         <a href="/luckyApp/luckyCarRule" class="subbtn_r">活动规则</a>
       </p>
       <dl class="lottery_dl">
         <dt><span>活动时间</span>5月13日至6月17日</dt>
         
         <dd class="tips" style="font-size:12px;">★如何获得汽车抽奖码？</dd>
         <dd>1.成功参加彩富人生计划可领取一个抽奖码。</dd>
         <dd>2.预缴半年及以上管理费/停车费可领取一个抽奖码。</dd>
         <dd>3.成功投资E理财产品满5000可领取一个抽奖码，<a href="<?=$urle;?>" class="linkcolor">立即投资&gt;&gt;</a></dd>
         <dd>4.参加红包抽惊喜活动有机会获得一个抽奖码，<a href="/luckyApp/luckyApp" class="linkcolor">立即参加&gt;&gt;</a></dd>
         <dd><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/img1.jpg');?>" class="lotteryimg"/></dd>
         <dd>温馨提示：返回首页进入E缴费参与彩富人生和预缴管理费/停车费。<br/>5月1日至5月12日满足领码条件的用户也可领取汽车抽奖码。</dd>
       </dl>
       <p class="tips" style="font-size:12px;">★注：大奖限彩生活社区住户、活动最终解释权归彩生活所有</p>
     </div>
     <div class="lotterybottom"></div>
   </div>
   
</div>
<!--弹出框 start-->
  <div class="opacity" style="height:920px; display:none;">
   
   <!--抽奖码 start-->
   <div class="alertcontairn" style="display:none;">
     <div class="textcontent">
       <P class="tips">恭喜您达成了<?php echo $count; ?>项领码条件！</P>
       <h3>获取<?php echo $count; ?>个汽车抽奖码：</h3>
       <ul>
          <?php foreach ($list as $k => $v) { ?>
              <li><?php echo $v->getFullDrawCode($v->mycode);?></li>
          <?php } ?>
       </ul>
       <p class="tips">祝您获得幸运大奖！</p>
       <div class="pop_btn">
         <a href="/luckyApp/carMyCode">我的抽奖码</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   
   <!--抽奖码 end-->
   <!--未获得抽奖码 start-->
   <div class="alertcontairn" style="display:none;">
     <div class="textcontent" style="padding-top:30px;">
       <P>很遗憾，您尚未获得领码资格，</P>
       <p>暂不能领取汽车抽奖码。</p>
       <p style="margin-bottom:25px;">现在就去<span class="tips">红包抽惊喜</span>获取抽奖码吧！</p>
       <div class="pop_btn">
         <a href="luckyApp/carTopic">进入活动</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   
   <!--未获得抽奖码 end-->
   
  
  
 </div>
<!--弹出框 end-->
<script type="text/javascript"> 


$(function(){
  $('.acitverule').click(function(){
     $('.opacity').show();
     <?php if($count>0){ ?>
        $('.alertcontairn').eq(0).show();
      <?php }else{ ?>
        $('.alertcontairn').eq(1).show();
      <?php } ?>
   })
 
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
	  $('.alertcontairn').hide();
	  
	})
	
  
})
</script> 
</body>
</html>
