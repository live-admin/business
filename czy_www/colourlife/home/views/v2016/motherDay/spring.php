<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>年货佳节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/spring.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/motherDay/');?>css/normalize.css">
	</head>
<body>
<header></header>
<section>
  <ul class="wrap"></ul>
</section>
<script>
	var data = <?php echo $currentProductArr;?>;
	var tgUrl = "<?php echo $shangChengUrl['tgHref'];?>";
	
	for(var i=0;i<data.length;i++){
		$("ul").append('<li class="single">'+
  							'<div class="show_pic"><img src="'+data[i]['img_name']+'"/></div>'+
    							'<p>'+data[i]['name']+'</p>'+
    							'<p>¥ <span>'+data[i]['price']+'</span></p>'+
  						'</li>'
  				);
  		if((i+1)%3 == 0){
			$("ul li").eq(i).css("margin-right","0");
		}			
	}
		
		
	$("ul").delegate("li","click",function(){
		var _index = $(this).index();
		window.location.href = tgUrl+"&pid="+data[_index]['id'];
	});
</script>   
</body>
</html>
