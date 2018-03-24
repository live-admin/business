<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>元宵祝福 私人定制</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>js/ReFontsize.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>css/layout.css"/>
		<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/');?>js/ShareSDK.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>js/jquery-1.11.3.js" ></script>
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
                "text" : "你是我的小元宵！",
                "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
                "url":"<?php echo F::getHomeUrl('/lanternFestival/share/?g='.$greeting.'&p='.$photo); ?>",
                "title" : "彩之云",
                "titleUrl" : "<?php echo F::getHomeUrl('/lanternFestival/share/?g='.$greeting.'&p='.$photo); ?>",
                "description" : "描述",
                "site" : "彩之云",
                "siteUrl" : "<?php echo F::getHomeUrl('/lanternFestival/share/?g='.$greeting.'&p='.$photo); ?>",
                "type" : $sharesdk.contentType.WebPage
            };
           $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ,$sharesdk.platformID.SinaWeibo], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {
            }); 
             
        };
     

    </script>
		
	</head>
	<body>
		<div class="containers">
			<div class="container">
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch01.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch02.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch03.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch04.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/ch05.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr06.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr07.jpg" />
	            <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr08.jpg" />
		        <img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/sr09.jpg" />
		    </div>
		    <div class="share_con">
		    	<div class="share_con1a">
		    	 	<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/<?php echo $greeting;?>.png">
		    	 </div>
		    	 <div class="share">
			    	<img src="<?php echo F::getStaticsUrl('/home/lanternFestival/'); ?>img/<?php echo $photo;?>.png">
			    	<a href="javascript:void(0)">分享喜悦</a>
			    	<input style="display: none" id="vserion" />
			    </div>
		    </div>
	    </div>
	     <script>
	      $(document).ready(function(){
	    		//点击祝福语隐藏
	    		$(".share").find("a").click(function(){
					$.ajax({
				          type: 'POST',
				          url: '/LanternFestival/Count',
				          data: 'cid='+'<?php echo $cust_id;?>'+'&validate='+'<?php echo $validate;?>'+'&g='+'<?php echo $greeting;?>'+'&p='+'<?php echo $photo;?>',
				          dataType: 'json',
				          success: function (result) {
			    	                  				  
				          }
				        });
		    		var cid='<?php echo $cust_id;?>';
		    		if(cid==0){
		    			window.location.href="<?php echo F::getHomeUrl('/lanternFestival/share/'); ?>?g=<?php echo $greeting;?>&p=<?php echo $photo;?>";
			    	}else{
		    			showShareMenuClickHandler();
				    }
	    		});
	    	});
	    </script>
	</body>
</html>