<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>神奇花园-获奖明细</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>js/PrizeDetail.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/normalize.css">
	</head>
	<body>
		<div class="int">
			<div class="award_top">
					<p>奖品名称</p>
					<p>消耗积分</p>
					<p>获奖时间</p>
			</div>
			<div class="award">
				
				<?php if(!empty($prizeMobileArr)){
					foreach ($prizeMobileArr as $value){?>
				<!--1开始-->
				<div class="award_ban">
					<p><?php echo $value->prize_name;?></p>
					<p><?php echo $value->integration;?></p>
					<p><?php echo date("Y-m-d",$value->create_time);?><br/><?php echo date("H:i:s",$value->create_time);?></p>
				</div>
			    <!--1结束-->
			    <?php }
				}?>
			</div>
			<div class="award_footer">
				<p>实物奖品以一元购码的形式实时发放到你的账户，请到彩之云一元购专区购买。</p>
				<p>* 定制U盘、充电宝、ipad大奖将由工作人员联系发放.</p>
			</div>
			<div class="footer_bg"></div>
		</div>
	</body>
</html>

