<!DOCTYPE html>
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩之云水果嘉年华--分享</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/fruitCarnival/css/layout.css');?>" rel="stylesheet" />
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fruitCarnival/js/ShareSDK.js');?>"></script>
<!--        <script>
            function getAppVersion(){}
        </script>-->
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
                "text" : "我秀出了自己的DIY的水果大餐，这么牛掰的作品一定要让小伙伴看到。下载http://dwz.cn/8YPIv",
                "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
                "url":"http://dwz.cn/8YPIv",
                "title" : "彩之云",
                "titleUrl" : "http://dwz.cn/8YPIv",
                "description" : "描述",
                "site" : "彩之云",
                "siteUrl" : "http://dwz.cn/8YPIv",
                "type" : $sharesdk.contentType.WebPage
            };
           $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {
//                alert("state = " + state + "\nshareInfo = " + shareInfo + "\nerror = " + error);
            }); 
             
        };
     

    </script>
</head>
<body style="text-align: center">
    <div class="send_out">
			<div class="send_out1a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg1.jpg');?>">
			</div>
			<div class="send_out2a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg2.jpg');?>">
			</div>
			<div class="send_out3a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg3.jpg');?>">
			</div>
			<div class="send_out4a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg4.jpg');?>">
			</div>
			<div class="send_out5a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show2_bg5.jpg');?>">
			</div>
			<div class="send_out6a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg6.jpg');?>">
			</div>
			<div class="send_out7a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg7.jpg');?>">
			</div>
			<div class="send_out8a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg8.jpg');?>">
			</div>
			<div class="send_out9a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg9.jpg');?>">
			</div>
			<div class="send_out10a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_bg10.jpg');?>">
			</div>
			<div class="show">
				<div class="show1a">
					<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/conter.jpg');?>">
				</div>
				<div class="show2a">
					<a href="javascript:void(0);">
						<img onclick="showShareMenuClickHandler()" src="<?php echo F::getStaticsUrl('/home/fruitCarnival/images/show_button.png');?>">
                        <input style="display: none" id="vserion" />
					</a>
				</div>
			</div>
		</div>
    <!--<input type="button" value="显示分享菜单111111111" onclick="showShareMenuClickHandler()" style="width: 200px; height: 30px;" /><br /><br/>-->
</body>
</html>