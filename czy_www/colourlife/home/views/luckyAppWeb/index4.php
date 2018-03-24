<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>5月幸福中国行</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/lottery.css'); ?>" rel="stylesheet">    
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.web.js?time=New Date()'); ?>"></script>
<?php if($isGuest){?>
<script type="text/javascript">
	var isGuest=1;
	var loginHref="<?php echo $href;?>";
</script>
<?php }else{?>
<script type="text/javascript">
	var isGuest=0;
</script>
<?php }?>
<script type="text/javascript"> 
var path_start = "<?php echo F::getStaticsUrl('/common/rich/luckyApp/na3.mp3'); ?>";    
var path_mp3 = "<?php echo F::getStaticsUrl('/common/rich/luckyApp/nb1.mp3'); ?>";
//$(function(){
	
	
//	$("#startbtn").rotate({
//		bind:{
//			click:function(){
//				var a = Math.floor(Math.random() * 360);
//				 $(this).rotate({
//					 	duration:3000,
//					 	angle:0, 
//            			animateTo:1440+a,
//						easing: $.easing.easeOutSine,
//						callback: function(){
//							  $('.opacity').show();
//							  center($('.alertcontairn6'));
//							  
//						}
//				 });
//			}
//		}
//	});
//	var e = jQuery.Event("click");
//	$(".duoduo").click(function(){
//      $("#startbtn").trigger(e);
//    });
//	
//	$('.grab_btn').click(function(){
//	  $('.opacity').show();
//	  center($('.grab_over4'));
//    })  
//});
</script> 

</head>

<body>  
    
