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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>js/share.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="conter">
			<div class="bg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_01.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_02.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_03.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_04.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_05.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_06.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_07.jpg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/bg_08.jpg">
			</div>
			<div class="conter_z">
				<div class="conter_z_top">
					<div class="conter_z_top_box1a">
						<div class="conter_z_top_box1a_img">	
							<p>等级：<span>3</span></p>
							<p><span>400</span>/<span>600</span></p>
						</div>
					</div>
					<div class="btn_watering">
						<!--<div class="conter_z_top_box3a_img01"></div>
						<div class="conter_z_top_box3a_img01_pree hide"></div>-->
						<img class="bt_show" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/watering.png"/>
						<img class="bt_press hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/watering01.png"/>
						<p><span class="num"></span>/5</p>
					</div>
				</div>	
				<div class="conter_z_ban">
					<div class="conter_z_ba_01">
						<ul class="togg">
							<li class="land01 move">
								<span class="new hide"></span>
							</li>
							<li class="land02 hide">
								<span class="new hide"></span>
							</li>
							<li class="land03 hide">
								<span class="new hide"></span>
							</li>
							<li class="land04 hide">
								<span class="new hide"></span>
							</li>
							<li class="land05 hide">
								<span class="new hide"></span>
							</li>
							<li class="land06 hide">
								<span class="new hide"></span>
							</li>
							<li class="land07 hide">
								<span class="new hide"></span>
							</li>
							<li class="land08 hide">
								<span class="new hide"></span>
							</li>
						</ul>
						<div class="growth">
							<p>成长值 <span><?php echo $moRenGrowValue;?></span>/400</p>
						</div>
					</div>
					<div class="conter_z_ba_02">
						<div class="tomatoes active">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tomatoes_fruit.png" class="tomatoes_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tomatoes.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="yellow_chili active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/yellow_chili_fruit.png" class="yellow_chili_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/yellow_chili.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="red_chili active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/red_chili_fruit.png" class="red_chili_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/red_chili.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="eggplant active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/eggplant_fruit.png" class="eggplant_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/eggplant.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="cucumber active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/cucumber_fruit.png" class="cucumber_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/cucumber.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="chili active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/chili_fruit.png" class="chili_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/chili.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="apple active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/apple_fruit.png" class="apple_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/apple.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
						<div class="litchi active hide">
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/litchi_fruit.png" class="litchi_img hide">
							<div class="tree">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/litchi.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/tree_seedlings.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/germination.png" class="hide">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/seeds.png">
							</div>
						</div>
					</div>
				</div>
				
				<!--浇水-->
				<div class="watering hide">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/watering_move.png"/>
				</div>	
				<div class="btn_rule">
					<p>活动规则</p>
				</div>
			</div>
		</div>
		<!--下载开始-->
		<div class="btm_bg"></div>
	    <div class="share_footer">
	     	<div class="share_footer_box01">
	     		<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/czy_logo.png">
	     	</div>
	     	<div class="share_footer_box02">
	     		<a href="javascript:void(0);">
	     			立即下载
	     		</a>
	     	</div>
	     	<div class="share_footer_box03">
	     		<a href="javascript:void(0)">
	     			<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/delet.png">
	     		</a>
	     	</div>
	    </div>
		<!--下载结束-->
		
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗开始-->
		<div class="popup_reg hide">
			<div class="popup_reg_t popup_reg_conter">
				<p>浇水成功！</p>
				<p>成功为好友增加了1点成长值</p>
			<!--	<p>每日浇水可以抽取iPad大奖！</p>-->
			</div>
			<div class="kind_buttom">
				<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway"></a>
			</div>
		</div>
		<!--弹窗结束-->
		
		
		<script type="text/javascript">
			var sd_id = "<?php echo $seed_id;?>";
			var cid = "<?php echo $cust_id;?>";
			var sign = "<?php echo $validate;?>";
			var tuDiArr = <?php echo json_encode($tuDiArr) ?>;
			var moRenGrowValue = <?php echo $moRenGrowValue?>;
			var list_id = <?php echo json_encode($list_id);?>;
			var num = <?php echo json_encode($num);?>;
		</script>
		
		
	</body>
</html>


