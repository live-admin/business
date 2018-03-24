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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>js/index.js"></script>
     	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    	<style>
    			@font-face {
					font-family:fontstyle;
					src: url('<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>fonts/fontstyle.ttf');
				}
    	</style>
	</head>
	<body style="background:#9ED039;">
		<input style="display: none" id="vserion" />
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
							<p><span style="color:#B07550;">400</span>/<span>600</span></p>
						</div>
						<p class="jilu hide">连续登录+<span>1</span></p>
					</div>
					<div class="conter_z_top_box2a">
						<div class="conter_z_top_box2a_img"></div>
					</div>
					<!-- <div class="conter_z_top_box3a">
						<div class="conter_z_top_box3a_img01"></div>
						<div class="conter_z_top_box3a_img02"></div>
					</div> -->
					<div class="btn_watering">
						<!--<div class="conter_z_top_box3a_img01"></div>
						<div class="conter_z_top_box3a_img01_pree hide"></div>-->
						<img class="bt_show" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/watering.png"/>
						<img class="bt_press hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/watering01.png"/>
						<img class="yijian_show " src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/a_key_water01.png"/>
						<img class="yijian_press hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/a_key_water.png"/>
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
							<p>成长值 <span>400</span>/400</p>
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
				<div class="btn_share">
					<p>分享</p>
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
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗开始-->
		<!--一键浇水开始-->
		<div class="popup hide">
			<div class="popup_t">
				<span id="caiFuBtn"></span>
			</div>
			<div class="popup_b">
				<div class="popup_i">
					<p>您是彩富人生用户</p>
					<p>专享一键浇水功能</p>
					<p>一次性为所有植物浇水</p>
				</div>
			</div>
		</div>
		<!--一键浇水结束-->
		<!--升级提醒弹窗1开始-->
		<div class="popup_up hide">
			<div class="popup_up_t">
				<span class="pop_land01"></span>
			</div>
			<div class="popup_up_b">
				<p>恭喜你升到 3 级</p>
				<p>解锁了第 3 块土地</p>
				<p>并为你增加了10点经验值</p>
			</div>
		</div>
		<!--升级提醒弹窗1开始-->
		<!--邀请注册浇水成功弹窗开始-->
		<div class="popup_reg hide">
			<div class="popup_reg_t">
				<p>浇水成功！</p>
				<p>恭喜你获得1成长值</p>
				<p>邀请好友帮忙浇水可获更多成长值</p>
			</div>
			<div class="popup_reg_button">
				<a href="javascript:void(0)"></a>
			</div>
		</div>
		<!--邀请注册浇水成功弹窗结束-->
		<!--分享成功-->
		<div class="popup_reg_success hide">
			<div class="popup_reg_t_success">
				<p>分享成功！</p>
				<p>每邀请一位好友注册彩之云可获得10点成长值</p>
			</div>
			<div class="popup_reg_button_success">
				<a href="javascript:void(0)"></a>
			</div>
		</div>
		<!--分享结束-->
		<!--邀请彩富送成长值 开始-->
		<div class="popup_grow hide">
			<div class="popup_gro_t">
				<p>您在环球精选下单3单</p>
				<p>获得15点成长值</p>
			</div>	
			<div class="popup_gro_b">
				<p class="seltse">农场1 （当前成长值390/400）</p>
				<p>农场2 （当前成长值390/400）</p>
				<p>农场3 （当前成长值390/400）</p>
			</div>
			<div class="popup_gro_button">
				<a href="javascript:void(0)"></a>
			</div>
		</div>
		<!--邀请彩富送成长值 结束-->
		<!--老用户打开提示开始-->
		<div class="old_popup hide">
			<div class="old_popup_t">
				<p>欢迎回来</p>
				<p>新一期的活动已经开始</p>
			</div>
			<div class="old_popup_b">
				<p>原有经验值已为你转化为等级</p>
				<p>您有<span style="color:#F93232;">352点</span>经验值</p>
				<p>所对应等级为<span style="color:#F93232;">4级</span>(拥有<span>4</span>块土地)</p>
			</div>
			<div class="old_popup_f">
				<div class="old_popup_f_t">
					<ul>
						<li>Lv.1</li>
						<li>Lv.2</li>
						<li>Lv.3</li>
						<li>Lv.4</li>
						<li>Lv.5</li>
						<li>Lv.6</li>
						<li>Lv.7</li>
						<li>Lv.8</li>
					</ul>
				</div>
				<div class="old_popup_f_b">
					<ul>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
					<p class="red_flag"></p>
				</div>
				<div class="old_popup_f_f">
					<ul>
						<li>99点</li>
						<li>299点</li>
						<li>799点</li>
						<li>1599点</li>
						<li>2999点</li>
						<li>5999点</li>
						<li>11999点</li>
						<li>24000点</li>
					</ul>
				</div>
			</div>
		</div>
		<!--老用户打开提示结束-->
		<!--弹窗结束-->
		
		
	</body>
	<script>
		var checkNewOld = <?php echo json_encode($checkNewOld);?>;
		var isCai = <?php echo json_encode($isCai);?>;
		var loginExperienceValue = <?php echo json_encode($loginExperienceValue);?>;
		var list = <?php echo json_encode($list);?>;
		var list_id = <?php echo json_encode($list_id);?>;
		var tuDiArr = <?php echo json_encode($tuDiArr);?>;
		var moRenGrowValue = <?php echo json_encode($moRenGrowValue);?>;
		var listIdZhai = <?php echo json_encode($listIdZhai);?>;
		var xingChenOrderTan = <?php echo json_encode($xingChenOrderTan);?>;
		var jingDongOrderTan = <?php echo json_encode($jingDongOrderTan);?>;
		var huanQiuOrderTan = <?php echo json_encode($huanQiuOrderTan);?>;
		var caiTeGongOrderTan = <?php echo json_encode($caiTeGongOrderTan);?>;
		var tuiJianOrderTan = <?php echo json_encode($tuiJianOrderTan);?>;
		var yaoQingOrderTan = <?php echo json_encode($yaoQingOrderTan);?>;
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>images/img_pro.png";
		
		var url = "<?php echo F::getHomeUrl('/ZhiShuJieThree/Share?reUrl='.$url.'&share=ShareWeb&sl_key=treeThree'); ?>"
		var listIdNew = <?php echo json_encode($listIdNew);?>;
		var listIdValue = <?php echo json_encode($listIdValue);?>;
		var NotCFhref = <?php echo json_encode($NCUrl);?>;
		var isCaiTan = <?php echo json_encode($isCaiTan);?>;
		//新版分享功能参数
		var u = navigator.userAgent, app = navigator.appVersion;
	    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	    
	    if (isAndroid) {
			var param={
		    		"platform": [
		                 "ShareTypeWeixiSession",
		                 "ShareTypeWeixiTimeline",
		                 "ShareTypeQQ",
		                 "ShareTypeSinaWeibo",
						 "NeighborShare"
		             ],
		             "title": "帮我浇水吧！",
		             "url": url,
		             "image": imgUrl,
		             "content": "快来神奇的花园，一起浇水、开拓土地，赢大奖！",
//		             "NeighborShare":1 //0不显示，1显示
		         };
	    }
	    else if (isiOS) {
	    	var param={
	    		"platform": [
	                 "ShareTypeWeixiSession",
	                 "ShareTypeWeixiTimeline",
	                 "NeighborShare",
//	                 "ShareTypeQQ",
//	                 "ShareTypeSinaWeibo"
	
	             ],
	             "title": "帮我浇水吧！",
	             "url": url,
	             "image": imgUrl,
	             "content": "快来神奇的花园，一起浇水、开拓土地，赢大奖！",
	             
	         };
	    }
		
   	 	//旧版分享功能参数
		var params = {
	            "text" : "快来神奇的花园，一起浇水、开拓土地，赢大奖！",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title" : "帮我浇水吧！",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };
	</script>
</html>


