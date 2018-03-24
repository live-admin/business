<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>天天摇大奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/september/shake.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
 
<script type="text/javascript">
	$('.closeOpacity').live('click',function(){
	  $('.opacity').hide();
	  $('.opacity>div').hide();	
		$("#shake").show();
    $('.openpacket').hide();
})   
</script>


</head> 
 
<body style="position:relative;"> 
   <div class="phone_contairn phone_contairnWeb">
     
    
       <div class="s_phone_img">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/duoduo1.png');?>" class="lotteryimg" id="shake"/>
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/duoduo2.png');?>" class="lotteryimg openpacket" style="display:none;"/>
     </div>
     <div class="ground">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/tips.png');?>" class="tips"/>
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/ground.png');?>" class="lotteryimg"/>
     </div>

     <div class="rulelink_modify">
       <a href="luckyApp/shakeRule" id="clearE" >活动规则</a>
       <span class="spar">|</span>
       <a href="/luckyApp/shakeResult">摇奖结果</a>
       <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
       <p class="record_txt">您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次抽奖机会，已有<span id=""><?php echo $allJoin; ?></span>人参与</p>
     </div>
     <!--广告 start-->
     <div class="advertisement_modidy">
       <div class="produce_img">
         <a href="/robPerfectCrab"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/product1.png');?>" class="lotteryimg"/></a>
         <a href="/luckyApp/fruitGet"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/product2.png');?>" class="lotteryimg"/></a>
       </div>
       <a href='http://www.e-zhongjie.com:81/Sceret/zi/Yad.aspx'><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/advertisement.jpg');?>" class="lotteryimg" /></a>
     </div>
     <!--广告 end-->
   </div>
   
   
   <!--弹出框 start-->
   <div class="opacity" style="display:none;">







     <!--次数用光 start-->
     <div class="alertcontairn alertcontairn_total" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>亲，您的摇奖次数用光了。<br/>邀请好友注册成功可获得5次摇奖机会哦。<br/>邀请路径：APP首页——>邀请注册。</p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/shakeResult">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--次数用光 end-->
     




     <!--摇奖超过五次 start-->
     <div class="alertcontairn alertcontairn_today" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>亲，每天只能摇奖5次哦，明天再来摇奖吧！<br/>邀请好友注册成功可获得5次摇奖机会哦。<br/>邀请路径：APP首页——>邀请注册。</p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/shakeResult">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--摇奖超过五次 end-->







      <!--谢谢参与 start-->
     <div class="alertcontairn alertcontairn_thanks" style="display:none">
       <div class="textinfo textinfoback1">
         <table>
           <tr>
             <td>
               <p>这次没摇到什么，继续摇一摇，就有好运来，<br />再摇摇看吧～</p>
               <br />
               <br />
               <br />
               <br />
               <br />
               <br />
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/shakeResult">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--谢谢参与 end-->







     <!--中奖 start-->
     <div class="alertcontairn alertcontairn_ok" style="display:none;">
       <div class="textinfo textinfoback2">
         <table>
           <tr>
             <td>
               <p>好运摇出来，恭喜您，<span id="red_packet"></span>元红包摇到手。</p>
               <br />
               <br />
               <br />
               <br />
               <br />
               <br />
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/shakeResult">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--中奖 end-->




      <!--中奖5000元 start-->
     <div class="alertcontairn alertcontairn_5000" style="display:none;">
       <div class="textinfo textinfoback2">
         <table>
           <tr>
             <td>
               <p style="padding-left:15px; text-align:left;">摇一摇，惊喜从天而降，恭喜您，<span class="red_packet5000">5000元</span>大奖和您<br />
                  只有一步之遥。只要您是彩生活业主或租户，待客<br />
                  户经理上门核实身份后，彩生活将发放<span class="red_packet5000">5000元</span>到您<br />
                  的红包帐号。
               </p>
               <br />
               <br />
               <br />
               <br />
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/shakeRule5000">红包发放说明</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--中奖5000元 end-->





     <!--泰康人寿一 start-->
     <div class="alertcontairn alertcontairn_taikang1" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>摇一摇，更健康，恭喜您，摇到泰康人寿<br />一年"免费意外保险"，最高保100万。</p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyApp/taikanglingqu">领取</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--泰康人寿一 end-->
   
     
     
    
    
     
     
   </div>
   <!--弹出框 end-->
   
   <script type="text/javascript">  
	 var i,gtime,j=0;
	  $(document).ready(function(){
		  $('#shake').click(function(){

        var postCan = parseInt($('#luckyTodayCan').text());
        var custCan = parseInt($('#luckyCustCan').text());
        //alert(postCan);
        if (custCan < 1) { //总抽奖次数用完
          $('.opacity').show();
          $('.alertcontairn_total').show();
          return false;
        }
        if (postCan < 1) { //今日可以抽奖次数用完
          $('.opacity').show();
          $('.alertcontairn_today').show();
          return false;
        }
        //return true;
			$('#shake').unbind('click');//解除绑定
			gtime=setTimeout('gaibian()',100);
		  })
		  
	  });
	  
	  function gaibian(){
		j++;
		if(i==0){
			i=1;
			$("#shake").removeClass("zhuan_left");
			$("#shake").addClass("zhuan_right");
			}else{
				i=0;
				$("#shake").addClass("zhuan_left");
				$("#shake").removeClass("zhuan_right");
				}
		 if(j<20)
		   gtime=setTimeout('gaibian()',100)
		 else{
		   stopShake();		   
		 }
		}
		
		function stopShake(){//停止晃动，重新绑定点击事件
			$("#shake").removeClass("zhuan_right");
			$("#shake").removeClass("zhuan_left");
			clearTimeout(gtime);
      $("#shake").hide();
      $('.openpacket').show();
			j=0;
      var showpacket=setTimeout(function(){
        getLuckyData();
        clearTimeout(showpacket);
      },200)
      
			$('#shake').click(function(){

			  var postCan = parseInt($('#luckyTodayCan').text());
        var custCan = parseInt($('#luckyCustCan').text());
        //alert(postCan);
        if (custCan < 1) { //总抽奖次数用完
          $('.opacity').show();
          $('.alertcontairn_total').show();
          return false;
        }
        if (postCan < 1) { //今日可以抽奖次数用完
          $('.opacity').show();
          $('.alertcontairn_today').show();
          return false;
        }
        //return true;
      $('#shake').unbind('click');//解除绑定
      gtime=setTimeout('gaibian()',100);
			})
		}



    function mylottery() {
        var postCan = parseInt($('#luckyTodayCan').text());
        var custCan = parseInt($('#luckyCustCan').text());
        //alert(postCan);
        if (custCan < 1) { //总抽奖次数用完
          $('.opacity').show();
          $('.alertcontairn_total').show();
          return false;
        }
        if (postCan < 1) { //今日可以抽奖次数用完
          $('.opacity').show();
          $('.alertcontairn_today').show();
          return false;
        }
        return true;
    }


    function getLuckyData() { //得到数据
          $.ajax({
            type: 'POST',
            url: '/luckyApp/doShakeLucky',
            data: 'actid=7',
            dataType: 'json',
            async: false,
            error: function () {
              $('.opacity').show();
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
                showPackag(getPrize);
              }
            }
          });
    }



    //根据结果弹出红包
    function showPackag(prize) {        
        var prizeid=parseInt(prize.id);
        if (prizeid==69) {
          $('.opacity').show();
          $('.alertcontairn_thanks').show();
        } else if (prizeid==70) {
          $('.opacity').show();
          $('.alertcontairn_taikang1').show();
        } else if(prizeid==68){
          $('.opacity').show();
          $('.red_packet5000').html(prize.rednum);
          $('.alertcontairn_5000').show();   
        } else if(prizeid>=63&&prizeid<=67){
          $('.opacity').show();
          $('#red_packet').html(prize.rednum);
          $('.alertcontairn_ok').show();   
        }else{
          $('.opacity').show();
          $('.alertcontairn_thanks').show();   
        }
    }
  
  
   </script>
</body> 

</html>