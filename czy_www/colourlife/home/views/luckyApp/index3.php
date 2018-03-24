<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="0"> 
<title>抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.js?time=New Date()'); ?>"></script>

<script type="text/javascript">
var path_start = "<?php echo F::getStaticsUrl('/common/rich/luckyApp/na3.mp3'); ?>";    
var path_mp3 = "<?php echo F::getStaticsUrl('/common/rich/luckyApp/nb1.mp3'); ?>";
</script> 


</head>

<body style="position:relative">
<div class="lottery_topic" style="position:static">
   <div class="lottery" id="lottery">     
     <p class="changes" style="font-size:14px;">
         <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
         您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次抽奖机会，
         已有<span><?php echo $allJoin; ?></span>人次参加
     </p>
     </div>

     <div class="lotteryList" style="position:relative;">
       <p class="biglottery" style="position:absolute; width:100%; left:0px; top:0px; color:#a01c06; background:#3fcc10;font-size:12px;">
           恭喜成都<?php echo $dajiang[0]['community']; ?><?php echo $dajiang[0]['name'];?>获得5000元红包大奖！<br />
           恭喜上海<?php echo $dajiang[1]['community']; ?><?php echo $dajiang[1]['name'];?>获得5000元红包大奖！
       </p>
       <dl id="ticker">
           <dt>&nbsp;</dt>
       </dl>
     </div>
     <div class="lottery_box">
       <div class="roulette">
         <div id="start">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/point.png'); ?>" id="startbtn" class="lotteryimg"/>
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/point_logo.png'); ?>" class="duoduo"/>
         </div>
         
       </div>
     </div>
     <a href="/robRiceDumplings" class="zongzi_btn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/zongzi_btn.png'); ?>" /></a>
     <div class="lottery_details_link">
       <a href="/luckyApp/colourRule" class="acitverule">活动规则</a> 
       <a href="/luckyApp/mylottery" class="lottery_details">中奖情况</a>
       <a href="http://shop.sz189.cn/huodong/lw/index.html" style="background: none; padding:0;">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/820x100.jpg'); ?>" class="lotteryimg" />
       </a>
     </div>     

   </div>    
   
  
   <!--弹出框 start-->
   <div class="opacity" style="display:none;">
     <!--抽奖次数已用完 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p>
            亲，每天只能抽奖5次哦，<br />
            明天再来试试手气吧！<br />
            
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/colourRule"><span>查看活动规则</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--抽奖次数已用完 end-->
     <!--抽奖次数已用完 start-->
     <div class="alertcontairn0" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p>
            亲，你的抽奖次数用光了。<br />
            缴物业费、停车费、充值、投诉报修 <br />
            可获得更多抽奖次数。
           </p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/colourRule"><span>查看活动规则</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--抽奖次数已用完 end-->
     <!--谢谢参与1 start-->
     <div class="alertcontairn1" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p>
            谢谢您的参与，彩生活有您更精彩！            
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
           <a href="http://shop.sz189.cn/huodong/lw/index.html"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/578x100.jpg'); ?>" class="lotteryimg" /></a>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/colourRule"><span>查看活动规则</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--谢谢参与1 end-->
     
     <!--雅士利 start-->
     <div class="alertcontairn3" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/popimg1.jpg'); ?>" class="lotteryimg" />
           <p>
            恭喜您抽到了“雅士利”精美礼包一份。<br />
            礼包为：雅士利正味麦片600克。<br />            
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/howgetit"><span>奖品领取说明</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--雅士利 end-->
     <!--健康美味 start-->
     <div class="alertcontairn4" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/popimg3.png'); ?>" class="lotteryimg" />
           <p>
            恭喜您抽到了"健康美味"礼包一份,<br />
            礼包为：海南大金芒5斤装。
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/howgetit"><span>奖品领取说明</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--健康美味 end-->
     <!--电信充值卡 start-->
     <div class="alertcontairn5" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/popimg2.png'); ?>" class="lotteryimg" />
           <p>恭喜您抽中了中国电信10元手机充值卡一张。</p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="javascript:getit();"><span>奖品领取</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--电信充值卡 end-->
     <!--中红包 start-->
     <div class="alertcontairn6" style="display:none">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/little_money.png'); ?>" class="money"/>
           <div class="redpack_box">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/redpack.png'); ?>" class="lotteryimg" />
                <span id="bonus_amount_small">88元</span>
           </div>
           <p>
            恭喜您获得了<span id="bonus_amount">88</span>元红包！
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>
               邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/mylottery"><span>查看中奖情况</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--中红包 end-->
     <!--5000元大奖提示 start-->
     <div class="alertcontairn7" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/large_money.png'); ?>" class="money"/>
           <p>
            &nbsp;&nbsp;&nbsp;&nbsp;好运降临，您离５０００元大奖只有一步之遥！　只要是您是彩生活业主（彩生活服务小区房产所有人或租赁人）及其直系亲属（配偶、父母、子女），待彩生活客户经理上门核实身份后，系统会在一个工作日内发放５０００元大奖到您的红包帐户．
           </p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/howgethb"><span>红包发放说明</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--5000元大奖提示 end-->
     
     <!--输入手机号码 start-->
     <div class="alertcontairn8" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p>
            请输入您充值的中国电信手机号码：
           </p>
           <p><input type="text" class="moblie_number" id="moblie_number" value="您的中国电信手机号码" defaultTxt="您的中国电信手机号码"/></p>
           <p>提示：</p>
           <p>1、为了保障能顺利获得奖品，请您务必登记您的中国电信手机号码。</p>
           <p>2、中国电信系统将在10天之内把话费卡号和密码以短信方式发送到您登记的中国电信手机号码。  </p>
           <p>3、如果您登记的不是中国电信号码，我们将无法保证为您的手机充值。</p>
         </div>
         <div class="pop_btn">
           <a href="javascript:mobileNumber();"><span>提交</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--输入手机号码 end-->
     
     <!--提示输入电信号码 start-->
     <div class="alertcontairn9" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="text-align:center;">
            本次抽奖仅限中国电信手机号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:getit();"><span>返回</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>放弃</span></a>
         </div>
       </div>
     </div>
     <!--提示输入电信号码 end-->
     
     
   </div>
   <!--弹出框 end-->
  
  <audio src="<?php echo F::getStaticsUrl('/common/rich/luckyApp/na3.mp3'); ?>" id="start_adio" loop="loop"></audio>
  <audio src="<?php echo F::getStaticsUrl('/common/rich/luckyApp/nb1.mp3'); ?>" id="adio"></audio>
  
