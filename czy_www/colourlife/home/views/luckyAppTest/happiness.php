<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>抽奖</title>

<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<!-- <link href="css/lottery.css" rel="stylesheet">
<script src="js/jquery.min.js?time=New Date()"></script>
<script src="js/jQueryRotate.2.2.js?time=New Date()"></script>
<script src="js/jquery.easing.min.js?time=New Date()"></script>
<script src="js/gunDong.js?time=New Date()"></script> -->
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/happiness/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<!--<script src="<?php //echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>-->
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.js'); ?>"></script>

<script type="text/javascript"> 


// $(function(){
	
	
// 	$("#startbtn").rotate({
// 		bind:{
// 			click:function(){
// 				// var a = Math.floor(Math.random() * 360);
// 				 $(this).rotate({
// 					 	duration:3000,
// 					 	angle:0, 
//             animateTo:1440,
// 						easing: $.easing.easeOutSine,
// 						callback: function(){
// 							$('.opacity').show();
// 						}
// 				 });
// 			}
// 		}
// 	});
	
// });
</script> 


</head>

<body>
<div class="lottery_topic">
   <div class="lottery" id="lottery">
     <div class="lottery_tittle">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/tittle.png');?>" class="lotteryimg tt"/>
       <h3 class="number clearfix">
         <a href="javascript:void(0);" class="select_down"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/select.png');?>" class="lotteryimg"/></a>
         <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
         您还有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次抽奖机会
         <dl class="sublink">
           <dd><a href="/luckyApp/happinessResult"><span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/select_icon1.gif');?>" class="lotteryimg"/></span>抽奖记录</a></dd>
           <p class="line-x"></p>
           <dd><a href="/luckyApp/happinessRule"><span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/select_icon2.gif');?>" class="lotteryimg"/></span>活动规则</a></dd>
         </dl>
       </h3>
     </div>
     <div class="roulette">
       <div id="start">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/point.png');?>" id="startbtn" class="cup_start lotteryimg"/>
       </div>
     </div>
     <div class="lottery_details_link">
       <?php //<a href="/invite" class="acitverule"><img src=" echo F::getStaticsUrl('/common/images/lucky/happiness/sub_btn1.png');" class="lotteryimg"/></a> ?>
       <a href="/luckyApp/introduce" class="acitverule"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/sub_btn2.png');?>" class="lotteryimg"/></a> 
      <!-- <a href="/luckyApp/happinessSnh" class="acitverule"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/sub_btn3.png');?>" class="lotteryimg"/></a> -->
     </div>
     <!-- <div><a href=""><img src="<?php //echo F::getStaticsUrl('/common/images/lucky/happiness/820x100.jpg');?>" class="lotteryimg" /></a></div> -->

   </div>
   
  
   
  <!--弹出框 start-->
  <div class="opacity" style="display:none">
   <!--超过5次 start-->
   <div class="alertcontairn alertcontairn_dayfive" style="display:none">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               亲，每天只能抽奖５次哦，<br />欢迎明天继续！<br />喜洋洋转好运，新春大奖等您领！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/happinessResult">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     
   </div>
   <!--超过5次 end-->
   <!--次数用完 start-->
   <div class="alertcontairn alertcontairn0" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               亲，您的抽奖次数已经用完。<br />您可以通过更多途径获得抽奖次数。
             </p>
             <p class="alert_p">
               <a href="/luckyApp/happinessRule" class="lookrule">查看活动规则&nbsp;&gt;&gt;</a>
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/happinessResult">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     
   </div>
   <!--次数用完 end-->
   <!--谢谢参与 start-->
   <div class="alertcontairn alertcontairn1" style="display:none">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <h3>谢谢参与</h3>
             <p class="alert_p">
               彩生活祝您：<br />
               天天开心"喜羊羊"；事事如意"美羊羊"；<br />
               工作愉快"懒羊羊"；合家幸福"暖羊羊"；<br />
               愿您一切都好，羊年喜气"羊羊"！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/happinessResult">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--谢谢参与 end-->
   
   <!--黑莓酒 start-->
   <div class="alertcontairn alertcontairn_heimeijiu" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td style="width:25%">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/wine.jpg');?>" class="lotteryimg"/>
           </td>
           <td>
             <p class="alert_p" style="font-size:16px; margin-bottom:30px;">
               恭喜您！抽中彩生活特供黑莓酒一盒，果香浓郁、柔和爽口。
             </p>
             <p class="alert_p">
               <a href="javascript:void(0);" class="lookrule">两瓶不够畅饮？请进入彩之云天天团</a>
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleHeiMeiJiu">领取说明</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--黑莓酒 end-->
   <!--泰康人寿1 start-->
   <div class="alertcontairn alertcontairn_taikanglife" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               恭喜您！抽中泰康人寿一年免费意外险一份，百万保障、安全出行！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/taikanglingqu">领取</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--泰康人寿1 end-->
   
  
   <!--88元大奖 start-->
   <div class="alertcontairn alertcontairn_redpacket" style="display:none;">
     <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/pack_top.png');?>" class="lotteryimg" style="display:block;"/></div>
     <div class="textinfo packcontairn">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               恭喜您！抽中<span style="font-size:24px; color:#fff7c2" id="bonus_amount"></span>元的新年祝福红包，<br />好运连连、幸福满满！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <!-- <a href="javascript:void(0);">红包发放说明</a> -->
         <a href="/luckyApp/happinessResult">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--88元大奖 end-->


   <!--电子券-阳江海陵岛颐景 start-->
   <div class="alertcontairn e_coupon alertcontairn_hailingdao" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中阳江海陵岛颐景度假公寓礼包，舒适享受，漫游海陵。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingHailingdao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img1.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_hailingdao"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleHailingdao">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-阳江海陵岛颐景 end-->
   <!--电子券-苏州太湖天成酒店 start-->
   <div class="alertcontairn e_coupon alertcontairn_taihutiancheng" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中苏州太湖天成酒店礼包，温润江南，小楼听风雨。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingTaihutiancheng"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img2.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_taihutiancheng"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleTaihutiancheng">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-苏州太湖天成酒店 end-->
   <!--电子券-趣园私人公寓 start-->
   <div class="alertcontairn e_coupon alertcontairn_quyuan" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中趣园私人公寓大礼包，私人订制，尽享奢华。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingQuyuan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img3.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_quyuan"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleQuyuan">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-趣园私人公寓 end-->
   <!--电子券-婺源清风仙境 start-->
   <div class="alertcontairn e_coupon alertcontairn_wonderland" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中婺源清风仙境畅享礼包，揽怡人美景，享陈酿美酒。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingWonderland"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img4.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_wonderland"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleWonderland">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-婺源清风仙境 end-->
   <!--电子券-巽寮皓雅酒店 start-->
   <div class="alertcontairn e_coupon alertcontairn_haoyahotel" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中惠州巽寮皓雅酒店礼包，海天一色，纵享自然。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingHaoyahotel"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img5.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_haoyahotel"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleHaoyahotel">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-巽寮皓雅酒店 end-->
   <!--电子券-罗浮山飞云山庄 start-->
   <div class="alertcontairn e_coupon alertcontairn_flyvilla" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中惠州罗浮山彩别院礼包，山色美景，尽情畅享。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="luckyApp/christmasShuoMingFlyvilla"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img6.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_flyvilla"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="luckyApp/christmasRuleFlyvilla">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-罗浮山飞云山庄 end-->
   <!--电子券-惠州丽兹公馆 start-->
   <div class="alertcontairn e_coupon alertcontairn_lizigongguan" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中惠州丽兹公馆大礼包，带给您宾至如归的温馨。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="luckyApp/christmasShuoMingLizigongguan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img7.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_lizigongguan"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="luckyApp/christmasRuleLizigongguan">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-惠州丽兹公馆 end-->
   <!--电子券-深圳豪派特华美达 start-->
   <div class="alertcontairn e_coupon alertcontairn_huameida" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中深圳豪派特华美达豪礼包，五星级享受，欢乐跨新年。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingHuameida"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img8.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_huameida"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleHuameida">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-深圳豪派特华美达 end-->
   <!--电子券-惠州巽寮三角洲岛 start-->
   <div class="alertcontairn e_coupon alertcontairn_sanjiaozhou" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您！抽中惠州巽寮三角洲岛畅游礼包，垂钓浮潜，与海共舞。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingSanjiaozhou"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img9.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
              <p id="lucky_sanjiaozhou"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleSanjiaozhou">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-惠州巽寮三角洲岛 end-->
   <!--电子券-惠州巽寮凤池岛 start-->
   <div class="alertcontairn e_coupon alertcontairn_fenchidao" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               恭喜您!抽中惠州巽寮凤池岛度假村旅行套餐，日暖凤池，与自然零距离。<br />
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a href="/luckyApp/christmasShuoMingFengchidao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img10.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_fenchidao"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="/luckyApp/christmasRuleFengchidao">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-惠州巽寮凤池岛 end-->
 </div>
<!--弹出框 end-->
  
</div>

<script type="text/javascript"> 


$(function(){
  
 
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
    $('.opacity>div').hide();
	  
	})
	
  $('.select_down').click(function(){
	  $('.sublink').show();
	})
  document.onclick = function (event)  
  {     
	  var e = event || window.event;  
	  var elem = e.srcElement||e.target;  
	  while(elem)  
	  {   
		  if(elem.className == "select_down"||elem.className == "sublink")  
		  {  
				  return;  
		  }  
		  elem = elem.parentNode;       
	  }  
	  //隐藏div的方法  
	  $('.sublink').hide();
  }
})
</script> 
</body>
</html>
