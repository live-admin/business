<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>幸福中国行</title>
<!-- <link href="css/lottery.css" rel="stylesheet" type="text/css">
<script src="js/jquery.min.js"></script>
<script src="js/jQueryRotate.2.2.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script src="js/gunDong.js"></script> -->
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/happiness/lottery.css?time=76543210'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.web.js?datetime=123456'); ?>"></script>
<script type="text/javascript">
//  $(function(){
// 	function center(obj) {
// 	  var screenWidth = $(window).width();
// 	  var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
// 	  var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
// 	  var objLeft = (screenWidth - obj.width())/2 ;
// 	  var objTop = (screenHeight - obj.height())/2 + scrolltop;
// 	  obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
// 	  //浏览器窗口大小改变时
// 	  $(window).resize(function() {
// 	  screenWidth = $(window).width();
// 	  screenHeight = $(window).height();
// 	  scrolltop = $(document).scrollTop();
// 	  objLeft = (screenWidth - obj.width())/2 ;
// 	  objTop = (screenHeight - obj.height())/2 + scrolltop;
// 	  obj.css({left: objLeft + 'px', top: objTop + 'px'});
// 	  });
	 
// 	} 
	
// 	$("#startbtn").rotate({
// 		bind:{
// 			click:function(){
// 				var a = Math.floor(Math.random() * 360);
// 				 $(this).rotate({
// 					 	duration:3000,
// 					 	angle:0, 
//             			animateTo:3600+a,
// 						easing: $.easing.easeOutSine,
// 						callback: function(){
// 							$('.opacity').show();
// 							center($('.opacity>div').eq(3));
// 						}
// 				 });
// 			}
// 		}
// 	});
	
// });
	
	
   
</script>
<?php $domain = F::getCommunityDomain(); $domain  = empty($domain)?'ckcyds':F::getCommunityDomain();?>

</head> 
 
