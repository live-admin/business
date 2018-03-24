<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />

    <title>抢月饼</title>
    <link href="<?php echo F::getStaticsUrl('/common/css/lucky/august/lottery.css?time=7654321'); ?>" rel="stylesheet">
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=7654321'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js?time=7654321'); ?>"></script>



</head>

<body style="position:relative; background:#f7f3e8;">
<div class="lottery_topic">
    <div class="mn_head">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/mncake_head.jpg'); ?>" class="lotteryimg"/>
        <a href="/robMoonCakes/rule" class="mn_aciverule">活动规则</a>
    </div>
    <div class="mn_content" style="margin-bottom:0;">
        <div class="mn_content_ct">
            <h3>
                <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/mn_tag.jpg'); ?>" class="lotteryimg"/></span>
                福利一：0.1元抢月饼
            </h3>
            <div class="mn_part">
                <dl id="ticker">
                    <?php foreach($newInfo as $_v){ ?>
                        <dt><?php echo $_v; ?></dt>
                    <?php } ?>
                </dl>
                <div class="line"></div>
                <p style="text-align:left;">
                    <span id="coloulife_refush2"><?php if($able == 1){ echo '本';}else{ echo '下'; }?>场剩余月饼</span>: <span class="nexttime" id="remaining"><?php if($able == 1){ echo $remaining;}else{ echo "100"; } ?></span> 份，<span class="nexttime" id="colourlife_span"><?php echo $timeRemainingNext?"下一场: ".date("d日H点",strtotime($timeRemainingNext))."准时开启":"活动已全部结束"; ?></span>
                </p>
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
                <p style="font-size:14px; margin:10px 0;">
                    <span id="coloulife_refush">距离<?php if($able == 1){ ?>本场结束<?php }else{ ?>下场开始<?php } ?></span>
                    还有：<span class="hour"><?php echo $h; ?></span>小时<span class="minute"><?php echo $i; ?></span>分<span class="sec"><?php echo $s; ?></span>秒
                </p>
                </div>

                <a href="javascript:void(0);" id="luck_tip" class="mn_btn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/mn_btn.jpg'); ?>" class="lotteryimg"/></a>
                <p style="margin:10px 0 15px;">每参加1次抢月饼会扣除0.1元红包</p>
                <a href="/robMoonCakes/mylottery" class="mn_result_btn">抢月饼结果</a>
            </div>
            <div class="mn_part">
                <h3 style="padding:0;">
                    <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/mn_tag.jpg'); ?>" class="lotteryimg"/></span>
                    福利二 ：满百返红包
                </h3>
                <p style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8月11日至8月31日，凡是上彩之云App和网站天天团购买标题带有“返红包”的月饼套餐，单个套餐交易成功返回一定金额的红包，单套餐最高返35元红包，红包在订单交易成功后次日内到帐，指定套餐多买多返，不设上限。</p>
            </div>
            <div class="mn_part">
                <h3 style="padding:0;">
                    <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/mn_tag.jpg'); ?>" class="lotteryimg"/></span>
                    福利三 ：购买送抽奖
                </h3>
                <p style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8月11日至8月31日，凡是上彩之云App和网站天天团购买任意月饼套餐，单个订单购买成功就可以获赠10次抽奖机会，抢百万现金红包。</p>
                <p style="text-align:left;">说明：1、单个订单购买成功立即获赠5次抽奖机会，剩下5次抽奖机会次日内赠送完。<br/>2、抽奖机会按订单赠送，单个订单多个月饼套餐也是赠送10次抽奖机会。</p>
            </div>
        </div>


    </div>

   <div class="lottery_bottom">
        <a href="/luckyApp" style="margin:0 auto 30px; background:#fbe3ab; color:#c70b3c;">返回</a>
      </div>
</div>


