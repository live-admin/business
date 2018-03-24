<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>周年庆活动</title>
		<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
    	<link href="<?php echo F::getStaticsUrl('/common/css/lucky/djh/djh.css');?>" rel="stylesheet">
	</head>

	<body style="background:#E5004F">
		<div class="hwzg">
			<div class="anim_image">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/img1.jpg');?>" class="lotteryimg" />
				<div class="animate_content">
				   <img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/img1.jpg');?>" class="anmt_img" />
				</div>
			</div>
			<div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/title.jpg');?>" class="lotteryimg" /></div>
            <div><a href="/ToFriend?userid=<?=$cust_id;?>"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/img8.jpg');?>" class="lotteryimg" /></a></div>
			<div class="hwzg_content">
				<a href="/ingInvite" class="left"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/img2.png');?>"/></a>
				<!--汽车    -->
				<a href="/luckyApp/carTopic" class="row"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/bnr1.jpg');?>" class="lotteryimg"/></a>
				<!--红包    -->				
				<a href="/luckyApp/luckyApp" class="row"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/bnr2.jpg');?>" class="lotteryimg"/></a>
				<!--六一   -->
				<a href="/robRiceDumplings" class="row"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/bnr3.png');?>" class="lotteryimg"/></a>
				<!--粽子  -->
				<a href="/robLitChi" class="row"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/djh/bnr4.png');?>" class="lotteryimg"/></a>
				<!-- javascript:void(0); -->
			</div>
		    <p class="botp">★注：彩生活享有本次活动的最终解释权 </p>
		</div>
        <script type="text/javascript">
        	$(document).ready(function(){
        		$('.anmt_img').animate({width:'100%', marginTop:'0'},1000);        		
        	})
        </script>
		
	</body>

</html>