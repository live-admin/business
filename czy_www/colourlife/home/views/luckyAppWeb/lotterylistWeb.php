<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
<title>奖品领取说明</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/august/lottery.css?time=76543210'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>


</head>

<body style="background:#f7f3e8;position:relative;">
<div class="lottery_topic lottery_topic_webs">

    <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/august/rulehead.jpg'); ?>" class="lotteryimg" /></div>
     <div class="lottery_content" style="padding-top:0px;">
      <table class="lotteryrecord">
         <thead>
           <tr>                                                             
             <th>抽奖时间</th>
             <th>奖品</th>
             <th>领奖情况</th>
           </tr>
         </thead>
         <tbody>

          


           <!-- 红包抽奖奖品 -->
           <?php foreach ($list as $value){?>
              <tr>
               <td>
                   <?php echo $value->lucky_date ;?>                                                       
               </td>
                 <td>
                   <?php echo $value->prize->prize_name; ?>
                 </td>


               <td <?php echo $value->deal_state==0?("class='not_getit'"):(''); ?> >
                        <!-- 5000大奖红包 -->

                        <?php if($value->prize->prize_name == "5000大奖红包"){ 
                          if($value->deal_state!=2){ ?>
                            待审核
                          <?php }else{ ?>
                            已领奖
                          <?php } ?>

                        <!-- 5000大奖红包 -->



                        <!-- 中国电信话费 -->
                        
                        <?php }elseif($value->prize->prize_name == "中国电信话费"){ 
                          if($value->deal_state!=2){ ?>
                            未领奖
                            <?php if($value->getTelecom()){ ?>
                              <div class="pop_btn modify"><a class="modifybtn" href="javascript:updateMobile(<?php echo $value->id; ?>);"><span>修改</span></a></div>
                            <?php } ?>
                          <?php }else{ ?>
                            已领奖
                          <?php } ?>

                        <!-- 中国电信话费 -->


                        <!-- 其余红包 -->

                        <?php }else{ 
                          if($value->deal_state!=2){ ?>
                            未领奖
                          <?php }else{ ?>
                            已领奖
                          <?php } }?>

                        <!-- 其余红包 -->



               </td>
             </tr>
           <?php }?>
           <!-- 红包抽奖奖品 -->


           



         </tbody>
      </table>
      <dl class="warmTip" style="margin-bottom:50px">
        <dt>温馨提示：</dt>
        <dd>
         1、品果精品美味礼盒奖品说明：
         <ul>
           <li>(1)奖品内容：伊莎贝尔饼干、吃果籽果冻或布丁，随机发放一盒。</li>
           <li>(2)奖品配送：8月25日安排8月11日到8月24日的奖品配送；9月12日安排8月25日至9月11日的奖品配送；
预计安排配送后10天内由彩生活客户经理送到中奖人家里。</li>
         </ul>
        </dd>

        <dd>
        2、中国电信10元话费奖品说明：
        <ul><li>(1)奖品内容：10元中国电信手机话费</li>
            <li>(2)奖品发放： 中国电信系统将在中奖后20天内将10元话费充值到中奖人登记的中国电信手机号码。</li>
        </ul>
        </dd>

        <dd>
         3、5000元大奖领取说明：
         <ul>
           <li>(1).中奖后3个工作日内彩生活客户经理上门核实中奖人的中奖信息和中奖身份（必须是彩生活业主或者是
彩生活业主直系亲属）后，系统在一个工作日发放5000元红包到中奖人的红包帐号。</li>
           <li>(2).彩生活业主证明要求出示：在彩生活服务小区的房产所有证或租赁合同、身份证。 </li>
           <li>(3).彩生活业主直系亲属证明要求出示：与彩生活业主直系亲属关系的户口本、彩生活业主的房产所有证或
租赁合同、中奖人身份证及业主身份证。</li>
         </ul>
        </dd>
        <dd>
         4、5000元大奖得主可携带家属（包括大奖得主，总共两大一小）出席彩生活年度盛会，年会现场将在公证
