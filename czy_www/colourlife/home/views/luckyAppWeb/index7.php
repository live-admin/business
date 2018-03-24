<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>百万大奖</title>
    <link href="<?php echo F::getStaticsUrl('/common/css/lucky/august/lottery.css?time=76543210'); ?>" rel="stylesheet">
    <link href="<?php echo F::getStaticsUrl('/common/css/lucky/september/crab.css?time=123'); ?>" rel="stylesheet">
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
</head>

<body style="background:#7c2f35; position:relative;">
<div class="lottery_topic_web">
    <div class="lottery" style="width:974px; margin:0 auto;">

        <div class="lotteryList">
            <p class="changebox">
                <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
                您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span> 次机会，已有<span id=""><?php echo $allJoin; ?></span>人次参加
            </p>
            <dl id="ticker">
                <?php foreach($listResult as $result){ ?>  
                    <dt><?php echo $result['msg']; ?></dt>
                <?php } ?>
            </dl>
            <div class="moon"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/moon.png'); ?>" class="lotteryimg"/></div>
        </div>

        <div class="moonlottery" id="moonLucky">

            <div class="moonboxclose"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/moonboxWeb.png'); ?>" class="lotteryimg"/></div>
            <div class="moonboxopen" style="display:none;">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/moonbox1Web.png'); ?>" class="lotteryimg"/>
                <div class="yan" style="display:none;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/yanWeb.png'); ?>" class="lotteryimg"/></div>
            </div>
            <div class="moonlight"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/lightWeb.png'); ?>" class="lotteryimg"/></div>
        </div>

        <div class="lotteryrule">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/clickhereWeb.png'); ?>"/>
            <a href="javascript:void(0);" class="lotterybtn throw"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/lotterybtn1Web.png'); ?>" class="lotteryimg"/></a>
            <a href="/luckyAppWeb/lotteryrule" class="rulebtn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/lotterybtn2Web.png'); ?>" class="lotteryimg"/></a>
            <a href="/luckyAppWeb/mylottery" class="rulebtn1"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/lotterybtn3Web.png'); ?>" class="lotteryimg"/></a>
            <a href="/luckyAppWeb/lotteryrule" class="lotterybtn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/lotterybtn5Web.png'); ?>" class="lotteryimg"/></a>
        </div>


    </div>
    <!--抢月饼 start-->
    <div class="mooncake">
        <div class="adver">
            <a href="http://<?php echo HOME_DOMAIN;?>/crabWeb">
                <img class="lotteryimg" src="<?php echo F::getStaticsUrl('/common/images/lucky/august/crabbanner.jpg');?>" />
            </a>
        </div>
    </div>
    <!--抢月饼 start-->
    
</div>

<div class="gloab_light"></div>

<!--弹出框 start-->
<div class="opacity opacityWeb" style="display:none;">

<!--手气用完 start-->
    <div class="alertcontairn" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo" style="width:70%;">
         <p style="color:#b20224; font-size:24px; line-height:150%; margin:150px 0 20px;">亲，你的穿越领红包次数用光了。</p>
         <p style="font-size:20px;">
           邀请邻居注册成功可获得5次穿越领红包机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont" style="font-size:20px;">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--手气用完 end-->


   <!--每天5次 start-->
   <div class="alertcontairn0" style="display:none;">
     <div class="alertcontairn_content">
       <div class="textinfo" style="width:70%;">
        <p style="color:#b20224; font-size:24px; line-height:150%; margin:150px 0 20px;">亲，每天只能穿越领红包5次哦，明天再来抽大奖吧！</p>
       
         <p style="font-size:20px;">
           邀请邻居注册成功可获得5次穿越领红包机会哦。<br />
           满10位好友得50元红包，<a href="/invite" class="redfont" style="font-size:20px;">&gt;&gt;立即邀请</a>
         </p>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--每天5次 end-->


<!--谢谢参与 start-->
<div class="alertcontairn1" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo" style="padding-left:70px;">

        </div>
        <div class="pop_btn">
            <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--谢谢参与 end-->


<!--民国 start-->
<div class="alertcontairn2" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <a href="javascript:void(0);" class="redpackclose">
                <span>0.18</span>元
            </a>
        </div>
        <div class="pop_btn">
            <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--民国 end-->


<!--清朝 start-->
<div class="alertcontairn3" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <a href="javascript:void(0);" class="redpackclose">
                <span>1.8</span>元
            </a>
        </div>
        <div class="pop_btn">
            <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--清朝 end-->


