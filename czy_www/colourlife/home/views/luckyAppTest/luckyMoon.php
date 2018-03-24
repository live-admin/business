<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<title>月光宝盒</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/august/lottery.css?time=76543210'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.moon.js?datetime=123456'); ?>"></script>
</head>

<body style="position:relative; color:#c0c0c0;">
<div class="lottery_topic">
   <div class="lottery">

     <div class="lotteryList">
       <p class="changebox">
         <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
         您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span> 次机会，已有<span id=""><?php echo $allJoin; ?></span>人次参加
       </p>
      
     
       <dl id="ticker">
         <?php foreach($listResutl as $result){ ?>  
            <dt><?php echo $result['msg']; ?></dt>
         <?php } ?>
       </dl>
   

       <div class="moon">
          <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/moon.png'); ?>" class="lotteryimg"/>
        </div>
     </div>
     
    <div class="moonlottery">
       
       <div class="moonboxclose"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/moonbox.png'); ?>" class="lotteryimg"/></div>
       <div class="moonboxopen" style="display:none;">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/moonbox1.png'); ?>" class="lotteryimg"/>
         <div class="yan" style="display:none;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/yan.png'); ?>" class="lotteryimg"/></div>
       </div>
       <div class="moonlight"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/light.png'); ?>" class="lotteryimg"/></div>
     </div>
     
     <div class="lotteryrule">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/clickhere.png'); ?>" style="width:110px;"/>
       <a href="javascript:void(0);" class="lotterybtn throw" style="font-size:16px;">般若波罗蜜</a>
       <a href="/luckyApp/lotteryrule" class="rulebtn">穿越规则</a>
       <a href="/luckyApp/mylottery" class="rulebtn1">穿越情况</a>
       <a href="/crab" class="lotterybtn" style="margin-bottom:20px;">
         <span class="tagbtn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/crabbox.png'); ?>" class="lotteryimg"/></span>
         <span class="wordbtn">金秋蟹礼</span>
       </a>
       <a href="/invite" class="lotterybtn">
         <span class="tagbtn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/redbox.png'); ?>" class="lotteryimg"/></span>
         <span class="wordbtn">邀请好友送红包</span>
       </a>
     </div>
   
    <div class="adver">
     <a href='http://sz189.cn/mini/colourlife/index.html'><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/820X100.jpg'); ?>" class="lotteryimg"/></a>
    </div>
