<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>活动特权</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiFuActivityPrivilege/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiFuActivityPrivilege/');?>css/normalize.css">
	</head>
	<body style="background: #f2f3f4;">
		<div class="container">
			<div class="top">
				<div class="top_box">
					<div class="top_box_img">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiFuActivityPrivilege/');?>images/love.png"/>
					</div>
					<div class="top_box_txt">
						<p>活动特权</p>
						<p>每月多重惊喜等您来</p>
						
						
					</div>
				</div>
			</div>
			
			
			<?php  
				if(!empty($heads)){
					foreach($heads as $val){
				?>
			<div class="bar_box">
				<div class="bar_box_txt">
					<p><span><?php echo $val['title'];?></span><span><?php echo $nickname;?></span></p>
					<p><?php echo nl2br(htmlspecialchars($val['content']));?></p>
				</div>
				<div class="bar_box_img">
					<img src="<?php echo $val['img'];?>" />
				</div>
			</div>
			<?php		
				}
			}
			if(!empty($activitys)){	
			?>
			
			
			<div class="qy_deail">
				<p class="qy_txt">权益详情</p>
				<!--第一个banner-->
				<?php 
					foreach($activitys as $val){
				?>
					<div class="txt_bg">
						<div class="txt_li">
							<ul>
								<li><img src="<?php echo F::getStaticsUrl('/activity/v2016/caiFuActivityPrivilege/');?>images/dian.png"/></li>
								<li>
									<p><?php echo $val['title'];?></p>
									<p><?php echo nl2br(htmlspecialchars($val['content']));?></p>
								</li>
							</ul>
						</div>
						<div class="clear"></div>
						<a href="<?php echo $val['url'];?>"><img class="banner" src="<?php echo $val['img'];?>" /></a>
					</div>
				<?php		
					}
				?> 
					
				<!--第二个banner-->
					<!--<div class="txt_bg">
						<div class="txt_li">
							<ul>
								<li><img src="images/dian.png"/></li>
								<li>
									<p>神奇的花园</p>
									<p>生命之神，铸就梦想</p>
								</li>
							</ul>
						</div>
						<div class="clear"></div>
						<a href="#"><img class="banner" src="images/banner2.png"/></a>
					</div>-->
			</div>
		<?php }?>
		</div>
	</body>
</html>
