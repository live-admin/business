<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
        <meta name="format-detection" content="telephone=no" />
        <meta name="MobileOptimized" content="320"/>
    <title>彩生活携手蒙牛优惠大酬宾</title>
    <link href="<?php echo F::getStaticsUrl('/common/css/lucky/october/newmilk.css'); ?>" rel="stylesheet" />
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>" type="text/javascript"></script>
  </head>
  


  <body>
    <div class="eLoan">
            <div class="head">
              <img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/milk/head.jpg');?>" class="boximg" />
            </div>

            <!-- 增加开始 -->
            <div class="mn_part" style="color:#fff; margin-left:10%; position:relative; top:-20px;">
               
                <p style="margin-top:20px; margin-bottom:5px;">
                    <span id="coloulife_refush2"><span class="nexttime" id="colourlife_span"><?php echo  ($status==1||$status==3)&&$timeRemainingNext?"下一场：".date("d日H点",strtotime($timeRemainingNext))."准时开启":"本次活动已全部结束"; ?></span>
                </p>
                <div class="residue_time">
                  <!--目标时间start-->
                  <p style="display: none;">
                      <span class="year">2015</span>
                      <span class="month"><?php echo $m; ?></span>
                      <span class="day"><?php echo $d; ?></span>
                      <span class="colourlife_h"><?php echo $h; ?></span>
                      <span class="colourlife_i"><?php echo $i; ?></span>
                      <span class="colourlife_s"><?php echo $s; ?></span>
                  </p>
                  <!--目标时间end-->
                  <div class="time_p">
                    <p>                    
                      <?php if($status==1){?>
                          <span id="coloulife_refush">距离活动开始</span> 还有：<span class="hour"><?php echo $h; ?></span>小时<span class="minute"><?php echo $i; ?></span>分<span class="sec"><?php echo $s; ?></span>秒
                      <?php }?>

                      <?php if($status==3){?>
                          <span id="coloulife_refush">距离本场活动结束</span> 还有：<span class="hour"><?php echo $h; ?></span>小时<span class="minute"><?php echo $i; ?></span>分<span class="sec"><?php echo $s; ?></span>秒
                      <?php }?>

                      <?php if($status==2){?>
                          <span id="coloulife_refush">活动已全部结束</span>
                      <?php }?>                    
                    </p>
                    <!-- <p>已有<span><?php //echo $allJoin;?></span>位业主参与此活动</p> -->
                  </div>
                </div> 
            </div> 
            <!-- 增加结束 -->


            <div class="indexcontent">
                <a  href="javascript:void(0);" class="apply"  id="<?php echo $status==3?'luck_tip':'';?>"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/milk/buy.jpg');?>" class="boximg" /></a>
              <p style="font-size:14px; text-align:right;"><a href="/ingMilk/mylottery" style="color:#fff; text-decoration:underline; margin-right:10%;">我的抢购纪录</a></p>
              <dl>
                <dt>购买说明：</dt>
                <dd>1、抢购时间：3月26日-3月29日，每天11点开抢， 每次限量100，抢完即止。</dd>
                <dd>2、本次抢购仅开放给碧水龙庭、七星广场、南国丽园、友邻公寓、华景园用户，每个用户限制抢购一支。</dd>
                <dd>3、抢购结束后，3个工作日内由彩生活客户经理配送到家。</dd>
                <dd>4、彩生活服务集团享受法律范围的活动最终解释权。</dd>
              </dl>
            </div>
      
    </div>
        <!--弹出框-->
        <div class="opacity"  style="display:none;">
           <!--抢中 start-->
           <div class="alertcontairn" id="grab_over4" style="display:none;">
             <div class="ac_content">
               <div class="textinfo">
                 <div class="justword">
                   <div class="center-img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/milk/milk.jpg');?>" class="boximg" /></div>
                   <p style="font-size:14px;">恭喜你抢到了纯甄牛奶一支</p>
                 </div>
               </div>
               <a href="javascript:void(0);"  class="addresslink register_btn" style="margin-bottom:20px;">确认收货地址</a>
             </div>
           </div>
           <!--抢中 end-->
           <!--收货地址 start-->
           <div class="alertcontairn" id="grab_over1" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <div class="justword" style="padding:15px">
                   <p style="font-size:14px; margin-bottom:10px;">确认您的地址：</p>
                   <div class="address">
                     <p id="colour_address"></p>
                     <table>
                       <tr>
                         <th>收货人：</th>
                         <td><input type="text" id="linkman" class="ads_input"/></td>
                       </tr>
                       <tr>
                         <th style="height:15px;"></th>
                         <td style="height:15px;"><p class="errortip_linkman" style="color:red;"></p></td>
                       </tr>
                       <tr>
                         <th>联系电话：</th>
                         <td><input type="text" id="tel" class="ads_input" /></td>
                       </tr>
                       <tr>
                         <th style="height:15px;"></th>
                         <td style="height:15px;"><p class="errortip_tel" style="color:red;"></p></td>
                       </tr>
                       <tr>
                         <th>楼栋：</th>
                         <td><input type="text" id="build" class="ads_input" /></td>
                       </tr>
                       <tr>
                         <th style="height:15px;"></th>
                         <td style="height:15px;"><p class="errortip_build" style="color:red;"></p></td>
                       </tr>
                        <tr>
                         <th>房号：</th>
                         <td><input type="text" id="room" class="ads_input" /></td>
                       </tr>
                       <tr>
                         <th style="height:15px;"></th>
                         <td style="height:15px;"><p class="errortip_room" style="color:red;"></p></td>
                       </tr>
                     </table>
                     <dl>
                       <dt style="margin:30px 0 10px;">温馨提示：</dt>
                       <dd>1、变更或者修改收货人及联系电话请先修改后确认。</dd>
                       <dd>2、收货地址不正确，请在"我-我的账户"中修改。</dd>
                     </dl>
                   </div>
                 </div>
               </div>
               <a href="javascript:void(0);" class="register_btn" id="sure_address" style="margin-bottom:20px;">确认收货地址</a>
             </div>
           </div>
           <!--收货地址 end-->
           <!--扣款提示 start-->
           <div class="alertcontairn" id="grab_over0"  style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:30px auto;"><a href="javascript:rob();" class="confirmlink">亲，抢购成功才会扣除0.1元红包哦。 请确认红包满满的 &gt;&gt;</a></p>

                 <div class="center-img" style="width:222px;"><a href="javascript:rob();"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/milk/bbuy.jpg');?>" class="boximg" /></a></div>
               </div>
               <a href="javascript:void(0);" class="cancel closeOpacity" style="margin-bottom:20px;">放弃</a>
             </div>
           </div>
           <!--扣款提示 end-->
           <!--抢中再抢 start-->
           <div class="alertcontairn" id="grab_over2" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;">每个用户最多抢购一支牛奶，留一点给别人吧！<br/><a href="/luckyApp/SpecialTopic?cust_id=<?php echo $userId;?>" style="text-decoration
                  :underline;color:red;">不够喝，买一箱也有优惠哦〉〉〉</a></p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--抢中再抢 end-->

          <!--抢中再抢 start-->
           <div class="alertcontairn" id="grab_over5" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;">亲，活动还未开始，请耐心等待。</p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--抢中再抢 end-->

           <!--余额不足 start-->
           <div class="alertcontairn" id="grab_over6" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;">亲，红包余额不足，抢不了哦！<a href="/luckyApp/SpecialTopic?cust_id=<?php echo $userId;?>" style="text-decoration
                  :underline;color:red;">喜欢纯甄？买一箱也有优惠哦〉〉〉</a></p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--余额不足 end-->
           <!--抢光 start-->
           <div class="alertcontairn" id="grab_over3" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;"><a href="/luckyApp/SpecialTopic?cust_id=<?php echo $userId;?>" class="confirmlink" style="text-decoration
                  :underline;color:red;">很遗憾，牛奶已经抢完了，看看其它活动吧！</a></p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--抢光 end-->
           <!--收货信息提交成功 start-->
           <div class="alertcontairn" id="grab_over8" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;"><a href="" class="confirmlink">收货信息提交成功</a></p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--收货信息提交成功 end-->
           
           <!--收货信息提交成功 start-->
           <div class="alertcontairn" id="grab_over9" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;">您不是彩科彩悦的业主，没有权限参加抢购。</p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--收货信息提交成功 end-->

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


    $(function(){
        $('.Tnamenum').cl_inputDefaultTxt({});

    });
