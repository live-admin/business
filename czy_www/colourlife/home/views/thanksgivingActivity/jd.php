<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>京东首页</title>
		<link href="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>css/gedc.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js"></script>
	</head>

	<body>
		<div class="gedc">
			<a id="top"></a>
			<!--头部图片-->
			<div class="head_img">
                                <?php if (time()<= strtotime('2015-06-30 23:59:59')){?>
				   <img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/head.jpg" />
				   <img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_01.jpg" />
                                <?php } else {?>
                                   <img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_head.png" />
                                <?php }?>
			</div>

			<!--1元购惊喜-->
			<div class="title">手机电脑<a href="<?=$jdurl?>"><span class="text_red">更多热卖</span></a>
			</div>

			<!--嘿客与海外直购-->
			<div class="frist_row_img clearfix height_auto">
				<img class="iphone_type" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_03.jpg" />
				<img class="samsung_type" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_02.jpg" />
			</div>

			<!--热卖商品-->
			<div class="second_row_img clearfix">
				<div class="clearfix">
					<a href="<?=$jdurl?>&pid=9847">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_05.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=2075">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_06.jpg" />
					</a>
					<img class="dostyle_type" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_07.jpg" />
				</div>
			</div>

			<!--电子产品-->
			<div class="three_row clearfix height_auto">
				<a href="<?=$jdurl?>&pid=9846">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_08.jpg" />
				</a>
				<a href="<?=$jdurl?>&pid=3343">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_09.jpg" />
				</a>
				<a href="<?=$jdurl?>&pid=3375">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_10.jpg" />
				</a>
				<a href="<?=$jdurl?>&pid=2137">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_11.jpg" />
				</a>
				<a href="<?=$jdurl?>&pid=2206">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_12.jpg" />
				</a>
				<a href="<?=$jdurl?>&pid=3457">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_13.jpg" />
				</a>

			</div>

			<!--家电保健-->
			<div class="title">家电保健<a href="<?=$jdurl?>"><span class="text_red">更多热卖</span></a>
			</div>

			<!--家电保健-->
			<div class="frist_row_img clearfix height_auto">
				<div class="row_left ">
                                    <a href="<?=$jdurl?>&pid=5150"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_15.jpg" /></a>
				</div>
				<div class="two_row">
					<div>
                                            <a href="<?=$jdurl?>&pid=9840"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_14.jpg" /></a>
					</div>
                                    <a href="<?=$jdurl?>&pid=5166"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_16.jpg" /></a>
                                    <a href="<?=$jdurl?>&pid=9841"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/jd_17.jpg" /></a>
				</div>
			</div>

			<!---->
			<div class="foot_list">
				<div class="four_row clearfix height_auto">
					<a href="<?=$jdurl?>&pid=8760">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_02.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=8739">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_03.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=8732">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_04.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=8882">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_05.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=9850">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_06.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=9842">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_07.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=9851">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_08.jpg" />
					</a>
					<a href="<?=$jdurl?>&pid=6972">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/630JD2_09.jpg" />
					</a>
				</div>

			</div>
			<p class="botp">★注：彩生活享有本次活动的最终解释权 </p>
		</div>

		<!--弹出框-->
		<div class="pop_up" style="display: none;">
			<!--Iphone -->
			<div class="iphone_pop iphone" style="display: none;">
				<p>请选择型号</p>
				<div class="iphone_src clearfix">
					<div>
						<a href="<?=$jdurl?>&pid=2033"></a>iPhone 6 (A1586)</br>16GB 金色 全网通</div>
					<div>
						<a href="<?=$jdurl?>&pid=4874"></a>iPhone 6 Plus (A1524)</br>16GB 金色 全网通</div>
					<div>
						<a href="<?=$jdurl?>&pid=4886"></a>iPhone 6 (A1586)</br>64GB 金色 全网通</div>
					<div>
						<a href="<?=$jdurl?>&pid=4886"></a>iPhone 6 Plus (A1524)</br>64GB 金色 全网通</div>
				</div>
			</div>

			<!--三星 -->
			<div class="iphone_pop samsung" style="display: none;">
				<p>请选择型号</p>
				<div class="iphone_src clearfix">
					<div>
						<a href="<?=$jdurl?>&pid=5492"></a>G9208 32G版 星钻黑 </br>移动 4G 双卡双待</div>
					<div>
						<a href="<?=$jdurl?>&pid=5495"></a>G9208 32G版 雪晶白 </br>移动 4G 双卡双待</div>
					<div>
						<a href="<?=$jdurl?>&pid=5007"></a>G9209 32G版 星钻黑 </br>电信 4G 双卡双待</div>
					<div>
						<a href="<?=$jdurl?>&pid=5005"></a>G9209 32G版 雪晶白 </br>电信 4G 双卡双待</div>
				</div>
			</div>

			<!--耳机 -->
			<div class="iphone_pop dostyle" style="display: none;">
				<p>请选择型号</p>
				<div class="iphone_src clearfix">
					<div>
						<a href="<?=$jdurl?>&pid=9848"></a>HS303 多功能入耳式耳机 </br>炫动红</div>
					<div>
						<a href="<?=$jdurl?>&pid=9849"></a>HS303 多功能入耳式耳机 </br>钛金灰</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			 //iphone弹出框
			$('.iphone_type').click(function() {
				$('.pop_up').show();
				$('.iphone').show();
			});
			 //samsung弹出框
			$('.samsung_type').click(function() {
				$('.pop_up').show();
				$(".samsung").show();
			});
			$('.dostyle_type').click(function() {
				$('.pop_up').show();
				$(".dostyle").show();
			});
			 //关闭窗口
			$('.dostyle').click(function() {
				$(this).parents('.popuo_return').hide();
				$('.gedc').show();
			});
			
			$('.pop_up').click(function(e) {
				
				var obj = e.srcElement || e.target;
				
				if ($(obj).is('.pop_up')) {
					$('.pop_up,.pop_up>div').hide();
				}
			});
		</script>
	</body>

</html>