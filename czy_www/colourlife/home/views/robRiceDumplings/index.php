<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>抢粽子</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/zongzi.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.CountDown.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
</head>

<body>
<div class="zongzi">
  <div class="head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/head.jpg');?>" class="lotteryimg"/></div>
  <div class="zongzi_content">
    <!-- gundong -->
    <!-- <?php //if(date('Y-m-d H:i:s')>='2015-06-08 14:03:00'){?> -->
    <div class="lotteryList">
      <dl id="ticker">
       <?php foreach($newInfo as $_v){ ?>  
          <dt><?php echo $_v; ?></dt>
       <?php } ?>
      </dl>
    </div>
    <?php //} ?>
    <!-- gundong -->
    <div class="timebox">
      <!--本场时间节点 start-->
      <div class="tm_node">
        <input type="hidden" value="20" />
        <p class="current_tm clearfix"><span class="f_span" value=10>10:00</span><span class="s_span" value=14>14:00</span><span class="t_span" value=16>16:00</span><span class="fr_span" value=20>20:00</span></p>
        <p class="mod_count clearfix"><span class="floatleft">本场有<i style="font-style:normal;" id="remaining"><?php if($flag ==1){ echo $remaining;}else{ echo "0"; } ?></i>份粽子</span><span class="nexttime floatright">下一场:<i style="font-style:normal;">14</i>:00准时开抢</span></p>
      </div>
      <!--本场时间节点 end-->
      <!--倒计时容器 start-->
      <div class="count_down_box">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/time.gif');?>" class="tm_png"/>
        <label>即将开抢倒计时：</label>
        <span class="count_down price">00时00分00秒</span>
      </div>
      <!--倒计时容器 end-->
    </div>
    <!--焦点图 start-->
    <div class="focus_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/focus.jpg');?>" class="lotteryimg"/><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/focus1.jpg');?>" class="go lotteryimg" style="display:none;"/></div>
    <!--焦点图 end-->
    <div class="active_btn clearfix">
      <a href="/robRiceDumplings/mylottery" class="floatleft">活动战绩</a>
      <a href="/robRiceDumplings/rule" class="floatright">活动规则</a>
    </div> 
    <!--粽子产品 start--> 
    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/rm.png');?>" style="width:32%; margin:10% auto;"/>
    <div class="product_group clearfix">
      <dl class="floatleft">
        <a href="<?=$url?>&pid=1751">&nbsp;</a>
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img1.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味广式礼篮粽1160g</h3>
          <p>精选好料，丰富口味</p>
          <p>端午放价：<span class="price">78</span>元</p>
          <p>原价：98元</p>
        </dd>
      </dl>
      <dl class="floatright">
        <a href="<?=$url?>&pid=1752">&nbsp;</a>
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img2.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味百福礼盒粽1440g</h3>
          <p>甄选美味，端午佳礼</p>
          <p>端午放价：<span class="price">78</span>元</p>
          <p>原价：98元</p>
        </dd>
      </dl>
    </div>
    <div class="product_group clearfix">
      <dl class="floatleft">
        <a href="<?=$url?>&pid=1753">&nbsp;</a>
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img3.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味南粤五福礼篮1320g</h3>
          <p>南粤风味，五福呈祥</p>
          <p>端午放价：<span class="price">118</span>元</p>
          <p>原价：148元</p>
        </dd>
      </dl>
      <dl class="floatright">
        <a href="<?=$url?>&pid=1754">&nbsp;</a>
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img4.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味岭南八珍礼盒1280g</h3>
          <p>八珍八味，岭南秘制</p>
          <p>端午放价：<span class="price">148</span>元</p>
          <p>原价：168元</p>
        </dd>
      </dl>
    </div>
    <!--粽子产品 end--> 
    <p class="bot_stamp">★注：彩之云享有本次活动的最终解释权 </p> 
     
  <!--弹出框 start-->
    <div class="opacity" style="display:none;">
     
     <!--粽子抢没了 start-->
     <div class="alertcontairn grab_over1" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg1.jpg');?>" class="lotteryimg" /></div>
         <p>小伙伴们已经把粽子都带走了，下次早点来抢吧！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">休息会</a>
         </div>
       </div>
     </div>     
     <!--粽子抢没了 end-->



     <!--不在时间段内 start-->
     <div class="alertcontairn grab_over2" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg2.jpg');?>" class="lotteryimg" /></div>
         <p>你们城里人真会玩，现在暂停接客，下场早点来吧！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">休息会</a>
         </div>
       </div>
     </div>
     <!--不在时间段内 end-->



     <!--没抢到1 start-->
     <div class="alertcontairn grab_over3" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg2.jpg');?>" class="lotteryimg" /></div>
         <p>粽子过来忽悠了你一下又跑开了，千万别放过它！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">继续抢</a>
         </div>
       </div>
     </div>
     <!--没抢到1 end-->



     <!--没抢到2 start-->
     <div class="alertcontairn grab_over4" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg3.jpg');?>" class="lotteryimg" /></div>
         <p>粽子从你手边溜走了，再来一次，抓住它！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">继续抢</a>
         </div>
       </div>
     </div>
     <!--没抢到2 end-->



     <!--没抢到3 start-->
     <div class="alertcontairn grab_over5" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg3.jpg');?>" class="lotteryimg" /></div>
         <p>粽子离你还有一厘米，再接再厉，再来一次！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">继续抢</a>
         </div>
       </div>
     </div>
     <!--没抢到3 end-->


     <!--抢到 start-->
     <div class="alertcontairn grab_over6" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg4.jpg');?>" class="lotteryimg" /></div>
         <p class="price" style="font-size:14px;">恭喜您抢到了一份福气粽子，可凭码1元换购</p>
         <div class="pop_btn">
           <a href="<?=$url2?>&pid=5464" class="closeOpacity">去换购</a>
         </div>
       </div>
     </div>
     <!--抢到 end-->


     <!--今天已经抢到过了 start-->
     <div class="alertcontairn grab_over7" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg4.jpg');?>" class="lotteryimg" /></div>
         <p class="price" style="font-size:14px;">每天每个用户最多抢到一份福气粽子哦~<br/>您今天好运满满，留一点给别人吧！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">休息会</a>
         </div>
       </div>
     </div>
     <!--今天已经抢到过了 end-->
     
    
    
   </div>
  <!--弹出框 end-->
    
  </div>
