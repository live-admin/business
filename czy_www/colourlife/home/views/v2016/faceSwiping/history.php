<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>刷脸拼颜值历史记录</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>css/layout.css" />
</head>
<body>
    <div class="content record_content">
    	<div class="top">
        	<p>我的颜值：<span></span>分</p>
            <div class="histroy_icon"></div>
            <img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/histroy_line.png">
        </div>
        <div class="record">
            <ul class="record_title">
                <li><p>获取路径</p></li>
                <li><p>颜值加分</p></li>
                <li><p>时间</p></li>
            </ul>
            <ul class="record_body">
                <!--<!--<li>
                    <p>邀请好友<br><span>131xxxx1234</span></p>
                    <p>+5</p>
                    <p>2016-03-01 15:10:04</p>
                </li>-->
            </ul>
        </div>
  	
		<div class="footer"></div>
	</div>
</body>
</html>
	
<script>
      var hlist =<?php echo $hlist;?>;
	  var record_body=$(".record_body");
	  
	  $(".top p span").text(hlist["totalScore"]);
//	  alert(hlist["historyList"][0].way);
      for(var i=0;i<hlist["historyList"].length;i++){
      	if(hlist["historyList"][i].way==1){
      		 var lis='<li>'+
	                    '<p>上传头像</p>'+
	                    '<p>'+hlist["historyList"][i].score+'</p>'+
	                    '<p>'+hlist["historyList"][i].addDate+'<br/>'+hlist["historyList"][i].addTime+'</p>'+
	                '</li>'
	         record_body.append(lis);
      	}
      	if(hlist["historyList"][i].way==2){
	        var lis='<li>'+
	                    '<p>来自'+
	                    	'<span>'+hlist["historyList"][i].name+'</span><br/>'+
	                    	'<span>#'+ hlist["historyList"][i].content +'#</span>'+
	                    '</p>'+
	                    '<p>'+hlist["historyList"][i].score+'</p>'+
	                    '<p>'+hlist["historyList"][i].addDate+'<br/>'+hlist["historyList"][i].addTime+'</p>'+
	                '</li>'
	         record_body.append(lis);
      	}
      	if(hlist["historyList"][i].way==3){
      		  var lis='<li>'+
	                    '<p>邀请好友'+'<br/>'+
	                    	'<span>'+ hlist["historyList"][i].name +'</span>'+
	                    '</p>'+
	                    '<p>'+hlist["historyList"][i].score+'</p>'+
	                    '<p>'+hlist["historyList"][i].addDate+'<br/>'+hlist["historyList"][i].addTime+'</p>'+
	                '</li>'
	        record_body.append(lis);
      	}
      	
    	   
	    
      }

      
      
</script>