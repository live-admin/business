<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
<title>业主中奖信息</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/july/lottery.css?time=123'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>


</head>

<body style="background:#fff;">
<div class="lottery_topic" style="background:#fff;"> 

     <div class="lottery_content">
       <table class="lotteryrecord">
         <thead>
           <tr>                                                             
             <th>抽奖时间</th>
             <th>奖品</th>
             <th>领奖情况</th>
           </tr>
         </thead>
         <tbody>
           <?php foreach ($list as $value){?>
	           	<tr>
	             <td>
	                 <?php echo $value->lucky_date ;?>                                                       
	             </td>
              	 <td>
                	 <?php echo $value->prize->prize_name; ?>
                 </td>
	             <td <?php echo $value->deal_state==0?("class='not_getit'"):(''); ?> >
                        <?php if($value->prize->prize_name == "5000.00元红包" && $value->deal_state!=2){ ?>
                         待审核
                       <?php }else{ ?> 
                            <?php echo $value->deal_state==2?("已领奖"):("未领奖") ;?> 
                            <?php if($value->prize->prize_name == "电信充值卡" && $value->getTelecom()){ ?><a href="javascript:updateMobile(<?php echo $value->id; ?>);">修改手机号码</a><?php } ?>
                       <?php } ?>     
	             </td>
	           </tr>
           <?php }?>
         </tbody>
      </table>
      <dl class="warmTip">
        <dt><span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/loveHeart.png'); ?>" /></span><span>温馨提示: </span></dt>
        <dd>1、除5000元大奖外， 其他红包金额抽中立即发放到中奖人的红包帐户。</dd>
        <dd style="color:#ff7e00">2、5000元大奖领取说明：</dd>
        <dd style=" text-indent:15px;">
          中奖后3个工作日内彩生活客户经理上门核实中奖人的中奖信息和中奖身份（必须是彩生活
业主或者是彩生活业主直系亲属）后，系统在一个工作日发放5000元红包到中奖人的红包帐号。
        </dd>
        <dd style="color:#ff7e00; text-indent:15px;">
        彩生活业主证明要求出示：在彩生活服务小区的房产所有证或租赁合同、身份证。彩生活业
主直系亲属证明要求出示：与彩生活业主直系亲属关系的户口本、彩生活业主的房产所有证或租
赁合同、中奖人身份证及业主身份证。</dd>
        <dd>
          3、5000元大奖得主可携带家眷（包括大奖得主，总共两大一小）出席彩生活年度盛会，将在现
场参与抽取百万现金大奖活动。 彩生活将免费提供五星级酒店住宿，外地业主将提供往返交通费。
        </dd>
        <dd>
          4、红包可用于：缴纳物业费和停车费， 预缴物业费，商品交易，手机充值。
        </dd>
        <dd>
          5、您可以在"我-我的红包"中查看红包余额。
        </dd>
        <dd>
          6、本次活动仅限彩生活业主（彩生活服务小区内的房产所有人或租赁人）及其直系亲属 （配偶、
父母、子女）参加，彩生活有权对中奖人信息进行身份核实，并享有法律范围内的最终解释权。
        </dd>
        
        
      </dl>   
      <div class="lottery_bottom">
        <a href="/luckyApp/luckyApp">返回</a>
      </div>
     </div>
    
     
    
<div class="opacity" style="display:none;">  
   <div class="alertcontairn8" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <input type="hidden" id="lucky_cust_result_id" value=""/>  
           <p>
            请输入您充值的手机号码：
           </p>
           <p><input type="text" class="moblie_number" id="moblie_number" value="您的电信手机号码" defaultTxt="您的电信手机号码" /></p>
           <p>提示：</p>
           <p>1、为了保障能顺利获得奖品，请您务必登记您的电信手机号码；</p>
           <p>2、系统将在10天之内将话费卡号和密码将以短信方式发送给登记
            的电信号码；</p>
           <p>3、如果您登记的不是电信号码，我们将无法保证为您的手机充值。</p>
         </div>
         <div class="pop_btn">
           <a href="javascript:mobileNumber();"><span>提交</span></a>
           <a href="javascript:myclose();" class="closeOpacity"><span>关闭</span></a>
         </div>
       </div>
     </div>
    
    <!--提示输入电信号码 start-->
     <div class="alertcontairn9" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <p style="text-align:center;">
            本次抽奖仅限电信号码！
           </p>
         </div>
         <div class="pop_btn">
           <a href="javascript:myclose();" class="closeOpacity"><span>返回</span></a>
         </div>
       </div>
     </div>
     <!--提示输入电信号码 end-->
    
</div>

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
        $('.alertcontairn8').show();
        $('.alertcontairn9').hide();
    }
    
    
    function myclose(){
        $('.opacity').hide();
        $('.alertcontairn8 .alertcontairn9').hide();        
    }
    
    //提交手机号码
    function mobileNumber(){
        var moblie_number = $('#moblie_number').val();
        var lucky_cust_result_id = $('#lucky_cust_result_id').val();
        $.post(
            '/luckyApp/updateTelecom',
            {'lucky_cust_result_id':lucky_cust_result_id,'mobile':moblie_number},
            function (data){
                if(data == 1){
                    location.reload();
                }else{
                    $('.alertcontairn8').hide();
                    $('.alertcontairn9').show();
                }
            }
        ,'json');
    }
</script>
    
</body>
</html>
