<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $model->share_title;?></title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/ReFontsize.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>css/layout.css"/>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/jquery-1.11.3.js" ></script>
	</head>
	<body>
		<div class="conter">
			<div class="user">
				<div class="user_header">
					<div class="user_header_box1a">
						<p><?php echo $model->share_title;?></p>
						<span>作者：<?php echo $name;?></span>
						<span>创建时间：<?php echo date('Y.m.d', $model->create_time);?></span>
						<div class="clear"></div>
					</div>
				</div>
				<div class="user_banner_top">
					<p><?php echo $model->share_content;?></p>
					<?php $img_arr=explode(";",$model->share_img);foreach($img_arr as $v){
	            		if (empty($v)){
							continue;
						}
	            	?>
		            	<img src="<?php echo F::getUploadsUrl().$v;?>">
		            <?php }?>
				</div>


			</div>
			<div class="footer1a" id="bar">
				<div class="footer_con">
					<div class="footer_con_box1a">
						<img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/logo.png" />
					</div>
					<div class="footer_con_box2a">
						<a href="http://mapp.colourlife.com/m.html">
							立即下载
						</a>
					</div>
					<div class="footer_con_box3a" >
						<a href="javascript:void(0)" onclick="removeElement('bar')">
							<img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/delete.png" />
						</a>

					</div>
				</div>
			</div>




		</div>
		<div class="mask hide" ></div>

	<script>
		function removeElement(id) {
			document.getElementById(id).style.display = "none";
		}
	</script>

	</body>
</html>