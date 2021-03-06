<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>春节活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/layout.css" />
	</head>
	<body>
		<input style="display: none" id="vserion" />
		<div class="content">
			<div class="deng_bg">
				<ul>
					<li></li>
					<li>
						<p></p>
						<p class="animal"></p>
					</li>
				</ul>
			</div>
			<div class="zan_pic">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/zan_icon.png"/>
				<p>快来给我点赞吧！~</p>
				<div class="zan_btn" id="raised">点赞</div>
			</div>
			<div class="zan_list">
				<ul>
				</ul>
			</div>
			<div class="clour_bg">
			</div>
		</div>
	
		<div class="mask hide"></div>
		<div class="Pop_qiang hide">
			<div class="yuanbao_qiang_txt">
				<p>你今天已点赞成功</p>
			</div>
			<div class="Pop_qiang_btn_box">
				<div class="Pop_qiang_btn">确定</div>
			</div>
		</div>
	
		<script>
		    var sd_id=<?php echo json_encode($sd_id);?>;
		    var praise_message=<?php echo json_encode($praise_message);?>;
		    var customer_info = <?php echo json_encode($customer_info)?>;
			var img_url = "<?php echo F::getStaticsUrl('/activity/v2016/spring/images');?>";
			
			$(document).ready(function(){
				var zodiac = customer_info.zodiac ? customer_info.zodiac : "";
				var phoneList = (customer_info.mobile.substring(0,3)+"****"+(customer_info.mobile.substring(7,11)));
				
				var userId;
				var u = navigator.userAgent, app = navigator.appVersion;
				var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
    			var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
				if(isAndroid){
					try{
							userId = $.parseJSON(jsObject.getUserInfoHandler()).uid;
							var vserionStr = jsObject.getVersionCode();
						}
					catch(error){
							console.log(error.message);
							alert("请升级到最新版本");
						}
										
				}
				 if(isiOS){
			        try{
			            function setupWebViewJavascriptBridge(callback) {
			        	        if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
			        	        if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
			        	        window.WVJBCallbacks = [callback];
			        	        var WVJBIframe = document.createElement('iframe');
			        	        WVJBIframe.style.display = 'none';
			        	        WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__';
			        	        document.documentElement.appendChild(WVJBIframe);
			        	        setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0)
			        	        		        		//分享之后返回的信息
			        	    
	
			        	    }
			        	    
			        	setupWebViewJavascriptBridge(function(bridge) {
			        	    	
			        	      //点击分享按钮
			        			bridge.callHandler('getUserInfoHandler', "", function(response) {

			        				userId = response.uid;

			        				if (!userId) {
			        					alert("请升级到最新版本");
			        				}
			    				})
			        		})
			            }catch(e){
			                console.log(e.message);
			                alert("请升级到最新版本");
			            }
			        }					
					
				$(".deng_bg ul li:eq(1)>p:eq(0)").text(phoneList);
				
				switch(customer_info['zodiac']){
					case '1':
						$(".deng_bg ul").find('.animal').text('生肖鼠');
						break;
					case '2':
						$(".deng_bg ul").find('.animal').text('生肖牛');
						break;
					case '3':
						$(".deng_bg ul").find('.animal').text('生肖虎');
						break;	
					case '4':
						$(".deng_bg ul").find('.animal').text('生肖兔');
						break;	
					case '5':
						$(".deng_bg ul").find('.animal').text('生肖龙');
						break;	
					case '6':
						$(".deng_bg ul").find('.animal').text('生肖蛇');
						break;	
					case '7':
						$(".deng_bg ul").find('.animal').text('生肖马');
						break;	
					case '8':
						$(".deng_bg ul").find('.animal').text('生肖羊');
						break;
					case '9':
						$(".deng_bg ul").find('.animal').text('生肖猴');
						break;
					case '10':
						$(".deng_bg ul").find('.animal').text('生肖鸡');
						break;	
					case '11':
						$(".deng_bg ul").find('.animal').text('生肖狗');
						break;	
					case '12':
						$(".deng_bg ul").find('.animal').text('生肖猪');
						break;	
				}
				if(zodiac){
					$(".deng_bg ul li:eq(1)").append('<img src="'+img_url+'/'+zodiac+'.png'+'">');
				}
				$(".deng_bg ul li:eq(0)").append('<img src="'+customer_info.avatar+'">');
				
				
				for (var i=0; i<praise_message.length; i++) {
					var phoneList = [];
					phoneList = (praise_message[i]['mobile'].substring(0,3)+"****"+(praise_message[i]['mobile'].substring(7,11)));
					$(".zan_list ul").append('<li><span>'+phoneList+'</span>'+' 给我点赞了'+'</li>')
				}
				
				$(".zan_pic").click(function(){

				 	$.ajax({
					async:true,
					type:"POST",
					url:"AjaxPraise/",
					data:{from_id:userId,sd_id:sd_id},
					dataType:'json',
					success:function(resut){

						if(resut.retCode == 1){  //根据后台返回的状态
							$(".yuanbao_qiang_txt>p").css("padding-bottom","0rem");
							$(".yuanbao_qiang_txt>p").text("你今天已点赞成功");
							$(".Pop_qiang").removeClass("hide");
							$(".mask").removeClass("hide");
						}
						else if(resut.retCode == 2){
							$(".yuanbao_qiang_txt>p").css("padding-bottom","0rem");
							$(".yuanbao_qiang_txt>p").text("不能给自己点赞哟～");
							$(".Pop_qiang").removeClass("hide");
							$(".mask").removeClass("hide");
						}
						else if(resut.retCode == 3){
							$(".yuanbao_qiang_txt>p").css("padding-bottom","0rem");
							$(".yuanbao_qiang_txt>p").text("你今天已点赞成功");
							$(".Pop_qiang").removeClass("hide");
							$(".mask").removeClass("hide");
						}
						else if(resut.retCode == 4){
							$(".yuanbao_qiang_txt>p").css("padding-bottom","0rem");
							$(".yuanbao_qiang_txt>p").text("你今天已点赞成功");
							$(".Pop_qiang").removeClass("hide");
							$(".mask").removeClass("hide");
						}
						else if(resut.retCode == 5){
							alert("活动已结束");
						}
						else {
							console.log(resut.retMsg);
						}
					} 
					});
				});
				
				$(".Pop_qiang_btn").click(function(){
					$(".Pop_qiang").addClass("hide");
					$(".mask").addClass("hide");
				});
			});	
		</script>
	</body>
</html>


