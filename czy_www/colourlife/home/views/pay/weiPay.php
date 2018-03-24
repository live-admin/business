<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>支付</title>
	<link type="text/css" rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/pay/');?>css/style.css" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/pay/');?>js/jquery-1.11.1.min.js"></script>
</head>
<body>
	<div class="logo_area">
		<img src="<?php echo F::getStaticsUrl('/home/pay/');?>images/logo.png"/>
		<h1><?php echo $community_name; ?></h1>
		<h2><?php echo $community_address; ?></h2>
	</div>
	<div class="input_area">
		<span>金额  ￥</span>
		<input id="amount" type="number" value=""/>
	</div>

		
	<div class="submit">
		<p class="pay">确定支付</p>
	</div>

</body>

<script>
	var u = navigator.userAgent, app = navigator.appVersion;
	var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	var userAdderssInfo,userId,community_id;
	if(isAndroid){
		try{
			//android获取用户id和小区id
			userId = $.parseJSON(jsObject.getUserInfoHandler()).uid;
			community_id =  $.parseJSON(jsObject.getUserInfoHandler()).community_id;
			setCookie('userId',userId);
			setCookie('community_id',community_id);
			//经纬度
			userAdderssInfo = $.parseJSON(jsObject.getLocationActivity());

			//var vserionStr = jsObject.getVersionCode();
			getToken(userId,community_id,userAdderssInfo);
		}catch(error){
			console.log(error.message);
			console.log("Android请升级到最新版本");
		}
	}else if(isiOS){
		try{
			//接口函数
			function setupWebViewJavascriptBridge(callback) {
				if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
				if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
				window.WVJBCallbacks = [callback];
				var WVJBIframe = document.createElement('iframe');
				WVJBIframe.style.display = 'none';
				WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__';
				document.documentElement.appendChild(WVJBIframe);
				setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0)
			}
			setupWebViewJavascriptBridge(function(bridge){
				//获取用户经纬度
				bridge.callHandler('getLocationActivity','', function callback(response) {
					userAdderssInfo = response;

					if (!userId) {
						console.log("iOS请升级到最新版本");
					}
				});
				//获取用户信息
				bridge.callHandler('getUserInfoHandler','', function callback(response) {
					//ios获取用户id和小区id
					userId = response.uid;
					community_id = response.community_id;
					setCookie('userId',userId);
					setCookie('community_id',community_id);
					getToken(userId,community_id,userAdderssInfo);
					if (!userId) {
						console.log("iOS请升级到最新版本");
					}
				});

			});
		}catch(e){
			console.log(e);
			console.log("请升级到最新版本");
		}
	}else{
		if(getCookie('userId') && getCookie('community_id')){
			userId = getCookie('userId');
			community_id = getCookie('community_id');
		}else{
			userId = '1511998';
			community_id = '2';
		}

	}
	var community_uuid = '<?php echo $community_uuid;?>';
	$('.pay').bind('click',function(){
		var amount = $(" #amount ").val();
		window.location.href = "http://www.colourlife.com/pay/createWeiPay?amount="+amount+"&community_uuid="+community_uuid+"&userId="+ userId;
	})
</script>
</html>