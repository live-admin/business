<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>春节旅游</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <link type="text/css" rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link type="text/css" rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/springTravel/');?>css/layout.css">
	</head>
	<body id="top">
	<header></header>
	<section>
		<div class="item_bg jihuinandei">
			<span class="denglong01"></span>
			<span class="font"></span>
			<ul>
				
			</ul>
		</div>

		<div class="item_bg chaozhi">
			<span class="denglong02"></span>
			<span class="font"></span>
			<ul></ul>
		</div>

		<div class="item_bg waiguo">
			<span class="denglong01"></span>
			<span class="denglong02"></span>
			<span class="font"></span>
			<ul></ul>
		</div>
	</section>
	<footer>
		<p>咨询热线：400-055-8888</p>
		<a href="#top"></a>
	</footer>

	<script>
	var data=<?php echo json_encode($urlArr);?>;
	var info=[
				{"tip":"温暖一夏","price":"999","intro":"< 精选厦门经典游玩路线，带你玩转一厦 > 四天三晚游"},
				{"tip":"多彩江西","price":"1699","intro":"< 黄果树瀑布，下司古镇，苗妹非遗博物馆，青岩古镇，多彩贵州风表演 > 四天三晚团"},
				{"tip":"珠海温泉","price":"1140","intro":"< 御温泉套票(散客）住房+双温泉门票+双早餐 > 两日游"},
				{"tip":"魅力云南","price":"2540","intro":"< 昆明石林、大理洱海、丽江玉龙雪山动车 > 六天团"},
				{"tip":"天涯海角","price":"3320","intro":"< 南湾猴岛、分界洲岛、呀诺达、三亚千古情 > 双飞五天团"},
				{"tip":"情迷贵州","price":"2150","intro":"< 黄果树瀑布、黄果树6D科普电影、荔波小七孔、西江千户苗寨、青岩古镇 > 五天四晚团"},
				{"tip":"静好时光","price":"2180","intro":"< 新马圣淘沙名胜世界、云顶高原 >六日游"},
				{"tip":"畅享雪舞","price":"2280","intro":"< 韩国首济两晚五花酒店、近郊滑雪场、爱来魔相艺术馆、韩秀表演、汗蒸幕 > 五天游"},
				{"tip":"浪漫普吉","price":"4280","intro":"< 蓝色诱惑 轻松畅游海岛> 四晚五天游"},
				{"tip":"泰式风情","price":"3580","intro":"< 独家原始森林主题度假村，体验泰式风情 > 五晚六天游"},
				{"tip":"玩转首尔","price":"3340","intro":"< 乐天世界、阳智滑雪、南怡岛 > 五天超值游"},
				{"tip":"难忘欧洲","price":"6999","intro":"< 浓缩欧洲六国最经典最精华的景点 > 十二日游"},
				{"tip":"马来西亚","price":"4880","intro":"< 云顶、太子城、国家清真寺、水上清真寺等 > 五日游"},
				{"tip":"日本本州","price":"13800","intro":"< 日本本州“米其林 + 百选温泉系列”> 七日游"},
			]
	//机会难得
	for(var i=0;i<2;i++){
		$(".jihuinandei ul").append('<li>'+
	 				'<div class="pic">'+
	 					'<img src="<?php echo F::getStaticsUrl('/activity/v2016/springTravel/');?>images/'+(i+1)+'.jpg"/>'+
	 					'<p><span>'+info[i]["tip"]+'</span></p>'+
	 				'</div>'+
	 				'<div class="des">'+
	 					'<p class="small_font">'+info[i]["intro"]+'</p>'+
	 					'<p>¥<span>'+info[i]["price"]+'</span>起</p>'+
	 					'<a href="javascript:void(0);">立即购买</a>'+
	 				'</div>'+
	 			'</li>'
	 			);
	}
	
	//超值精选
	for(var i=2;i<6;i++){
		$(".chaozhi ul").append('<li>'+
	 				'<div class="pic">'+
	 					'<img src="<?php echo F::getStaticsUrl('/activity/v2016/springTravel/');?>images/'+(i+1)+'.jpg"/>'+
	 					'<p><span>'+info[i]["tip"]+'</span></p>'+
	 				'</div>'+
	 				'<div class="des">'+
	 					'<p class="small_font">'+info[i]["intro"]+'</p>'+
	 					'<p>¥<span>'+info[i]["price"]+'</span>起</p>'+
	 					'<a href="javascript:void(0);">立即购买</a>'+
	 				'</div>'+
	 			'</li>'
	 			);
	}
	 			
	//国外爆款 			
	for(var i=6;i<14;i++){
		$(".waiguo ul").append('<li>'+
	 				'<div class="pic">'+
	 					'<img src="<?php echo F::getStaticsUrl('/activity/v2016/springTravel/');?>images/'+(i+1)+'.jpg"/>'+
	 					'<p><span>'+info[i]["tip"]+'</span></p>'+
	 				'</div>'+
	 				'<div class="des">'+
	 					'<p class="small_font">'+info[i]["intro"]+'</p>'+
	 					'<p>¥<span>'+info[i]["price"]+'</span>起</p>'+
	 					'<a href="javascript:void(0);">立即购买</a>'+
	 				'</div>'+
	 			'</li>'
	 			);
	} 			
	 		
	 //机会难得
	 $(".jihuinandei ul li").eq(0).click(function(){
	 	window.location.href=data[0]['xiamen'];
	 })	
	 
	 $(".jihuinandei ul li").eq(1).click(function(){
	 	window.location.href=data[0]['xijiang'];
	 })	
	 	
	 //超值精选
	 $(".chaozhi ul li").eq(0).click(function(){
	 	window.location.href=data[1]['zhuhai'];
	 });
	 $(".chaozhi ul li").eq(1).click(function(){
	 	window.location.href=data[1]['yunnan'];
	 })				
	 $(".chaozhi ul li").eq(2).click(function(){
	 	window.location.href=data[1]['tianyahaijiao'];
	 })	
	 $(".chaozhi ul li").eq(3).click(function(){
	 	window.location.href=data[1]['guizhou'];
	 })	
	 
	 //国外爆款 		
	 $(".waiguo ul li").eq(0).click(function(){
	 	window.location.href=data[2]['xinma'];
	 });
	 $(".waiguo ul li").eq(1).click(function(){
	 	window.location.href=data[2]['xuewu'];
	 })				
	 $(".waiguo ul li").eq(2).click(function(){
	 	window.location.href=data[2]['puji'];
	 })	
	 $(".waiguo ul li").eq(3).click(function(){
	 	window.location.href=data[2]['taishifengqing'];
	 })	
	 $(".waiguo ul li").eq(4).click(function(){
	 	window.location.href=data[2]['shouer'];
	 });
	 $(".waiguo ul li").eq(5).click(function(){
	 	window.location.href=data[2]['ouzhou'];
	 })				
	 $(".waiguo ul li").eq(6).click(function(){
	 	window.location.href=data[2]['malaixiya'];
	 })	
	 $(".waiguo ul li").eq(7).click(function(){
	 	window.location.href=data[2]['riben'];
	 })	
	 
	</script>
	</body>
</html>
