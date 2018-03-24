<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>荔枝文化节-9.9秒杀</title>
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
		<div class="conter conter_h">
			<div class="conter_ban">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/images/bg01.jpg');?>">
			</div>
			<div class="bell">
				<span class="bell_img"></span> <span>下单后单请在30分钟内完成付款，不然会取消订单。</span>
			</div>
			<div class="conter_site">
				<!--1开始-->
				<?php if(!empty($seckillProducts)){
        		?>
				<div class="conter_site01 conter_site01_posi conter01">
					<a href="javascript:void(0)"  data-url="<?php if (!empty($shangChengUrl1)){echo $shangChengUrl1.'&pid='.$seckillProducts[0]['id'];}?>">
						<div class="conter_site01_l">
							<?php echo CHtml::image($seckillProducts[0]['img_name']); ?>
						</div>
						<div class="conter_site01_r">
							<div class="conter_site01_r_top">
								<p><?php echo $seckillProducts[0]['name'];?></p>
							</div>
							<div class="conter_site01_r_bottom">
								<div class="ter_site01_r_bottom_l">
									<p>￥9.9</p>
									<p class="cun1" data-cun="<?php echo $seckillProducts[0]['ku_cun'];?>">库存：<?php echo $seckillProducts[0]['ku_cun'];?>/5</p>
								</div>
								<div class="ter_site01_r_bottom_r">
									<p>立即购买</p>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="over_time">
							<p class="dt0" data-dt="<?php echo $seckillProducts[0]['date'];?>">开始时间：<?php echo $seckillProducts[0]['date'];?></p>
						</div>
					</a>
					<div class="conter_site01_posi_mask hide">
						<div class="end">
							<p>已结束</p>
						</div>
					</div>
				</div>
				<!--1结束-->
			</div>
			
			<div class="conter_site">
				<!--2开始-->
				<div class="conter_site01 conter_site01_posi conter02">
					<a href="javascript:void(0)" data-url="<?php if (!empty($shangChengUrl2)){echo $shangChengUrl2.'&pid='.$seckillProducts[1]['id'];}?>">
						<div class="conter_site01_l">
							<?php echo CHtml::image($seckillProducts[1]['img_name']); ?>
						</div>
						<div class="conter_site01_r">
							<div class="conter_site01_r_top">
								<p><?php echo $seckillProducts[1]['name'];?></p>
							</div>
							<div class="conter_site01_r_bottom">
								<div class="ter_site01_r_bottom_l">
									<p>￥9.9</p>
									<p class="cun2" data-cun="<?php echo $seckillProducts[1]['ku_cun'];?>">库存：<?php echo $seckillProducts[1]['ku_cun'];?>/5</p>
								</div>
								<div class="ter_site01_r_bottom_r">
									<p>立即购买</p>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="over_time">
							<p class="dt1" data-dt="<?php echo $seckillProducts[1]['date'];?>">开始时间：<?php echo $seckillProducts[1]['date'];?></p>
						</div>
					</a>
					<div class="conter_site01_posi_mask hide">
						<div class="end">
							<p>已结束</p>
						</div>
					</div>
				</div>
				<!--2结束-->
				<?php 
				    
				}?>
			</div>
		</div>
		<div class="on_time">
			<p>6月4日-6月13日 每天10:00 16:00 准时开抢</p>
		</div>
		<script>
			$(document).ready(function(){
				var num1 = parseInt($(".cun1").attr("data-cun"));
				var num2 = parseInt($(".cun2").attr("data-cun"));
				
				var myDate = new Date();
				var h = myDate.getHours();//获取时间
				if(num1 <= 0){
					$(".conter01").find(".conter_site01_posi_mask").removeClass("hide");
				}
				if(num2 <= 0){
					$(".conter02").find(".conter_site01_posi_mask").removeClass("hide");
				}
				$(".conter01 a").click(function(){
						var tips=$(".dt0").attr("data-dt");
						if(h < 10){
							alert(tips+'开始！');
						}else{
							if(num1 > 0){
								var url1=$(".conter01 a").attr("data-url");
								window.location.href=url1;
							}else{
								$(".conter01 a").attr("data-url","");
							}
						}
						return false;
				});

				$(".conter02 a").click(function(){
						var tip1=$(".dt1").attr("data-dt");
						if(h < 16){
							alert(tip1+'开始！');
							return false;
						}else{
							if(num2 > 0){
								var url2=$(".conter02 a").attr("data-url");
								window.location.href=url2;
							}else{
								$(".conter02 a").attr("data-url","");
							}
						}
			});
				
			});
		</script>
		
	</body>
</html>


