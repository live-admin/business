<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="0"> 
<title>抢月饼</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>

<script type="text/javascript">


</script>

</head>

<body>
<div class="lottery_topic">
   <div class="lottery" id="lottery">
     <?php if($over){ ?>   
     <p class="warmTip" style="color:#0E4815; width:90%; margin:20px auto;">
         截至5月28日，全民抢月饼活动已经结束了，恭喜所有抢到月饼的业主们！没有抢到月饼的业主们，也请不要灰心，百万红包抽奖仍在进行，业主们可继续关注参与，马上抽奖，马上有钱！
     </p>
     <?php } ?>
     <div class="lotteryList">
       <dl id="ticker">
         <?php foreach($newInfo as $_v){ ?>  
            <dt><?php echo $_v; ?></dt>
         <?php } ?>
       </dl>
     </div>
     <div class="lottery_box zongzi_box">
      <?php if(!$over){ ?> 
      <div class="during">         
        <p class="during_next">
          本场剩余：<span class="nexttime" id="remaining"><?php if($able == 1){ echo $remaining;}else{ echo "0"; } ?></span>份五芳斋月饼
          <br />
          &nbsp;<span class="nexttime" id="colourlife_span" style="padding-left:25px;">
              <?php echo $timeRemainingNext?"下一场：".date("Y-m-d H",strtotime($timeRemainingNext))."点准时开启":"活动已全部结束"; ?></span>
        </p>
         <div class="during_content">
           <div class="clock">
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/clock.png'); ?>" />
           </div>
           <div class="residue_time">
             <p style="display: none;">
                 <span class="year">2014</span>
                 <span class="month"><?php echo $m; ?></span>
                 <span class="day"><?php echo $d; ?></span>
                 <span class="colourlife_h"><?php echo $h; ?></span>
                 <span class="colourlife_i"><?php echo $i; ?></span>
                 <span class="colourlife_s"><?php echo $s; ?></span>
             </p>
             <!--目标时间-->
             <span id="coloulife_refush">距离<?php if($able == 1){ ?>本场结束<?php }else{ ?>下场开始<?php } ?></span>
             还有：<span class="hour"><?php echo $h; ?></span>小时<span class="minute"><?php echo $i; ?></span>分<span class="sec"><?php echo $s; ?></span>秒
           </div>
         </div>
       </div>
      <?php } ?>
       
       <div class="zongzi_content">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/zongzi_img.png'); ?>" class="zongzi_img" />
         <?php if(!$over){ ?>  
         <a href="<?php if($this_count > 0 && $able == 1){ ?>javascript:rob();<?php }else{ ?>javascript:rob();<?php } ?>" class="grab<?php if($this_count == 0 || $able == 0){ ?> no_grab<?php } ?>" id="rob_grab">&nbsp;</a>
         <?php } ?>  
       </div>
      
         <div class="lottery_details_link">
         <a href="/luckyApp/myDumplings" class="lottery_details">中奖情况</a> 
         <a href="/luckyApp" class="lottery_details">返回</a> 
       </div>
       <div class="grab_rule">
         <dl>
           <dt>抢月饼活动规则</dt>
           <dd>1、活动时间：2014年5月5日至2014年5月28日</dd>
           <dd>2、免费抢粽：</dd>
           <dd>
             <ul>
               <li>（1）每天（8点至22点）整点准时开抢</li>
               <li>（2）每次限量开抢，先抢先得</li>
               <li>（3）每天每个用户最多1次抢到月饼的机会</li>
             </ul>
           </dd>
           <dd>3、抢到的礼品粽在抢到后15个工作日内由彩生活客户经理配送到您确认的收货地址。</dd>
           <dd>4、彩生活享有法律范围内的活动最终解释权。</dd>
         </dl>
       </div>
     </div>
     
     

   </div>
   
  
   <!--弹出框 start-->
   <div class="opacity" style="display:none;">
     <!--收货地址 start-->
     <div class="grab_over" id="grab_over1" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <h3>确认您的地址</h3>
           <p id="colour_address">碧水龙庭B栋3单元402</p>
           <p><label>姓名：</label><input type="text" id="linkman" value="王昊" /></p>
           <p><label>电话：</label><input type="text" id="tel" value="13800138000" /></p>
           <div class="spr_line"></div>
           <p>温馨提示：</p>           
           <p>1、变更或者修改收货人及联系电话请先修改后确认。</p>
           <p>2、收货地址不正确，请在“我-我的账户”中修改。</p>
           <p>3、如果您中奖时没有确认收货信息，可以在“我抢到的月饼”中再次确认。</p>
         </div>
         
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity" id="sure_address"><span>确定</span></a>
         </div>
       </div>
     </div>
     <!--收货地址 end-->
     <!--中了月饼之后再抢 start-->
     <div class="grab_over" id="grab_over2" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p class="win_already">每天每个用户最多1次抢到月饼的机会。</p>
           <p>您真棒！今天已经抢到月饼了，明天再来发挥吧~</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/myDumplings"><span>我抢到的月饼</span></a>  
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--中了月饼之后再抢 end-->
     <!--月饼抢光了 start-->
     <div class="grab_over" id="grab_over3" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p class="win_already">您来晚了，本场月饼已经抢光，下一场要加油哦~</p>
           <p>抢月饼活动每天（8点到22点）整点准时限量开抢，先抢先得！</p>
         </div>
         <div class="pop_btn">
           <a href="/luckyApp/myDumplings"><span>我抢到的月饼</span></a>
           <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
     <!--月饼抢光了 end-->
     <!--抢到月饼 start-->
     <div class="grab_over" id="grab_over4" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo" style="text-align:center;">
           <h3>
            恭喜您抢到一份五芳斋月饼！
           </h3>
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/zongzi_img.png'); ?>" class="lotteryzongzi" />
         </div>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="addresslink"><span>确认收货地址</span></a>
         </div>
       </div>
     </div>
     <!--抢到月饼 end-->
     
     
   </div>
   <!--弹出框 end-->
  
  
