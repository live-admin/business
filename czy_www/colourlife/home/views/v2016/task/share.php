<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>国庆7天乐</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/task/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/task/');?>css/layout.css">
	</head>
	<body>
		<div class="con">
			<section class="banner">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/task/');?>images/banner01.png"> 
			</section>
			<section class="blessing">
				<p>已收集到<span><?php echo $num; ?></span>个祝福</p>
			</section>
			<section class="bird01">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/task/');?>images/bird02.png" />
			</section>
			<section class="bless_button">
				<p>祝福</p>
			</section>
			<section class="footer"></section>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗开始-->
		<div class="pop hide">
			<div class="pop_top"></div>
			<div class="pop_con">
				<p></p>
				<div class="pop_btn">
					<a href="javascript:void(0);">我也要参加</a>
				</div>
				
			</div>	
		</div>
		
		<script>
			$(document).ready(function(){
				var user_id = <?php echo $sd_id; ?>;
				//点击送祝福
				$(".bless_button p").click(function(){
					$.ajax({
	                    type: 'POST',
	                    url: '/Task/ZhuFu',
	                    data: 'sd_id='+user_id,
	                    dataType: 'json',
	                    success: function (data) {
	                        if(data.status==1)
	                        {
								$(".pop").find(".pop_con p").text("送祝福成功");
								$(".blessing span").text(data.num);
								$(".pop").removeClass("hide");
								$(".mask").removeClass("hide");  
	                        }
	                        else
	                        {
	                        	if(data.msg == -3)
	                        	{
	                        		$(".pop").find(".pop_con p").text("你已送完祝福");
	                        	}
	                        	else if(data.msg == -2)
	                        	{
	                        		$(".pop").find(".pop_con p").text("祝福已收集满啦！");
	                        	}
	                        	else if(data.msg == -4)
	                        	{
	                        		$(".pop").find(".pop_con p").text("不在祝福时间");
	                        	}

	                        	else
	                        	{
	                        		console.log("error");
	                        	}
	                        	$(".pop").removeClass("hide");
								$(".mask").removeClass("hide"); 
	                        }
	                    }
	                }); 
				});
				//关闭遮罩层
				$(".mask").click(function(){
					$(".mask").addClass("hide");
					$(".pop").addClass("hide");
				});

				$(".pop_btn a").click(function(){
					window.location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway";
				})
			})
		</script>
	</body>
</html>


