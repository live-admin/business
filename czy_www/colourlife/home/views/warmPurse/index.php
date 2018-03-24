<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		
	<title>冬日饭票</title>
	<link href="<?php echo F::getStaticsUrl('/home/warmPurse/');?>css/style.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>js/jquery-1.11.3.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/warmPurse/js/ShareSDK.js');?>"></script>
	<script language="javascript" type="text/javascript">
        
        function showShareMenuClickHandler()
        {
            
            var u = navigator.userAgent, app = navigator.appVersion;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
            
             if(isAndroid){
                 try{
                     var version = jsObject.getAppVersion();
//                     alert(version);
                 }catch(error){
                      //alert(version);
                      if(version !="4.3.6.2"){
                        alert("请到App--我--检查更新,进行更新");
                        return false;
                    }
                 }finally {}
                $sharesdk.open("85f512550716", true);
            }
            
            if(isiOS){
                try{
                    if(getAppVersion && typeof(getAppVersion) == "function"){
                         getAppVersion();
                         var vserion = document.getElementById('vserion').value;
                        }
                    }catch(e){
                        
                    }
               
                if(vserion){
                    //alert(vserion);
                    $sharesdk.open("62a86581b8f3", true); 
                }else{  
                    alert("请到App--我--检查更新,进行更新");
                    return false;
                 }  
                }
            
            var params = {
                "text" : "我刚在彩之云完成了任务获得5元饭票，邀请码：<?php echo $code;?>，注册的时候要填我哦",
                "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
                "url":"http://dwz.cn/8YPIv",
                "title" : "彩之云",
                "titleUrl" : "http://dwz.cn/8YPIv",
                "description" : "描述",
                "site" : "彩之云",
                "siteUrl" : "http://dwz.cn/8YPIv",
                "type" : $sharesdk.contentType.WebPage
            };
           $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {
//                alert("state = " + state + "\nshareInfo = " + shareInfo + "\nerror = " + error);
            }); 
             
        };
     

    </script>
</head>

<body>
<div class="container">
    <div class="banner">
    	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/banner01.jpg"/>
		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/banner02.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/banner03.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/banner04.jpg"/>            	
    </div>
    <div class="head">
    	<div class="background">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/head_gb01.jpg"/>
            <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/head_gb02.jpg"/>
            <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/head_gb03.jpg"/>
            <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/head_gb04.jpg"/>
            <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/head_gb05.jpg"/>
        </div>
    	<button class="rulesBtn">活动规则</button>
        <div class="tip">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/title.png"/>
            <span><?php echo $total;?></span>
        </div>
    </div>
    <div class="content">
    	<div class="task" id="task1">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task1.png" />
			<span class="title">完成奖励</span>
            <div class="info">
            	<div class="left">
                	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task1Pic.png"/>
                    <a href="javascript:void(0)" data-id="<?php echo $uid;?>"></a>
                </div>
                <div class="right">
                <?php if (!empty($warmPurse1)){?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate1_ok.png"/>
                <?php }else{?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate1_none.png"/>
                <?php }?>
                </div>
                <div class="clear"></div>
           	</div>
        </div>
        <div class="task" id="task2">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task2.png" />
            <div class="info">
            	<div class="left">
            	 <?php if (!empty($warmPurse1)){?>
	            		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task2up_ok.png"/>
	            		<a href="<?php echo $this->createUrl('EDecorate');?>"></a>
            	<?php }else{?>	
            			<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task2up_none.png"/>
            	<?php } 
            		  if (!empty($warmPurse2)){?>
	                	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task2down_ok.png"/>
	                	<a href="javascript:void(0)" data-id="<?php echo $uid;?>"></a>
                <?php }else{?>		
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task2down_none.png"/>
                <?php }?>
               	</div>
                <div class="right center">
                <?php if (!empty($warmPurse2)&&!empty($warmPurse3)){?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate2_ok.png"/>
                <?php }else{?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate2_none.png"/>
                <?php }?>
               	</div>
                <div class="clear"></div>
        	</div>
        </div>
        <div class="task" id="task3">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task3.png" />
            <div class="info">
            	<div class="left">
            	<?php if (!empty($warmPurse3)){?>
            			 <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task3_ok.png"/>
            			 <a href="<?php echo $this->createUrl('ERent');?>"></a>
            	<?php }else{?>
            			<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task3_none.png"/>
            	<?php }?>
                </div>
                <div class="right">
                <?php if (!empty($warmPurse4)){?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate2_ok.png"/>
                <?php }else{?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate2_none.png"/>
                <?php }?>
                </div>
                <div class="clear"></div>
        	</div>
        </div>
        <div class="task" id="task4">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task4.png" />
            <div class="info">
            	<div class="left">
            	<?php if (!empty($warmPurse4)){?>
            			<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task4_ok.png"/>
            			<a href="<?php echo $this->createUrl('HYBX');?>"></a>
            	<?php }else{?>
            			<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task4_none.png"/>
            	<?php }?>
               	</div>
                <div class="right">
                <?php if (!empty($warmPurse5)){?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate1_ok.png"/>
                <?php }else{?>
                		<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate1_none.png"/>
                <?php }?>
                </div>
                <div class="clear"></div>
        	</div>
        </div>
        <?php if (!empty($received1)){?>
        <button class="receiveBtn" disabled="disabled">已经领取</button>
        <?php }else {?>
        <button class="receiveBtn" data-id="<?php echo $uid;?>" data-able="<?php echo $able_rec;?>">领取奖励</button>
        <?php }?>
        <div class="task" id="taskFinal">
        	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/taskFinal.png" />
            <div class="info">
            	<div class="left">
            	<?php if (!empty($warmPurse5)){?>
            			<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task_final_ok.png"/>
            			<a href="javascript:void(0)"></a>
            	<?php }else{?>
            			<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task_final_none.png"/>
            	<?php }?>
               	</div>
                <div class="right">
                <?php if (empty($received2)){?>
                	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate3_ok.png"/>
                	<a href="javascript:void(0)" data-cid="<?php echo $uid;?>" data-invite="<?php echo $rec_invite;?>"></a>
                <?php }else {?>
                	<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate3_none.png"/>
                <?php }?>
                </div>
                <div class="clear"></div>
        	</div>
        </div>
        <button class="shareBtn" onclick="showShareMenuClickHandler()">分享喜悦到朋友圈</button>
        <input style="display: none" id="vserion" />
    </div>
    <div class="infoWin hidden">
    	<span>完成所有任务才能领奖哦</span>
        <button>朕知道了</button>
    </div>
    <div class="inviteFriends hidden">
    	<button class="close">关闭</button>
    	<span>请输入好友电话号码：</span>
        <input type="text" placeholder="请输入正确的手机号码"/>
        <div class="btns">
        	<button>继续添加</button>
        	<button>确&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;定</button>
        </div>
    </div>