<!--明朝 start-->
<div class="alertcontairn4" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <a href="javascript:void(0);" class="redpackclose">
                <span>8.8</span>元
            </a>
        </div>
        <div class="pop_btn">
            <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--明朝 end-->


<!--元朝 start-->
<div class="alertcontairn5" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <a href="javascript:void(0);" class="redpackclose">
                <span>18</span>元
            </a>
        </div>
        <div class="pop_btn">
            <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--元朝 end-->


<!--宋朝 start-->
<div class="alertcontairn6" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <a href="javascript:void(0);" class="redpackclose">
                <span>88</span>元
            </a>
        </div>
        <div class="pop_btn">
            <a href="/luckyAppWeb/mylottery">查看中奖情况</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--宋朝 end-->


<!--唐朝 start-->
<div class="alertcontairn7" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <a href="javascript:void(0);" class="redpackclose">
                <span>5000</span>元
            </a>
        </div>
        <div class="pop_btn">
            <a href="/LuckyAppWeb/dajiangshuomingw" class="bigpriceBtn">红包发放说明</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--唐朝 end-->


<!--隋朝 start-->
<div class="alertcontairn8" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <div class="alertimg" style="margin:75px auto 20px; width:253px;">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/alertimg.jpg'); ?>"/>
            </div>
            <p style="color:#b20224; font-size:24px; text-align:center; line-height:150%; margin:0;">您获得品果精品美味礼盒</p>
            <p style="text-align:center;">（图片为奖品之一，具体查看奖品发放规则）</p>
        </div>
        <div class="pop_btn">
            <a href="/LuckyAppWeb/shuomingw" class="priceBtn">奖品发放说明</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--隋朝 end-->


<!--三国 start-->
<div class="alertcontairn9" style="display:none;">
    <div class="alertcontairn_content">
        <div class="textinfo">
            <div class="alertimg" style="margin:175px auto 20px; width:266px;">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/dxcz.jpg'); ?>"/>
            </div>
            <p style="color:#b20224; font-size:24px; text-align:center; line-height:150%; margin:0;">您获得中国电信10元手机话费</p>
        </div>
        <div class="pop_btn">
            <a href="javascript:void(0);" class="lingqu">奖品领取</a>
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
        </div>
    </div>
</div>
<!--三国 end-->


<!--输入电信手机号码 start-->
<div class="alertcontairn10" style="display:none; padding-bottom:10px; height:auto;">
    <div class="alertcontairn_content">
        <div class="textinfo" style="width:82%;">
            <p style="margin-top:138px;">请输入您充值的中国电信手机号码：</p>
            <p><input type="text" class="moblie_number" id="moblie_number" value="" defaultTxt="您的电信手机号码" style="margin:0; font-size: 16px; height: 32px;line-height: 32px;"/></p>
            <p class="errortip" style="color:red; height:20px;"></p>
            <p>提示：</p>
            <p>1、为了保障能顺利获得奖品，请您务必登记您的中国电信手机号码。</p>
            <p>2、中国电信系统将在中奖后20天内将10元话费充值到您登记的中国电信手机号码。</p>
            <p>3、如果您登记的不是中国电信号码，我们将无法保证为您的手机充值。</p>
        </div>
        <div class="pop_btn">
            <a href="javascript:mobileNumber();" class="submit" style="margin-top:10px;">提交</a>
            <a href="javascript:void(0);" class="closeOpacity" style="margin-top:10px;">关闭</a>
        </div>
    </div>
</div>
<!--输入电信手机号码 end-->

<!--提示输入电信号码 start-->
     <div class="alertcontairn11" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo" style="width:70%;">
           <p style="text-align:center; margin-top:180px; font-size:24px;">
            本次穿越领红包仅限电信号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity"><span></span></a>
         </div>
       </div>
     </div>
<!--提示输入电信号码 end-->




<!--抢月饼弹出框 end-->

<!--抢到月饼 end-->

</div>
<!--弹出框 end-->