<body style="position:relative;"> 
   <div class="web_christmas">
        <div class="web_christmas_content">
          <!--拆礼盒 start-->
          <div class="web_s_box">
            <h3 class="christmas_tt">
              <a href="/luckyAppWeb/happinessRule">&gt;&gt; 查看活动规则</a>
              <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
              您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次机会，已有<span><?php echo $allJoin; ?></span>人次参加
              <img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/money.png');?>" class="money">
            </h3>
            <div class="web_s_content clearfix">
              <div class="lotterylist">
                <h3>
                 <a href="/luckyAppWeb/happinessResult">&gt;&gt; 查看抽奖记录</a>
                 最新中奖
                </h3>
                <div class="lotterylsit_ct">
                  <dl id="ticker">
                    <?php foreach($listResutl as $result){ ?>  
                      <dt><?php echo $result['msg']; ?></dt>
                    <?php } ?>  
                  </dl>
                </div>
              </div>
              <div class="web_christmas_img">
                <div id="start">
                  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/pointweb.png');?>" id="startbtn" class="cup_start lotteryimg"/>
                </div>
              </div>
              
            </div> 
                   
          </div>
          <!--拆礼盒 end-->
          <!--规则说明 start-->
          <div class="indexrule">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/webimg1.jpg');?>" class="lotteryimg"/>
            <a class="imglink" href="http://<?php echo $domain;?>.c.colourlife.com/activity/createProperty">&gt;&gt;立即参加</a>
          </div>
          <div class="indexrule">
            <a href="http://<?php echo $domain;?>.c.colourlife.com/cheap"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/webimg2.jpg');?>" class="lotteryimg"/></a>
            <a class="imglink" href="http://<?php echo $domain;?>.c.colourlife.com/cheap">&gt;&gt;进入天天团</a>
          </div>
          <!--规则说明 end-->
         
          <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/bot.jpg');?>"/></div>
        </div>

   
   
   </div>
   
   
   <!--弹出框 start-->
   <div class="opacity opacityweb" style="display:none">
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
           <a href="/luckyAppWeb/happinessResult">我的抽奖记录</a>
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
                 <a href="/luckyAppWeb/happinessRule" class="lookrule">查看活动规则&nbsp;&gt;&gt;</a>
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/happinessResult">我的抽奖记录</a>
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
           <a href="/luckyAppWeb/happinessResult">我的抽奖记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>
         </div>
       </div>
     </div>
     <!--谢谢参与 end-->
     
     <!--黑莓酒 start-->
     <div class="alertcontairn alertcontairn_heimeijiu" style="display:none">
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
                 <a href="http://<?php echo $domain;?>.c.colourlife.com/goods/1459" class="lookrule">两瓶不够畅饮？请进入彩之云天天团</a>
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_wine">领取说明</a>
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
           <a href="/luckyAppWeb/lingqutaikang">领取</a>
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
           <a href="/luckyAppWeb/happinessResult">我的抽奖记录</a>
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
               <a href="/luckyAppWeb/view?tpl=yi_jing"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img1.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_hailingdao"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_yijing">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=tai_hu"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img2.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_taihutiancheng"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_taihu">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=quyuan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img3.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_quyuan"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_quyuan">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=qing_feng"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img4.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_wonderland"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_qingfeng">使用规则</a>
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
               <<a href="/luckyAppWeb/view?tpl=hao_ya"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img5.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_haoyahotel"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_haoya">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=luofushang"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img6.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_flyvilla"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_luofushang">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=lizhi"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img7.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_lizigongguan"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_lizhi">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=huameida"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img8.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_huameida"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_huameida">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=shanjiaozhou"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img9.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_sanjiaozhou"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_xunliaowan">使用规则</a>
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
               <a href="/luckyAppWeb/view?tpl=fengchidao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/img10.jpg');?>" class="lotteryimg"/></a>
             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p id="lucky_fenchidao"></p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_fengchidao">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>
         </div>
       </div>
     </div>
     <!--电子券-惠州巽寮凤池岛 end-->
   </div>
   <!--弹出框 end-->
   
   <script type="text/javascript">

	 //  var i= 0;
	 //  var w=78;
	 //  var x,y,tout;
	 //  var t = -1;//角度
	  
	 //  var dir='manleft';//方向
	 //  var roundx=440; 
	 //  var roundy=550;
	 //  //半径
	 //  var r=350;
	 //  //设置定义圆心坐标
	 //  var step_man=$('.person');
	 //  function step(){
		// var src='images/'+dir+'.png';
		// step_man.children('img').attr('src',src);
		// if(t<2.5){
		//   t += 0.3;
		//   x=roundx - Math.sin(t)* r;
		//   y=roundy - Math.cos(t)* r; 
		//   step_man.css({'left':x,'top':y});
		//   if(t>1){
		// 	  dir='manright';
		// 	  r-=75;
		// 	  }
		//   if(t>1.5){
		// 	  roundx+=5;
		// 	  roundy-=15;
		// 	  }
		// }
		// else{
		//   dir='mancenter';
		//   if(w<350){
		// 	w+=35;
		// 	step_man.css({'width':w+'px','left':x=x+1});
		//   }
		//   else{
		// 	clearTimeout(tout);
		// 	setTimeout(function(){
		// 	  step_man.children('img').attr('src','images/box.png');
		// 	  step_man.children('img').addClass('endimg'); 
		// 	  $('.tips').fadeIn(500);
		// 	  },500);
		// 	return true;
		// 	  }
		// 	}
		
		// var tout=setTimeout('step()',200);
	 //  }
	  
	  
	  
	 //  $('.endimg').live('click',function(){
		// $('.opacity').show();
		// center($('.alertcontairn').eq(1));
	 //  })
	  
	  $('.closeOpacity').click(function(){
    $('.opacity').hide();
    $('.opacity>div').hide();
    
  })
	  
	 //  $(document).ready(function(){
		//   step_man.show();
		//   step();
		  
	 //  })
	  
	  
  </script>
</body> 

</html>