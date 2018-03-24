<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport" />
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="0"> 
<title>抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/luckyApp.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.easing.min.js?time=New Date()'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky_gunDong.js?time=New Date()'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/jQueryRotate.js?time=New Date()'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky.circle.js?time=New Date()'); ?>"></script>
</head>

<body>
<div class="lottery_topic">
	<img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/yuanbao.png'); ?>" class="lotteryimg yuanbao"/>
   <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/lotteryBack1.jpg'); ?>" class="lotteryimg main_back"/>
   <div class="lottery">
     <p class="lotteryNumber">
       您有
       <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
       <span id="luckyCustCan" ><?php echo $luckyCustCan;?></span>
       次机会，已有
       <span><?php echo $allJoin;?></span>
       人次参加
     </p>
     <div class="dajiang">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/dajiang.png'); ?>"/>
       <div class="dajiang_content">
         <p>恭喜金陵天成小区业主陈先生获得5000元红包大奖！</p>
         <p>恭喜<?php echo $dajiang['community'];?>小区<?php echo $dajiang['name'];?>获得5000元红包大奖！</p>
         <p>立即邀请邻居注册获得更多中大奖机会！</p>
       </div>
     </div>
     <div class="lotteryList">
     	<img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/lotteryer.png'); ?>" class="lotteryimg"/>
       <dl id="ticker">
       	<dt>&nbsp;</dt>
       </dl>
     </div>
     <div class="roulette">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/disk.png'); ?>" id="disk" class="lotteryimg"/>
       <div id="start"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/start.png'); ?>" id="startbtn" class="lotteryimg"/></div>
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/duoduo.png'); ?>" class="duoduo" />
     </div>
     <div class="lotteryDetails">
       <a href="/luckyApp/lotteryrule"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/rule.png'); ?>" class="lotteryimg"/></a>
       <a href="/luckyApp/mylottery"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/detail.png'); ?>" class="lotteryimg"/></a>
     </div>

   </div>
   
   <div class="bot_link">
     <a href="/luckyApp/bieyangcheng">&gt;&gt;&nbsp;查看别样城更多惊喜</a>
     <span><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/linkimg.jpg'); ?>"/></span>
   </div>
  
   <!--弹出框 start-->
   <div class="opacity" style="display:none;">
     <!--抽奖次数已用完 start-->
     <div class="alertcontairn" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack1.png'); ?>" class="lotteryimg" />
       <div class="alertContent">
         <div class="alert">
           <div class="priceInfo">
             <p>亲，今日抽奖机会已用完，<br>
             明天再来试试手气吧！<br>
             缴物业费、停车费、投诉报修<br>
             可获得更多机会试手气哟！
             </p>
           </div>
           <div class="blueblock">
             <h3>加油！再接再厉!</h3>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>" class="lotteryimg"/></a>
             <a href="/luckyApp/lotteryrule"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/checkrule.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--抽奖次数已用完 end-->
     <!--谢谢参与1 start-->
     <div class="alertcontairn1" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack1.png'); ?>" class="lotteryimg"/>
       <div class="alertContent" >
         <div class="alert">
           <div class="priceInfo">
             <p>不要灰心，花样年惠州别样城<br>
              更多惊喜等着您！<br>
              缴物业费、停车费、投诉报修<br>
              可获得更多机会试手气哟！
             </p> 
           </div>
           <div class="blueblock">
             <a href="/luckyApp/bieyangcheng" target="_blank" class="lookmoreHappy">&gt;&gt;&nbsp;查看别样城更多惊喜</a>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>" class="lotteryimg"/></a>
             <a href="javascript:lottery()"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/tryagain.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--谢谢参与1 end-->
     <!--谢谢参与2 start-->
     <div class="alertcontairn2" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack1.png'); ?>" class="lotteryimg"/>
       <div class="alertContent">
         <div class="alert">
           <div class="priceInfo">
             <p>不要灰心，花样年惠州别样城<br>
              更多惊喜等着您！<br>
              缴物业费、停车费、投诉报修<br>
              可获得更多机会试手气哟！
             </p>
           </div>
           <div class="blueblock">
             <a href="/luckyApp/bieyangcheng" target="_blank" class="lookmoreHappy">&gt;&gt;&nbsp;查看别样城更多惊喜</a>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>" class="lotteryimg"/></a>
             <a href="/luckyApp/lotteryrule"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/checkrule.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--谢谢参与2 end-->
     <!--再来一次 start-->
     <div class="alertcontairn3" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack2.png'); ?>" class="lotteryimg"/>
       <div class="alertContent">
         <div class="alert">
           <div class="priceInfo">
             <p class="redInfo">好运根本停不下来！<br>
              您获得了"再来一次"的抽奖机会，<br>
              赶紧再试试手气吧！
             </p>
           </div>
           <div class="priceBtn">
             <a href="javascript:getAgain();" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/onemoretime.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--再来一次 end-->
     <!--抽中奖品 start-->
     <div class="alertcontairn4" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack2.png'); ?>" class="lotteryimg"/>
       <a href="javascript:open()" class="openLiBao"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/openLiBao.png'); ?>" class="lotteryimg"/></a>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">天上掉馅饼了！<br>
              您获得了一个"<span id="packageName"></span>"礼包。<br>
              赶紧打开来看看吧。
             </p>
           </div>
         </div>
       </div>
     </div>
     <!--抽中奖品 end-->
     <!--拆开礼包 start-->
     <div class="alertcontairn5" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack3.png'); ?>" class="lotteryimg"/>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">您打开了"幸福美味"礼包！<br>
             	礼品为价值88元精选小木瓜<br/>10斤礼品装+价值3000元的<br/>旅游优惠券一份。<br>
             </p>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>" class="lotteryimg"/></a>
             <a href="/luckyApp/howgetit"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/howgetit.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--拆开礼包 end-->
     <!--拆开礼包2 start-->
     <div class="alertcontairn5_2" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack3.png'); ?>" class="lotteryimg"/>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">您打开了"吉祥如意"礼包！<br>
               礼品为供应商赞助的神秘小礼<br/>品一份，
              详情请查看<br/>礼包领取说明。
             </p>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>"class="lotteryimg" /></a>
             <a href="/luckyApp/howgetit"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/howgetit.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--拆开礼包2 end-->
     
     <!--拆红包5000 start-->
     <div class="alertcontairn6" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack4.png'); ?>" class="lotteryimg"/>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">您拆开了花样年惠州别样城<br/>赞助的"欢欢喜喜"红包，<br/>
              红包金额5000元!<br/>
              并有机会抽取百万大奖！
             </p>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>" class="lotteryimg"/></a>
             <a href="/luckyApp/howgethb"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/howgetit_hb.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--拆红包5000 end-->
     <!--拆红包 start-->
     <div class="alertcontairn7" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack4.png'); ?>" class="lotteryimg"/>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">您拆开了"<span id="chaikaiName"></span>"红包，<br>
             红包金额<span id="chaikaiNum"></span>元!
             </p>
           </div>
           <div class="priceBtn">
             <a href="javascript:void(0);" class="closeOpacity"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/close.png'); ?>" class="lotteryimg"/></a>
             <a href="/luckyApp/howuse"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/use.png'); ?>" class="lotteryimg"/></a>
           </div>
         </div>
       </div>
     </div>
     <!--拆红包 end-->
     <!--5000元大奖提示 start-->
     <div class="alertcontairn8" style="display:none">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack5.png'); ?>" class="lotteryimg"/>
       <a href="javascript:open()" class="openHongBao"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/openHongBao.png'); ?>" class="lotteryimg"/></a>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">1秒变土豪，恭喜您中大奖了!<br>
              您获得了一个花样年惠州别样城<br>
              赞助的"欢欢喜喜"红包，<br>
              赶紧拆开来看看吧!
             </p>
           </div>
         </div>
       </div>
     </div>
     <!--5000元大奖提示 end-->
     <!--抽中红包 start-->
     <div class="alertcontairn9" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/boxBack5.png'); ?>" class="lotteryimg"/>
       <a href="javascript:open()" class="openHongBao"><img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/openHongBao.png'); ?>" class="lotteryimg"/></a>
       <div class="alertContent">
         <div class="alert">
           
           <div class="priceInfo">
             <p class="redInfo">好运从天而降，恭喜您中奖了!<br>
              您获得了一个"<span id="packageName"></span>"红包,<br>
              赶紧拆开来看看吧!
             </p>
           </div>
         </div>
       </div>
     </div>
     <!--抽中红包 end-->
   </div>
   <!--弹出框 end-->
  
   
  </audio>
  
</div>
</body>
</html>