<!--弹出框 start-->
<div class="opacity" style="display:none;">
    <!--扣款提示 start-->
    <div class="mn_alertbox" id="grab_over0" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <p>亲，每参加1次抢月饼会扣除0.1元红包哦。确认红包满满的 &gt;&gt;</p>
                <a href="javascript:rob();" class="qiang">
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/qiang.jpg'); ?>" class="lotteryimg"/>
                </a>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:void(0);" class="closeOpacity"><span>放弃</span></a>
            </div>
        </div>
    </div>
    <!--扣款提示 end-->
    <!--确定收货地址 start-->
    <div class="mn_alertbox" id="grab_over1" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo" style="text-align:left; color:#505050;">
                <h3>确认您的地址</h3>
                <p id="colour_address"></p>
                <p><label>姓名：</label><input type="text" class="Tnamenum" id="linkman" defaultTxt="请输入收货人姓名" /></p>
                <p><label>电话：</label><input type="text" class="Tnamenum" id="tel" defaultTxt="请输入电话号码" /></p>
                <div class="line" style="border-color:#bca270"></div>
                <dl>
                    <dt>温馨提示：</dt>
                    <dd>1、变更或者修改收货人及联系电话请先修改后确认。</dd>
                    <dd>2、收货地址不正确，请在"我-我的账户"中修改。</dd>
                    <dd>3、如果您中奖时没有确认收货信息，可以在"抢月饼结果"中再次确认。</dd>
                </dl>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:void(0);" class="closeOpacity confirm" id="sure_address"><span>确定</span></a>
            </div>
        </div>
    </div>
    <!--确定收货地址 end-->
    <!--中了月饼之后再抢 start-->
    <div class="mn_alertbox" id="grab_over2" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <div style="padding:30px 0 0 30px;">
                    <p>每天每个用户最多抢到一盒月饼哦~</p>
                    <p>您今天好运满满，留一点给别人吧！</p>
                </div>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--中了月饼之后再抢 end-->
    <!--活动还没开始 start-->
    <div class="mn_alertbox" id="grab_over5" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <div style="padding:30px 0 0 30px;">
                    <p>亲，活动还未开始，请耐心等待。</p>
                </div>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="/robMoonCakes/mylottery" class="closeOpacity confirm"><span>抢月饼结果</span></a>
                <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--活动还没开始 end-->
    <!--红包余额不足 start-->
    <div class="mn_alertbox" id="grab_over6" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <div style="padding:30px 0 0 30px; text-align:left;">
                    <p>亲，您的红包余额不足，无法参加活动。快开启月光宝盒穿越领红包吧！</p>
                    <a href="/luckyApp" style="display:block; margin-top:20px; color:#c30937; font-size:14px;">&gt;&gt;出发</a>
                </div>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="/robMoonCakes/mylottery" class="closeOpacity confirm"><span>抢月饼结果</span></a>
                <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--红包余额不足 end-->
    <!--月饼抢光了 start-->
    <div class="mn_alertbox" id="grab_over3" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <div style="padding:10px 0 0 10px;">
                    <p>很遗憾，亲，本场月饼已抢光，敬请关注下一场</p>
                </div>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="/robMoonCakes/mylottery" class="closeOpacity confirm"><span>抢月饼结果</span></a>
                <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--月饼抢光了 end-->
    <!--没抢到月饼 start-->
    <div class="mn_alertbox" id="grab_over7" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <div style="padding:10px 0 0 10px;">
                    <p>呜呜，抢的人太多了，手慢了一步，本次没有抢到，赶紧再试一次</p>
                </div>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="/robMoonCakes/mylottery" class="closeOpacity confirm"><span>抢月饼结果</span></a>
                <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--没抢到月饼 end-->
    <!--抢到月饼 start-->
    <div class="mn_alertbox" id="grab_over4" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo">
                <p style="color:#c30937; text-align:center;">鸿运当头，福利月饼一盒到手啦！</p>
                <a href="javascript:void(0);" class="qiang">
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/mooncake_img.jpg'); ?>" class="lotteryimg"/>
                </a>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:void(0);"  class="addresslink confirm"><span>确认收货地址</span></a>
            </div>
        </div>
    </div>
    <!--抢到月饼 end-->


</div>
<!--弹出框 end-->

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
                '/robMoonCakes/flushByAjax',
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
                '/robMoonCakes/flushByAjax',
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
//        $("#grab_over5").show();
//
//        return ;
//    }
//    if(colour_m>=50 && colour_m<59){                          //活动结束
//        $('.opacity').show();
//        $("#grab_over5").show();
//
//        return ;
//    }
    $.post(
        '/robMoonCakes/rob',
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
            '/robMoonCakes/getCustomerInfonew',
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
            '/robMoonCakes/fillReceivingnew',
            {'linkman':linkman,'tel':tel},
            function (data){
                //$('.opacity').hide();
                //location.reload();
                location.href = "/robMoonCakes/mylottery";
            }
            ,'json');
    }
);






</script>
</body>
</html>