<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>元宵祝福 私人定制</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>js/ReFontsize.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>css/layout.css"/>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>js/jquery-1.11.3.js" ></script>
	</head>
	<body>
		<div class="containers">
			<div class="container">
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch01.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch02.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch03.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch04.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch05.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr06.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr07.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr08.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr09.jpg" />
		    </div>
		    <div class="play_con">
		    	 <div class="play_con1a">
		    	 	<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/<?php echo $greeting;?>.png">
		    	 </div>
			    	<div class="play">
				    	<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/<?php echo $photo;?>.png">
				    	<a href="<?php echo $this->createUrl('index');?>">我也要玩</a>
				    </div>
		    </div>
	    </div>
	    <?php if (!empty($tips)){?>
	    <div class="fe_x">
		      <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/tips.png" />
		</div>
		<?php }?>
		<script>
				$(document).ready(function(){
					setTimeout(function(){
						$(".fe_x").addClass("hide")	
					}, 2000);
				});
		</script>
	</body>
</html>