</div>
<script type="text/javascript"> 


$(function(){
  $('.go').click(function(){
    $('.opacity').show();
    $.post(
        '/robRiceDumplings/rob',
        function (data){
           $('#remaining').text(data.remaining);
            if(data == 1){//抢到粽子
                $('.grab_over6').show();   
            }else if(data == 2){//今天已抢到粽子,不能再抢
                // $(".grab_over7").show();
                $(".grab_over5").show();                
                // $('.focus_img .go').hide().siblings('img').show();   
            }else if(data == 4){//活动未开始
                $(".grab_over2").show();
            }else if(data == 5){//粽子未抢到
                $(".grab_over3").show();
            }else if(data == 6){//粽子未抢到
                $(".grab_over5").show();
            }else if(data == 7){//粽子未抢到
                $(".grab_over4").show();
            }else if(data == 0){//粽子抢光了
                $(".grab_over1").show();
            }else{//粽子抢光了
                $('.grab_over1').show();
            }
        } 
    ,'json');
  })


  $('.closeOpacity').click(function(){
	  $('.opacity').hide().find('.alertcontairn').hide();
	})

  $('.close_img').click(function(){
    $('.opacity').hide().find('.alertcontairn').hide();
  })
  
  //倒计时  
  var nodeCurrent=$('.current_tm span'),day,hour,minute,second,id2;
  var is_inner=false,node_time,next_time,optn;
  function currentTimeNode(day,h,m,s){
	  is_inner=false;
	  nodeCurrent.each(function(index){
		   var node_t=parseFloat($(this).attr('value'));
		   node_time=node_t;
		  if(h<21&&h>9){
			 if(h>node_t){
			   return true;//往下跳
		     }
			 if(h<node_t){
			   node_time=parseFloat($(this).prev('span').attr('value'));//获取当前时间
			   next_time=node_t;//获取下一场时间
			   return false;//跳出循环
			 }
			 if(h==node_t){
			   (m<60)?is_inner=true:is_inner=false;
			   node_time=node_t;//获取当前时间
			   next_time=parseFloat($(this).next('span').attr('value'))||10;//获取下一场时间
			   return false;//跳出循环	 
			  }
		   }
		   else{//不在时间段内
			  if(h>=21&&h<24){
				  day++;          
			  }
        node_time=20;
        next_time=10;
        return false;
		    }
	   })
	   return optn={
		   tDay:day,
		   cNodeTime:node_time,
		   nNodeTime:next_time,
		   isInner:is_inner
		   }
  }
  
  
  function countDown(){//执行时的回调函数
     var nowTime = new Date();
     day=nowTime.getDate();
	   hour = nowTime.getHours();
	   minute=parseFloat(nowTime.getMinutes());
	   second=nowTime.getSeconds();
	   var temp;
	   var opt=currentTimeNode(day,hour,minute,second);//返回对象，对象包含：当前时间，下一场时间，是否是开抢时间
	   $('.current_tm span:contains('+opt.cNodeTime+')').addClass('cur_time').siblings('span').removeClass('cur_time');
	   $('.nexttime i').text(opt.nNodeTime);
	   if(opt.isInner){
		  temp=[2015,6,opt.tDay,opt.cNodeTime,59,59] ;
		  $('.count_down_box label').text('距离本场结束还有:');
		  $('.focus_img .go').show().siblings('img').hide();
	   }
	   else{
		  temp=[2015,6,opt.tDay,opt.nNodeTime,0,0]; 
		  $('.count_down_box label').text('即将开抢倒计时:');
		  $('.focus_img .go').hide().siblings('img').show();
		  
	   }
	
	   $('.count_down').cl_countdown({
		   endTime:temp,
	     goingTemp:"{hour}时{min}分{sec}秒",
		   endCallback:function(){
			 nowTime=null;
			 countDown();
			 }
	   })
	   
	   //if(hour-nodeh)
   //获取当前的系统后台时间小时
   //与时间节点比较，有一个开抢的时间差是有效的。
   //在开抢时间差内，1点绿当前节点字体，2改变下一场时间，
   //3改变倒计时结束时间（开抢时间差倒计）
   //在开抢时间差外，改变倒计时结束时间（下一场时间差倒计）
	  //id2=setTimeout(countDown, 1000);		 
  }
  countDown();
  
  
  function checkTime(){
      var NowTime = new Date();
      var colour_h=NowTime.getHours();
      // var m=NowTime.getMinutes();
      // var colourlife_sec = NowTime.getSeconds();
      var num = parseFloat($('#remaining').text());
      if(colour_h==10 || colour_h==14 || colour_h==16 || colour_h==20){//活动结束
          $.post(
              '/robRiceDumplings/newFlushByAjax',
              {'remaining':num},
              function (data){
                  if(data.success == "ok"){
                    $('#remaining').text(data.remaining);
                  }
              }
              ,'json');
      }
  }

  setInterval(checkTime,3000);
  
})
</script> 
</body>
</html>
