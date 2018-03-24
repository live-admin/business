<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>我的宝藏</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>js/ReFontsize.js"></script>
	    <script src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>js/jquery-1.11.3.js"></script>
	    <link href="<?php echo F::getStaticsUrl('/home/baoXiang/');?>css/layout.css" rel="stylesheet">
	    <style>
				body{
					background-image: url(<?php echo F::getStaticsUrl('/home/baoXiang/');?>/images/banner_bg.jpg) !important;  
				}
       </style>
	</head>
	<body>
		<div class="query_t_t_chest quert_hight">
			<div class="query_t_t_chest1a">
				<ul class="query_t_t_chest2a">
					<li>类型</li>
					<li>详情</li>
					<li>挖宝时间</li>
				</ul>
			</div>
			<div class="query_t_t_chest3a">
			<?php 
				if (!empty($other_prize_result)){
					foreach($other_prize_result as $val){
			?>
				
				<?php if (strpos($val->code, "zuhe")!==false){
				    $p_result=BaoXiang::model()->getPrize($val->code);
				    if (!empty($p_result)){
						foreach ($p_result as $k=>$v){
							foreach ($v as $vv){
				?>
				<ul class="query_t_t_chest4a">
					<li><?php echo $k;?></li>
					<li><?php echo $vv;?></li>
					<li><?php echo date("Y-m-d",$val->create_time);?></li>
				</ul>
				<?php    }
						}
					 }
					}else {
				?><ul class="query_t_t_chest4a">
					<li><?php if (strpos($val->code, "eweixiu")!==false){?>E维修<?php }elseif (strpos($val->code, "ezufang")!==false){?>E租房<?php }elseif ($val->code=='youlun'){?>邮轮<?php }?></li>
					<li><?php echo $val->prize_name;?></li>
					<li><?php echo date("Y-m-d",$val->create_time);?></li>
				</ul>
				<?php }
				  }
				}
				if (!empty($user_coupons_result)){
					foreach($user_coupons_result as $val){
			?>
				<ul class="query_t_t_chest4a">
					<li>优惠券</li>
					<li><?php $youhuiquan=BaoXiang::model()->getYouHuiQuan($val->you_hui_quan_id);if (!empty($youhuiquan)){echo $youhuiquan->name; }?></li>
					<li><?php echo date("Y-m-d",$val->create_time);?></li>
				</ul>
			<?php }
				}
			?>			
			</div>
			<div class="query_t_t_chest_p">
					<p>ps：1、我的卡券可以查询折扣券记录</p>
					<p style="margin: 0 0 0 0.5rem">2、E维修代金券可在“E维修>个人保修”里使用</p>
			</div>
		</div>
	</body>
</html>