<div class="lottery_topic_web">
   <div class="topic">
     <p class="changes">
         <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>         
         您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次抽奖机会，
         已有<span><?php echo $allJoin; ?></span>人次参加
     </p> 
     <div class="topic_part1">
       <div class="lottery_web">
         <div class="roulette">
           <div id="start">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/point.png'); ?>" id="startbtn" class="lotteryimg"/>
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/point_logo.png'); ?>" class="duoduo"/>             
           </div>
         </div>
       </div>
       <div class="new_lottery">
         <h3>
             <a href="/luckyAppWeb/mylottery" class="lookfor_lottery floatright">&gt;&gt;查看中奖情况</a>
             最新中奖
         </h3>
         <div class="lotteryList" style="position:relative;">
           <p class="biglottery" style="position:absolute; line-height: 25px; padding-left: 15px; width:100%; left:0px; top:0px; color:#a01c06; background:#3fcc10;">
               恭喜成都<?php echo $dajiang[0]['community']; ?><?php echo $dajiang[0]['name'];?>获得5000元红包大奖！<br>
               恭喜上海<?php echo $dajiang[1]['community']; ?><?php echo $dajiang[1]['name'];?>获得5000元红包大奖！
           </p>
           
           <dl id="ticker">
             <?php foreach($newLuckyInfo as $_info){ ?>  
                <dt><?php echo $_info; ?></dt>
             <?php } ?>   
           </dl>
         </div>
       </div>
       <div class="clr"></div>
     </div>
     <div class="topic_part2">
       <div class="time_line"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/time_line.png'); ?>" /></div>       
        <p class="grab_title">
        <?php if(!$dumplingsOver){ ?>    
            每天（8点至22点）整点准时开抢，每次限量开抢，先抢先得！
            <span id="rob_title">
            <?php if($able == 1 && $remaining > 0){ ?>活动进行中<?php }else{ ?>上一场（已结束）<?php } ?>
            </span>
        <?php }else{ ?>
            &nbsp;
        <?php } ?>    
        </p>       
       <div class="lottery_user">
         <div class="lottery_user_content">
           <h3>中奖用户</h3>
           <div class="spr_line"></div>
           <dl id="user_list">
             <?php foreach($newInfo as $_one){ ?> 
                <dt><?php echo $_one; ?></dt>
             <?php } ?>
           </dl>
           <div class="spr_line"></div>
           <h3><a href="/luckyAppWeb/myDumplings" class="lookfor_lottery">&gt;&gt;我抢到的粽子</a></h3>
         </div>
       </div>
       
       <div class="grab_zongzi">
         <?php if($dumplingsOver){ ?>  
            <p style="text-align:left; font-size:18px; margin-bottom:70px; padding-left:25px; line-height:200%;">
              截至5月28日，全民抢粽子活动已经结束了，恭喜所有抢到粽子的业主们！
              没有抢到粽子的业主们，也请不要灰心，百万红包抽奖仍在进行，业主们可继续关注参与，马上抽奖，马上有钱！
            </p><!--增加-->  
         <?php } ?>
         <div class="zongzi_img_web"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/zongzi_img.png'); ?>" /></div>
         <dl>
           <dt>抢粽子活动规则</dt>
           <dd>1、活动时间：2014年5月5日至2014年5月28日</dd>
           <dd>2、免费抢粽：</dd>
           <dd>
             <ul>
               <li>（1）每天（8点至22点）整点准时开抢</li>
               <li>（2）每次限量开抢，先抢先得</li>
               <li>（3）每天每个用户最多1次抢到粽子的机会</li>
             </ul>
           </dd>
           <dd>3、抢到的礼品粽在抢到后15个工作日内由彩生活客户经理配送到您确认的收货地址。</dd>
           <dd>4、彩生活享有法律范围内的活动最终解释权。</dd>
         </dl>
         <div class="clr"></div>
         <?php if(!$dumplingsOver){ ?>
         <p class="surplus">
              <span class="floatright" id="colourlife_right">下场开始时间：<span class="nexttime">21：00</span></span>
              本场剩余：<span id="remaining_number"><?php echo $remaining; ?></span>份五芳斋粽子
         </p>
         <a href="javascript:void();" class="grab_btn" style="<?php if($able == 1 && $remaining > 0){ ?>display:block;<?php }else{ ?>display:none;<?php } ?>">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/zongzi_btnWeb.png'); ?>" />
         </a>
         
         <a class="no_grab_btn" style="<?php if($able == 0 || $remaining == 0){ ?>display:block;<?php }else{ ?>display:none;<?php } ?>;">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/no_grab.png'); ?>" />
         </a>
         <?php } ?>         
       </div>
       <div class="clr"></div>
       
     </div>
     <div style="padding-top:10px; background:#CCFFB3;">
         <a href="http://shop.sz189.cn/huodong/lw/index.html">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/976x100.jpg'); ?>" class="lotteryimg" style="height:100px;" />
         </a>
     </div>
     
     <div class="lcontent"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/bot.png'); ?>" /></div>
     
  </div>
  
  
   <!--弹出框 start-->
   <div class="opacity" style="display:none;">
     <!--抽奖次数已用完 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="padding:50px 0 50px 20px;">
            亲，每天只能抽奖5次哦，<br />
            明天再来试试手气吧！<br />
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyAppWeb/lotteryrule"><span>查看活动规则</span></a>
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
           <a href="/luckyAppWeb/lotteryrule"><span>查看活动规则</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--抽奖次数已用完 end-->
     
     <!--谢谢参与1 start-->
     <div class="alertcontairn1" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="padding:50px 0 50px 20px;">
            谢谢您的参与，彩生活有您更精彩！<br />
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyAppWeb/lotteryrule"><span>查看活动规则</span></a>
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
           <p style="font-size:22px;">
            恭喜您抽到了“雅士利”精美礼包一份。<br />
            礼包为：雅士利正味麦片600克。<br /><br />
           </p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyAppWeb/howgetit"><span>奖品领取说明</span></a>
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
           <a href="/luckyAppWeb/howgetit"><span>奖品领取说明</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--健康美味 end-->
     <!--电信充值卡 start-->
     <div class="alertcontairn5" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/popimg2.png'); ?>" class="lotteryimg" style="width:328px" />
           <p>恭喜您抽中了中国电信10元手机充值卡一张。</p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="javascript:showTelDiv();"><span>奖品领取</span></a>
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
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/redpack.png'); ?>" class="lotteryimg"/>
             <span id="bonus_amount_small">0.18</span>
           </div>
           <p>恭喜您获得了<span id="bonus_amount">0.18</span>元红包！</p>
           <p class="add_pp">邀请邻居注册成功可获得5次抽奖机会哦！<br/>邀请路径：App首页-&gt;邀请注册</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyAppWeb/mylottery"><span>查看中奖情况</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--中红包 end-->
     <!--5000元大奖提示 start-->
     <div class="alertcontairn7" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo" style="width:560px;">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/large_money.png'); ?>" class="money"/>
           <p style="padding:160px 0 50px 20px; font-size:18px;">
            &nbsp;&nbsp;&nbsp;&nbsp;好运降临，您离５０００元大奖只有一步之遥！　只要是您是彩生活业主（彩生活服务小区房产所有人或租赁人）及其直系亲属（配偶、父母、子女），待彩生活客户经理上门核实身份后，系统会在一个工作日内发放５０００元大奖到您的红包帐户．
           </p>
         </div>
         <div class="pop_btn">
           <a href="/luckyAppWeb/issuelotteryWeb"><span>红包发放说明</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--5000元大奖提示 end-->
     <!--输入手机号码 start-->
     <div class="alertcontairn8" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo" style="width:550px;">
           <p>
            请输入您充值的中国电信手机号码：
           </p>
           <p><input type="text" class="moblie_number" id="moblie_number" value="您的中国电信手机号码" defaultTxt="您的中国电信手机号码" /></p>
           <p>提示：</p>
           <p>1、为了保障能顺利获得奖品，请您务必登记您的中国电信手机号码。</p>
           <p>2、中国电信系统将在10天之内把话费卡号和密码以短信方式发送到您登记的中国电信手机号码。  </p>
           <p>3、如果您登记的不是中国电信号码，我们将无法保证为您的手机充值。</p>
         </div>
         <div class="pop_btn">
           <a href="javascript:mobileNumber();"><span>提交</span></a>
         </div>
       </div>
     </div>
     <!--输入手机号码 end-->
     
     <!--提示输入电信号码 start-->
     <div class="alertcontairn9" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="padding:50px 0; text-align:center;">
            本次抽奖仅限中国电信手机号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:showTelDiv();"><span>返回</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>放弃</span></a>
         </div>
       </div>
     </div>
     <!--提示输入电信号码 end-->
     
     
     <!--收货地址 start-->
     <div class="grab_over grab_over1" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <h3>确认您的地址</h3>
           <p id="colour_address">碧水龙庭B栋3单元402</p>
           <p><label>收货人：</label><input type="text" id="linkman" value="王昊" /></p>
           <p><label>联系电话：</label><input type="text" id="tel" value="13800138000" /></p>
           <div class="spr_line"></div>
           <p>温馨提示：</p>           
           <p>1、变更或者修改收货人及联系电话请先修改后确认。</p>
           <p>2、收货地址不正确，请在“我-我的账户”中修改。</p>
           <p>3、如果您中奖时没有确认收货信息，可以在“我抢到的粽子”中再次确认。</p>
         </div>
         
         <div class="pop_btn">
           <a href="javascript:sureAddress();" id="sure_address" class="closeOpacity"><span>确定</span></a>
         </div>
       </div>
     </div>
     <!--收货地址 end-->
     <!--中了粽子之后再抢 start-->
     <div class="grab_over grab_over2" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p class="win_already">每天每个用户最多1次抢到粽子的机会。</p>
           <p>您真棒！今天已经抢到粽子了，明天再来发挥吧~</p>
         </div>
         <div class="pop_btn">
             <a href="/luckyAppWeb/myDumplings"><span>我抢到的粽子</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--中了粽子之后再抢 end-->
     <!--粽子抢光了 start-->
     <div class="grab_over grab_over3" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           
           <p class="win_already">您来晚了，本场粽子已经抢光，下一场要加油哦~</p>
           <p>抢粽子活动每天（8点到22点）整点准时限量开抢，先抢先得！</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyAppWeb/myDumplings"><span>我抢到的粽子</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--粽子抢光了 end-->
     <!--抢到粽子 start-->
     <div class="grab_over grab_over4" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo" style="text-align:center;">
           <h3 style="text-align:center;">
            恭喜您抢到一份五芳斋粽子！
           </h3>
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/zongzi_img.png'); ?>" class="lotteryzongzi" />
         </div>
         <div class="pop_btn">
           <a href="javascript:submitAddress();"><span>确认收货地址</span></a>
         </div>
       </div>
     </div>
     <!--抢到粽子 end-->
     
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

    
    
    
  //提示填收货地址  
  function submitAddress(){
      $.post(
            '/luckyAppWeb/getCustomerInfo',
            function (data){
                $('#linkman').val(data.name);
                $('#tel').val(data.tel);
                $('#colour_address').html(data.address);
            }
        ,'json');
      $('.grab_over4').hide();
      center($('.grab_over1'));
  }