</script>

<script type="text/javascript">
$(function(){
  $('#luck_tip').click(function(){
      $('.opacity').show();
      $('#grab_over0').show();
  })

  $('.closeOpacity').click(function(){
      $('.opacity').hide();
      $('.alertcontairn').hide();
  })

})

function opctyHeight(){
  $('.opacity').css('height',$(document).height());
}
window.onload=window.onresize=opctyHeight;


</script>

<script type="text/javascript">
$(function(){
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

})

//点击抢牛奶
function rob(){
    $('#grab_over0').hide();
    $.post(
        '/ingMilk/rob',
        function (data){
            if(data == 1){
                $('.opacity').show();
                $('#grab_over4').show();   //抢到月饼
            }else if(data == 2){
                $('.opacity').show();
                $("#grab_over2").show();    //今天已抢到月饼，不能再抢
            }else if(data == 3){
                $('.opacity').show();
                $("#grab_over6").show();    //红包余额不足
            }else if(data == 4){
                $('.opacity').show();
                $("#grab_over5").show();    //活动暂未开始
            }else if(data == 5){
                $('.opacity').show();
                $("#grab_over7").show();    //没抢到
            }else if( data== 9){
                $('.opacity').show();
                $("#grab_over9").show();    //没权限
            }else{
                $('.opacity').show();
                $('#grab_over3').show();    //月饼抢光了

            }
        }
        ,'json');
}

$('.addresslink').click(
    function (){
        $.post(
            '/newMilk/getCustomerInfonew',
            function (data){
                $('#linkman').val(data.name);
                $('#tel').val(data.tel);
                $('#build').val(data.build);
                $('#room').val(data.room);
                $('#colour_address').html(data.community);
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
        var build = $('#build').val();
        var room = $('#room').val();
        var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/; 
        if(linkman==''){
           $('.errortip_linkman').text('收货人不能为空');
           return;
        }else if(tel==''){
           $('.errortip_tel').text('手机号码不能为空');
           return;
        }else if(!(reg.test(tel))){
           $('.errortip_tel').text('手机号码格式不正确');
           return;
        }else if(build==''){
           $('.errortip_build').text('楼栋不能为空');
           return;
        }else if(room==''){
           $('.errortip_room').text('房间号不能为空');
           return;
        }else{
          $.post(
              '/newMilk/fillReceivingnew',
              {'linkman':linkman,'tel':tel,'build':build,'room':room},
              function (data){
                  $('.alertcontairn').hide();
                  $('.opacity').show();
                  // $('#grab_over4').hide();
                  $("#grab_over8").show();    //提交成功
              }
              ,'json');
        }
    }
);
</script>
</body>

</html>