<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>6月幸福中国行</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/june/lottery.css'); ?>" rel="stylesheet">    
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.web.js'); ?>"></script>
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
//$(function(){
//	
//	
//	$("#startbtn").rotate({
//		bind:{
//			click:function(){
//				 $(this).rotate({
//					 	duration:3000,
//					 	angle:0, 
//            			animateTo:720+30,
//						easing: $.easing.easeOutSine,
//						callback: function(){
//							  $('.opacity').show();
//							  center($('.alertcontairn4'));
//							  
//						}
//				 });
//			}
//		}
//	});
//	var e = jQuery.Event("click");
//	$(".cup_start").click(function(){
//      $("#startbtn").trigger(e);
//    });
//});
$(function(){
	var e = jQuery.Event("click");
	$(".cup_start").click(function(){
		lottery();
    });
});
</script> 

</head>

<body>
<div class="lottery_topic_web">
   <div class="topic">
     <h3 class="lt_title">
       <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>     
       <a href="/luckyAppWeb/lotteryrule" class="lookforrule" target="_blank">&gt;&gt;&nbsp; 查看活动规则</a>
       <p class="changes">您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次机会，已有<span><?php echo $allJoin; ?></span>人次参加</p>
     </h3>
     <div class="topic_part1">
       <div class="lottery_web">
         <div class="roulette">
           <div class="football"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/football.png'); ?>" id="startbtn" class="lotteryimg"/></div>
           <div id="start">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/cup.png'); ?>" class="cup_start"/>
           </div>
         </div>
       </div>
       <div class="new_lottery">
         <h3>
           <a href="/luckyAppWeb/mylottery" target="_blank" class="lookfor_lottery">&gt;&gt;查看中奖情况</a>
           最新中奖
         </h3>
         <div class="lotteryList" style="position:relative;">
           <p style="font-size:12px; color:#ff7e00; text-align:left; min-height:28px; line-height:28px; background:#d0f4d9; position:absolute; top:15px; left:15px;">
               恭喜祥和花园小区业主刘先生获得5000元红包大奖<br />恭喜龙府北郡小区业主李女士获得5000元红包大奖
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
     <h3 class="bigword" style="margin:50px 0 20px;">
       <div class="month_box july" style="width:310px; margin:0 10px;">
         <span class="group_tag" style="left:120px">淘汰赛</span>
         <dl class="clearfix">
           <dt style="padding-left:60px;">7月</dt>
           <dd day='29'>29</dd>
           <dd day='30'>30</dd>
           <dd day='1'>1</dd>
           <dd day='2'>2</dd>
           <dd day='5'>5</dd>
           <dd day='6'>6</dd>
           <dd day='9'>9</dd>
           <dd day='10'>10</dd>
           <dd day='12'>12</dd>
           <dd day='14'>14</dd>
         </dl>
       </div>
       <div class="month_box june">
         <span class="group_tag">小组赛</span>
         <dl class="clearfix">
           <dt>6月</dt>
           <dd day='12'>12</dd>
           <dd day='13' class="day_select">13</dd>
           <dd day='14'>14</dd>
           <dd day='15'>15</dd>
           <dd day='16'>16</dd>
           <dd day='17'>17</dd>
           <dd day='18'>18</dd>
           <dd day='19'>19</dd>
           <dd day='20'>20</dd>
           <dd day='21'>21</dd>
           <dd day='22'>22</dd>
           <dd day='23'>23</dd>
           <dd day='24'>24</dd>
           <dd day='25'>25</dd>
           <dd day='26'>26</dd>
           <dd day='27'>27</dd>
         </dl>
       </div>
       竞猜胜负
     </h3>
     <?php echo $this->renderPartial("_guessOutcome",array("custId"=>$custId,"arr_outcome"=>$arr_outcome,"encounters"=>$encounters)); ?>
     
     <?php

     $date=date("Y-m-d H:i:s");
     //$date='2014-06-28 00:00:00';
     $pid=2;
     $activeTime=SetTeamsPromotion::model()->findByPk($pid);
     if($date<$activeTime->start_time){
        echo $this->renderPartial("_promotion");
     }else{
         echo $this->renderPartial("_last_promotion");
     }

     ?>
     
     <?php echo $this->renderPartial("_winner"); ?>
     
     
     <div class="adver">
         <a href="http://sz189.cn/mini/colourlife/index.html" target="_blank">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/976X100.jpg'); ?>" />
         </a>
     </div>
     <div class="webbottom">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/webbottom.jpg'); ?>" />
     </div>
  </div>
  
  
   <!--弹出框 start-->
 <div class="opacity" style="display:none;">
   <!--手气用完 start-->
   <div class="alertcontairn" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">亲，每天只能抽奖5次哦，</p>
         <p class="redfont">明天再来抽大奖吧！</p>
         <p>
            邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>           
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/lotteryrule" target="_blank"><span>查看活动规则</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--手气用完 end-->
   <!--每天5次 start-->
   <div class="alertcontairn0" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">亲，你的抽奖次数用光了。</p>
         <p> 
            邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/lotteryrule" target="_blank"><span>查看活动规则</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--每天5次 end-->
   <!--谢谢参与 start-->
   <div class="alertcontairn1" style="display:none;">
     <div class="alertcontairn_content">
       <!--<div class="ticket">
         <a href=""><img src="<?php //echo F::getStaticsUrl('/common/images/lucky/june/caipiaoimg.jpg'); ?>" class="lotteryimg" /></a>
       </div>-->
       <div class="textinfo">
         <!--<p class="redfont">没有中奖？没关系！马上免费领取3元彩金，去彩票直通车下注试试手气吧！（每人限领一次）</p>-->
         <p class="redfont" style="margin-bottom:0;">谢谢您的参与，彩生活有您更加精彩！</p>
         <p>
            邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/lotteryrule" target="_blank"><span>查看活动规则</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--谢谢参与 end-->
   <!--降暑鲜果 start-->
   <div class="alertcontairn3" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/lz.jpg'); ?>" style="width:234px; height:174px; display:block;" />
         <p class="redfont" style="margin-bottom:0;">
           恭喜您抽到了"降暑鲜果"礼包一份,<br />
           礼包为：岭南桂味荔枝2斤装。
         </p>
         <p>
            邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/howgetit" target="_blank"><span>奖品领取说明</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--降暑鲜果 end-->
   <!--宝矿力水特 start-->
   <div class="alertcontairn4" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/bkl.jpg'); ?>" style="width:190px; height:190px; display:block;" />
         <p class="redfont" style="margin-bottom:0;">
           恭喜您抽到了"宝矿力水特饮料"一瓶。<br />
           奖品规格为500ml/瓶 。
         </p>
         <p>
            邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/howgetit" target="_blank"><span>奖品领取说明</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--宝矿力水特 end-->
   <!--电信充值卡 start-->
   <div class="alertcontairn5" style="display:none">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/dxcz.jpg'); ?>" style="width:266px; height:172px; display:block;" />
         <p class="redfont" style="margin-bottom:0;">
           恭喜您抽中了中国电信10元手机充值卡一张。
         </p>
         <p>
            邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>
       </div>
       <div class="adver"><a href="http://sz189.cn/mini/colourlife/index.html"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/578x100.jpg'); ?>" /></a></div>  
       <div class="pop_btn">
         <a href="javascript:showTelDiv();"><span>奖品领取</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--电信充值卡 end-->
   <!--中红包 start-->
   <div class="alertcontairn6" style="display:none">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <div class="redpack_box">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/redpack.jpg'); ?>"/>
           <span id="bonus_amount_small">0.18</span>
         </div>
         <p class="redfont" style="margin-bottom:0;">恭喜您获得了<span id="bonus_amount">0.18</span>元红包！</p>
         <p>
           邀请邻居注册成功送抽奖哦～<br/>
            邀请者送5次，新用户送10次。<br/>
            邀请路径：App > 邀请注册 
         </p>
       </div>
       <div class="pop_btn">
         <a href="luckyAppWeb/mylottery" target="_blank"><span>查看中奖情况</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--中红包 end-->
   <!--5000元大奖提示 start-->
   <div class="alertcontairn7" style="display:none">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">
          &nbsp;&nbsp;&nbsp;&nbsp;好运降临，您离５０００元大奖只有一步之遥！
          只要您是彩生活业主（在彩生活服务小区内的房产所有人或租赁人）及其直系
          亲属（配偶，父母，子女），待彩生活客户经理上门核实身份后，系统会在一
          个工作日内发放５０００元大奖到您的红包帐户。
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/issuelotteryWeb" target="_blank"><span>红包发放说明</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
       </div>
     </div>
   </div>
   <!--5000元大奖提示 end-->
   <!--输入手机号码 start-->
   <div class="alertcontairn8" style="display:none">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p>
          请输入您充值的中国电信手机号码：
         </p>
         <p><input type="text" class="moblie_number" id="moblie_number" value="您的中国电信手机号码" defaultTxt="您的电信手机号码" /></p>
         <p>提示：</p>
         <p>1、为了保障能顺利获得奖品，请您务必登记您的中国电信手机号码。</p>
         <p>2、中国电信系统将在10天之内把话费卡号和密码以短信方式发送到您登记的中国电信号码。</p>
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
   <div class="alertcontairn9" style="display:none">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p class="redfont">
          本次抽奖仅限电信号码！
         </p>
       </div>
       <div class="pop_btn">
         <a href="javascript:showTelDiv();"><span>返回</span></a>
         <a href="javascript:void(0);" class="closeOpacity"><span>放弃</span></a>
       </div>
     </div>
   </div>
   <!--提示输入电信号码 end-->
   
 </div>
