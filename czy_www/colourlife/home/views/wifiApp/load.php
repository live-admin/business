<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>彩之云客户端</title>
		<link href="http://mapp.colourlife.com/czy.css" type="text/css" rel="stylesheet">
        <script src="http://mapp.colourlife.com/js/jquery.min.js" /></script>
	</head>
	<body>
		<div class="main" id="main">
            <div class="top_word" id="topWord" style="display:none">
              <div style="height:60px; margin:0 auto; background:#A238B4; color:#fff;">
                  <div class="top_word_one">
                      <div class="top_word_span1" >
                        <span style="float:left;">微信扫描后无法下载，请点击</span>
                        <span class="top_img"></span>
                        <span class="word_one">并</span>
                      </div>
                      <div class="top_word_span1" >
                        <span class="word_one">选择在</span>
                        <span class="word_two">浏览器</span>
                        <span class="word_one">中或在</span>
                        <span class="word_two">Safari</span>
                        <span style="float:left;" class="word_one">中打开</span>
                      </div>
                  </div>
                  <div class="top_r"></div>
              </div>
            </div>
            <div class="clear"></div>
            <div class="logo" style="height:auto;">
                <img src="<?php echo F::getStaticsUrl('/wifiapp/images/wifi.jpg');?>" style="width:100%;" />
			</div>
			<div class="download">
				<a href="javascript:void(0)"><img class="downloadbox" src="http://mapp.colourlife.com/images/mg_1_02.jpg" style="border:0px;" /></a>
				<a href="javascript:void(0)"><img class="downloadbox" src="http://mapp.colourlife.com/images/mg_2_02.jpg" style="border:0px;" /></a>
			</div>
			<div class="msgbox">
        	<p>管家服务</p>
            <div>通过电话、网络方式与物业管家互动，提出您的需求、建议。</div>
			</div>
			<div class="msgbox">
				<p>物业报修</p>
				<div>在线提交您的报修需求，客服中心会立即通知物业管家为您服务。</div>
			</div>
			<div class="msgbox">
				<p>周边特惠</p>
				<div>寻找您家周围的折扣优惠信息，提供预定联系方式。精彩生活省出来！</div>
			</div>
			<div class="msgbox">
				<p>物业缴费</p>
				<div>通过手机查询物业费用并缴纳(在线缴费近期开放)，让您省去排队缴费的烦忧!</div>
			</div>
			<div class="foot">
				CopyRight &copy; 彩之云 2013-2014
			</div>
		</div>
        <script>
		   /*function is_weixin(){
				var ua = navigator.userAgent.toLowerCase();
				if(ua.match(/MicroMessenger/i)=="micromessenger"||window.WeixinJSBridge!=undefined) {
					return true;
				} else {
					return false;
				}
			}
		  window.onload=function(){
				if(is_weixin()){
					document.getElementById("topWord").style.display="block";
				}
		   };*/
		   
		   
		   var Index = {
		   showDownload : function(isAndroid){
			    
				if(!$("#downloadDiv").length){
					var _d = document;
					var _b = _d.getElementById('main');
					var downloadDiv = _d.createElement("div");
					downloadDiv.setAttribute("id","downloadDiv");
					_b.appendChild(downloadDiv);
					isAndroid ? $(downloadDiv).addClass("download_android") : $(downloadDiv).addClass("download_ios");
					$(downloadDiv).fadeIn(function(){
						$(this).bind("click",function(){
							$(this).fadeOut();
							$(".download a").eq(0).attr("href","http://mapp.colourlife.com/czy.apk");
				            $(".download a").eq(1).attr("href","http://mapp.colourlife.com/ios.html");
						});
					});
				}
				
			},
		  init : function(){
				var ua = navigator.userAgent.toLowerCase();
				function isIOS(){
					var a  = ua;
					return (a.indexOf("iphone") != -1 || a.indexOf("ipad") != -1 || a.indexOf("ipod") != -1) ? 1 : 0;
				}
				function isAndroid(){
					return ua.indexOf("android") != -1 ? 1 : 0;
				}
				
				function is_weixin(){
					if(ua.match(/MicroMessenger/i)=="micromessenger"||window.WeixinJSBridge!=undefined) {
						return true;
					} else {
						return false;
					}
				}
				if(is_weixin()){//微信环境
				  document.getElementById("topWord").style.display="block";
				  $(".download a").attr("target","_self").bind("click",function(){
					  Index.showDownload(isAndroid());
				  })
				  /*if(isIOS()){
					  $("#download_a").attr("href","http://a.app.qq.com/o/simple.jsp?pkgname=com.koudai.weishop&g_f=991653");
				  }
				  else{
					  $("#download_a").attr("target","_self").bind("click",function(){
						  Index.showDownload(isAndroid());
					  })
				  }*/
				  
				  
				}
				else{
					$(".download a").eq(0).attr("href","http://mapp.colourlife.com/czy.apk");
				    $(".download a").eq(1).attr("href","http://mapp.colourlife.com/ios.html");
					
					}
			}
		}
		Index.init();
		   
		   
		   

		   
		</script>
	</body>
</html>
