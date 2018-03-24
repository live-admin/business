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
	<form action="build" method="post" id="build">
		<input type="hidden" name="cid" value="<?php echo $cust_id;?>" id="cid"/>
		<input type="hidden" name="photo" value="<?php echo $photo;?>"/>
		<div class="containers">
			<div class="container">
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg01.jpg">
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg02.jpg">
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg03.jpg">
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg04.jpg">
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg05.jpg">
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg06.jpg">
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg07.jpg">
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg08.jpg">
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/bg09.jpg">
		    </div>
		    <div class="choose_bg">
		    	<input type="hidden" name="greeting" value="" class="greeting"/>
		    	<div class="choose_bg1a">
		    		<a href="javascript:void(0)">
		    			<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/hf00.png">
		    		</a>
		    	</div>

		    </div>
			<div class="bg hide">
				<div class="container1a">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_01.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_02.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_03.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_04.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_05.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_06.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_07.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_08.jpg">
					<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/xz_09.jpg">
				</div>
				<div class="choose_bg2a">
					<div class="choose_bg3a">
						<img src="" id="img">
					</div>
					<div class="choose_bg4a">
						<a href="#">换一个</a>
					</div>
					<div class="choose_bg5a">
						<a href="javascript:void(0)">
							<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sc.png">
						</a>
					</div>
				</div>
			</div>

	    </div>
	 </form>
	    <script>
	    	$(document).ready(function(){
	    		//点击祝福语隐藏
	    		$(".choose_bg1a").find("a").click(function(){
	    			$(".container").addClass("hide");
	    			$(".bg").removeClass("hide");
	    			var num = Math.floor(Math.random() * 8 + 1);
	    			$("#img").attr("src","<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/"+num+".png");
	    			$(".greeting").val(num);
	    		});
	    		//换一个
	    		$(".choose_bg4a").find("a").click(function(){
	    			var num = Math.floor(Math.random() * 8 + 1);
	    			$("#img").attr("src","<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/"+num+".png");
	    			$(".greeting").val(num);
	    		});
	    		//生成图片
	    		$(".choose_bg5a").find("a").click(function(){
	    			$("#build").submit();
	    		});
	    	});
	    </script>
	</body>
</html>
