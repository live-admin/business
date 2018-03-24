<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>排名榜</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
    	<meta name="format-detection" content="telephone=no"/>
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	 	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/'); ?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/'); ?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/'); ?>js/ranking.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/'); ?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/'); ?>css/normalize.css">
	</head>
	<body>
		<div class="contaner_ranking" >
			<div class="contan">
				<!--标题开始-->
				<div class="bar_box">
						<div class="bar_box_number">
							<div class="bar_box_number_bg">
							    <p>名次</p>
							</div>
						</div>
						<div class="bar_box_tel">
							<p>手机</p>
						</div>
						<div class="bar_box_ml">
							<p>积分</p>
						</div>
				</div>
				
			    <!--标题结束-->
			    <!--排名开始-->
				<div class="bar_other">
				        
						<?php if(!empty($rankList)){
								foreach ($rankList as $key=>$value){?>
									<?php if($value['customer_id']==$currentUserID){?>
										<!--自己-->
										<div class="bar_box_my bar_padd">
											<div class="bar_box_other_number">
												<div class="bar_box_other_number_bg bg_blue_my">
												    <p><?php echo $key + 1; ?></p>
												</div>
											</div>
											<div class="bar_box_other_self">
												<p><?php echo $value['mobile']; ?></p>
											</div>
											<div class="bar_box_other_my">
												<p><?php echo $value['total']; ?></p>
											</div>
										</div>
										<!--自己-->
									<?php } ?>
						
									<!--其他-->
									<div class="bar_box_other">
										<div class="bar_box_other_number">
											<div class="bar_box_other_number_bg">
											    <p><?php echo $key + 1; ?></p>
											</div>
										</div>
										<div class="bar_box_other_tel">
											<p><?php echo substr_replace($value['mobile'], '****', 3, 4); ?></p>
										</div>
										<div class="bar_box_other_ml">
											<p><?php echo $value['total']; ?></p>
										</div>
									</div>
										<?php }
										}
									?>
									<!--其他-->
				</div>
				<div class="answ">
					<p>如有疑问请咨询彩生活客服：4008-893-893</p>
				</div>
			</div>
			<div class="foot_bg">		
				<img class="rule_img" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/'); ?>images/paiming_bg.jpg"/>
				<div class="rule_btn">
					<a href="/LoveHook/Rule">活动规则</a>
				</div>
			</div>		
		</div>
		<script>
			$(document).ready(function(){
                      if($(".bar_box_my").hasClass("bar_padd")){
							$(".bar_box_my").removeClass("bar_padd");
							$(".bar_other").addClass("bar_top")
                      }
			});
		</script>
	</body>
</html>