<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>领取流量</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	 	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/draw.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background-color: #FFF5D7;">
		<div id="top">
			<div class="linqu_btn">领取流量</div>
		</div>
		<div id="footer">
			<div class="active_rule">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>images/active_rule_h1.png"/>				
				<ol>
					<li>新用户下载并注册彩之云，登录即可领取流量包，最高可领1G！（安卓用户可在应用市场中搜索下载；苹果用户可在App Store中搜索下载）</li>
					<li>流量领取成功后，当月生效。</li>
					<li>本次活动适用于全国移动、联通、电信用户（港澳台号码除外）。</li>
				</ol>
			</div>
		</div>
		
		<!--遮罩层-->
		<div class="mask hide"></div>
		
		<div class="content hide">
			<div class="content_bg">
				<div class="content_txt">
					<p>领取成功</p>
					<p>500M</p>
					<p>已发放到你的登录手机号</p>
					<p>实际到账情况请以运营商短信为准</p>
				</div>
				<div class="content_btn">
					<a href="javascript:void(0);">确定</a>
				</div>
			</div>
			<div class="dele_btn">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>images/dele.png"/>
			</div>
		</div>
	
		<script type="text/javascript">
	 	var _id = <?php echo json_encode($id);?>;
	 	var chance = <?php echo json_encode($chance);?>;
	 	
		if (chance) {
			$(".linqu_btn").click(function(){
				$.ajax({
				async:true,
				type:"POST",
				url:"/Liang/GetByApp",
				data:{'id' :_id},
				dataType:'json',
				success:function(data){
					if(data.status == 1){
						$(".content").removeClass("hide");
						$(".mask").removeClass("hide");
					}
					else{
						alert(data.msg);
					}
				} 
			});
		});
		}
		else{
			$(".linqu_btn").text("您无领取资格");
			$(".linqu_btn").css({"background":"#C0C0C0","box-shadow":"0px 2px 0px 0px #82372E"});
		}
		
		$(".dele_btn>img,.content_btn").click(function(){
			$(".content").addClass("hide");
			$(".mask").addClass("hide");
		});
		</script>
	</body>
</html>
