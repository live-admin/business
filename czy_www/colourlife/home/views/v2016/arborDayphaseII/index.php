<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>神奇花园-首页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>js/main.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/normalize.css">
    	<style>
	       	@font-face {
				font-family:fontstyle;
				src: url('<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>fonts/fontstyle.ttf');
			}
			body{
				background:#9FD039;
			}
	   </style>
    	<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/warmPurse/js/ShareSDK.js');?>"></script>
		<script language="javascript" type="text/javascript">
	        
	        function showShareMenuClickHandler()
	        {
	            
	            var u = navigator.userAgent, app = navigator.appVersion;
	            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	            
	             if(isAndroid){
	                 try{
	                     var version = jsObject.getAppVersion();
	//                     alert(version);
	                 }catch(error){
	                      //alert(version);
	                      if(version !="4.3.6.2"){
	                        alert("请到App--我--检查更新,进行更新");
	                        return false;
	                    }
	                 }finally {}
	                $sharesdk.open("85f512550716", true);
	            }
	            
	            if(isiOS){
	                try{
	                    if(getAppVersion && typeof(getAppVersion) == "function"){
	                         getAppVersion();
	                         var vserion = document.getElementById('vserion').value;
	                        }
	                    }catch(e){
	                        
	                    }
	               
	                if(vserion){
	                    //alert(vserion);
	                    $sharesdk.open("62a86581b8f3", true); 
	                }else{  
	                    alert("请到App--我--检查更新,进行更新");
	                    return false;
	                 }  
	                }
	            
	            var params = {
	                "text" : "快来给我的种子浇水吧！一起瓜分丰厚奖品！",
	                "imageUrl" : "<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/img_pro1.png",
	                "url":"<?php echo F::getHomeUrl('/ZhiShuJieTwo/Share?reUrl='.$url); ?>",
	                "title" : "给种子浇水，赢iPad Pro",
	                "titleUrl" : "<?php echo F::getHomeUrl('/ZhiShuJieTwo/Share?reUrl='.$url); ?>",
	                "description" : "描述",
	                "site" : "彩之云",
	                "siteUrl" : "<?php echo F::getHomeUrl('/ZhiShuJieTwo/Share?reUrl='.$url); ?>",
	                "type" : $sharesdk.contentType.WebPage
	            };
	           $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {}); 
	             
	        };
	     

	    </script>   
	</head>
	<body>
		<div class="contanes">
			<div class="contanes_bg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg1.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg3.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg4.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg5.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg6.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/bg7.jpg"/>
			</div>
			
			<div class="exp">
				<p>经验值：<span><?php echo $experienceValue?></span></p>
			</div>
			
			<div class="intg">
				<p>积&nbsp;分：<span><?php echo $integrationValue?></span></p>
			</div>
			
			<div class="everday">
				<p>每日登录＋<span><?php echo $loginExperienceValue['value']?></span></p>
			</div>

			<div class="getGuoShi hide">
				<p>摘到果实＋<span>50</span></p>
			</div>
			
			<div class="btn_jf">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/btn_jf.png"/>
			</div>
			
			<div class="btn_watering">
				<img class="bt_show " src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/btn_move.png"/>
				<img class="bt_press hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/btn_press.png"/>
			</div>
			
			<div class="btn_share">
				<p>分享</p>
			</div>
			
			<div class="btn_rule">
				<p>活动规则</p>
			</div>
			
			<div class="grow">
				<p>成长值 <span><?php echo $growValue?></span>/400</p>
			</div>
			
			<div class="tree">
				<img class="tree_one hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr1.png"/>
				<img class="tree_two hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr2.png"/>
				<img class="tree_three hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr3.png"/>
				<img class="tree_four hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr4.png"/>
			</div>
			<div class="tree_shou hide">
				<div class="breath">
				</div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/hongsi.png"/>
			</div>
			
			<div class="watering hide" id="water">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/watering.png"/>
			</div>
		</div>
		
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<div class="shareMask hide"></div>
		<!--遮罩层结束-->
			
		<!--弹窗开始-->
		<div class="Popup hide">
			<div class="e_shifu">
				<div class="e_shifu_con">
					<div class="e_shifu_con_txt">
						<p></p>
					</div>
					<div class="e_shifu_con_btn">
						<a href="javascript:void(0)"></a>
					</div>
				</div>
			</div>	 
			<!--关闭按钮-->

			<div class="dele_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/dele.png"/>
			</div>
		</div>

		<!--分享弹窗开始-->
		<div class="SharePopup hide" id="SharePopup">
			<div class="e_shifu">
				<div class="e_shifu_con_share">
					<div class="e_shifu_con_share_txt">
						<p>分享成功</p>
						<p>快去呼朋唤友来浇水吧！邀请朋友注册彩之云还可以获得额外奖励</p>
					</div>
					<div class="e_shifu_con_share_btn">
						<a href="javascript:void(0)">邀请注册</a>
					</div>
				</div>
			</div>	 
			
			<div class="dele_img_share">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/dele.png"/>
			</div>
		</div>
		<!--分享弹窗结束-->

		<!--浇水弹窗开始-->
		<div class="jiaoshuiPopup hide" id="jiaoshuiPopup">
			<div class="e_shifu">
				<div class="e_shifu_con_jiaoshui">
					<div class="e_shifu_con_jiaoshui_txt">
						<p>你今天已经浇过水了</p>
						<p>邀请好友帮忙浇水每天最高可获25成长值</p>
					</div>
					<div class="e_shifu_con_jiaoshui_btn">
						<a href="javascript:void(0)">邀请好友</a>
					</div>
				</div>
			</div>	 

			<div class="dele_img_jiaoshui">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/dele.png"/>
			</div>
		</div>
		<!--浇水弹窗结束-->

		<input style="display: none" id="vserion" />
	   
		<script type="text/javascript">
			var isCaiFuUser = "<?php echo $isCaiFuUser;?>";
			var isNoCaiFuUser = "<?php echo $isNoCaiFuUser;?>";
			var resCount = "<?php echo $resCount;?>";
			var res2Count = "<?php echo $res2Count;?>";
			var res3Count = "<?php echo $res3Count;?>";
			var yaoQingNum = "<?php echo $yaoQingNum;?>";
			var growValue = "<?php echo $growValue;?>";
			var integrationValue = "<?php echo $integrationValue;?>";
			var experienceValue = "<?php echo $loginExperienceValue['value'];?>";
			var NotCFhref = "<?php echo $NCUrl;?>";
		</script>
	</body>
</html>