</div>
<script type="text/javascript"> 
$(function(){
//  $('.grab').click(function(){
//	  $('.opacity').show();
//	  
//  })
  
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
          $('.grab_over').hide();
	})
  
    //抢购倒计时
  function CountDown() {
	  this.y;
	  this.d;
	  this.h;
	  this.hr;
	  this.m;
          this.s;
	  this.startTime;
	  this.obj;
	  this._self;
  }

  //初始化对象
  CountDown.prototype.init = function () {
		  this.startTime.setFullYear(this.y, this.d, this.h);
		  this.startTime.setHours(this.hr);
		  this.startTime.setMinutes(this.m);
		  this.startTime.setSeconds(this.s);
		  this.startTime.setMilliseconds(999);
		  this.EndTime = this.startTime.getTime();
		  this._self = this;
		  this.GetRTime();
  }
  
  CountDown.prototype.GetRTime = function () {
	  var NowTime = new Date();
	  var nMS = this.EndTime - NowTime.getTime();
	  var nH = Math.floor(nMS / (1000 * 60 * 60));
	  var nM = Math.floor(nMS / (1000 * 60))%60;
	  var nS = Math.floor(nMS / 1000) % 60;
	  if (nMS > 0) {
		  this.obj.find(".hour").text(nH);
		  this.obj.find(".minute").text(nM);
		  this.obj.find(".sec").text(nS);
	  }
	  else{
		  this.obj.find(".hour").text("0");
		  this.obj.find(".minute").text("0");
		  this.obj.find(".sec").text("0");
		  }
	  var _self = this;
	  window.setTimeout(function (){_self.GetRTime();}, 1000);
  }
  var ObjArray = new Array();
  var TmpObject = new CountDown();
  function activeTime(year,month,day,hour,minute,second){
	  month=(month)?month-1:month;
	$(".residue_time").each(function () {
		TmpObject.startTime = new Date();
		TmpObject.y = year||parseFloat($(this).find(".year").text());
		TmpObject.d = month||parseFloat($(this).find(".month").text())-1;
		TmpObject.h = day||parseFloat($(this).find(".day").text());
		TmpObject.hr = hour||parseFloat($(this).find(".hour").text())||0;
		TmpObject.m = minute||parseFloat($(this).find(".minute").text())||0;
                TmpObject.s = second||parseFloat($(this).find(".second").text())||0;
		TmpObject.obj = $(this);
		var tmp = $(this).find(".hour");
		TmpObject.init();
	});
  }
  activeTime();
  
  
  function checkTime(){

    var NowTime = new Date();
    var m=NowTime.getMinutes();
    var colourlife_sec = NowTime.getSeconds();
    if(m==50 && (colourlife_sec==0 || colourlife_sec==8)){                          //活动结束
          $.post(
               '/robMoonCakes/flushByAjax',
               function (data){
                   if(data.success == "ok"){                       
                       $('#rob_grab').attr('class','grab no_grab');
                       $('.month').text(data.m);
                       $('.day').text(data.d);
                       $('.hour').text(data.h);
                       $('.minute').text(data.i);
                       $('.second').text(data.s);
                       activeTime('2015',data.m,data.d,data.h,data.i,data.s);
                       $('#remaining').text(data.remaining);
                       $('#colourlife_span').text("下一场："+data.timeRemainingNext+"点准时开始");
                       if(data.able == 1){                           
                           $('#coloulife_refush').text("距离本场结束");
                       }else{
                           $("#remaining").html(0);
                           $('#coloulife_refush').text("距离下场开始");
                       }                       
                   }                   
               }
          ,'json');
     }else if(m==0 && (colourlife_sec==0 || colourlife_sec==8)){ 
           $.post(
               '/robMoonCakes/flushByAjax',
               function (data){
                   if(data.success == "ok"){
                       $('#rob_grab').attr('class','grab');
                       $('#rob_grab').attr('href',"javascript:rob()");
                       $('.month').text(data.m);
                       $('.day').text(data.d);
                       $('.hour').text(data.h);
                       $('.minute').text(data.i);
                       $('.second').text(data.s);
                       activeTime('2015',data.m,data.d,data.h,data.i,data.s);
                       $('#remaining').text(data.remaining);
                       $('#colourlife_span').text("下一场："+data.timeRemainingNext+"点准时开始");
                       if(data.able == 1){
                           $('#coloulife_refush').text("距离本场结束");
                       }else{
                           $("#remaining").html(0);
                           $('#coloulife_refush').text("距离下场开始");
                       }
                       if(data.over){
                           location.href = location.href;
                       }
                   }                   
               }
          ,'json'); 
            
     }
}

    
setInterval(checkTime,1000);
  
})

