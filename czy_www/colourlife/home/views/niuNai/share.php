<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>疯狂挤牛奶</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/flexible.js');?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/jquery-1.11.3.js');?>"></script>
	    <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/milkAcowShare.js');?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/niuNai/');?>css/layout.css">
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/niuNai/');?>css/normalize.css">
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
       	@font-face {
			font-family:fontstyle3;
			src: url("<?php echo F::getStaticsUrl('/home/niuNai/'); ?>fonts/fontstyle.ttf");
		}
        
       </style>
	</head>
	<body style="color:#8EB936">
	     <div class="milk_con">
	     	<div class="milk_bg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg01.jpg"style="height:0.2rem">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg02.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg03.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg04.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg05.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg06.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg07.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg08.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg09.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg10.jpg">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg11.jpg">
	     	</div>
	     	<div class="milk_index">
	     		<div class="milk_top">
	     			<p>Ta的牧场</p>
                    <h6>疯狂挤牛奶</h6>
	     		</div>
	     		<div class="milk_ban">
	     			<div class="card">
	     				<a href="/NiuNai/PaiMing?type=1" class="card01">
	     					<p>牛奶量：<span><?php echo $niuNaiValue;?>ml</span></p>
	     					<p>当前排名：<span><?php echo $paiMing;?></span></p>
	     				</a>
	     				<a href="/NiuNai/PaiMing?type=1" class="card02">
	     					活动排名
	     				</a>
	     				<a href="javascript:void(0)" class="card03">
	     					<?php echo $num;?>/15
	     				</a>
	     			</div>
	     			<div class="cow">
	     				<div id="cowHeadMap"></div>
	     				<div id="cowBodyMap"></div>
	     			</div>
	     			<div class="bull">
	     			<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/bull03.png">
                    <p class="nubl hide" style="left: 0.4rem;">+20</p>
	     			</div>
	     		</div>
	     		<div class="milk_footer">
	     			<p>本次活动奖品由环球精选赞助</p>
	     		</div>
	     	</div>
	     	
	     </div>
	     <div class="rule">
	     	<a href="/NiuNai/Rule">
	     		活动规则
	     	</a>
	     </div>
	     <!--底部背景-->
		<div class="bot_bg">
		</div>
	     <div class="share_footer">
	     	<div class="share_footer_box01">
	     		<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/czy_logo.png">
	     	</div>
	     	<div class="share_footer_box02">
	     		<a href="#">
	     			立即下载
	     		</a>
	     	</div>
	     	<div class="share_footer_box03">
	     		<a href="#">
	     			<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/delet.png">
	     		</a>
	     	</div>
	     </div>
	     <!--遮罩层开始-->
	     <div class="mask hide"> </div>
	     <div class="mask1 hide"> </div>
	     <!--遮罩层结束-->
	     <!--弹窗优惠卷开始-->
	     	<div class="popup_coupons hide">
	     		<div class="popup_con">
	     			<div class="popup_con_h">
	     				<p>恭喜您获得奖品</p>
	     			</div>
	     			<div class="popup_con_b">
	     				<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/securities.png">
	     				<p>环球精选优惠券：满500减45券</p>
	     			</div>
	     			<div class="popup_con_f">
	     				<a href="javascript:void(0)">
	     					确定
	     				</a>
	     			</div>
	     		</div>
	     	</div>
	     	<!--弹窗优惠卷结束-->
	     	<!--弹窗实物开始-->
	     	<div class="popup_physical hide">
	     		<div class="popup_con">
	     			<div class="popup_con_h">
	     				<p>恭喜您获得奖品</p>
	     			</div>
	     			<div class="popup_physical_con popup_physical_con_p">
	     				<p>贝瑟斯创意陶瓷马克杯1个</p>
	     				<p>* 一元购码将在活动结束后3个工作日内自动发放到您的彩之云账户</p>
	     			</div>
	     			<div class="popup_con_f">
	     				<a href="javascript:void(0)">
	     					确定
	     				</a>
	     			</div>
	     		</div>
	     	</div>
	     	<!--弹窗实物开始-->
	     	<!--默认弹窗开始-->
             <div class="native hide">
	     		<div class="native_h">
	     			<p>您已成功帮好友挤牛奶！</p>
	     		</div>
	     		<div class="native_f">
	     			<a href="#">确定</a>
	     		</div>
	     	</div>

	     	<!--默认弹窗结束-->
	     	<!--首次弹窗3秒消失开始-->
			<div class="finger hide">
		     		<div class="finger_h">
		     			<p>快来点击奶牛挤牛奶吧！</p>
		     		</div>
                    <div class="finger_b">
                        <img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/cow01.png"/>
                    </div>
		     		<div class="finger_f">
		     			<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/finger.png"/>
		     		</div>
		     </div>
            <div class="download hide" style=" width: 100%;height: 100%;position: absolute;top: 0;left: 0;z-index: 10;">
                <img src="<?php echo F::getStaticsUrl('/home/niuNai/'); ?>images/download_ios.png" />
            </div>
		   <!--首次弹窗3秒消失结束-->
           <input type="hidden" id="imgpath" value="<?php echo F::getStaticsUrl('/home/niuNai/');?>">
           <input type="hidden" id="num" value="<?php echo $num;?>">
           <input type="hidden" id="validate" value="<?php echo $validate;?>">
           <input type="hidden" id="niuNaiValue" value="<?php echo $niuNaiValue;?>">
           <input type="hidden" id="paiMing" value="<?php echo $paiMing;?>">
           <input type="hidden" id="isTan" value="<?php echo $isTan;?>">
	</body>
</html>
