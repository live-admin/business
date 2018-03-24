<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>拆礼盒中百万大奖</title>
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/december/christmas.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js');?>"></script>
<script type="text/javascript">
  
  //弹出框居中函数
	 function center(obj) {
	  var screenWidth = $(window).width();
	  var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
	  var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
	  var objLeft = (screenWidth - obj.width())/2 ;
	  var objTop = (screenHeight - obj.height())/2 + scrolltop;
	  obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
	  //浏览器窗口大小改变时
	  $(window).resize(function() {
	  screenWidth = $(window).width();
	  screenHeight = $(window).height();
	  scrolltop = $(document).scrollTop();
	  objLeft = (screenWidth - obj.width())/2 ;
	  objTop = (screenHeight - obj.height())/2 + scrolltop;
	  obj.css({left: objLeft + 'px', top: objTop + 'px'});
	  });
	 
	} 
	
	
   
</script>

<?php $domain = F::getCommunityDomain(); $domain  = empty($domain)?'ckcyds':F::getCommunityDomain();?>

</head> 
 
<body style="position:relative;"> 
   <div class="web_christmas">
      <div class="background_part2">
        <div class="web_christmas_content">
          <!--拆礼盒 start-->
          <div class="web_s_box">
            <h3 class="christmas_tt">
              <a href="#christmasRule">&gt;&gt; 查看活动规则</a>
              <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
              您有 <span id="luckyCustCan"><?php echo $luckyCustCan;?></span> 次机会，已有 <span id=""><?php echo $allJoin; ?></span> 人次参加
            </h3>
            <div class="web_s_content clearfix">
              <div class="lotterylist">
                <h3>
                 <a target="_blank" href="/luckyAppWeb/ChristmasResultWeb">&gt;&gt; 查看拆礼盒记录</a>
                 最新中奖
                </h3>
                <div class="lotterylsit_ct">
                  <dl id="ticker">
                    <?php foreach($listResult as $result){ ?>  
                      <dt><?php echo $result['msg']; ?></dt>
                    <?php } ?>
                  </dl>
                </div>
              </div>
              <div class="web_christmas_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/tree_web.jpg');?>" class="lotteryimg"/></div>
              
            </div> 
                   
          </div>
          <!--拆礼盒 end-->
          <!--规则说明 start-->
          <div class="indexrule">
            <a name="christmasRule"></a>
            <div class="rule_content">
               <h3>活动规则:</h3>
               <ul>
                 <li>1、本期活动时间：2014年12月20日至2015年1月31日</li>
                 <li>2、拆礼盒资格说明：
                     <p>(1)老用户每天登录App或网站可获得1次拆礼盒机会 ；</p>
                     <p>(2)新注册用户登录App或网站成功后马上获得10次拆礼盒机会；</p>
                     <p>(3)用户推荐好友注册成功可获得5次拆礼盒机会；</p>
                     <p>(4)用户任意交易成功（充值、商品交易、物业费、停车费）获得5次拆礼盒机会；</p>
                     <p>(5)用户使用投诉报修功能即可获得1次拆礼盒机会；</p>
                     <p>(6)每天每位用户最多可拆礼盒5次。</p>
                 </li>
                 <li>3、红包可用于：缴纳物业费和停车费，预缴物业费，商品交易，手机充值。</li>
                 <li>4、用户可以在"我-我的红包"中查看红包余额。</li>
                 <li>5、本次活动仅限彩生活业主（彩生活服务小区内的房产所有人或租赁人）及其直系亲属（配偶、父母、子女）参加，彩生活有权对中奖人进行身份核实，并享有法律范围内的最终解释权。</li>
               </ul>
            </div>
          </div>
          <div class="indexrule">
            <div class="rule_content">
               <h3>奖项设置:</h3>
               <ul>
                 <li>1、100万个红包，红包金额大小不等，金额最高为5000元大奖。</li>
                 <li>2、每名5000元大奖得主可携带家属（包括大奖得主，总共两大一小）出席彩生活年度盛会，年会现场将在公证人员监督下抽取百万现金大奖，且免费提供五星级酒店住宿，外地业主将提供往返交通费。</li>
                 <li>3、10000份泰康人寿一年免费意外保险, "飞常保"和"铁定保"二选一。</li>
                 <li>4、10000瓶彩生活特供养生黑莓酒。</li>
                 <li>5、100万张景点或酒店电子优惠劵。</li>
                 
               </ul>
            </div>
          </div>
          <!--规则说明 end-->
          <div class="place">
            <div class="person" style="display:none;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/manleft.png');?>" class="lotteryimg"/></div>
          </div>
          <div class="tips_web"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/tips_web.png');?>"  class="lotteryimg" /></div>
          <!-- <a href="#"><img src="<?php //echo F::getStaticsUrl('/common/images/lucky/december/advertisement1.jpg');?>"/></a> -->
          <a href="/luckyAppWeb/introduce" target="_blank" style="display:block;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/active_web++.jpg');?>" class="lotteryimg" /></a>
          <a href="http://www.oznerwater.com/Api/saleredirect.aspx?<?php echo $completeURL;?>" style="display:block; margin-bottom:10px;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/666-03.jpg');?>" class="lotteryimg" /></a>
          <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/bottom.jpg');?>"/></div>
        </div>
      </div>
   
   
   </div>
   
   
   <!--弹出框 start-->
   <div class="opacity opacityweb" style="display:none">
     <!--超过5次 start-->
     <div class="alertcontairn alertcontairn_dayfive" style="display:none">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 亲，每天只能拆5次礼盒哦，欢迎明天继续！<br />快乐拆礼盒，新年大奖等您领！
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/ChristmasResultWeb">我的拆礼盒记录</a>
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
                 <a href="/luckyAppWeb/view?tpl=rules" class="lookrule">查看活动规则&nbsp;&gt;&gt;</a>
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/ChristmasResultWeb">我的拆礼盒记录</a>
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
           <a href="/luckyAppWeb/ChristmasResultWeb">我的拆礼盒记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>
         </div>
       </div>
     </div>
     <!--谢谢参与 end-->
     <!--公仔 start-->
     <div class="alertcontairn alertcontairn_caiduoduo" style="display:none">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/alert_top2.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 恭喜您 ! 拆出了Q萌彩多多公仔一个，<br />快带它回家吧！
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/ChristmasResultWeb">我的拆礼盒记录</a>
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
                 恭喜您！　拆出了彩生活特供黑莓酒一盒，果香浓郁、柔和爽口。
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
           <a href="/luckyAppWeb/lingqutaikang">领取</a>
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
                 恭喜您！<span style="font-size:24px; color:#fff7c2">5000</span>元大奖近在眼前，只要您是彩生
                 活业主或租客，待客户经理上门核实您的身份后，彩生活将发放5000元到您的红包帐号。
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/view?tpl=shuoming_5000">红包发放说明</a>
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
                 恭喜您！拆出了红包<span id="lucky_redpacket" style="font-size:24px; color:#fff7c2"></span>元的新年祝福红包，<br />好运连连、幸福满满！
               </p>
             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="/luckyAppWeb/ChristmasResultWeb">我的拆礼盒记录</a>
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
               <a href="/luckyAppWeb/view?tpl=yi_jing"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img1.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！拆出苏州太湖天成酒店礼包，温润江南，小楼听风雨。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=tai_hu"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img2.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！拆出趣园私人公寓大礼包，私人订制，尽享奢华。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=quyuan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img3.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！拆出婺源清风仙境畅享礼包，揽怡人美景，享陈酿美酒。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=qing_feng"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img4.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！拆出惠州巽寮皓雅酒店礼包，海天一色，纵享自然。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=hao_ya"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img5.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！获得罗浮山彩别院礼包，山色美景，尽情畅享。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=luofushang"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img6.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您，拆出惠州丽兹公馆大礼包，带给您宾至如归的温馨。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=lizhi"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img7.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！拆出了深圳豪派特华美达豪礼包，五星级享受，欢乐跨新年。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=huameida"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img8.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您！拆出惠州巽寮三角洲岛畅游礼包，垂钓浮潜，与海共舞。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=shanjiaozhou"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img9.jpg');?>" class="lotteryimg"/></a>
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
                 恭喜您!获得惠州巽寮凤池岛旅行套餐，日暖凤池，与自然零距离。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/boxtips.jpg');?>" class="boxtips"/></p>
             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href="/luckyAppWeb/view?tpl=fengchidao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/december/img10.jpg');?>" class="lotteryimg"/></a>
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
	  var i= 0;
	  var w=78;
	  var x,y,tout;
	  var t = -1;//角度
	  
	  var dir='manleft';//方向
	  var roundx=440; 
	  var roundy=550;
	  //半径
	  var r=350;
	  //设置定义圆心坐标
	  var step_man=$('.person');
	  function step(){
		// var src='images/'+dir+'.png';
    var src = '<?php echo F::getStaticsUrl("/common/images/lucky/december");?>/'+dir+'.png';
		step_man.children('img').attr('src',src);
		if(t<2.5){
		  t += 0.3;
		  x=roundx - Math.sin(t)* r;
		  y=roundy - Math.cos(t)* r; 
		  step_man.css({'left':x,'top':y});
		  if(t>1){
			  dir='manright';
			  r-=75;
			  }
		  if(t>1.5){
			  roundx+=5;
			  roundy-=15;
			  }
		}
		else{
		  dir='mancenter';
		  if(w<350){
			w+=35;
			step_man.css({'width':w+'px','left':x=x+1});
		  }
		  else{
			clearTimeout(tout);
			setTimeout(function(){
			  step_man.children('img').attr('src','<?php echo F::getStaticsUrl("/common/images/lucky/december/box.png");?>');
			  step_man.children('img').addClass('endimg'); 
			  $('.tips_web').fadeIn(500);
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
  		// $('.opacity').show();
  		// center($('.alertcontairn').eq(1));
	  })
	  
	  $('.closeOpacity').click(function(){
		    $('.opacity').hide(); 
        $('.opacity>div').hide();
        $('.tips_web').fadeOut(500);
        $('.tips_web').fadeIn(1000);
	  })
	  
	  $(document).ready(function(){
		  step_man.show();
		  step();
		  
	  })
	  
	  function lottery(){
        var postCan=parseInt($("#luckyTodayCan").text());
        var custCan=parseInt($("#luckyCustCan").text());
        
          if(custCan<1){
              $('.opacity').show();
              center($('.alertcontairn_nochance'));
              //$('.alertcontairn_nochance').show();
              return false;
          }
          else if(postCan<1){
              $('.opacity').show();
              center($('.alertcontairn_dayfive'));
              //$('.alertcontairn_dayfive').show();
              return false;
          }else{
             return true;
          }
         
      }



      function getLuckyData() { //得到数据
            $.ajax({
              type: 'POST',
              url: '/luckyAppWeb/doShakeLucky',
              data: 'actid=9',
              dataType: 'json',
              async: false,
              error: function () {
                $('.').show();
                center($('.alertcontairn_thanks'));
                //$('.alertcontairn_thanks').show();
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
                  center($('.alertcontairn_thanks'));
                  //$('.alertcontairn_thanks').show();
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
                center($('.alertcontairn_thanks'));
                //$('.alertcontairn_thanks').show();
            } else if (prizeid==86) {//泰康人寿
                $('.opacity').show();
                center($('.alertcontairn_life'));
                //$('.alertcontairn_life').show();
            } else if(prizeid==85){//5000大奖红包
                $('.opacity').show();
                center($('.alertcontairn_big_5000'));
                //$('.alertcontairn_big_5000').show();
            } else if(prizeid>=80&&prizeid<=84){//其他红包
                $('.opacity').show();
                $('#lucky_redpacket').html(prize.rednum);
                center($('.alertcontairn_redpacket'));
                //$('.alertcontairn_redpacket').show();
            } else if(prizeid==87){//黑莓酒
                $('.opacity').show();
                center($('.alertcontairn_wine'));
                //$('.alertcontairn_wine').show();
            } else if(prizeid==89){//华美达优惠券
                $('.opacity').show();
                $('#lucky_huameida').html(prize.lucky_code);
                center($('.alertcontairn_huameida'));
                //$('.alertcontairn_huameida').show();
            }else if(prizeid==90){//趣园酒店公寓优惠券
                $('.opacity').show();
                $('#lucky_quyuan').html(prize.lucky_code);
                center($('.alertcontairn_quyuan'));
                //$('.alertcontairn_quyuan').show();
            }else if(prizeid==91){//清风仙境优惠券
                $('.opacity').show();
                $('#lucky_wonderland').html(prize.lucky_code);
                center($('.alertcontairn_wonderland'));
                //$('.alertcontairn_wonderland').show();
            }else if(prizeid==92){//海岛三角洲优惠券
                $('.opacity').show();
                $('#lucky_sanjiaozhou').html(prize.lucky_code);
                center($('.alertcontairn_sanjiaozhou'));
                //$('.alertcontairn_sanjiaozhou').show();
            }else if(prizeid==93){//苏州太湖天成优惠券
                $('.opacity').show();
                $('#lucky_taihutiancheng').html(prize.lucky_code);
                center($('.alertcontairn_taihutiancheng'));
                //$('.alertcontairn_taihutiancheng').show();
            }else if(prizeid==94){//海陵岛优惠券
                $('.opacity').show();
                $('#lucky_hailingdao').html(prize.lucky_code);
                center($('.alertcontairn_hailingdao'));
                //$('.alertcontairn_hailingdao').show();
            }else if(prizeid==95){//惠州丽兹公馆优惠券
                $('.opacity').show();
                $('#lucky_lizigongguan').html(prize.lucky_code);
                center($('.alertcontairn_lizigongguan'));
                //$('.alertcontairn_lizigongguan').show();
            }else if(prizeid==96){//皓雅养生度假酒店优惠券
                $('.opacity').show();
                $('#lucky_haoyahotel').html(prize.lucky_code);
                center($('.alertcontairn_haoyahotel'));
                //$('.alertcontairn_haoyahotel').show();
            }else if(prizeid==97){//罗浮山公路度假山庄优惠券
                $('.opacity').show();
                $('#lucky_flyvilla').html(prize.lucky_code);
                center($('.alertcontairn_flyvilla'));
                //$('.alertcontairn_flyvilla').show();
            }else if(prizeid==98){//凤池岛优惠券
                $('.opacity').show();
                $('#lucky_fenchidao').html(prize.lucky_code);
                center($('.alertcontairn_fenchidao'));
                //$('.alertcontairn_fenchidao').show();
            }else if(prizeid==99){//Q萌彩多多公仔
                $('.opacity').show();
                center($('.alertcontairn_caiduoduo'));
                //$('.alertcontairn_caiduoduo').show();
            }else{//异常
                $('.opacity').show();
                center($('.alertcontairn_thanks'));
                //$('.alertcontairn_thanks').show();
            }
        }



  </script>
</body> 

</html>