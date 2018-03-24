<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $model->travel_title;?></title>
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
			<div class="banner">
				<div class="banner_top">
						<div class="banner_img">
							<?php
				                echo str_replace(array('{$image1}','{$image2}','{$image3}','{$image4}','{$image5}'),
				                    array('<img src="'.F::getStaticsUrl('/home/colourTravel/').'images/'.$model->id.'pic01.png">',
				                        '<img src="'.F::getStaticsUrl('/home/colourTravel/').'images/'.$model->id.'pic02.png">',
				                        '<img src=" '.F::getStaticsUrl('/home/colourTravel/').'images/'.$model->id.'pic03.png">',
				                        '<img src=" '.F::getStaticsUrl('/home/colourTravel/').'images/'.$model->id.'pic04.png">',
				                        '<img src=" '.F::getStaticsUrl('/home/colourTravel/').'images/'.$model->id.'pic05.png">'),
				                        $model->travel_introduce); ?>
						</div>
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
					<div class="footer_con_box3a">
						<a href="javascript:void(0)" onclick="removeElement('bar')">
							<img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/delete.png" />
						</a>

					</div>
				</div>
			</div>




		</div>

		<script>
			function removeElement(id) {
				document.getElementById(id).style.display = "none";
			}
		</script>
	</body>
</html>
