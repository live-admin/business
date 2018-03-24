<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>荔枝文化节</title>
		<meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
		<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>css/layout.css" />
		<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>css/normalize.css">
	</head>
	<body>
		<div class="conter">
			<div class="conter_ban">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/images/bg.jpg');?>">
			</div>
			<div class="conter_con">
				<a href="/LiZhiCulture/Seckill">
					<div class="conter_con_box conter_con_box_active">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/images/hour.png');?>">
						<h4>9.9元秒杀</h4>
						<p>每天10:00/16:00</p>
					</div>
				</a>
				<a href="/LiZhiCulture/NewTalent">
					<div class="conter_con_box conter_con_box_active">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/images/goods.png');?>">
						<h4>新人有礼</h4>
						<p>6.4-6.13注册抽奖</p>
					</div>
				</a>
				<a href="/LiZhiCulture/CaiFuVip">
					<div class="conter_con_box conter_con_box_active">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/images/care.png');?>">
						<h4>彩富专享</h4>
						<p>6.4-6.30定投即送</p>
					</div>
				</a>
			</div>
			<div class="conter_site">
				<!--1开始-->
				<?php if(!empty($productsList)){
                   		foreach($productsList as $list){
        		?>
				<div class="conter_site01">
					<a href="<?php echo $shangChengUrl.'&pid='.$list['id']; ?>">
						<div class="conter_site01_l">
							<?php echo CHtml::image($list['img_name']); ?>
						</div>
						<div class="conter_site01_r">
							<div class="conter_site01_r_top">
								<p><?php echo $list['name'];?></p>
							</div>
							<div class="conter_site01_r_bottom">
								<div class="ter_site01_r_bottom_l">
									<p>￥<?php echo $list['price'];?></p>
									<p>已售：<?php echo $list['sales'];?></p>
								</div>
								<div class="ter_site01_r_bottom_r">
									<p>立即购买</p>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="clear"></div>
					</a>
				</div>
				<?php 
				    }
				}?>
				<!--1结束-->
			</div>
		</div>
	</body>
</html>


