<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>双旦寻宝</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<section class="conter share">
			<header class="share_header share_request">
				<p><span class="tel"></span>求赠<span class="share_num">一</span>张拼图</p>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner01.png">
				<p>你有<span class="num">三</span>张拼图可以赠送</p>
				<a href="javascript:void(0)" class="giving">赠送一个</a>
				<a href="javascript:void(0)" class="refused">残忍拒绝</a>
			</header>
			<!--遮罩层开始-->
			<div class="mask hide"></div>
			<!--遮罩层结束-->
			<!--版本号弹窗开始-->
			<div class="version hide">
				<div class="version_pop">
					<p>赶紧升级彩之云，参与双旦夺宝大狂欢，齐来瓜分10万大奖</p>
					<a href="javascript:void(0);" class="btn">确认</a>
				</div>
			</div>
		<!--版本号弹窗结束-->
		</section>
		<script>
		  $(document).ready(function(){
		  	 var data= <?php echo $outData;?>;
			 var phone=data.request.request_user_mobile;
			 var tel=phone.replace(/(\d{3})\d{5}(\d{3})/, '$1*****$2');
			 $(".tel").text(tel);
			 $(".share_request").find("img").attr("src",data.card.image);
		     var num=parseInt(data.card.number);
		     console.log(num);
		     switch(num){
		     	case 0:
		     	 $(".num").text("零")
		     	break;
		     	case 1:
		     	 $(".num").text("一")
		     	break;
		     	case 2:
		     	 $(".num").text("二")
		     	break;
		     	case 3:
		     	 $(".num").text("三")
		     	break;
		     }
		     //赠送
		     $(".giving").click(function(){
            		$.ajax({
						type:"POST",
						url:"/SdActivity/Reply",
						data:{'request_id':data.request.id,'reply_code':true},
						dataType:'json',
						success:function(data){
							console.log(data);
							if(data.retCode == 0){
								alert(data.retMsg);
							}else if(data.retCode == 1){
								$(".mask").removeClass("hide");
								$(".version").removeClass("hide");
								$(".version_pop").find("p").text(data.data.msg);
								console.log(data.data.msg);
								
							}
						}
            	    });
            });
		    //拒绝
		    $(".refused").click(function(){
            		$.ajax({
						type:"POST",
						url:"/SdActivity/Reply",
						data:{'request_id':data.request.id,'reply_code':false},
						dataType:'json',
						success:function(data){
							console.log(data);
							if(data.retCode == 0){
								alert(data.retMsg);
							}else if(data.retCode == 1){
								$(".mask").removeClass("hide");
								$(".version").removeClass("hide");
								$(".version_pop").find("p").text(data.data.msg);
								console.log(data.data.msg);
							}
						}
            	    });
            });
            //确认
            $(".btn").click(function(){
            	window.location.href="/SdActivity/index";
            })
		  });
		   
				
            	

		</script>
	</body>
</html>