</div>
<div class="mask hidden"></div>
<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/warmPurse/js/redirect.js');?>"></script>
<script>
$(document).ready(function(){
	$("#taskFinal .right a").click(function(){
		var $this=$(this);
		var id=$this.attr("data-cid");
		var invite=$this.attr("data-invite");
		if(invite!=1){
			 $(".infoWin").find("span").html("要邀请三人或以上且成功注册才可以领取哦！");
			  $(".mask").removeClass("hidden");
	  		  $(".infoWin").removeClass("hidden");
	  		  return false;
		}
	  	$.ajax({
	          type: 'POST',
	          url: '/WarmPurse/InviteReward',
	          data: 'id='+id,
	          dataType: 'json',
	          success: function (result) {
		          if(result.status==1){
		        	  changeReceiveBtnStatus(2);
			  		  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }else{
					  $(".infoWin").find("span").html(result.msg);
					  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }
	          }
	        });
	});
//1.点击查看活动规则说明	
	$(".rulesBtn").click(function(){
		window.location.href="/WarmPurse/Rule"; 
		});
	});
//任务一
	$("#task1 .left a").click(function(){
		var $this=$(this);
		var id=$this.attr("data-id");
	  	$.ajax({
	          type: 'POST',
	          url: '/WarmPurse/CFRS',
	          data: 'id='+id,
	          dataType: 'json',
	          success: function (result) {
		          if(result==1){
			          var str='<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task2up_ok.png"/><a href="<?php echo $this->createUrl('EDecorate');?>"></a>';
					  $("#task2 .left").find("img:first").remove();
					  $("#task2 .left").find("a:first").remove();
					  $("#task2 .left").find("img").before(str);
					  $("#task1 .right").html('<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate1_ok.png"/>');
		        	  jumpCaiFuRenSheng();
				  }else if(result==2){
					  $(".infoWin").find("span").html("非法操作！");
					  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }
	          }
	        });
	});
//任务二 维修
	$("#task2 .left a:first").next().next().click(function(){
		var $this=$(this);
		var id=$this.attr("data-id");
	  	$.ajax({
	          type: 'POST',
	          url: '/WarmPurse/ERepair',
	          data: 'id='+id,
	          dataType: 'json',
	          success: function (result) {
		          if(result==1){
			          var str=' <img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/task3_ok.png"/><a href="<?php echo $this->createUrl('ERent');?>"></a>';
			          $("#task3 .left").html(str);
			          $("#task2 .right").html('<img src="<?php echo F::getStaticsUrl('/home/warmPurse/');?>images/rate2_ok.png"/>');
		        	  jumpEWeiXiu();
				  }else if(result==2){
					  $(".infoWin").find("span").html("非法操作！");
					  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }else if(result==3){
					  $(".infoWin").find("span").html("前面还有任务没完成！");
					  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }
	          }
	        });
	});
	
