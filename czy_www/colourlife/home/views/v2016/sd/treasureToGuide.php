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
		<section class="conter treasure_c">
			<article class="treasure">
				<h4>集齐6张不同的拼图即可瓜分10万彩饭票！</h4>
				<section class="ban_ka">
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner01.png">
						<p>在彩住宅、e维修、e评价、个人中心出现的机率最高哦！</p>
						<span>立即前往</span>
					</a>
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner02.png">
						<p>缴纳物业费、停车费更易获得～</p>
						<span>立即前往</span>
					</a>
				</section>
				<section class="ban_ka">
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner03.png">
						<p>参加彩富人生就能获得哟！</p>
						<span>立即前往</span>
					</a>
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner04.png">
						<p>充值个饭票吧，说不定就得到了。</p>
						<span>立即前往</span>
					</a>
				</section>
				<section class="ban_ka">
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner05.png">
						<p>邀请好友注册拼图就到手了！</p>
						<span>立即前往</span>
					</a>
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner06.png">
						<p>据说经常出现在彩生活特供、邻里、e费通、商圈这几个地方！</p>
						<span>立即前往</span>
					</a>
				</section>
			</article>
		</section>
		<script>
		    var data= <?php echo $outData;?>;
		    console.log(data.czz_url);
		    console.log(data.ctg_url)
			$(document).ready(function(){
				$(".treasure section:nth-child(2)").find("a:nth-child(1)").click(function(){
					window.location.href=data.czz_url;
				});
				$(".treasure section:nth-child(2)").find("a:nth-child(2)").click(function(){
					mobileJump("EProperty");
				});
				$(".treasure section:nth-child(3)").find("a:nth-child(1)").click(function(){
					mobileJump("EReduceList");
				});
				$(".treasure section:nth-child(3)").find("a:nth-child(2)").click(function(){
					mobileJump("Ticket");
				});
				$(".treasure section:nth-child(4)").find("a:nth-child(1)").click(function(){
					mobileJump("Invite");
				});
				$(".treasure section:nth-child(4)").find("a:nth-child(2)").click(function(){
					window.location.href=data.ctg_url;
				});
			    //原生跳转函数
				function mobileJump(cmd)
			    {
			        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
			            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
			            document.location = _cmd;
			        } else if (/(Android)/i.test(navigator.userAgent)) {
			            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
			            eval(_cmd);
			        } else {
			
			        }
			    }
			});
		</script>
	</body>
</html>