</div>
<script type="text/javascript">
    /*
     * name		: $(node).cl_inputDefaultTxt(opt)
     * content	: input标签添加默认文本，聚焦时，默认文本消失
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
      $('.moblie_number').cl_inputDefaultTxt({});
    })

    var e = jQuery.Event("click");
    $(".moonboxclose").click(function(){
      $(".throw").trigger(e);
    });   


    $('.closeOpacity').click(function(){
        $('.opacity').hide();
        Init();
    })

    $('.lingqu').click(function(){//点击领取电信充值弹出框
        $('.opacity>div').eq(10).hide();
        center($('.opacity>div').eq(11).show());

    })


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
        }

          $.post(
            '/luckyAppWeb/telecom',
            {'mobile':moblie_number},
            function (data){
                if(data == 1){
                    $('.opacity').hide();
                    $('.alertcontairn10').hide();
                    // location.href = "/luckyAppWeb/mymoonlottery";
                    location.reload();
                }else{
                    $('.alertcontairn10').hide();
                    center($('.alertcontairn11').show());
                }
            }
        ,'json');
      

    }




    //穿越
    function Init(){//初始化
        $(".moonlight").css('height','1px');
        $('.moonboxclose').show();
        $('.moonboxopen').hide();
        $('.opacity>div').hide();
        $(".yan").css('height','1px');
        if(gloab_klint == 2){
            $('.throw').unbind('click');
        }else{
            $('.throw').bind('click',function(){
                $(this).unbind('click');
                 lottery();
            });

        }

    }

    var gloab_klint = 0;
    function lottery(){    
        var postCan=parseInt($("#luckyTodayCan").text());
        var custCan=parseInt($("#luckyCustCan").text());
        if(custCan<1){
            $('.opacity').show();
            center($('.alertcontairn').show());
            return;
        }
        if(postCan<1){
              $('.opacity').show();
              center($('.alertcontairn0').show());
              return;
        }

        aniDiv();

    }

    function sceneShow(scene){
        $('.opacity').show();
        var obj=$('.opacity>div').eq(scene);
        center(obj);
        $('.opacity>div').eq(scene).siblings().hide();
        $('.gloab_light').hide();//隐藏渐白div
        $(".gloab_light").css('opacity','0');//设渐白div为完全透明

    }

    function aniDiv(){//月光洒下来
        $(".moonlight").animate({height:492},2000,boxShake);
    }
    function boxShake(){//月光宝盒晃动
        for(i=0;i<13;i++){
            if(i<12){
                $('.moonboxclose').animate({left:333},50);
                $('.moonboxclose').animate({left:337},50);
            }
            else{
                $('.moonboxclose').animate({left:335},200,boxOpen);
            }
        }
    }
    function boxOpen(){//月光宝盒打开
        $('.moonboxclose').hide();
        $('.moonboxopen').show();

        $(".yan").animate({height:281},10,function(){sceneShow(getData());});//烟冒出来,调用场景透明渐变
    }

    // function opacityWhite(){//场景透明渐变
    //     $('.gloab_light').show();
    //     $('.gloab_light').animate({opacity:1},2000,function(){sceneShow(getData());});  //场景渐白，调用弹出框;
    // }

    //得到数据
    function getData(){
       var klint = 2; 
       $.ajax({ 
            type: "POST",
            url: "/luckyAppWeb/doMoonLucky",
            data: "actid=6",
            dataType:'json',
            async: false, 
            error:function(){           
                klint = 2;         
            },

            success:function(data){
                var postCan=parseInt($("#luckyTodayCan").text());
                var custCan=parseInt($("#luckyCustCan").text());
                $("#luckyTodayCan").text(postCan-1);
                $("#luckyCustCan").text(custCan-1);
                if(data.success==0 && data.data.location==1){
                    location.href=data.data.href;
                    return;
                }

                if(data.success==0){    
                    gloab_klint = klint = 2;
                }else{          
                    getPrize=data.data.result;
                    klint = showPackag(getPrize);         
                }
            }
   
        }); 
        return klint; 
    }
  
    //根据结果弹出红包
    function showPackag(prize){
        var prizeid=parseInt(prize.id);
        if(prizeid == 60){
          klint = 2;
        }else if(prizeid == 54){
          $('#bonus_amount3').html(prize.rednum);    
          klint = 3;
        }else if(prizeid == 55){   
          $('#bonus_amount4').html(prize.rednum);
          klint = 4;
        }else if(prizeid == 56){
          $('#bonus_amount5').html(prize.rednum);    
          klint = 5;
        }else if(prizeid == 57){  
          $('#bonus_amount6').html(prize.rednum);      
          klint = 6;
        }else if(prizeid == 58){
          $('#bonus_amount7').html(prize.rednum);        
          klint = 7;
        }else if(prizeid == 59){ 
          $('#bonus_amount8').html(prize.rednum);       
          klint = 8;
        }else if(prizeid == 61){       
          klint = 9;   
        }else if(prizeid == 62){      
          klint = 10;
        }else{
          klint = 2;
        }
        return klint;
    }

    $('.throw').bind('click',function(){
        $(this).unbind('click');
        lottery();
    });

</script>

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
$(function(){
    //
    $('#luck_tip').click(function(){
        $('.opacity').show();
        center($('#grab_over0'));
        $('#grab_over0').siblings().hide();
        $('#grab_over0').show();
    })

    $('.closeOpacity').click(function(){
        $('.opacity').hide();
        $('.mn_alertbox').hide();
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
                '/luckyAppWeb/flushByAjax',
                function (data){
                    if(data.success == "ok"){

                        $('.month').text(data.m);
                        $('.day').text(data.d);
                        $('.hour').text(data.h);
                        $('.minute').text(data.i);
                        $('.second').text(data.s);
                        activeTime('2014',data.m,data.d,data.h,data.i,data.s);
                        $('#remaining').text(data.remaining);
                        $('#colourlife_span').text("下一场："+data.timeRemainingNext+"点准时开始");
                        if(data.able == 1){
                            $('#coloulife_refush').text("距离本场结束");
                            $('#coloulife_refush2').text("本场剩余月饼");
                        }else{
                            $("#remaining").html(0);
                            $('#coloulife_refush').text("距离下场开始");
                            $('#coloulife_refush2').text("下场剩余月饼");
                        }
                    }
                }
                ,'json');
        }else if(m==0 && (colourlife_sec==0 || colourlife_sec==8)){
            $.post(
                '/luckyAppWeb/flushByAjax',
                function (data){
                    if(data.success == "ok"){
                        $('.month').text(data.m);
                        $('.day').text(data.d);
                        $('.hour').text(data.h);
                        $('.minute').text(data.i);
                        $('.second').text(data.s);
                        activeTime('2014',data.m,data.d,data.h,data.i,data.s);
                        $('#remaining').text(data.remaining);
                        $('#colourlife_span').text("下一场："+data.timeRemainingNext+"点准时开始");
                        if(data.able == 1){
                            $('#coloulife_refush').text("距离本场结束");
                            $('#coloulife_refush2').text("本场剩余月饼");
                        }else{
                            $("#remaining").html(0);
                            $('#coloulife_refush').text("距离下场开始");
                            $('#coloulife_refush2').text("下场剩余月饼");
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
    $('#grab_over0').hide();
//    if(colour_h !=10 && colour_h !=16){
//        $('.opacity').show();
//        center($('#grab_over5'));
//        $('#grab_over5').siblings().hide();
//        $('#grab_over5').show();
//
//        return ;
//    }
//    if(colour_m>=50 && colour_m<59){                          //活动结束
//        $('.opacity').show();
//        center($('#grab_over5'));
//        $('#grab_over5').siblings().hide();
//        $('#grab_over5').show();
//
//        return ;
//    }
    $.post(
        '/luckyAppWeb/rob',
        function (data){
            if(data == 1){
                $('.opacity').show();
                center($('#grab_over4'));
                $('#grab_over4').siblings().hide();
                $('#grab_over4').show();   //抢到月饼
            }else if(data == 2){
                $('.opacity').show();
                center($('#grab_over2'));
                $('#grab_over2').siblings().hide();
                $("#grab_over2").show();    //今天已抢到月饼，不能再抢

            }else if(data == 3){
                $('.opacity').show();
                center($('#grab_over6'));
                $('#grab_over6').siblings().hide();
                $("#grab_over6").show();    //红包余额不足

            }else if(data == 4){
                $('.opacity').show();
                center($('#grab_over5'));
                $('#grab_over5').siblings().hide();
                $("#grab_over5").show();    //活动暂未开始

            }else if(data == 5){
                $('.opacity').show();
                center($('#grab_over7'));
                $('#grab_over7').siblings().hide();
                $("#grab_over7").show();    //月饼没抢到

            }else{
                $('.opacity').show();
                center($('#grab_over3'));
                $('#grab_over3').siblings().hide();
                $('#grab_over3').show();    //月饼抢光了

            }
        }
        ,'json');
}

$('.addresslink').click(
    function (){
        $.post(
            '/luckyAppWeb/getCustomerInfo',
            function (data){
                $('#linkman').val(data.name);
                $('#tel').val(data.tel);
                $('#colour_address').html(data.address);
            }
            ,'json');
        $('#grab_over4').hide();
        center($('#grab_over1'));
        $('#grab_over1').siblings().hide();
        $('#grab_over1').show();
    }
);

$('#sure_address').click(
    function (){
        var linkman = $('#linkman').val();
        var tel = $('#tel').val();
        $.post(
            '/luckyAppWeb/fillReceiving',
            {'linkman':linkman,'tel':tel},
            function (data){
                //$('.opacity').hide();
                //location.reload();
                location.href = "/luckyAppWeb/mymoonlottery";
            }
            ,'json');
    }
);






</script>
</body>
</html>
