<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>邀请好友参加活动</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/october/milk.css?time=123456'); ?>" rel="stylesheet" />
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<style type="text/css">
.milk_contairnnew{
	margin-top: 100px;
}
.invite_btn{
	margin-top: 50px; 
}

.invite_name,.phone_num {
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #b4b4b4;
    color: #6d6d6d;
    font-size: 16px;
    height: 40px;
    line-height: 40px;
    margin: 0 auto;
    padding-left: 5px;
    width: 245px;
    margin-bottom:25px;     
}
</style> 
</head> 
 
<body stylye="position:relative;"> 
   <div class="milk_contairnnew">
     <!-- <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/head.jpg');?>" class="lotteryimg"/></div>  -->
     <div class="milk_content">
      
       <div class="invite">
         <h3>疯狂奖励&nbsp;&nbsp;呼朋唤友</h3>
         <p style="font-size:16px;">姓名：<input type="text" class="invite_name" value=""/></p>
         <p style="font-size:16px;">手机：<input type="text" class="phone_num" value="请输入被邀请人的手机号"/></p>
         <p style="font-size:16px;">类型：
         <select class="type_num" name="type">
            <option value="1" checked="checked">1号</option>
            <option value="2">2号</option>
            <option value="3">3号</option>
            </select>
         </p>  
           <button class="invite_btn">立即邀请</button>
         <p class="invite_link_box">
           <!-- <a href="/milk#rule">邀请规则</a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="/milk/myInviteRecord">邀请记录</a> -->
         </p>
         <!-- <a href="/milk" class="goback_btn">返回</a> -->
       </div>
       
     </div>
     
     
   </div>



   <!--弹出框 start-->
 <div class="opacity" style="display: none;">
   <div class="tips_contairn" style="display: none;">

       <h3>温馨提示</h3>
       <div class="textinfo">
         <p style="word-spacing: -4px;"> 
          <!--您已经成功邀请了好友，短信发送内容为：您的好友詹秋凤邀请您注册彩之云，下载地址http://dwz.cn/8YPIv。-->
         </p>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">确定</a>
       </div>

   </div>
 </div>
 <!--弹出框 end-->
   
<script type="text/javascript"> 
  $(function(){

  	// $('.invite_name').focus(function(){
   //  var val=$(this).val();  
   //  if(val=="请输入被邀请人姓名")
   //    $(this).val('');
   //  })
   //  $('.invite_name').blur(function(){
   //  var val=$.trim($(this).val()); 
   //  if(val=="")
   //    $(this).val('请输入被邀请人姓名');
   //  })



    $('.phone_num').focus(function(){
    var val=$(this).val();  
    if(val=="请输入被邀请人的手机号")
      $(this).val('');
    })
    $('.phone_num').blur(function(){
    var val=$.trim($(this).val()); 
    if(val=="")
      $(this).val('请输入被邀请人的手机号');
    })
    
  })
 
 $('.invite_btn').click(function(){
   $('.opacity').show(); 
   if($('.invite_name').val()!=''){
	   if ($('.phone_num').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
	        $.post(
	          '/luckyAppTest/createcustomer',
	          {'name':$('.invite_name').val(),'mobile':$('.phone_num').val(),'type':$('.type_num').val()},
	          function (data){
	             if(data.code == "success"){
	                 $('.textinfo p').text("恭喜，邀请成功！");
	                 $('.tips_contairn').show();                 
	             }else{
	                 $('.textinfo p').text(data.code);
	                 $('.tips_contairn').show();                     
	             } 
	          }
	          ,'json').error(function() {
	              $('.textinfo p').text("您的网络异常，请检查网络后重试；");
	              $('.tips_contairn').show(); 
	          });
	   }else{
	        $('.textinfo p').text("您输入的手机号码格式不对！");
	        $('.tips_contairn').show();    
	   }
	}else{
		$('.textinfo p').text("请输入的邀请人姓名！");
	    $('.tips_contairn').show(); 
	}   
 });
 
$('.closeOpacity').click(function(){
      $('.opacity').hide(); 
      $('.tips_contairn').hide();     
      $('.textinfo p').text('');     
}); 
</script> 
   

  
</body> 

</html>