人员监督下抽取百万现金大奖，且免费提供五星级酒店住宿，外地业主将提供往返交通费。
        </dd>
        <dd>
         5、红包可用于：缴纳物业费和停车费，预缴物业费，商品交易，手机充值。
        </dd>
        <dd>
         6、您可以在"我-我的红包"中查看红包余额。
        </dd>
        <dd>
         7、本次活动仅限彩生活业主（彩生活服务小区内的房产所有人或租赁人）及其直系亲属 （配偶、父母、子
女）参加，彩生活有权对中奖人信息进行身份核实，并保留法律范围内的最终解释权。
        </dd>
      </dl>
      <div class="lottery_bottom">
        <a href="/LuckyAppWeb" style="width:400px;">返回</a>
      </div>
     </div>
</div>

<div class="opacity opacitynew" style="display:none;">  
   <div class="tips_contairn" style="display:none; padding-bottom:10px; height:auto;">
       <div class="alertcontairn_content">
         <div class="textinfo" style="width:82%;">
           <input type="hidden" id="lucky_cust_result_id" value=""/>  
           <p>请输入您充值的中国电信手机号码：</p>
           <p><input type="text" class="moblie_number" id="moblie_number" value="您的电信手机号码" defaultTxt="您的电信手机号码" style="margin:0;"/></p>
           <p>提示：</p>
           <p>1、为了保障能顺利获得奖品，请您务必登记您的电信手机号码；</p>
           <p>2、中国电信系统将在中奖后20天内将10元话费充值到您登记的中国电信手机号码；</p>
           <p>3、如果您登记的不是电信号码，我们将无法保证为您的手机充值。</p>
         </div>
         <div class="pop_btn">
           <a href="javascript:mobileNumber();">提交</a>
           <a href="javascript:myclose();" class="closeOpacity">关闭</a>
         </div>
       </div>
     </div>


    <!--提示输入电信号码 start-->
     <div class="tips_contairn" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="text-align:center;">
            本次抽奖仅限电信号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:myclose();" class="closeOpacity">返回</span></a>
         </div>
       </div>
     </div>
     <!--提示输入电信号码 end-->


     <!--月饼确定收货地址 start-->
    <div class="tips_contairn" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo" style="text-align:left; color:#505050; border-style: none;">
              <input type="hidden" id="MoonCakes_result_id" value=""/>  
                <h3>确认您的地址</h3>
                <div class="line" style="border-color:#bca270"></div>
                <p id="colour_address"></p>
                <p><label>姓名：</label><input type="text" class="Tnamenum" id="linkman" defaultTxt="请输入收货人姓名" /></p>
                <p><label>电话：</label><input type="text" class="Tnamenum" id="tel" defaultTxt="请输入电话号码" /></p>
                <div class="line" style="border-color:#bca270"></div>
                <dl>
                    <dt>温馨提示：</dt>
                    <dd>1、变更或者修改收货人及联系电话请先修改后确认。</dd>
                    <dd>2、收货地址不正确，请在"我-我的账户"中修改。</dd>
                    <dd>3、如果您中奖时没有确认收货信息，可以在"我抢到的月饼"中再次确认。</dd>
                </dl>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:sure_address();" class="closeOpacity confirm"><span>确定</span></a>
                <a href="javascript:myclose();"  class="closeOpacity confirm"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--月饼确定收货地址 end-->




     <!--品果精品确定收货地址 start-->
    <div class="tips_contairn" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo" style="text-align:left; color:#505050; border-style: none;">
              <input type="hidden" id="fruitlucky_result_id" value=""/>  
                <h3>确认您的地址</h3>
                <div class="line" style="border-color:#bca270"></div>
                <p id="colour_fruitaddress"></p>
                <p><label>姓名：</label><input type="text" class="Tnamenum" id="fruit_linkman" defaultTxt="请输入收货人姓名" /></p>
                <p><label>手机：</label><input type="text" class="Tnamenum" id="fruit_tel" defaultTxt="请输入电话号码" /></p>
                <div class="line" style="border-color:#bca270"></div>
                <dl>
                    <dt>温馨提示：</dt>
                    <dd>1、变更或者修改收货人及手机号码请先修改后确认。</dd>
                    <dd>2、收货地址不正确，请在"我-我的账户"中修改。</dd>
                    <!-- <dd>3、如果您中奖时没有确认收货信息，可以在"中奖结果"中再次确认。</dd> -->
                </dl>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:sure_fruitaddress();" class="closeOpacity confirm"><span>确定</span></a>
                <a href="javascript:myclose();"  class="closeOpacity confirm"><span>关闭</span></a>
            </div>
        </div>
    </div>
    <!--品果精品确定收货地址 end-->


    <!--提示输入正确的手机号码 start-->
     <div class=" tips_contairn" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="text-align:center;">
            请您输入正确的手机号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:myclose();" class="closeOpacity"><span>返回</span></a>
         </div>
       </div>
     </div>
     <!--提示输入正确的手机号码 end-->


    
