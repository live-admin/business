<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>彩生活年会抽奖</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/lottery/'); ?>css/style.css"/>
    <script rel="script" src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>js/jquery-1.11.3.js"></script>
</head>
<body>
<div class="container">
    <div class="background">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_01.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_02.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_03.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_04.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_05.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_06.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_07.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_08.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_09.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_10.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_11.png">
        <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/bg_12.png">
    </div>
    <div class="choose_channel">
        <div class="buttons">
            <div class="staff_channel_btn">
                <a href="javascript:void(0)"></a>
                <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/staff_channel.png"/>
            </div>
            <div class="guest_channel_btn">
                <a href="javascript:void(0)"></a>
                <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/guest_channel.png"/>
            </div>
        </div>
    </div>
   <div class="staff_channel hidden">
       <div class="buttons">
           <div class="staff_channel_input_btn">
               <input type="text" placeholder=""/>
               <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/input_btn_bg.png"/>
           </div>
           <div class="staff_channel_input_btn">
               <input type="text" placeholder=""/>
               <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/input_btn_bg.png"/>
           </div>
           <div class="enter_btn">
               <a href="javascript:void(0)"></a>
               <img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/enter_btn.png"/>
           </div>
       </div>
   </div>

</div>
<script>
    $(document).ready(function(){
        var type='';
        //员工通道
       $(".staff_channel_btn a").click(function(){
            type=1;
            $(".staff_channel").find(".staff_channel_input_btn:first").find("input").attr("placeholder","请输入手机号");
            $(".staff_channel").find(".staff_channel_input_btn:nth-child(2)").find("input").attr("placeholder","请输入OA账号");
            $(".choose_channel").addClass("hidden");
            $(".staff_channel").removeClass("hidden");
       });
        //嘉宾通道
        $(".guest_channel_btn a").click(function(){
            type=2;
            $(".staff_channel").find(".staff_channel_input_btn:first").find("input").attr("placeholder","请输入姓名");
            $(".staff_channel").find(".staff_channel_input_btn:nth-child(2)").find("input").attr("placeholder","请输入手机号");
            $(".choose_channel").addClass("hidden");
            $(".staff_channel").removeClass("hidden");
        });
        //进入按钮
        $(".staff_channel").find(".enter_btn a").click(function(){
        	var mobile ='';
        	var name ='';
            if(type == 1){
            	mobile = $(".staff_channel").find(".staff_channel_input_btn:first").find("input").val();
                name = $(".staff_channel").find(".staff_channel_input_btn:nth-child(2)").find("input").val();
                if(!oaNumCheck(name))
                    var oaNumFlag = false;
                else
                	var oaNumFlag = true;
            	
                if(!numberCheck(mobile))
                	var numberFlag = false;
                else
                	var numberFlag = true;
            	
            	if(!(oaNumFlag&&numberFlag))
                	return false;
            	
            }else  if(type == 2){
            	name = $(".staff_channel").find(".staff_channel_input_btn:first").find("input").val();
                mobile = $(".staff_channel").find(".staff_channel_input_btn:nth-child(2)").find("input").val();
                if(!nameCheck(name))
                	var nameFlag = false;
                else
                	var nameFlag = true;
            	
                if(!numberCheck(mobile))
                	var numberFlag = false;
                else
                	var numberFlag = true;
            	
                if(!(nameFlag&&numberFlag))
                	return false;
            }
    	  	$.ajax({
    	          type: 'POST',
    	          url: '/Lottery/Entry',
    	          data: 'name='+name+'&mobile='+mobile+'&type='+type,
    	          dataType: 'json',
    	          success: function (result) {
    		          if(result.status==1){
    		        	  window.location.href=result.url;
    			      }else{
    				      alert(result.msg); 
    				  }
    	          }
    	        });
        });


        function numberCheck(temp){
        	$.trim(temp);
            var a=/^[1]{1}[0-9]{10}$/;

            if(!a.test(temp))
            {
                alert("手机号输入有误");
                return false;
            }
            return true;
        }
        //10位以内的汉字
        function nameCheck(temp){
        	$.trim(temp);
            var a = /^[\u4e00-\u9fa5a-zA-Z]{1,10}$/;
            if(!a.test(temp))
            {
                alert("姓名输入有误");
                return false;
            }
            return true;
        }
        //20位以内的英文+字母
        function oaNumCheck(temp){
        	$.trim(temp);
            var a = /^[a-zA-Z0-9]{1,20}$/;
            if(!a.test(temp))
            {
                alert("OA号输入有误");
                return false;
            }
            return true;
        }

    });
</script>
</body>
</html>