//点击抢月饼
function rob(){
    var ColourTime = new Date();
    var colour_m=ColourTime.getMinutes();
    var colour_h = ColourTime.getHours();
    if(colour_h <9 || colour_h >17){
          $('.opacity').show();
          $("#grab_over3").show();  
          $('#rob_grab').attr('class','grab no_grab');
          return ;
    }
    if(colour_m>=50 && colour_m<59){                          //活动结束
          $('.opacity').show();
          $("#grab_over3").show();  
          $('#rob_grab').attr('class','grab no_grab');
          return ;
    }    
    $.post(
        '/robMoonCakes/rob',
        function (data){
            if(data == 1){
                $('.opacity').show();
                $('#grab_over4').show();   //抢到月饼
            }else if(data == 2){
                $('.opacity').show();
                $("#grab_over2").show();    //今天已抢到月饼，不能再抢
                $('#rob_grab').attr('class','grab no_grab');
                //$('#rob_grab').attr('href',"javascript:void()");
            }else{
                $('.opacity').show();
                $('#grab_over3').show();    //月饼抢光了
                $('#rob_grab').attr('class','grab no_grab');
                //$('#rob_grab').attr('href',"javascript:void()");
            }
        } 
    ,'json');
}

$('.addresslink').click(
    function (){
        $.post(
            '/robMoonCakes/getCustomerInfo',
            function (data){
                $('#linkman').val(data.name);
                $('#tel').val(data.tel);
                $('#colour_address').html(data.address);
            }
        ,'json');
        $('#grab_over4').hide();   
        $('#grab_over1').show();   
    }
);

$('#sure_address').click(
   function (){
       var linkman = $('#linkman').val();
       var tel = $('#tel').val();
       $.post(
           '/robMoonCakes/fillReceiving',
           {'linkman':linkman,'tel':tel},
           function (data){
               //$('.opacity').hide();
               //location.reload();
               location.href = "/luckyApp/myDumplings";
           }
       ,'json');
   }
);






</script> 
</body>
</html>
