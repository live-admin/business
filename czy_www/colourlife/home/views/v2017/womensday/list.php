<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>员工专享</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>

	<body style="background: #f7f7f7;">
		<div class="nav">
			<ul>
				<!--<li>
					<div class="xianshi hide"></div>
					<img src="images/pro_icon.png"/>
					<p>四川凉山会理石榴精挑6个装单果300-400g </p>
					<p><span>¥<span>26.90</span></span><span>立返<span>100</span></span></p>
				</li>
				<li>
					<img src="images/pro_icon.png"/>
					<p>四川凉山会理石榴精挑6个装单果300-400g </p>
					<p><span>¥<span>26.90</span></span></p>
				</li>
				<li>
					<img src="images/pro_icon.png"/>
					<p>四川凉山会理石榴精挑6个装单果300-400g </p>
					<p>¥26.90</p>
				</li>-->
			</ul>
		</div>		
		<script type="text/javascript">
			var list=<?php echo json_encode($goodsList);?>;
			var gurl=<?php echo json_encode($url);?>;
			
			
			//通用列表
			for (var i=0; i<list.length; i++) {
				$(".nav ul").append(
					'<li id="'+list[i].id+'">'+
						'<a href="'+gurl+'&pid='+list[i].id+'">'+
							'<img src="'+list[i].img_name+'"/>'+
							'<p>'+list[i].name+'</p>'+
							'<p><span>'+'¥'+'<span>'+list[i].price+'</span></span></p>'+
						'</a>'+
					'</li>'
				);
			}			
		</script>
		
	</body>

</html>