<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>中秋节晚会抽奖</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/lotteryActivity/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
</head>
<body style="background-color:#F6E8E7">
	<header >
		<img src="<?php echo F::getStaticsUrl('/activity/v2016/lotteryActivity/');?>images/banner.jpg">	
	</header>
	
    <!--中奖名单-->
	<div class="prize_content order_content hide">
    	<div class="order_wrap">
            <div class="order_top"></div>
            <div class="order">
            	<!--<p>三等奖</p>
                <p>二等奖</p>
                <p>2饭票</p>
                <p>2饭票</p>-->
            </div>
        </div>
    </div>
    
    <!--没有中奖-->
    <div class="prize_content noPrize_content hide">
    	<div class="order_tips"></div>    
    </div>
    
    <div class="order_footer"></div>
    
    <script>

		
//		var list=[{"prize_name":"\u4e00\u7b49\u5956"},
//				{"prize_name":"\u4e00\u7b49\u5956"},
//				{"prize_name":"\u4e00\u7b49\u5956"},
//				{"prize_name":"\u4e00\u7b49\u5956"}
//		]

		var list = <?php echo($list);?>;
		
		if(list.length == 0){
			$(".noPrize_content").removeClass("hide");	
			$(".order_content").addClass("hide");	
		}else{
			$(".noPrize_content").addClass("hide");	
			$(".order_content").removeClass("hide");
			for(var l in list){
				$(".order").append('<p>'+list[l].prize_name+'</p>');
			}
			
		}

    </script>
</body>
</html>

