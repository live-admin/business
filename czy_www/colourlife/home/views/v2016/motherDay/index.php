<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>年末大派送</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>js/MotherDay.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/normalize.css">
	</head>
	<body style="background: #fdf4e9;">
		<div class="contanis">
			<div class="top_box">
				<div class="top_box_tips">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/tips.png"/>
				</div>
				<div class="top_box_txt">
					<p>爆款商品，每周五上新！</p>
				</div>
				<div class="top_box_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/dele.png"/>
				</div>
			</div>
			<div class="banner_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/banner_02.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/this.png"/>
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
							<div class="mask hide" id="firstProMask">
								<p>已抢完</p>
							</div>
					</div>	
						<p><?php echo $currentProductArr[0]['name']?></p>
						<p class="txt_color"><?php if ($currentProductArr[0]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[0]['ku_cun']?></span>/<?php echo $currentProductArr[0]['total'];?><?php }else {?>不限量<?php }?></p>
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
							<!--<div class="mask" id="secondProMask">
								<p>邀请1人注册可购买</p>
							</div>-->
					</div>
						<p><?php echo $currentProductArr[1]['name']?></p>
						<p class="txt_color"><?php if ($currentProductArr[1]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[1]['ku_cun']?></span>/<?php echo $currentProductArr[1]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
			</div>
			
			<div class="product_box">
				<!--第三个-->
				<div class="product_one" id="productC">
					<div class="two_div">
						<a href="javascript:void(0)">
							<?php echo CHtml::image($currentProductArr[2]['img_name']); ?>
							<div class="product_one_border">
								<ul>
									<li><span class="money">¥ </span><span><?php echo $currentProductArr[2]['price']?></span></li>
									<li><span>立即抢购</span><span class="triangle_right"></span></li>
								</ul>
							</div>
							<div class="clear"></div>
							
						</a>
							<!--已抢完遮罩层-->
							<div class="mask hide" id="firstProMask">
								<p>已抢完</p>
							</div>
					</div>		
						<p><?php echo $currentProductArr[2]['name']?></p>
						<p class="txt_color"><?php if ($currentProductArr[2]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[2]['ku_cun']?></span>/<?php echo $currentProductArr[2]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
				
				<!--第四个-->
				<div class="product_two" id="productD">
					<div class="two_div">
						<a href="javascript:void(0)">
							<?php echo CHtml::image($currentProductArr[3]['img_name']); ?>
							<div class="product_two_border">
								<ul>
									<li><span class="money">¥ </span><span><?php echo $currentProductArr[3]['price']?></span></li>
									<li><span>立即抢购</span><span class="triangle_right"></span></li>
								</ul>
							</div>
							<div class="clear"></div>
						</a>
							<div class="mask" id="secondProMask">
								<p>邀请1人注册可购买</p>
							</div>
					</div>
						<p><?php echo $currentProductArr[3]['name']?></p>
						<p class="txt_color"><?php if ($currentProductArr[3]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[3]['ku_cun']?></span>/<?php echo $currentProductArr[3]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
			</div>
			
			
			
			<div class="banner_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/next.png"/>
			</div>	
			
			<!--下周预告-->
			<div class="product_box">
				<!--第一个-->
				<div class="product_one product_other">
					<?php echo CHtml::image($nextProductArr[0]['img_name']); ?>
					<div class="product_one_border">
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
						<div class="product_two_border">
							<ul>
								<li><span class="money">¥ </span><span><?php echo $nextProductArr[1]['price']?></span></li>
								<li class="expect"><span>敬请期待</span><span class="triangle_right triangle"></span></li>
							</ul>
						</div>
						<div class="clear"></div>
						<p class="nexttwo_p"><?php echo $nextProductArr[1]['name']?></p>
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
         	var cId = "<?php echo $currentProductArr[2]['id'];?>";
            var dId = "<?php echo $currentProductArr[3]['id'];?>";
            var aku = "<?php echo $currentProductArr[0]['ku_cun']?>";
            var bku = "<?php echo $currentProductArr[1]['ku_cun']?>";
           	var cku = "<?php echo $currentProductArr[2]['ku_cun']?>";
            var eku = "<?php echo $currentProductArr[3]['ku_cun']?>";
            var tal_a = "<?php echo $currentProductArr[0]['total'];?>";
            var tal_b = "<?php echo $currentProductArr[1]['total'];?>";
            var tal_c = "<?php echo $currentProductArr[2]['total'];?>";
            var tal_e = "<?php echo $currentProductArr[3]['total'];?>";
            var registerNum = "<?php echo $registerNum;?>"
            var time = "<?php echo $next_date;?>";
		</script>
	</body>
</html>