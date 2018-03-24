<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>情系中秋-首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telphone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
</head>
<body>
	<header>
		<div class="news">
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/new.png" class="news_pic"/> 
			<p>中秋特惠，满800返50，满300返15！</p>
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/close.png" class="close_pic"/>
		</div>
		<div class="banner">
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/index_banner.png">
		</div>
	</header>
	
	<div class="content">
		<div class="content_goods">
			<!--月饼专区 start-->
			<div class="item">
				<div class="border_top">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/goods_top_bg.png">
					<div class="mooncake_font"></div>	
				</div>
				<div class="figure_wrap">
					
				</div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/goods_bottom_bg.png" class="border_pic">
			</div>
			<!--月饼专区 end-->
			
			<!--酒水专区 start-->
			<div class="item">
				<div class="border_top">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/goods_top_bg.png">
					<div class="drinks_font"></div>
				</div>
				<div class="figure_wrap">
					
				</div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/goods_bottom_bg.png" class="border_pic">
			</div>
			<!--酒水专区 end-->
			
			<!--香茗专区 start-->
			<div class="item">
				<div class="border_top">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/goods_top_bg.png">
					<div class="tea_font"></div>
				</div>
				<div class="figure_wrap">
					
				</div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/goods_bottom_bg.png" class="border_pic">
			</div>
			<!--酒水专区 end-->
		</div>
	</div>	
	
<script>		
	var jdUrl="<?php echo $jdUrl;?>";
	var ctgUrl= "<?php echo $ctgUrl;?>";
	var goods = <?php echo $goods;?>;
	
	$("header").click(function(){
		window.location.href="/MidAutumn/intro";
	})

	//点击消息的关闭按钮，防止冒泡
	$(".close_pic").click(function(){
		$(".news").hide();
		return false;
	})
	
	for(var list in goods){
		for(var i=0;i<goods[list].length;i++){
		var index=$(".content_goods").find(".item").eq(list-1);
			index.find(".figure_wrap").append('<figure>'+
											'<img src="'+goods[list][i].img_name +'">'+
											'<figcaption>'+
												'<p>'+goods[list][i].name+'</p>'+
												'<div class="buying">'+
													'<span>¥&nbsp;<em>'+goods[list][i].customer_price+'</em></span>'+
													'<span>立即购买  <img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/arrow.png"></span>'+	
												'</div>'+
											'</figcaption>'+
										'</figure>');
			}
			
	}
	
	
	//跳转商品
	$(".figure_wrap").delegate("figure","click",function(){

		var parentIndex = $(".figure_wrap").index($(this).parent());
		var index = $(this).index();
		
		console.log(parentIndex+" ,"+index)
		var type=goods[parentIndex+1][index].shop_type;
		$.ajax({
	          type: 'POST',
	          url: '/MidAutumn/Log',
	          data: 'type=4&gid='+goods[parentIndex+1][index].gid,
	          dataType: 'json',
	          success: function (result) {
		          
	          }
	    });	
		if(type == 1){
			window.location.href = jdUrl+"&pid="+goods[parentIndex+1][index].pid;
		}else{
			window.location.href = ctgUrl+"&pid="+goods[parentIndex+1][index].pid;
		}
	});
	
</script>
</body>
</html>
