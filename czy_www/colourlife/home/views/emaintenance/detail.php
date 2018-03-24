<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>报修</title>
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
		<div class="e_Maintenance t_repair">
			<div class="t_repair1a">
				<p><?php echo $result->title;?></p>
			</div>
			<div class="t_repair2a">
				<p><?php echo $result->ex_msg;?></p>
			</div>
			<div class="t_repair3a">
				<p>状态：<span class="p_color"><?php echo $result->statename;?></span></p>
				<p><?php echo $result->create_time;?></p>
				
			</div>
			<div class="clear"></div>
			<?php if (!empty($is_all)){?>
				<div class="footer1a">
					<a href="<?php echo $eshifu_url;?>">一键请师傅</a>
				</div>
				<div class="footer2a">
					<a href="<?php echo $eorder_url;?>">订单查询</a>
				</div>
			<?php }elseif (!empty($is_EshiFuUrl)){?>
				<div class="footer1a">
					<a href="<?php echo $eshifu_url;?>">一键请师傅</a>
				</div>
			<?php }elseif (!empty($is_EshiFuOrder)){?>
				<div class="footer2a">
					<a href="<?php echo $eorder_url;?>">订单查询</a>
				</div>
			<?php }else{?>
				<div class="footer">
					<a href="javascript:void(0)">一键请师傅</a>
				</div>
			<?php }?>
		</div>
	</body>
</html>
