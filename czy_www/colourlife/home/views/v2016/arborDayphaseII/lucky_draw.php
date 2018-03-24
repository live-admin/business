<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>神奇花园-抽奖页面</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>js/luckyDraw.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/normalize.css">
	</head>
	<body>
		<div class="int">
			<div class="int_top">
				<p>当前积分：<span><?php echo $integrationValue;?></span></p>
			</div>
			<div class="int_p">
				<p>消耗的积分越高，奖品价值越高</p>
			</div>
			<div class="int_int">
				<div class="int_box01 int_box_img02" id="Twenty">
					<p>20积分</p>
				</div>
				<div class="int_box01 int_box_img01 int_box_l" id="Fifty">
					<p>50积分</p>
				</div>
				<div class="int_box01 int_box_img03 int_box_l" id="hundred">
					<p>100积分</p>
				</div>
			</div>
			<div class="int_footer">
				<div class="int_footer_p">
					<div class="int_footer_p_top">
						<p></p>
					</div>
					
					<p class="prompt">图片仅供参考，活动奖品以实物为准。</p>
				</div>
				
			</div>
			<div class="round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/round01.png">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/round.png">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/cursor01.png">
				<div class="p" id="actionBtn">
					开始
				</div>
				<img class="triangle" style="display:none" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/triangle.png">
			</div>

			<!--遮罩层开始-->
			<div class="mask hide"></div>
			<!--遮罩层结束-->
				
			<!--弹窗开始-->
			<div class="Popup hide">
			<div class="e_shifu">
				<div class="e_shifu_con">
					<div class="e_shifu_con_txt">
						<p>你的积分不足</p>
					</div>
					<div class="e_shifu_con_btn">
						<a href="javascript:void(0)">确定</a>
					</div>
				</div>
			</div>	 
			<!--关闭按钮-->
			<div class="dele_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/dele.png"/>
			</div>
			</div>

		</div>
		<div class="award_fix"></div>
		
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