</div>
 
 <div class="gloab_light"></div>

 <!--弹出框 start-->
 <div class="opacity" style="display:none;">

  <!--手气用完 start-->
    <div class="alertcontairn" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback10.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont" style="margin:20px 0;">亲，你的穿越领红包次数用光了。</p>
         <p>
           邀请好友注册成功可获得5次穿越领红包机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont" style="font-size:12px;">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--手气用完 end-->


   <!--每天5次 start-->
   <div class="alertcontairn0" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback10.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont" style="margin:20px 0;">亲，每天只能穿越５次哦，明天再来开启月光宝盒，穿越领红包吧！</p>
         <p>
           邀请好友注册成功可获得5次穿越领红包机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont" style="font-size:12px;">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--每天5次 end-->




   <!--谢谢参与 start-->
   <div class="alertcontairn1" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback1.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo" style="color:#b20224; padding-left:70px;">
         <p>穿越未成功，</p>
         <p>月光宝盒送您回家赏月。</p>
         <p>彩生活祝您合家团圆，中秋愉快！</p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--谢谢参与 end-->


   <!--民国 start-->
   <div class="alertcontairn2" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback2.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>民国多才子</p>
         <p>您成功穿越并与徐志摩夜游康桥</p>
         <p>获得0.18元红包</p>
         <a href="javascript:void(0);" class="redpackclose">
           <span>0.18</span>元
         </a>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--民国 end-->
   <!--清朝 start-->
   <div class="alertcontairn3" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback3.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>九龙夺嫡多惊险</p>
         <p>您成功穿越并助四爷争夺皇位</p>
         <p>获得1.8元红包</p>
         <a href="javascript:void(0);" class="redpackclose">
           <span>1.8</span>元
         </a>
       </div>
         <div class="pop_btn">
           <a href="/luckyApp/mylottery">查看穿越情况</a>
           <a href="javascript:void(0);" class="closeOpacity">关闭</a>
         </div>
     </div>
   </div>
   <!--清朝 end-->
   <!--明朝 start-->
   <div class="alertcontairn4" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback4.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>明朝多豪杰</p>
         <p>您成功穿越并与郑和同下西洋</p>
         <p>获得8.8元红包</p>
         <a href="javascript:void(0);" class="redpackclose">
           <span>8.8</span>元
         </a>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--明朝 end-->
   <!--元朝 start-->
   <div class="alertcontairn5" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback5.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>元朝多骄子</p>
         <p>您成功穿越并与忽必烈对酒</p>
         <p>获得18元红包</p>
         <a href="javascript:void(0);" class="redpackclose">
           <span>18</span>元
         </a>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--元朝 end-->
   <!--宋朝 start-->
   <div class="alertcontairn6" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback6.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>宋朝男儿气节高</p>
         <p>您成功穿越并解救岳飞</p>
         <p>获得88元红包</p>
         <a href="javascript:void(0);" class="redpackclose">
           <span>88</span>元
         </a>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/mylottery">查看穿越情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--宋朝 end-->
   <!--唐朝 start-->
   <div class="alertcontairn7" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback7.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       
       <div class="textinfo">
         <p style="font-size:12px;">
           举头望明月，大奖乘风来。<br/>
           您穿越至大唐盛世,5000元大奖就在您面前！<br/>只要您
           是彩生活业主(在彩生活服务小区内的房产所有人或租
           赁人及其直系亲属配偶、父母或子女),待彩生活客户经
           理上门核实身份后,系统会在一个工作日内发放5000元
           大奖到您的红包账户。
         </p>
         <a href="javascript:void(0);" class="redpackclose">
           <span>5000</span>元
         </a>
       </div>
       <div class="pop_btn" style="padding:35px 0;">
         <a href="/luckyApp/dajiangshuoming" class="bigpriceBtn">红包发放说明</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--唐朝 end-->
   <!--隋朝 start-->
   <div class="alertcontairn8" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback8.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>隋朝人民智慧高</p>
         <p>您成功穿越并帮助修建大运河</p>
         <div class="alertimg">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/alertimg.jpg'); ?>" class="lotteryimg"/>
         </div>
         <p style="color:#b20224; font-size:14px; text-align:center; line-height:150%; margin:0;">您获得品果精品美味礼盒</p>
         <p style="text-align:center;">（图片为奖品之一，具体查看奖品发放说明）</p>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/shuoming" class="priceBtn">奖品发放说明</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--隋朝 end-->
   <!--三国 start-->
   <div class="alertcontairn9" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback9.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
        <p>三国多英雄</p>
         <p>您成功穿越并参与赤壁之战大破曹军</p>
         <p>获得中国电信10元手机话费</p>
         <div class="alertimg" style="width:120px; margin:25px auto 0;">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/dxcz.jpg'); ?>" class="lotteryimg"/>
         </div>
          <p style="color:#b20224; font-size:14px; text-align:center; line-height:150%; margin:0 0 10px 0;">您获得中国电信10元手机话费</p>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="lingqu">奖品领取</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--三国 end-->
   <!--输入电信手机号码 start-->
   <div class="alertcontairn10" style="display:none;">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback10.jpg'); ?>" class="lotteryimg scene_back"/>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>请输入您充值的中国电信手机号码：</p>
         <p><input type="text" class="moblie_number" id="moblie_number" value="" defaultTxt="您的电信手机号码" style="margin:0;"/></p>
         <p class="errortip" style="color:red; height:20px;"></p>
         <p>提示：</p>
         <p>1、为了保障能顺利获得奖品，请您务必登记您的中国电信手机号码。</p>
         <p>2、中国电信系统将在中奖后20天内将10元话费充值到您登记的中国电信手机号码。</p>
         <p>3、如果您登记的不是中国电信号码，我们将无法保证为您的手机充值。</p>
       </div>
       <div class="pop_btn" style="padding:15px 0 35px;">
         <a href="javascript:mobileNumber();" class="submit" style="margin-top:10px;">提交</a>
         <a href="javascript:myclose();" class="closeOpacity" style="margin-top:10px;">关闭</a>
       </div>
     </div>
   </div>
   <!--输入电信手机号码 end-->


    <!--提示输入电信号码 start-->
     <div class="alertcontairn11" style="display:none;">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/newalertback10.jpg'); ?>"  class="lotteryimg scene_back"/>
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="text-align:center; margin-top:50px;">
            本次抽奖仅限电信号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:myclose();" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--提示输入电信号码 end-->




 </div>
<!--弹出框 end-->
<script type="text/javascript"> 
/*
 * name   : $(node).cl_inputDefaultTxt(opt)
 * content  : input标签添加默认文本，聚焦时，默认文本消失
 */


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


var e = jQuery.Event("click");
$(".moonboxclose").click(function(){
    $(".throw").trigger(e);
});   


$(function(){
  $('.moblie_number').cl_inputDefaultTxt({});
})

  $('.lingqu').click(function(){
    $('.opacity>div').eq(10).hide();
     $('.opacity>div').eq(11).show();
    // center($('.opacity>div').eq(11));    
  })

  function myclose(){
        $('.opacity').hide();
        $('.alertcontairn10 .alertcontairn11').hide();        
    }


  //提交手机号码
  function mobileNumber(){
        var moblie_number = $('#moblie_number').val();
        var reg = /^(133|153|180|181|189)\d{8}$/; 
        if(moblie_number==''||moblie_number=='您的电信手机号码'){
           $('.errortip').text('号码不能为空');
           return;
        }else if(!(reg.test(moblie_number))){
           $('.errortip').text('不是正确的电信手机号码');
           return;
        }else{         
          $.post(
              '/luckyApp/telecom',
              {'mobile':moblie_number},
              function (data){
                  if(data == 1){
                      location.reload();
                  }else{
                      $('.alertcontairn10').hide();
                      $('.alertcontairn11').show();
                      // center($('.alertcontairn11'));
                  }
              }
          ,'json');
      } 

 }


</script> 
</body>
</html>
