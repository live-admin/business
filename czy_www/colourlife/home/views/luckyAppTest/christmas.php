<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="format-detection" content="telephone=no" />
		<title>Merry Christmas</title>
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
    <link href="<?php echo F::getStaticsUrl('/common/css/lucky/december/christmas.css');?>" rel="stylesheet">
	</head>
	


	<body class="christmas">
  		<div class="main">
          <p class="persons">当前共有<span id=""><?php echo $allJoin; ?></span>人参加此活动</p>
          <div class="chrismas_content" style="height:650px;">
              <div class="person" style="display:none;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/manleft.png');?>" class="lotteryimg"/></div>
              <div class="tree">
                  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/treebig.png');?>" class="lotteryimg"/>
                  <div class="linkgrounp">
                     <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
                     <p>您还有</p>
                     <p style="margin:5px 0;"><span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次拆礼盒的机会</p>
                     <p><a href="/luckyApp/christmasResult">拆盒记录</a><span class="spr">|</span><a href="/luckyApp/christmasRule">活动规则</a><span></p>
                  </div>
              </div>
              <div class="tips"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/tips.png');?>" class="lotteryimg"/></div>
          </div>
          <!--广告 start--> 
          <a href="/luckyApp/introduce" style="display:block;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/active++.jpg');?>" class="lotteryimg" /></a>
          <!-- <a href=""><img src="<?php //echo F::getStaticsUrl('/common/images/lucky/december/advertisement.jpg');?>" class="lotteryimg"/></a> -->
          <!--广告 end-->
  		</div>
     

        <!--弹出框 start-->
       <div class="opacity" style="display:none">
         <!--超过5次 start-->
         <div class="alertcontairn alertcontairn_dayfive" style="display:none">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     亲，每天只能拆5次礼盒哦，<br />欢迎明天继续！<br />快乐拆礼盒，新年大奖等您领！
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="/luckyApp/christmasResult">我的拆礼盒记录</a>
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
           
         </div>
         <!--超过5次 end-->
         <!--次数用完 start-->
         <div class="alertcontairn alertcontairn_nochance" style="display:none">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     亲，您的拆礼盒次数已经用完了。<br />您可以通过更多途径获得拆礼盒次数。
                   </p>
                   <p class="alert_p">
                     <a href="/luckyApp/christmasRule" class="lookrule">查看活动规则&nbsp;&gt;&gt;</a>
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="/luckyApp/christmasResult">我的拆礼盒记录</a>
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
           
         </div>
         <!--次数用完 end-->
         <!--谢谢参与 start-->
         <div class="alertcontairn alertcontairn_thanks" style="display:none">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <h3>谢谢参与</h3>
                   <p class="alert_p">
                     2015来了，彩生活送您祝福要您笑：一份和谐愿平安，一份安然乐逍遥；一份深情送吉祥，<span style="color:red;">新年快乐</span>！
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="/luckyApp/christmasResult">我的拆礼盒记录</a>
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>
         <!--谢谢参与 end-->

         <!--公仔 start-->
         <div class="alertcontairn alertcontairn_caiduoduo" style="display:none;">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top2.png');?>" class="lotteryimg"/></div>
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     恭喜您 ! 拆出了Q萌彩多多公仔一个，<br />快带它回家吧！
                   </p>
                   <p class="alert_p" style="font-size:12px; margin-bottom:30px;">12月20日彩之云体验专享奖品，请于大中华彩之云体验区现场领奖品，过期失效。</p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="/luckyApp/christmasResult">我的拆礼盒记录</a>
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>
         <!--公仔 end-->


         <!--黑莓酒 start-->
         <div class="alertcontairn alertcontairn_wine" style="display:none;">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo">
             <table>
               <tr>
                 <td style="width:25%">
                   <img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/wine.jpg');?>" class="lotteryimg"/>
                 </td>
                 <td>
                   <p class="alert_p" style="font-size:16px; margin-bottom:30px;">
                     恭喜您！拆出了彩生活特供黑莓酒一盒，果香浓郁、柔和爽口。
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
         <!--泰康人寿 start-->
         <div class="alertcontairn alertcontairn_life" style="display:none;">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     恭喜您！拆出了泰康人寿一年免费意外险一份，百万保障、安全出行！
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
         <!--泰康人寿 end-->         
         <!--5000大奖 start-->
         <div class="alertcontairn alertcontairn_big_5000" style="display:none;">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/pack_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo packcontairn">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     恭喜您！<span style="font-size:24px; color:#fff7c2">5000</span>元大奖近在眼前，只要您是彩生活业主或租客，待客户经理上门核实您的身份后，彩生活将发放5000元到您的红包帐号。
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="/luckyApp/christmasRule5000">红包发放说明</a>
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>
         <!--5000大奖 end-->
         <!--红包奖 start-->
         <div class="alertcontairn alertcontairn_redpacket" style="display:none;">
           <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/pack_top.png');?>" class="lotteryimg"/></div>
           <div class="textinfo packcontairn">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     恭喜您！拆出了红包<span id="lucky_redpacket" style="font-size:24px; color:#fff7c2"></span>元的新年祝福红包，好运连连、幸福满满！
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="/luckyApp/christmasResult">我的拆礼盒记录</a>
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>
         <!--红包奖 end-->
         <!--电子券-阳江海陵岛颐景 start-->
         <div class="alertcontairn e_coupon alertcontairn_hailingdao" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td colspan="2">
                   <p class="alert_p">
                     恭喜您！拆出阳江海陵岛颐景度假公寓礼包，舒适享受，漫游海陵。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingHailingdao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img1.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！拆出苏州太湖天成酒店礼包，温润江南，小楼听风雨。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingTaihutiancheng"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img2.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！拆出趣园私人公寓大礼包，私人订制，尽享奢华。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingQuyuan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img3.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！拆出婺源清风仙境畅享礼包，揽怡人美景，享陈酿美酒。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingWonderland"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img4.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！拆出惠州巽寮皓雅酒店礼包，海天一色，纵享自然。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingHaoyahotel"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img5.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！获得罗浮山彩别院礼包，山色美景，尽情畅享。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="luckyApp/christmasShuoMingFlyvilla"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img6.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您，拆出惠州丽兹公馆大礼包，带给您宾至如归的温馨。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="luckyApp/christmasShuoMingLizigongguan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img7.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！拆出了深圳豪派特华美达豪礼包，五星级享受，欢乐跨新年。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingHuameida"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img8.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您！拆出惠州巽寮三角洲岛畅游礼包，垂钓浮潜，与海共舞。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingSanjiaozhou"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img9.jpg');?>" class="lotteryimg"/></a>
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
                     恭喜您!获得惠州巽寮凤池岛旅行套餐，日暖凤池，与自然零距离。<br />
                   </p>
                   <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
                 </td>
               </tr>
               <tr>
                 <td style="width:65%; padding:0;">
                   <a href="/luckyApp/christmasShuoMingFengchidao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img10.jpg');?>" class="lotteryimg"/></a>
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
		<script type="text/javascript">
      var i= 0;
			var w=35;
			var x,y,tout;
			var t = -1;//角度
			
			var dir='manleft';//方向
			var roundx=$('.chrismas_content').width()/2; 
			var roundy=$('.chrismas_content').height()/2;
			//半径
			var r=roundx/2;
			//设置定义圆心坐标
			roundx=roundx+50;
			//roundy=roundy-roundy/2;
			roundy=roundy/2+90;
			var step_man=$('.person');
			
			//x=step_man.position().left;
			//y=step_man.position().top;
			function step(){
			  //var src='images/'+dir+'.png';
        var src = '<?php echo F::getStaticsUrl("/common/images/lucky/december");?>/'+dir+'.png';
			  step_man.children('img').attr('src',src);
			  if(t<2.3){
				t += 0.3;
				r+=10;
				x=roundx - Math.sin(t)* r;
				y=roundy - Math.cos(t)* r; 
				step_man.css({'left':x,'top':y});
				if(t>1){
					dir='manright';
					r-=r*35/100;
					}
          if(t>1.5){
          roundx+=roundx*5/100;;
          roundy+=5;
          }
			  }
			  else{
				dir='mancenter';
				if(w<180){
				  w+=20;
				  step_man.css({'width':w+'px','left':x=x-5,'top':y=y-10});
				}
				else{
				  clearTimeout(tout);
				  setTimeout(function(){
					step_man.children('img').attr('src','<?php echo F::getStaticsUrl("/common/images/lucky/december/box.png");?>');
				    step_man.children('img').addClass('endimg'); 
					$('.tips').fadeIn(500);
					},500);
				  return true;
					}
				  }
			  
			  var tout=setTimeout('step()',200);
			}
			
			
			
			$('.endimg').live('click',function(){
			  $('.opacity').hide();
        if(lottery()){
          getLuckyData();
        }
		  })
			
			$('.closeOpacity').click(function(){
			  $('.opacity').hide();	
        $('.opacity>div').hide();
        $('.tips').fadeOut(500);
        $('.tips').fadeIn(1000);
			})
			

     function letsGo(){
        step_man.show();
        step();       
      }

      $(document).ready(function(){
        setTimeout(letsGo,2000);
        
      })
      

			
			


      function lottery(){
        var postCan=parseInt($("#luckyTodayCan").text());
        var custCan=parseInt($("#luckyCustCan").text());
        
          if(custCan<1){
              $('.opacity').show();
              $('.alertcontairn_nochance').show();
              return false;
          }
          else if(postCan<1){
              $('.opacity').show();
              $('.alertcontairn_dayfive').show();
              return false;
          }else{
             return true;
          }
         
      }



      function getLuckyData() { //得到数据
            $.ajax({
              type: 'POST',
              url: '/luckyApp/doShakeLuckyNew',
              data: 'actid=9',
              dataType: 'json',
              async: false,
              error: function () {
                $('.').show();
                $('.alertcontairn_thanks').show();
              },
              success: function (data) {
                var postCan = parseInt($('#luckyTodayCan').text());
                var custCan = parseInt($('#luckyCustCan').text());
                $('#luckyTodayCan').text(postCan-1);
                $('#luckyCustCan').text(custCan-1);
                if (data.success == 0 && data.data.location == 1) {
                  location.href = data.data.href;
                  return;
                }
                if (data.success == 0) {
                  $('.opacity').show();
                  $('.alertcontairn_thanks').show();
                } else {
                  getPrize = data.data.result;
                  showPackage(getPrize);
                }
              }
            });
        }

        //根据结果弹出红包
        function showPackage(prize) {        
            var prizeid=parseInt(prize.id);
            if (prizeid==88) {//谢谢参与
                $('.opacity').show();
                $('.alertcontairn_thanks').show();
            } else if (prizeid==86) {//泰康人寿
                $('.opacity').show();
                $('.alertcontairn_life').show();
            } else if(prizeid==85){//5000大奖红包
                $('.opacity').show();
                $('.alertcontairn_big_5000').show();
            } else if(prizeid>=80&&prizeid<=84){//其他红包
                $('.opacity').show();
                $('#lucky_redpacket').html(prize.rednum);
                $('.alertcontairn_redpacket').show();
            } else if(prizeid==87){//黑莓酒
                $('.opacity').show();
                $('.alertcontairn_wine').show();
            } else if(prizeid==89){//华美达优惠码
                $('.opacity').show();
                $('#lucky_huameida').html(prize.lucky_code);
                $('.alertcontairn_huameida').show();
            }else if(prizeid==90){//趣园酒店公寓优惠码
                $('.opacity').show();
                $('#lucky_quyuan').html(prize.lucky_code);
                $('.alertcontairn_quyuan').show();
            }else if(prizeid==91){//清风仙境优惠码
                $('.opacity').show();
                $('#lucky_wonderland').html(prize.lucky_code);
                $('.alertcontairn_wonderland').show();
            }else if(prizeid==92){//海岛三角洲优惠码
                $('.opacity').show();
                $('#lucky_sanjiaozhou').html(prize.lucky_code);
                $('.alertcontairn_sanjiaozhou').show();
            }else if(prizeid==93){//苏州太湖天成优惠码
                $('.opacity').show();
                $('#lucky_taihutiancheng').html(prize.lucky_code);
                $('.alertcontairn_taihutiancheng').show();
            }else if(prizeid==94){//海陵岛优惠码
                $('.opacity').show();
                $('#lucky_hailingdao').html(prize.lucky_code);
                $('.alertcontairn_hailingdao').show();
            }else if(prizeid==95){//惠州丽兹公馆优惠码
                $('.opacity').show();
                $('#lucky_lizigongguan').html(prize.lucky_code);
                $('.alertcontairn_lizigongguan').show();
            }else if(prizeid==96){//皓雅养生度假酒店优惠码
                $('.opacity').show();
                $('#lucky_haoyahotel').html(prize.lucky_code);
                $('.alertcontairn_haoyahotel').show();
            }else if(prizeid==97){//罗浮山公路度假山庄优惠码
                $('.opacity').show();
                $('#lucky_flyvilla').html(prize.lucky_code);
                $('.alertcontairn_flyvilla').show();
            }else if(prizeid==98){//凤池岛优惠码
                $('.opacity').show();
                $('#lucky_fenchidao').html(prize.lucky_code);
                $('.alertcontairn_fenchidao').show();
            }else if(prizeid==99){//Q萌彩多多公仔
                $('.opacity').show();
                $('.alertcontairn_caiduoduo').show();
            }else{//异常
                $('.opacity').show();
                $('.alertcontairn_thanks').show();
            }
        }


		</script>
		
	</body>

</html>