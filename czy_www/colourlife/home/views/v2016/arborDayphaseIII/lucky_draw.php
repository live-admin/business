<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>神奇的花园</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>js/lucky_draw.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    	<style>
	    			@font-face {
						font-family:fontstyle;
						src: url('<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>fonts/fontstyle.ttf');
					}
	    	</style>
	</head>
	<body>
		<div class="int" >
			<div class="int_top">
				<p>当前积分：<span class="integrationValue"><?php echo $integrationValue;?></span></p>
			</div>
			<div class="int_p">
				<p>消耗的积分越高，奖品价值更高</p>
			</div>
			
			<div class="grade">
				<ul>
					<li class="score50 grade_active" id="Twenty">50积分</li>
					<li class="score150" id="Fifty">150积分</li>
					<li class="score300" id="hundred">300积分</li>
				</ul>
			</div>
			
			<div class="award_detail">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/award_detail.png" />			
			</div>
			
			<div class="turntable_wrap">
				<div class="present_bg present_bg_50"></div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/draw_bg.png" />
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/arrow.png" / class="zhizhen">	
				<div id="actionBtn" class="start">开始</div>
				<!--<img src="images/start02.png" />-->
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/qq.png" class="result hide" />
				<div class="award_turn">
					<p></p>
				</div>
				<span>图片仅供参考，活动奖品以实物为准</span>
			</div>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>	
		<!--遮罩层结束-->
		<!--获奖弹窗开始-->
		<div class="popup_reg hide">
			<div class="popup_reg_c">
				<p></p>
				<p></p>
			</div>
			<div class="confirm_buttom">
				<a href="javascript:void(0)"></a>
			</div>
		</div>
		<!--获奖弹窗结束-->
		<script type="text/javascript">
			var integrationValue = "<?php echo $integrationValue;?>";
			<?php 
				$awarderList=array();
				if(!empty($jiangArr)){
				foreach($jiangArr as $jiang){
					$str='恭喜'.substr_replace($jiang['mobile'],'****',2,5).'抽中'.$jiang['prize_name'];
					$awarderList[]=$str;
				}
			}
			$awarderList=json_encode($awarderList);
			?>
			var awarderList = <?php echo $awarderList;?>;
			var imgUrl="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>";
		</script>
	</body>
</html>
