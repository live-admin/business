<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>春节活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>js/cornucopia.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<!--聚宝盆页面-->
		<div class="content">
			<div class="deng_bg">
				<ul>
					<li></li>
					<li>
						<p></p>
						<p class="animal"></p>
					</li>
				</ul>
			</div>
			
			<div class="fuqi_nav">
				<div class="fudai_bg yuanbao">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/yuanbao_icon.png"/>
					<p>聚宝盆每日凌晨自动生成3点福气值</p>
					<p>投资彩富人生可获得额外的8点福气值</p>
					<p>记得领取哟~</p>
				</div>
				<div class="xi_bin">收取福气</div>
				<div class="yuanbao_btn">首页</div>
			</div>
			<div class="clour_bg">
			</div>
		</div>
		
		<div class="mask hide"></div>
		<div class="Pop_qiang hide">
			<div class="yuanbao_qiang_txt">
				<p></p>
				<p></p>
				<p></p>
				<p></p>
			</div>
			<div class="Pop_qiang_btn_box">
				<div class="Pop_qiang_btn">确定</div>
			</div>
		</div>
		
		
		<script type="text/javascript">
		var customer_info = <?php echo json_encode($customer_info)?>;
		var img_url = "<?php echo F::getStaticsUrl('/activity/v2016/spring/images');?>";	
		</script>

	</body>
</html>
