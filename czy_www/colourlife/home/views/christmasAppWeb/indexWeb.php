<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>百万大奖第五波</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/december/christmas.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/christmasappweb/js/gunDong.js'); ?>"></script> 
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


</head> 
 
<body style="position:relative;"> 
   <div class="web_christmas">
      <div class="background_part2">
        <div class="web_christmas_content">
          <!--拆礼盒 start-->
          <div class="web_s_box">
            <h3 class="christmas_tt">
              <a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/ChristmasAppWeb/RobRule');?>">&gt;&gt; 查看活动规则</a>
              您有 <?php echo $luckyCustCan;?> 次机会，已有 <?php echo $allJoin; ?> 人次参加            </h3>
            <div class="web_s_content clearfix">
              <div class="lotterylist">
                <h3>
                 <a href="">&gt;&gt; 查看摇奖情况</a>
                 最新中奖                </h3>
                <div class="lotterylsit_ct">
                  <dl id="ticker">
                    <!--<dt>恭喜金陵天成小区业主陈先生获得5000元红包大奖！</dt>
                    <dt>恭喜汇港名苑小区业主赖小姐获得5000元红包大奖！</dt>
                    <dt>恭喜高行绿洲四期业主沈**获得了100元红包</dt>
                    <dt>恭喜东洲花园业主获得了50元红包</dt>
                    <dt>恭喜东洲花园业主获得了50元红包</dt>
                    <dt>恭喜135****5783获得了10000元红包</dt>-->
					<?php foreach($listResutl as $result){ ?>  
						<dt><?php echo $result['msg']; ?></dt>
					 <?php } ?> 
                  </dl>
                </div>
              </div>
              <div class="web_christmas_img"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/tree_web.jpg');?>" class="lotteryimg"/></div>
            </div> 
          </div>
          <!--拆礼盒 end-->
          <!--规则说明 start-->
          <div class="indexrule">
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
                 <li>3、饭票可用于：缴纳物业费和停车费，预缴物业费，商品交易，手机充值。</li>
                 <li>4、用户可以在"我-我的饭票"中查看饭票余额。</li>
                 <li>5、本次活动仅限彩生活业主（彩生活服务小区内的房产所有人或租赁人）及其直系亲属（配偶、父母、子女）参加，彩生活有权对中奖人进行身份核实，并享有法律范围内的最终解释权。</li>
               </ul>
            </div>
          </div>
          <div class="indexrule">
            <div class="rule_content">
               <h3>奖项设置:</h3>
               <ul>
                 <li>1、100万个饭票，饭票金额大小不等，金额最高为5000元大奖。</li>
                 <li>2、每名5000元大奖得主可携带家属（包括大奖得主，总共两大一小）出席彩生活年度盛会，年会现场将在公证人员监督下抽取百万现金大奖，且免费提供五星级酒店住宿，外地业主将提供往返交通费。</li>
                 <li>3、10000份泰康人寿一年免费意外保险, "飞常保"和"铁定保"二选一。</li>
                 <li>4、10000瓶产地直供"绿博士"黑莓酒。</li>
                 <li>5、10万张景点或酒店电子优惠劵。</li>
               </ul>
            </div>
          </div>
          <!--规则说明 end-->
          <div class="place">
            <div class="person" style="display:none;"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/manleft.png');?>" class="lotteryimg"/></div>
          </div>
          
          <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/advertisement1.jpg');?>"/></a>
          <div><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/bottom.jpg');?>"/></div>
        </div>
      </div>
   
   
   </div>
   
   
   <!--弹出框 start-->
   <div class="opacity opacityweb" style="display:none">
     <!--超过5次 start-->
     <div class="alertcontairn" style="display:none">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 亲，每天只能拆5次礼盒哦，欢迎明天继续！<br />快乐拆礼盒，新年大奖等您领！               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">我的拆礼盒记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--超过5次 end-->
     <!--次数用完 start-->
     <div class="alertcontairn" style="display:none">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 亲，您的拆礼盒次数已经用完了。<br />您可以通过更多途径获得拆礼盒次数。               </p>
               <p class="alert_p">
                 <a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/ChristmasAppWeb/RobRule');?>" class="lookrule">查看活动规则&nbsp;&gt;&gt;</a>               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">我的拆礼盒记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--次数用完 end-->
     <!--谢谢参与 start-->
     <div class="alertcontairn" style="display:none">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <h3>谢谢参与</h3>
               <p class="alert_p">
                 2015来了，彩生活送您祝福要您笑：一份和谐愿平安，一份安然乐逍遥；一份深情送吉祥，新年快乐！               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">我的拆礼盒记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--谢谢参与 end-->
     <!--公仔 start-->
     <div class="alertcontairn" style="display:none">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top2.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 恭喜您 ! 拆出了Q萌彩多多公仔一个，<br />快带它回家吧！               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">我的拆礼盒记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--公仔 end-->
     <!--黑莓酒 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td style="width:25%">
               <img src="<?php echo F::getStaticsUrl('/christmasappweb/images/wine.jpg');?>" class="lotteryimg"/>             </td>
             <td>
               <p class="alert_p" style="font-size:16px; margin-bottom:30px;">
                 恭喜您！　拆出了彩生活定制黑莓酒一盒，果香浓郁、柔和爽口。               </p>
               <p class="alert_p">
                 <a href="" class="lookrule">两瓶不够畅饮？请进入彩之云天天团</a>               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">领取说明</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--黑莓酒 end-->
     <!--泰康人寿1 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 恭喜您！拆出了泰康人寿一年免费意外险一份，百万保障、安全出行！               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">领取</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--泰康人寿1 end-->
     <!--泰康人寿2 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/alert_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 您的投保信息已经提交成功，泰康人寿将会在3个工作日内对投保信息进行审核处理；如果免费领取保险成功，泰康保险将会发送成功短信至投保人手机，并发送电子保单至投保人邮箱。               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">我的拆礼盒记录</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--泰康人寿2 end-->
     
     <!--5000大奖 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/pack_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo packcontairn">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 恭喜您！<span style="font-size:24px; color:#fff7c2">5000</span>元大奖近在眼前，只要您是彩生
                 活业主或租客，待客户经理上门核实您的身份后，彩
                 生活将发放5000元到您的饭票帐号。               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">饭票发放说明</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--5000大奖 end-->
     <!--88元大奖 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alert_top"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/pack_top.png');?>" class="lotteryimg"/></div>
       <div class="textinfo packcontairn">
         <table>
           <tr>
             <td>
               <p class="alert_p">
                 恭喜您！拆出了<span style="font-size:24px; color:#fff7c2">88</span>元的新年祝福饭票，<br />好运连连、幸福满满！               </p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">饭票发放说明</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--88元大奖 end-->
     <!--电子券-阳江海陵岛颐景 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出阳江海陵岛颐景度假公寓礼包，舒适享受，漫游海陵。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img1.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-阳江海陵岛颐景 end-->
     <!--电子券-苏州太湖天成酒店 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出苏州太湖天成酒店礼包，温润江南，小楼听风雨。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img2.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-苏州太湖天成酒店 end-->
     <!--电子券-趣园私人公寓 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出趣园私人公寓大礼包，私人订制，尽享奢华。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img3.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-趣园私人公寓 end-->
     <!--电子券-婺源清风仙境 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出婺源清风仙境畅享礼包，揽怡人美景，享陈酿美酒。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img4.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-婺源清风仙境 end-->
     <!--电子券-巽寮皓雅酒店 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出惠州巽寮皓雅酒店礼包，海天一色，纵享自然。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img5.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-巽寮皓雅酒店 end-->
     <!--电子券-罗浮山飞云山庄 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！获得罗浮山飞云山庄礼包，山色美景，尽情畅享。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img6.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-罗浮山飞云山庄 end-->
     <!--电子券-惠州丽兹公馆 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您，拆出惠州丽兹公馆大礼包，带给您宾至如归的温馨。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img7.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-惠州丽兹公馆 end-->
     <!--电子券-深圳豪派特华美达 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出了深圳豪派特华美达豪礼包，五星级享受，欢乐跨新年。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img8.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-深圳豪派特华美达 end-->
     <!--电子券-惠州巽寮三角洲岛 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您！拆出惠州巽寮三角洲岛畅游礼包，垂钓浮潜，与海共舞。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img9.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
       </div>
     </div>
     <!--电子券-惠州巽寮三角洲岛 end-->
     <!--电子券-惠州巽寮凤池岛 start-->
     <div class="alertcontairn e_coupon" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td colspan="2">
               <p class="alert_p">
                 恭喜您!获得惠州巽寮凤池岛旅行套餐，日暖凤池，与自然零距离。<br />
               </p>
               <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/boxtips.jpg');?>" class="boxtips"/></p>             </td>
           </tr>
           <tr>
             <td style="width:65%; padding:0;">
               <a href=""><img src="<?php echo F::getStaticsUrl('/christmasappweb/images/img10.jpg');?>" class="lotteryimg"/></a>             </td>
             <td class="yhm">
               <p>优惠码：</p>
               <p>1234567890</p>             </td>
           </tr>
         </table>
         <div class="pop_btn">
           <a href="javascript:void(0);">使用规则</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>         </div>
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
	  
	  
	  
	  var	message,nums,is_show,gua_result;
	  function getLuckyData() { //得到数据
	          $.ajax({
	            type: 'POST',
	            url: '/ChristmasAppWeb/doShakeLucky',
	            data: 'actid=8',
	            dataType: 'json',
	            async: false,
	            error: function () {
	              nums = 0;
	              message = "谢谢惠顾";
	              is_show = 0;
	              gua_result = {"nums":nums,"message":message,"is_show":is_show};
	            },
	            success: function (data) {
	             /* var postCan = parseInt($('#luckyTodayCan').text());
	              var custCan = parseInt($('#luckyCustCan').text());
	              $('#luckyTodayCan').text(postCan-1);
	              $('#luckyCustCan').text(custCan-1);*/
	              if (data.success == 0 && data.data.location == 1) {
	                location.href = data.data.href;
	                return;
	              }
	              if (data.success == 0) {
	                nums = 0;
	                message = "谢谢惠顾";
	                is_show = 0;
	                gua_result = {"nums":nums,"message":message,"is_show":is_show};
	              } else {
	                getPrize = data.data.result;
	                gua_result = showPackag(getPrize);
	              }
	            }
	          });				
				return gua_result;
	    	}

	  
	  
	  
	  
	  
	  	    	//根据结果弹出红包
		    function showPackag(prize) {        
		        var prizeid=parseInt(prize.id);
		        if (prizeid==79) {
		          nums = 0;
		          message = "谢谢惠顾";
		          is_show = 0;
		        } else if (prizeid==77) {
		          nums = 1;
		          message = "泰康人寿";
		          is_show = 1;
		        } else if(prizeid==76){
		          nums = 2;  
		          message = "5000元大奖";
		          is_show = 1;
		        } else if(prizeid>=71&&prizeid<=75){
		          nums = 3;
		          message = prize.rednum+"元饭票";
		          is_show = 0;
		        } else if(prizeid==78){
		          nums = 6;
		          message = "黑莓酒一瓶";
		          is_show = 1;
		        }else{
		          nums = 0;
		          message = "谢谢惠顾"; 
		          is_show = 0;
		        }
		        gua_result = {"nums":nums,"message":message,"is_show":is_show};
		        return gua_result;
		    }
	  
	  
	  
	  
	  
	  
	  
	  function step(){
		var src='<?php echo F::getStaticsUrl('/christmasappweb/images/');?>'+dir+'.png';
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
			  step_man.children('img').attr('src','<?php echo F::getStaticsUrl('/christmasappweb/images/box.png');?>');
			  step_man.children('img').addClass('endimg'); 
			  $('.tips').fadeIn(500);
			  },500);
			return true;
			  }
			}
		
		var tout=setTimeout('step()',200);
	  }
	  
	  
	  
	  $('.endimg').live('click',function(){
		$('.opacity').show();
		
		//document.getElementById('prize').innerHTML =htmlword.message;
		   var rs = getLuckyData();
		    //alert(ok);
		    $('.alertcontairn').hide();//隐藏底下默认提示文字
			//$('.alertcontairn div').hide();
			//if(htmlword.nums==3){
				//$("#prize_nums").text(htmlword.message);
			//}
			var t_ = $('.alertcontairn').eq(rs.nums);
			t_.show();
		    center(t_);
		
		
	  })
	  
	  $('.closeOpacity').click(function(){
		$('.opacity').hide();	
	  })
	  
	  $(document).ready(function(){
		  step_man.show();
		  step();
		  
	  })
	  
	  
  </script>
</body> 

</html>