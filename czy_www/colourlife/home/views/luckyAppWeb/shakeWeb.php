<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>百万大奖第五波</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/september/shake.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/jQueryRotate.js');?>"></script>
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
	
	$('.closeOpacity').live('click',function(){
	  $('.opacity').hide();
	  $('.opacity>div').hide();	
    $("#shake").show();//add
    $('.openpacket').hide();//add
		
	})
   
</script>
<style type="text/css">
.web_shake #ticker {
    height: 340px;  
}    
</style>

</head> 
 
<body style="position:relative;"> 
   <div class="web_shake">
      <div class="web_shake_content">
        <!--摇一摇 start-->
        <div class="web_s_box">
          <h3 class="shake_tt">
            <a href="/luckyAppWeb/shakeRuleWeb">&gt;&gt; 查看活动规则</a>
            <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
            您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次机会，已有 <span id=""><?php echo $allJoin; ?></span> 人次参加
          </h3>
          <a name="yaodajiang"></a>
          <div class="web_s_content clearfix">
            <div class="web_shake_img">
              <div class="s_phone_img">
                  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/duoduo1.png');?>" class="lotteryimg" id="shake"/>
                  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/duoduo2.png');?>" class="lotteryimg openpacket" style="display:none;"/>
               </div>
               <div class="ground">
                 <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/tips.png');?>" class="tips"/>
                 <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/ground.png');?>" class="lotteryimg"/>
               </div>
            </div>
            <div class="lotterylist">
              <h3>
               <a href="/luckyAppWeb/shakeResultWeb">&gt;&gt; 查看摇奖结果</a>
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
          </div> 
                 
        </div>
        <!--摇一摇 end-->
        <div class="partbox crab_box">
          <div>
            <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/tt1.jpg');?>"/></span>
            <a href="/luckyAppWeb/robPerfectCrab">&gt;&gt;&nbsp;马上点击&nbsp;&nbsp;进入抽奖</a>
          </div>
          <a href="/luckyAppWeb/robPerfectCrab"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_web_link.jpg');?>"/></a>
        </div>
        <div class="partbox fruit_box">
          <div>
            <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/tt2.jpg');?>"/></span>
            <a href="/luckyAppWeb/fruitGetWeb">&gt;&gt;&nbsp;点击查看详情</a>
          </div>
          <a href="/luckyAppWeb/fruitGetWeb"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/fruit_web_link.jpg');?>"/></a>
        </div>
        <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/webbot.jpg');?>"/></div>
      </div>
   
   
   
   </div>
   
   
   <!--弹出框 start-->
   <div class="opacity opacityWeb" style="display:none;">
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
         <a href="/luckyAppWeb/lingqutaikang">领取</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--泰康人寿一 end-->
     
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
         <a href="/luckyAppWeb/shakeResultWeb">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--摇奖超过五次 end-->


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
         <a href="/luckyAppWeb/shakeResultWeb">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--次数用光 end-->
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
         <a href="/luckyAppWeb/shakeResultWeb">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--谢谢参与 end-->
     <!--中奖5000元 start-->
     <div class="alertcontairn alertcontairn_5000" style="display:none;">
       <div class="textinfo textinfoback2">
         <table>
           <tr>
             <td>
               <p style="padding-left:15px; text-align:left;">摇一摇，惊喜从天而降，恭喜您，<span>5000元</span>大奖和您<br />
                  只有一步之遥。只要您是彩生活业主或租户，待客<br />
                  户经理上门核实身份后，彩生活将发放<span>5000元</span>到您<br />
                  的红包帐号。
               </p>
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
         <a href="/luckyAppWeb/shake_5000">红包发放说明</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--中奖5000元 end-->
     <!--中奖 start-->
     <div class="alertcontairn alertcontairn_ok" style="display:none;">
       <div class="textinfo textinfoback2">
         <table>
           <tr>
             <td>
               <p>好运摇出来，恭喜您，<span>0.18</span>元红包摇到手。</p>
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
         <a href="/luckyAppWeb/shakeResultWeb">我的摇奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--中奖 end-->
     
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
           center($('.alertcontairn_total'));
          return false;
        }
        if (postCan < 1) { //今日可以抽奖次数用完
          $('.opacity').show();
           center($('.alertcontairn_today'));
          return false;
        }
      $('#shake').unbind('click');//解除绑定
			if(navigator.userAgent.indexOf("MSIE")>0){//判断是否为ie版本
			   stopShake()
			}
			else{
			   gtime=setTimeout('gaibian()',10);
			}
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
  		 
      if(j<20){
  	      gtime=setTimeout('gaibian()',100)
      }else{
  	      stopShake();
      }
		}
		
		function stopShake(){//停止晃动，重新绑定点击事件
			$("#shake").removeClass("zhuan_right");
			$("#shake").removeClass("zhuan_left");
			clearTimeout(gtime);
      $("#shake").hide(); //add
      $('.openpacket').show();//add
			j=0;
      var showpacket=setTimeout(function(){//add
        getLuckyData();
        clearTimeout(showpacket);
      },800)
      
			$('#shake').click(function(){
        var postCan = parseInt($('#luckyTodayCan').text());
        var custCan = parseInt($('#luckyCustCan').text());
        //alert(postCan);
        if (custCan < 1) { //总抽奖次数用完
          $('.opacity').show();
           center($('.alertcontairn_total'));
          return false;
        }
        if (postCan < 1) { //今日可以抽奖次数用完
          $('.opacity').show();
          center($('.alertcontairn_today'));
          return false;
        }
			  $('#shake').unbind('click');
			  gtime=setTimeout('gaibian()',10);
			})
		}
	 
	 
    function mylottery() {
        var postCan = parseInt($('#luckyTodayCan').text());
        var custCan = parseInt($('#luckyCustCan').text());
        if (custCan < 1) { //总抽奖次数用完
          $('.opacity').show();
          center($('.alertcontairn_total'));
          return false;
        }
        if (postCan < 1) { //今日可以抽奖次数用完
          $('.opacity').show();
          center($('.alertcontairn_today'));
          return false;
        }
        return true;
    }


    function getLuckyData() { //得到数据
          $.ajax({
            type: 'POST',
            url: '/luckyAppWeb/doShakeLucky',
            data: 'actid=7',
            dataType: 'json',
            async: false,
            error: function () {
              $('.opacity').show();
              center($('.alertcontairn_thanks'));
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
              } else {
                getPrize = data.data.result;
                showPackag(getPrize);
              }
            }
          });
    }



    //根据结果弹出红包
    function showPackag(prize) {        
        var prizeid = parseInt(prize.id);
        if (prizeid == 69) {
          shake_flag=true;
          $('.opacity').show();
          center($('.alertcontairn_thanks'));
        } else if (prizeid == 70) {
          shake_flag=true;
          $('.opacity').show();
          center($('.alertcontairn_taikang1'));
        } else if(prizeid == 68){
          shake_flag=true;
          $('.opacity').show();
          $('.red_packet5000').html(prize.rednum);
          center($('.alertcontairn_5000'));   
        } else{
          shake_flag=true;
          $('.opacity').show();
          $('#red_packet').html(prize.rednum);
          center($('.alertcontairn_ok'));   
        }
    }
  
   </script>
</body> 

</html>