<!--弹出框 end-->
  
  


</div>
<script type="text/javascript">

$(function(){
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
	  $('.alertcontairn,.alertcontairn1,.alertcontairn2,.alertcontairn3,.alertcontairn4,.alertcontairn5,.alertcontairn6,.alertcontairn7').hide();
	  
  
  
 })
 
/*谁是王者*/
  $('.the_winner dd').click(function(){
	$('.check_box').removeClass('checked_box');
	$('.check_qi').removeClass('checked_qi');  
	$(this).find('.check_box').addClass('checked_box'); 
	$(this).find('.check_qi').addClass('checked_qi');
    var choice=$(this).find('.choicecode').text();
    $('#winner_val').val(choice);

	
  })
 /*谁能晋级*/	
 $('.group_game li').click(function(){
	var parents=$(this).parents('.group_game');
	var lichecked=parents.find('.lichecked');
	  if($(this).hasClass('lichecked'))
	  { 
	    $(this).find('.check_box').removeClass('checked_box'); 
	    $(this).find('.check_qi').removeClass('checked_qi');
	    $(this).removeClass('lichecked');
        $(this).find('.check_guess').attr("checked",false);
	  }
	  else{
	   if(lichecked.length<2){
		$(this).find('.check_box').addClass('checked_box'); 
	    $(this).find('.check_qi').addClass('checked_qi');
	    $(this).addClass('lichecked');
        $(this).find('.check_guess').attr("checked",true);
	   }
	  }
	  var allteam= $('.group_game li').length/2;
	  var allchecked=$('.lichecked').length;
	  $('.teams').text(allchecked);
	  $('.unteams').text(allteam-allchecked);
	
	 
	  
  })
  
   /*竞猜胜负*/
   $('.elimination dd').click(function(){
	$(this).addClass('elimchecked');
    $(this).find('.check_guess').attr("checked",true);
    $(this).siblings('dd').removeClass('elimchecked');
    $(this).siblings('dd').find('.check_guess').attr("checked",false);
	var allteam= $('.elimination dd').length/2;
	var allchecked=$('.elimchecked').length;
	$('.teams').text(allchecked);
	$('.unteams').text(allteam-allchecked);
	
  })
  
    /*竞猜选择*/
  $('.guess_outcome .check_box').live('click',function(){
        $(this).siblings('.check_box').find('.checked_img').hide();
        $(this).siblings('.check_box').find('.uncheck_img').show();	
        $(this).find('.uncheck_img').hide();
        $(this).find('.checked_img').show();
        var myguess=$(this).parents('dd').find('.myguess');
	$(this).addClass('checked_box');
	$(this).siblings('.check_box').removeClass('checked_box'); 
        
	myguess.find('.guess_word').text($(this).attr('words'));
	myguess.children('.guess_p').show();
	myguess.children('.guess_p').siblings('p').hide();
        if($(this).attr('words')=="胜"){
            $(this).parent('div').find('.customer_outcome').val(1);            
        }else if($(this).attr('words')=="负"){
            $(this).parent('div').find('.customer_outcome').val(2);
        }else{
            $(this).parent('div').find('.customer_outcome').val(3);
        }
        var encounter_game = $(this).parent('div').find('.word_cup').val();    //世界杯对阵表Id
        var my_outcome = $("#customer_outcome"+encounter_game).val();   //业主竞猜的结果
        $.post(
            '/luckyAppWeb/myoutcome',
            {'encounter_game':encounter_game,'my_outcome':my_outcome},
            function (data){
                if(data.code === 1){

                }else{
                    alert("选择失败,请刷新重试！");
                    location.reload();
                }
            }
        ,'json');
  });
  
  $('#editMyOutcome').click(function(e){      
     $('.go_content').each(function(){
        $(this).each(function (){
           $(this).find('span').addClass('check_box');
           $(this).find('span').find('img:.uncheck_img').show();
           $(this).find('span').eq(0).html('<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />'+
           '<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  style="display:none;"/>'+
                '<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back6.png'); ?>" class="undefine_img lotteryimg" style="display:none;" />胜');
           $(this).find('span').eq(1).html('<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />'+
           '<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:none;" />'+
                '<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back6.png'); ?>" class="undefine_img lotteryimg" style="display:none;" />平');
           $(this).find('span').eq(2).html('<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />'+
           '<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:none;" />'+
                '<img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back6.png'); ?>" class="undefine_img lotteryimg" style="display:none;" />胜');
          $(this).find('span').find('img:.uncheck_img').show();      
        });
     });
   });

  /*获取月份日期*/
  var date=new Date();
  var month=date.getMonth()+1;
  var day=date.getDate();
  switch(true){
    case (month>5&&month<7&&day>11&&day<29)://6月份12号至28号日期样式
     $('.june dd[day='+day+']').addClass('day_select');
     $('.june dd[day='+day+']').siblings().removeClass('day_select');  
     $('.july dd').removeClass('day_select');
     if(day>11&&day<20){//六月12号至27号猜16强样式
             $('.guesslist dl').eq(0).addClass('guesslist_selected');
             $('.guesslist dl').eq(0).siblings().removeClass('guesslist_selected');
             }
     else{//六月27号至28号猜8强样式
             $('.guesslist dl').eq(1).addClass('guesslist_selected');
             $('.guesslist dl').eq(1).siblings().removeClass('guesslist_selected');
             }
     break;
    case ((month>5&&month<7)?(day>28&&day<31):(day>0&&day<15))://6月份29号至30号、7月份1号至15号日期样式
     $('.july dd[day='+day+']').addClass('day_select');
     $('.july dd[day='+day+']').siblings().removeClass('day_select'); 
     $('.june dd').removeClass('day_select');  
     if(day>28&&day<31||day<2){//六月29号至7月1号猜8强样式
             $('.guesslist dl').eq(1).addClass('guesslist_selected');
             $('.guesslist dl').eq(1).siblings().removeClass('guesslist_selected');
             }
     else if(day>1&&day<6){//七月2号至5号猜4强样式
             $('.guesslist dl').eq(2).addClass('guesslist_selected');
             $('.guesslist dl').eq(2).siblings().removeClass('guesslist_selected');
             }
     else if(day>5&&day<10){//七月6号至9号猜半决赛样式
             $('.guesslist dl').eq(3).addClass('guesslist_selected');
             $('.guesslist dl').eq(3).siblings().removeClass('guesslist_selected');
             }
     break;

  }  

});


 </script>
</body>
</html>
