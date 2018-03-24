<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>你爱果？还是它！</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>js/MotherDay.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/week.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/normalize.css">
	</head>
	<body style="background: #f8fae9;">
		<div class="contanis">
			<div class="top_box next_top_box">
				<div class="top_box_tips">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/next_tips.png"/>
				</div>
				<div class="top_box_txt next_top_box_txt">
					<p>爆款商品，每周五上新！</p>
				</div>
				<div class="top_box_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/dele.png"/>
				</div>
			</div>
			<div class="banner_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/banner_01.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/this_week.png"/>
			</div>	
			
			
			<!--本周爆款-->
			<div class="product_box">
				<!--第一个-->
				<div class="product_one" id="productA">
					<div class="two_div">
						<a href="javascript:void(0)">
							<?php echo CHtml::image($currentProductArr[0]['img_name']); ?>
							<div class="product_one_border next_product_one_border">
								<ul>
									<li><span class="money">¥ </span><span><?php echo $currentProductArr[0]['price']?></span></li>
									<li><span>立即抢购</span><span class="triangle_right"></span></li>
								</ul>
							</div>
							<div class="clear"></div>
							<!--已抢完遮罩层-->
						</a>	
					</div>	
						<p><?php echo $currentProductArr[0]['name']?></p>
						<p class="txt_color next_txt_color"><?php if ($currentProductArr[0]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[0]['ku_cun']?></span>/<?php echo $currentProductArr[0]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
				
				<!--第二个-->
				<div class="product_two" id="productB">
					<div class="two_div">
						<a href="javascript:void(0)">
							<?php echo CHtml::image($currentProductArr[1]['img_name']); ?>
							<div class="product_two_border next_product_one_border">
								<ul>
									<li><span class="money">¥ </span><span><?php echo $currentProductArr[1]['price']?></span></li>
									<li><span>立即抢购</span><span class="triangle_right"></span></li>
								</ul>
							</div>
							<div class="clear"></div>
						</a>
					</div>
						<p><?php echo $currentProductArr[1]['name']?></p>
						<p class="txt_color next_txt_color"><?php if ($currentProductArr[1]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[1]['ku_cun']?></span>/<?php echo $currentProductArr[1]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
			</div>
			
			
			
			
			<div class="banner_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/next_week.png"/>
			</div>
			
		<!--下周预告-->
			<div class="product_box">
				<!--第一个-->
				<div class="product_one product_other">
					<?php echo CHtml::image($nextProductArr[0]['img_name']); ?>
					<div class="product_one_border next_product_one_border">
						<ul>
							<li><span class="money">¥ </span><span><?php echo $nextProductArr[0]['price']?></span></li>
							<li class="expect"><span>敬请期待</span><span class="triangle_right triangle"></span></li>
						</ul>
					</div>
					<div class="clear"></div>
					<p class="nextone_p"><?php echo $nextProductArr[0]['name']?></p>
				</div>
				
				<!--第二个-->
				<div class="product_two product_other">
					<?php echo CHtml::image($nextProductArr[1]['img_name']); ?>
					<div class="product_two_border next_product_one_border">
						<ul>
							<li><span class="money">¥ </span><span><?php echo $nextProductArr[1]['price']?></span></li>
							<li class="expect"><span>敬请期待</span><span class="triangle_right triangle"></span></li>
						</ul>
					</div>
					<div class="clear"></div>
					<p class="nexttwo_p"><?php echo $nextProductArr[1]['name']?></p>
				</div>
			</div>
			
			<div class="product_box">
				<!--第三个-->
				<div class="product_one product_other">
					<?php echo CHtml::image($nextProductArr[2]['img_name']); ?>
					<div class="product_one_border next_product_one_border">
						<ul>
							<li><span class="money">¥ </span><span><?php echo $nextProductArr[2]['price']?></span></li>
							<li class="expect"><span>敬请期待</span><span class="triangle_right triangle"></span></li>
						</ul>
					</div>
					<div class="clear"></div>
					<p class="nextone_p"><?php echo $nextProductArr[2]['name']?></p>
				</div>
				
				<!--第四个-->
				<div class="product_two product_other">
					<?php echo CHtml::image($nextProductArr[3]['img_name']); ?>
					<div class="product_two_border next_product_one_border">
						<ul>
							<li><span class="money">¥ </span><span><?php echo $nextProductArr[3]['price']?></span></li>
							<li class="expect"><span>敬请期待</span><span class="triangle_right triangle"></span></li>
						</ul>
					</div>
					<div class="clear"></div>
					<p class="nexttwo_p"><?php echo $nextProductArr[3]['name']?></p>
				</div>
			</div>
			
		<!--遮罩层开始-->
		<div class="mask_Pop hide"></div>
		</div>
		<!--弹窗开始-->
		<div class="Pop hide">
			<div class="Pop_txt">
				<p>邀请一名好友成功注册才能购买，赶紧去邀请好友吧！</p>
			</div>
			<div class="Pop_btn">
				<a href="javascript:void(0);">邀请好友</a>
			</div>
		</div>
		
	 	<script type="text/javascript">
			var tgUrl = "<?php echo $shangChengUrl['tgHref'];?>";
			var aId = "<?php echo $currentProductArr[0]['id'];?>";
            var bId = "<?php echo $currentProductArr[1]['id'];?>";
            var aku = "<?php echo $currentProductArr[0]['ku_cun']?>";
            var bku = "<?php echo $currentProductArr[1]['ku_cun']?>";
            var tal_a = "<?php echo $currentProductArr[0]['total'];?>";
            var tal_b = "<?php echo $currentProductArr[1]['total'];?>";
            var registerNum = "<?php echo $registerNum;?>"
            var time = "<?php echo $next_date;?>";
		</script>
			 
	</body>
	
	
</html>