$(function(){
  $('.moblie_number').cl_inputDefaultTxt({});  
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
	  $('.alertcontairn,.alertcontairn1,.alertcontairn2,.alertcontairn3,.alertcontairn4,.alertcontairn5,.alertcontairn6,.alertcontairn7,.alertcontairn8,.alertcontairn9,.grab_over').hide();
  
 })
 
 
  //点击抢粽子
  $('.grab_btn').click(function (){
      if(isGuest==1){
            location.href=loginHref;
	    return;
      }
      $.post(
        '/luckyAppWeb/rob',
        function (data){
            if(data == 1){
                $('.opacity').show();
                center($('.grab_over4'));   //抢到粽子
                $('.grab_btn').hide();
                $('.no_grab_btn').show();
            }else if(data == 2){
                $('.opacity').show();
                center($('.grab_over2'));   //今天已抢到一份无法再参加
                $('.grab_btn').hide();
                $('.no_grab_btn').show();
            }else{
                $('.opacity').show();                
                center($('.grab_over3'));    //粽子抢光了
                $('.grab_btn').hide();
                $('.no_grab_btn').show();
            }
        } 
    ,'json');
  });

	
  var NowTime = new Date();
  var h=NowTime.getHours();
  if(h>7&&h<22){
	  h=++h+":00"
	  $('.nexttime').text(h);
  }else if(h<8){
      $('.nexttime').text("8：00");
  }else{
      $('.nexttime').text("明天8：00");
  }
  
  
  
    function checkTime(){
        var NowTime = new Date();
        var new_day=NowTime.getDate();
        var new_h=NowTime.getHours();
        var new_m=NowTime.getMinutes();
        if(new_h>7&&new_h<23 && new_m==0){
              $('.no_grab_btn').hide();
              $('.grab_btn').show(); 
              $('#rob_title').html("活动进行中");
              if(parseInt($('#remaining_number').html()) != 50 && parseInt($('#remaining_number').html()) != 100){
                  if(new_h==10 || new_h==15 || new_h==16 || new_h==20 || new_h==21){
                      $('#remaining_number').html("100");
                  }else{
                      $('#remaining_number').html("50");
                  }
              }
              if(new_h>7&&new_h<22){
                    var colour_h=new_h+1;
                    $('.nexttime').text(colour_h+":00");
            }else{
                $('.nexttime').text("明天8：00");
            }
         }else if(new_h>7&&new_h<23 && new_m>=50){
              $('.grab_btn').hide();
              $('.no_grab_btn').show();
              $('#rob_title').html("上一场（已结束）");
              $('#remaining_number').html("0");
         }
         if(new_day == 28 && new_h>=22){
             $('#colourlife_right').text("活动全部结束");
         }
    }    
    setInterval(checkTime,1000);
    
    
  })  
  

  //确认收货地址
  function sureAddress(){
      var linkman = $('#linkman').val();
       var tel = $('#tel').val();
       $.post(
           '/luckyAppWeb/fillReceiving',
           {'linkman':linkman,'tel':tel},
           function (data){
               //$('.opacity').hide();
               //$('.alertcontairn,.alertcontairn1,.alertcontairn2,.alertcontairn3,.alertcontairn4,.alertcontairn5,.alertcontairn6,.alertcontairn7,.alertcontairn8,.alertcontairn9,.grab_over').hide();
               location.href = "/luckyAppWeb/myDumplings";
           }
       ,'json');
  }

 </script>
</body>
</html>
