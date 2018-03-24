<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>商品详情</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>css/swiper.min.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>css/layout.css" /> 
</head>
<body>
	<div class="nav">
		<ul></ul>
	</div>
	<div class="mask hide"></div>
	<div class="pop_prize hide">
		<div class="send_pic"></div>
		<div class="quan_name">
			<p>恭喜您获得</p>
			<p>单品5折抵用券</p>
		</div>
		<div class="quan_detail send_detail">
			<p>在此专区购物时记得使用哦~</p>
		</div>
		<div class="sure_btn">确定</div>
		<div class="close"></div>
	</div>
	
	<script>
	var fanPiaoUrl =<?php echo json_encode($fanPiaoUrl);?>;
	var goods =<?php echo json_encode($goods);?>;
	//商品内容
	for(var k in goods){
		for(var i=0;i<goods[k].length;i++){
			$(".nav ul").append(
				'<li>'+
//					'<a href="'+fanPiaoUrl+'&gid='+goods[k][i].pid+'">'+
						'<img src="'+goods[k][i].img_name+'"/>'+
						'<p>'+goods[k][i].name+'</p>'+
						'<p><span>¥&nbsp;<span>'+goods[k][i].customer_price+'</span></span></p>'+
//					'</a>'+	
				'</li>'
			);
		}	
			
		$(".nav ul li").click(function(){
			var _index = $(this).index();
			switch(goods[k][_index].shop_type){//0彩特供,1京东
				case '1':                  
					window.location.href=fanPiaoUrl+"&gid="+goods[k][_index].gid;
					break;
				case '0':
					window.location.href=fanPiaoUrl+"&gid="+goods[k][_index].gid;
					break;
			}
		});	
	}
	</script>
</body>
</html>