</div>
<script type="text/javascript"> 
    
(function($) {
	$.fn.cl_inputDefaultTxt = function(opt) {
		var _opt = $.extend({}, $.fn.cl_inputDefaultTxt.defaults, opt);
		
		return this.each(function() {
			var _this = $(this),
				_val = _this.val(),
				_dTxt = _this.attr("defaultTxt");
				
			_this.focus(function() {
				var __this = $(this),
					__val = __this.val();
					
				if (__val === _dTxt) {
					__this.val("");
				}
			});
			
			_this.focusout(function() {
				var __this = $(this),
					__val = __this.val();
					
				if (__val === "") {
					__this.val(_dTxt);
				}
			});
		});
	};
	
	$.fn.cl_inputDefaultTxt.defaults = {};
})(jQuery);

$(function(){
  $('.moblie_number').cl_inputDefaultTxt({});  
});    
    

function getit(){
    $('.alertcontairn5').hide();
    $('.alertcontairn9').hide();
    $('.alertcontairn8').show();
}

//提交手机号码
function mobileNumber(){
    var moblie_number = $('#moblie_number').val();
    $.post(
        '/luckyApp/telecom',
        {'mobile':moblie_number},
        function (data){
            if(data == 1){
                $('.opacity').hide();
		$('.alertcontairn8').hide();
                location.href = "/luckyApp/mylottery";
            }else{
                $('.alertcontairn8').hide();
                $('.alertcontairn9').show();
            }
        }
    ,'json');
}


</script> 
</body>
</html>
