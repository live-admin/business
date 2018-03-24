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
	    <script src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>js/jquery-1.11.3.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>css/layout.css"/>
	</head>
	<body>
	<form action="greeting" method="post">
		<input type="hidden" name="cid" value="<?php echo $cust_id;?>"/>
		<div class="containers">
			<div class="container">
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch1.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch2.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch3.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch4.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch5.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr6.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr7.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr8.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr9.jpg" />
		    </div>
		    <div class="choose">
		    	<div class="choose1a">
		    		<ul class="choose1a_sex">
		    			<li class="p1">男</li>
		    			<li>女</li>
		    		</ul>
		    	</div>
		    	<div class="choose_zong">
		    	<input type="hidden" name="photo" value="boy01" class="photo"/>
		    		<div class="choose2a">
			    		<div class="choose3a">
			    			<ul class="choose3a_tab">
			    				<li><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/boy01.png"></li>
			    				<li class="hide"><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/boy02.png"></li>
			    				<li class="hide"><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/boy03.png"></li>
			    				<li class="hide"><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/boy04.png"></li>
			    			</ul>
			    		</div>
			    		<div class="choose4a">
			    			<ul class="choose4a_figure">
			    				<li class="p2" data-name="boy01">财神</li>
			    				<li data-name="boy02">金童</li>
			    				<li data-name="boy03">超人</li>
			    				<li data-name="boy04">哆啦A梦</li>
			    			</ul>
			    		</div>
			    	</div>
			    	<div class="choose2b hide">
			    		<div class="choose3b">
			    			<ul class="choose3b_tab">
			    				<li><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/girl01.png"></li>
			    				<li class="hide"><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/girl02.png"></li>
			    				<li class="hide"><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/girl03.png"></li>
			    				<li class="hide"><img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/girl04.png"></li>
			    			</ul>
			    		</div>
			    		<div class="choose4b">
			    			<ul class="choose4b_figure">
			    				<li class="p2" data-name="girl01">小樱</li>
			    				<li data-name="girl02">玉女</li>
			    				<li data-name="girl03">小丸子</li>
			    				<li data-name="girl04">包租婆</li>
			    			</ul>
			    		</div>
			    	</div>
		    	</div>
		    </div>
		    <div class="choose5a">
		    		<input type="submit" name="submit" value="选好了"/>
		    </div>
	    </div>
	</form>    
	    <script>
		    $(document).ready(function(){
	    		//男和女切换
	    		$(".choose1a_sex li").click(function(){
	    			$(this).addClass("p1").siblings().removeClass();
	    			$(".choose_zong > div").hide().eq($(".choose1a_sex li").index(this)).show();
	    			var sex=$(".p1").text();
	    			if(sex=='女'){
	    				$(".photo").val('girl01');
		    		}else{
		    			$(".photo").val('boy01');
				    }
	    		});
	    		//人物切换
	    		$(".choose4a_figure li").click(function(){
	    			$(this).addClass("p2").siblings().removeClass();
	    			$(".choose3a_tab > li").hide().eq($(".choose4a_figure li").index(this)).show();
	    			var photo=$(this).attr("data-name");
	    			$(".photo").val(photo);
	    		});
	    		$(".choose4b_figure li").click(function(){
	    			$(this).addClass("p2").siblings().removeClass();
	    			$(".choose3b_tab > li").hide().eq($(".choose4b_figure li").index(this)).show();
	    			var photo=$(this).attr("data-name");
	    			$(".photo").val(photo);
	    		});
	    	});
	    </script>
	</body>
</html>