//点击领取奖励事件
	$(".receiveBtn").click(function(){
		var $this = $(this);
		var able=$this.attr("data-able");
		if(able!=1){
			$(".mask").removeClass("hidden");
	  		$(".infoWin").removeClass("hidden");
	  		return false;
		}
		var id=$this.attr("data-id");
	  	$.ajax({
	          type: 'POST',
	          url: '/WarmPurse/Reward',
	          data: 'id='+id,
	          dataType: 'json',
	          success: function (result) {
		          if(result.status==1){
		        	  changeReceiveBtnStatus(3);
			  		  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }else{
					  $(".infoWin").find("span").html(result.msg);
					  $(".mask").removeClass("hidden");
			  		  $(".infoWin").removeClass("hidden");
				  }
	          }
	        });
		});
//弹框确定按钮的事件,隐藏遮罩层
	$(".infoWin").find("button").click(function(){
	
		$(".infoWin").addClass("hidden");
		$(".mask").addClass("hidden");
		});
//邀请好友
	$("#taskFinal .left a").click(function(){
		
		$(".inviteFriends").removeClass("hidden");
		$(".mask").removeClass("hidden");
		});
//邀请好友，继续添加电话号码
	$(".inviteFriends .btns").find("button:first").click(function(){
		$(this).parents(".inviteFriends").find("input:last").after('<input type="text" placeholder="请输入正确的手机号码"/>');
		});
//邀请好友，先校验手机号码，确认提交,隐藏弹出框
	$(".inviteFriends").find("button:last").click(function(){
		var numbers = [];
		var index ="";//记录有错误的电话号码下标
		var numObjs = $(this).parents(".inviteFriends").find("input");
		for(var i=0;i<numObjs.length;i++){
			
				numbers[i] = numObjs[i].value;
			}
		
		if(index = numberCheck(numbers)){//如果有返回值则说明有号码不正确
			$(this).parents(".inviteFriends").find("input:first").siblings("input").remove();
			$(this).parents(".inviteFriends").find("input").val("");
			$(this).parents(".inviteFriends").find("input:last").after('<p>第'+index+'手机号码不正确，请重新输入正确的手机号</p>');
			setTimeout('$(".inviteFriends").find("p").remove();',3000);
			
			return false;
			}
		else{//所有号码都是正确的，可以提交数据
			  	$.ajax({
			          type: 'POST',
			          url: '/WarmPurse/InviteFriend',
			          data: 'tels='+numbers,
			          dataType: 'json',
			          success: function (result) {
				          alert(result.msg);
				          //window.location.href=window.location.href;
			          }
			        });
			}
		$(".mask").addClass("hidden");
		$(".inviteFriends").addClass("hidden");
		});
/* //分享
	$(".shareBtn").click(function(){
		
		$(".share").removeClass("hidden");
		$(".mask").removeClass("hidden");
		});
//取消分享
	$(".cancel").click(function(){
		
		$(".mask").addClass("hidden");
		$(".share").addClass("hidden");
		});
 */
	//关闭手机号填写弹出框
	$(".close").click(function(){
		$(".mask").addClass("hidden");
		$(".inviteFriends").addClass("hidden");
	});	 
//更改领取奖励按钮状态,更改点击领取奖励后的弹框内容
function changeReceiveBtnStatus(num){
	$(".receiveBtn").html("已经领取");
	$(".receiveBtn").attr("disabled","true");//不能点击
	$(".infoWin").find("span").html("成功领取"+num+"饭票");
}
//手机号码正则校验
function numberCheck(tempArry){
	var a=/^[1]{1}[0-9]{10}$/;
	var countArry = [];
	var tempStr="";
	var j =0;
	for(var i=0;i<tempArry.length;i++){
		if(!a.test(tempArry[i]))
    	{
        	countArry[j] = i;
			j++; 
    	}
	}
		if(countArry.length > 0){
			for(var i=0;i<countArry.length;i++)
				{
					tempStr =tempStr+(countArry[i]+1)+",";
					}
			tempStr=tempStr.substring(0,tempStr.length-1);
			//alert("第"+tempStr+"手机号码不正确，请重新输入正确的手机号");
        	return tempStr;
			}
	
}
</script>
</body>
</html>