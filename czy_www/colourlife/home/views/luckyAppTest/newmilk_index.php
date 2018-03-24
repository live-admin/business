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
            <div class="indexcontent">
              <a  href="javascript:rob();" class="apply"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/milk/buy.jpg');?>" class="boximg" /></a>
              <dl>
                <dt>购买说明：</dt>
                <dd>1、本次抢购只开放给彩科彩悦用户，限量500支，抢完为止；</dd>
                <dd>2、每个用户限制抢购一支；</dd>
                <dd>3、抢购结束后，2个工作日内由彩生活客户经理将纯甄酸牛奶配送到家；</dd>
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
                     <!-- <p style="font-size:12px;">彩悦彩科大厦</p> -->
                     <table>
                       <tr>
                         <th>收货人：</th>
                         <td><input type="text" id="linkman" class="ads_input"/></td>
                         <p class="errortip_linkman" style="color:red; height:20px;"></p>
                       </tr>
                       <tr>
                         <th>联系电话：</th>
                         <td><input type="text" id="tel" class="ads_input" /></td>
                         <p class="errortip_tel" style="color:red; height:20px;"></p>
                       </tr>
                        <tr>
                         <th>楼栋：</th>
                         <td><input type="text" id="build" class="ads_input" /></td>
                         <p class="errortip_build" style="color:red; height:20px;"></p>
                       </tr>
                        <tr>
                         <th>房号：</th>
                         <td><input type="text" id="room" class="ads_input" /></td>
                         <p class="errortip_room" style="color:red; height:20px;"></p>
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
                 <p style="width:90%; text-align:left; margin:30px auto;"><a href="" class="confirmlink">亲，抢购牛奶会扣除0.1元红包哦。 确认红包满满的 &gt;&gt;</a></p>
                 <div class="center-img" style="width:222px;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/milk/bbuy.jpg');?>" class="boximg" /></div>
               </div>
               <a href="javascript:void(0);" class="cancel closeOpacity" style="margin-bottom:20px;">放弃</a>
             </div>
           </div>
           <!--扣款提示 end-->
           <!--抢中再抢 start-->
           <div class="alertcontairn" id="grab_over2" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;">每个用户最多抢购一支牛奶，留一点给别人吧！</p>
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
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;">亲，您的红包余额不足，无法参加抢购</p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--余额不足 end-->
           <!--抢光 start-->
           <div class="alertcontairn" id="grab_over3" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;"><a href="" class="confirmlink">很遗憾，牛奶已经抢完了，看看其它活动吧！</a></p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--抢光 end-->
           <!--抢光 start-->
           <div class="alertcontairn" id="grab_over8" style="display:none;">
             <div class="ac_content">
               <div class="textinfo" style="text-align:left;">
                 <p style="width:90%; text-align:left; margin:60px auto; font-size:14px;"><a href="" class="confirmlink">收货信息提交成功</a></p>
               </div>
               <a href="javascript:void(0);" class="closeOpacity cancel" style="margin-bottom:20px;">关闭</a>
             </div>
           </div>
           <!--抢光 end-->
           
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

//点击抢牛奶
function rob(){
    $('#grab_over0').hide();
    $.post(
        '/newMilk/rob',
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
            '/newMilk/getCustomerInfonew',
            function (data){
                $('#linkman').val(data.name);
                $('#tel').val(data.tel);
                $('#build').val(data.build);
                $('#room').val(data.room);
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