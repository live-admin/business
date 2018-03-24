<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>吃着粽子出游</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/draw.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/normalize.css">
	</head>
	<body style="background: #eef2e9;">
		<div class="contanis">
			
			<div class="banner_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/zongziban_01.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/zongzi.png"/>
			</div>	
			
			
			<!--本周爆款-->
			<div class="product_box">
				<!--第一个-->
				<div class="product_one" id="productA">
                    <a href="<?php echo $shangChengUrl['tgHref']."&pid=".$currentProductArr[0]['id']?>">
						<?php echo CHtml::image($currentProductArr[0]['img_name']); ?>
						<div class="product_one_border next_product_one_border">
							<ul>
								<li><span class="money">¥ </span><span><?php echo $currentProductArr[0]['price']?></span></li>
								<li><span>立即抢购</span><span class="triangle_right"></span></li>
							</ul>
						</div>
					</a>	
						<div class="clear"></div>
						<p><?php echo $currentProductArr[0]['name']?></p>
						<p class="txt_color next_txt_color"><?php if ($currentProductArr[0]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[0]['ku_cun']?></span>/<?php echo $currentProductArr[0]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
				
				<!--第二个-->
				<div class="product_two" id="productB">
					<a href="<?php echo $shangChengUrl['tgHref']."&pid=".$currentProductArr[1]['id']?>">
						<?php echo CHtml::image($currentProductArr[1]['img_name']); ?>
						<div class="product_two_border next_product_one_border">
							<ul>
								<li><span class="money">¥ </span><span><?php echo $currentProductArr[1]['price']?></span></li>
								<li><span>立即抢购</span><span class="triangle_right"></span></li>
							</ul>
						</div>
					</a>
						<div class="clear"></div>
						<p><?php echo $currentProductArr[1]['name']?></p>
						<p class="txt_color next_txt_color"><?php if ($currentProductArr[1]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[1]['ku_cun']?></span>/<?php echo $currentProductArr[1]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
			</div>
			
			<div class="product_box">
				<!--第三个-->
				<div class="product_one" id="productC">
					<a href="<?php echo $shangChengUrl['tgHref']."&pid=".$currentProductArr[2]['id']?>">
						<?php echo CHtml::image($currentProductArr[2]['img_name']); ?>
						<div class="product_one_border next_product_one_border">
							<ul>
								<li><span class="money">¥ </span><span><?php echo $currentProductArr[2]['price']?></span></li>
								<li><span>立即抢购</span><span class="triangle_right"></span></li>
							</ul>
						</div>
					</a>	
						<div class="clear"></div>
						<p><?php echo $currentProductArr[2]['name']?></p>
						<p class="txt_color next_txt_color"><?php if ($currentProductArr[2]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[2]['ku_cun']?></span>/<?php echo $currentProductArr[2]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
				
				<!--第四个-->
				<div class="product_two" id="productD">
					<a href="<?php echo $shangChengUrl['tgHref']."&pid=".$currentProductArr[3]['id']?>">
						<?php echo CHtml::image($currentProductArr[3]['img_name']); ?>
						<div class="product_two_border next_product_one_border">
							<ul>
								<li><span class="money">¥ </span><span><?php echo $currentProductArr[3]['price']?></span></li>
								<li><span>立即抢购</span><span class="triangle_right"></span></li>
							</ul>
						</div>
					</a>
						<div class="clear"></div>
						<p><?php echo $currentProductArr[3]['name']?></p>
						<p class="txt_color next_txt_color"><?php if ($currentProductArr[3]['total']>0){?><span>剩余：</span><span><?php echo $currentProductArr[3]['ku_cun']?></span>/<?php echo $currentProductArr[3]['total'];?><?php }else {?>不限量<?php }?></p>
				</div>
				
			</div>
			
			<div class="banner_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>images/try.png"/>
			</div>
		<!--下周预告-->
			<div class="product_box">
				<!--第一个-->
				<div class="product_one product_other">
                    <a href="<?php echo $shangChengUrl['tgHref']."&pid=".$currentProductArr[4]['id']?>">
                        <?php echo CHtml::image($currentProductArr[4]['img_name']); ?>
                        <div class="product_one_border next_product_one_border">
                            <ul>
                                <li><span class="money">¥ </span><span><?php echo $currentProductArr[4]['price']?></span></li>
                                <li class="expect"><span>立即抢购</span><span class="triangle_right"></span></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                        <p class="nextone_p"><?php echo $currentProductArr[4]['name']?></p>
                    </a>
				</div>
				
				<!--第二个-->
				<div class="product_two product_other">
                    <a href="<?php echo $shangChengUrl['tgHref']."&pid=".$currentProductArr[5]['id']?>">
						<?php echo CHtml::image($currentProductArr[5]['img_name']); ?>
						<div class="product_two_border next_product_one_border">
							<ul>
								<li><span class="money">¥ </span><span><?php echo $currentProductArr[5]['price']?></span></li>
								<li class="expect"><span>立即抢购</span><span class="triangle_right"></span></li>
							</ul>
						</div>
						<div class="clear"></div>
						<p class="nexttwo_p"><?php echo $currentProductArr[5]['name']?></p>
                    </a>
				</div>
			</div>
		<!--遮罩层开始-->
		<div class="mask_Pop hide"></div>
		</div>
	</body>
	
	
</html>
