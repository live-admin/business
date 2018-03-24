<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>猴赛雷的宝箱</title>
     <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
     <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telphone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    <style>
		@font-face {
			font-family:fontstyle;
			src: url('<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>fonts/fontstyle.ttf');
		}
	</style>
</head>
<body>
	<div class="all_wrap">
		<div class="award_order">
        	<div class="award_top">
            	<p>活动礼品</p>
                <p>开箱时间</p>
            </div>
            <div class="award_detail">
	            <?php if(!empty($prizeMobileArr)){
						foreach ($prizeMobileArr as $value){?>
	            <div class="item">
	            	<p><?php echo $value->prize_name;?></p>
					<p><?php echo date("Y-m-d",$value->create_time);?><br/><?php echo date("H:i:s",$value->create_time);?></p>
	            </div>
	             <?php }
					}?>
			</div>
        </div>
        <div class="footer_resulte"></div>
    </div>
	
	<!--宝箱奖品为空页面-->
    <div class="null hide">
			  <div class="null_con">
			  	<img src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/smile.png">
			  	<p>您还未开启过宝箱</p>
			  	<p>快点开启吧！</p>
			  </div>
		      <div class="rule_footer null_footer"></div>	
	</div>
	
	<script>
	var prizeMobileArr=<?php echo json_encode($prizeMobileArr);?>;
		if(prizeMobileArr == ""){
			$(".all_wrap").addClass("hide");
			$(".null").removeClass("hide");
		}
	</script>
	</body>
</html>
