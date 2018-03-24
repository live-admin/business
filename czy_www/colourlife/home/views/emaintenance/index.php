<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>E维修</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/emaintenance/'); ?>js/ReFontsize.js"></script>
	    <link href="<?php echo F::getStaticsUrl('/home/emaintenance/'); ?>css/layout.css" rel="stylesheet">
	    <script src="<?php echo F::getStaticsUrl('/home/emaintenance/'); ?>js/jquery-1.11.3.js"></script>
    </head>
	<body>
		<div class="e_Maintenance">
		<?php foreach ($info as $val){?>
			<!--1开始-->
			<div class="e_Maintenance1a">
				<a href="<?php echo $val['detail_url'];?>">
					<div class="e_Maintenance1a_box1a">
						<img src="<?php echo F::getStaticsUrl('/home/emaintenance/'); ?>images/e_banner.png" />
						<p><?php echo $val['statename'];?></p>
					</div>
					<div class="e_Maintenance1a_box2a">
						<p style="width: 95%;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?php echo $val['title'];?></p>
						<p style="width: 95%;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?php echo $val['msg'];?></p>
						<p>创建时间：<?php echo $val['createtime'];?></p>
					</div>
				</a>
			</div>
			<!--1结束-->
		<?php }?>
		</div>
	</body>
</html>