</div>
<script type="text/javascript">
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
});

    function updateMobile(id){
        $('#lucky_cust_result_id').val(id);
        $('.opacity').show();
        center($('.opacity>div').eq(0).show());
        $('.opacity>div').eq(0).siblings().hide();
        // $('.alertcontairn8').siblings().hide();
    }
    
    
    function myclose(){
        $('.opacity').hide();
        $('.alertcontairn8 .alertcontairn9').hide();        
    }
    
    //提交电信充值手机号码
    function mobileNumber(){
        var moblie_number = $('#moblie_number').val();
        var lucky_cust_result_id = $('#lucky_cust_result_id').val();
        var reg = /^(133|153|180|181|189)\d{8}$/; 
        if(moblie_number==''||moblie_number=='您的电信手机号码'){
           $('.errortip').text('号码不能为空');
           return;
        }else if(!(reg.test(moblie_number))){
           $('.errortip').text('不是正确的电信手机号码');
           return;
        }
        $.post(
            '/luckyAppWeb/updateTelecom',
            {'lucky_cust_result_id':lucky_cust_result_id,'mobile':moblie_number},
            function (data){
                if(data == 1){
                    location.reload();
                }else{
                    $('.alertcontairn8').hide();
                    center($('.alertcontairn9').show());
                }
            }
        ,'json');
    }



  function fruit_update(id){
        $('#fruitlucky_result_id').val(id);      
        $.post(
            '/luckyAppWeb/getCustomerInfo',
            function (data){
                $('#fruit_linkman').val(data.name);
                $('#fruit_tel').val(data.tel);
                $('#colour_fruitaddress').html(data.address);
            }
            ,'json');
        $('.opacity').show();
        center($('.opacity>div').eq(3));
        $('.opacity>div').eq(3).siblings().hide();
    }


     function sure_fruitaddress(){
        var fruit_id = $('#fruitlucky_result_id').val(); 
        var fruit_linkman = $('#fruit_linkman').val();
        var fruit_tel = $('#fruit_tel').val();


        if(fruit_linkman==''){
          $('.errortiptel').text('收件人必填');
          return;
        }

        if(fruit_tel.length!=11 || isNaN(fruit_tel)){
            $('.errortiptel').text('不是正确的手机号码');
            return;
        }       
        if(fruit_tel==''){
           $('.errortiptel').text('号码不能为空');
           return;
        }


        $.post(
            '/luckyAppWeb/fruit_fillReceiving',
            {'id':fruit_id,'linkman':fruit_linkman,'tel':fruit_tel},
            function (data){
              if(data == 1){
                  location.href = "/luckyAppWeb/mylottery";
                }else{
                  $('.opacity>div').eq(3).hide();
                    center($('.opacity>div').eq(4));
                } 
            }
            ,'json');
    }



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

  
</script>

</body>
</html>
