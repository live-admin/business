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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>js/share.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>css/normalize.css">
	    	<style>
	    	//浏览器指示
		        .download {
		            width: 100%;
		            height: 100%;
		            position: absolute;
		            top: 0;
		            left: 0;
		            border:1px red solid;
		        }
		        .download img{
		            width:100%;
		            height: 100%;
		            position: absolute;
		            top: -8%;
		        }
		        body{
				    background:#9FD039;
			    }
		       	@font-face {
					font-family:fontstyle;
					src: url('<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>fonts/fontstyle.ttf');
				}
    	   </style>
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
				<p>经验值：<span><?php echo $experienceValue;?></span></p>
			</div>
			
			<div class="intg">
				<p>积&nbsp;分：<span><?php echo $integrationValue;?></span></p>
			</div>
			
			<div class="btn_watering">
				<img class="bt_show " src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/btn_move.png"/>
				<img class="bt_press hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/btn_press.png"/>
				<p><span id="water_num"><?php echo $num;?></span>/<span id="water_max">20</span></p>
			</div>
			
			
			<div class="btn_rule">
				<p>活动规则</p>
			</div>
			
			<div class="grow">
				<p>成长值 <span><?php echo $growValue;?></span>/400</p>
			</div>
			
			<div class="tree">
				<img class="tree_one" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr1.png"/>
				<img class="tree_two hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr2.png"/>
				<img class="tree_three hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr3.png"/>
				<img class="tree_four hide" src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/tr4.png"/>
			</div>
			
			<div class="tree_shou hide">
				<div class="breath">
			</div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/hongsi.png"/>
			</div>
			
			<div class="watering hide">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/watering.png"/>
			</div>
			
			<!--下载-->
		 <div class="btm_bg"></div>
	     <div class="share_footer">
	     	<div class="share_footer_box01">
	     		<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/czy_logo.png">
	     	</div>
	     	<div class="share_footer_box02">
	     		<a href="#">
	     			立即下载
	     		</a>
	     	</div>
	     	<div class="share_footer_box03">
	     		<a href="javascript:void(0)">
	     			<img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/delet.png">
	     		</a>
	     	</div>
	     </div>
	     	<!--下载结束-->
			
		</div>
		
		 <!--遮罩层开始-->
	     <div class="mask hide"></div>
	     <!--遮罩层结束-->
	     <div class="download hide" style=" width: 100%;height: 100%;position: absolute;top: 0;left: 0;z-index: 10;">
              <img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/download_ios.png" />
         </div> 
		
		<script type="text/javascript">
		    
			var sd_id = "<?php echo $seed_id;?>";
			var cid = "<?php echo $cust_id;?>";
			var sign = "<?php echo $validate;?>";
		</script>
		
		
	</body>
</html>
