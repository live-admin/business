<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>h-环球精选挤牛奶活动首页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/flexible.js');?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/jquery-1.11.3.js');?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/milkAcow.js');?>"></script>
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/niuNai/');?>css/layout.css">
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/niuNai/');?>css/normalize.css">
       
       <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/');?>js/ShareSDK.js"></script>
       <script language="javascript" type="text/javascript">
	    function showShareMenuClickHandler()
	    {
	        
	        var u = navigator.userAgent, app = navigator.appVersion;
	        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	        
	         if(isAndroid){
	             try{
	                 var version = jsObject.getAppVersion();
	//                 alert(version);
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
	            "text" : "全民疯狂挤牛奶，快来开通你的牧场",
	            "imageUrl" : "http://cc.colourlife.com/common/images/niu.png",
	            "url":"<?php echo F::getHomeUrl('/NiuNai/FenWeb?cust_id='.$cust_id.'&sign='.$sign); ?>",
	            "title" : "快来帮我挤牛奶！",
	            "titleUrl" : "<?php echo F::getHomeUrl('/NiuNai/FenWeb?cust_id='.$cust_id.'&sign='.$sign); ?>",
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : "<?php echo F::getHomeUrl('/NiuNai/FenWeb?cust_id='.$cust_id.'&sign='.$sign); ?>",
	            "type" : $sharesdk.contentType.WebPage
	        };
	       $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {}); 
	    };
	    </script>
        <style>
       	@font-face {
			font-family:fontstyle3;
			src: url("<?php echo F::getStaticsUrl('/home/niuNai/'); ?>fonts/fontstyle.ttf");
		}
       </style>
	</head>
    <body style="color:#8EB936">
	     <div class="milk_con">
	     	<div class="milk_bg">
                <img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/milk_bg01.jpg" style="height:0.2rem">
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
	     			<p>我的牧场</p>
	     			<h6>疯狂挤牛奶</h6>
	     		</div>
	     		<div class="milk_ban">
	     			<div class="card">
	     				<a href="/NiuNai/PaiMing" class="card01">
	     					<p>牛奶量：<span><?php echo $niuNaiValue;?>ml</span></p>
	     					<p>当前排名：<span><?php echo $paiMing;?></span></p>
	     				</a>
	     				<a href="/NiuNai/PaiMing" class="card02">
	     					活动排名
	     				</a>
	     				<a href="/NiuNai/JiangXiang" class="card03">
	     					获奖历史
	     				</a>
	     			</div>
	     			<div class="cow">
	     				<div id="cowHeadMap"></div>
	     				<div id="cowBodyMap"></div>
	     			</div>
	     			<div class="bull">
	     				<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/bull04.png"/>
	     				<p class="nubl hide">+20</p>
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
	     <!--遮罩层开始-->
	     <div class="mask hide"></div>
         <div class="mask1 hide"></div>
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
	     			<div class="popup_physical_con  popup_physical_con_p">
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
            <!--弹窗实物结束-->
            <!--首次弹窗3秒消失开始-->
			<div class="finger hide">
		     		<div class="finger_h">
		     			<p>快来点击奶牛挤牛奶吧！</p>
		     		</div>
		     		<div class="finger_b">
                        <img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/cow01.png"/>
                    </div>
		     		<div class="finger_f">
		     			<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/finger.png">
		     		</div>
		     </div>
		   <!--首次弹窗3秒消失结束-->
            <input type="hidden" id="imgpath" value="<?php echo F::getStaticsUrl('/home/niuNai/');?>">
            <input type="hidden" id="niuNaiValue" value="<?php echo $niuNaiValue;?>">
            <input type="hidden" id="paiMing" value="<?php echo $paiMing;?>">
            <input type="hidden" id="isCaiFuUser" value="<?php echo $isCaiFuUser;?>">
            <input type="hidden" id="isNoCaiFuUser" value="<?php echo $isNoCaiFuUser;?>">
            <input type="hidden" id="mobile" value="<?php echo $mobile;?>">
            <input type="hidden" id="orderCount" value="<?php echo $orderCount;?>">
            <input type="hidden" id="isJiNiuNai" value="<?php echo $isJiNiuNai;?>">
            <input type="hidden" id="prizeName" value="<?php echo $prizeName;?>">
            <input type="hidden" id="isTan" value="<?php echo $isTan;?>">
            <input type="hidden" id="result" value="<?php echo $result;?>">
            <input style="display: none" id="vserion" />
	</body>
</html>
