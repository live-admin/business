<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>饭票卷-订单详情</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/m/ticket/js/ShareSDK.js');?>"></script>
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
                "text" : "彩之云用户赶紧领取吧！",
                "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
                "url":"<?php echo F::getHomeUrl('/Test/Share?cust_id='.$cust_id); ?>",
                "title" : "彩之云",
                "titleUrl" : "<?php echo F::getHomeUrl('/Test/Share?cust_id='.$cust_id); ?>",
                "description" : "描述",
                "site" : "彩之云",
                "siteUrl" : "<?php echo F::getHomeUrl('/Test/Share?cust_id='.$cust_id); ?>",
                "type" : $sharesdk.contentType.WebPage
            };
            $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {});

        };


    </script>
	</head>
	<body>
		<div>
			<a href="javascript:showShareMenuClickHandler();">分享</a>
		</div>
	</body>
</html>