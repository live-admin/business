<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>11月电商特惠专场</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
</head>
<body>
	<div class="good_list nav_list">
		<!--数码家电专场-->
		<div class="digital_products item hide"></div>
	
		<!--一元购-->
		<div class="one_buy item hide"></div>
		
		<!--洗护清洁用品-->
		<div class="clean item hide"></div>
	</div>
	
	<script>
	var goods=<?php echo $goods ?>;	
	var url=<?php echo $url ?>;	
//		var goods={
//	"4":[
//		{"pid":"14270","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"1","customer_price":"25.90","market_price":"26.00"},	
//		{"pid":"14271","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"1","customer_price":"25.90","market_price":"26.00"},	
//		{"pid":"14272","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"1","customer_price":"25.90","market_price":"26.00"},
//		{"pid":"14273","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"1","customer_price":"25.90","market_price":"26.00"},	
//		{"pid":"14274","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"1","customer_price":"25.90","market_price":"26.00"},	
//		{"pid":"14275","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"1","customer_price":"25.90","market_price":"26.00"}	
//		
//		]//,
//	"5":[
//		{"pid":"14272","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"2","customer_price":"25.90","market_price":"26.00"}
//		],
//	"6":[
//		{"pid":"14272","img_name":"http:\/\/img20.360buyimg.com\/n0\/jfs\/t403\/13\/392683655\/84327\/8a4918c2\/54196c8fN61eff866.jpg","gid":"14272","shop_type":"1","name":"3","customer_price":"25.90","market_price":"26.00"}	
//	]
//	};

	//不同商品链接限制
	if(goods['4'] !== undefined ){
		addGood(goods["4"],$(".digital_products"),4);
		$(".digital_products figcaption span").addClass("small_top");
		detail(goods["4"]);
	}else if(goods['5'] !== undefined){
		addGood(goods["5"],$(".one_buy"),0);
		//$(".one_buy figcaption p").eq(1).addClass("hide");
		detail(goods["5"]);
	}else if(goods['6'] !== undefined){
		addGood(goods["6"],$(".clean"),0);
		//$(".clean figcaption p").eq(1).addClass("hide");
		detail(goods["6"]);
	}
	
	//获取商品
	function addGood(obj,ele,type){
		for(var i=0;i<obj.length;i++){
			var str = '';
			if(type == 4){
				str = '<p>返300</p>';
			}
			ele.append('<figure>'+
							'<img src="'+obj[i].img_name +'">'+
							'<figcaption>'+
								'<p>'+obj[i].name+'</p>'+
								str+
								'<span>¥&nbsp;<em>'+obj[i].customer_price+'</em></span>'+
							'</figcaption>'+
							'<div class="clearfix"></div>'+
						 '</figure>')
			}
		ele.removeClass("hide").siblings("li").addClass("hide");
	}
	
	//商品详情链接
	function detail(obj){
		$("figure").click(function(){
			var index = $(this).index();
			switch(obj[index].shop_type){
				case '1':                  
					window.location.href=url.jdUrl+"&pid="+obj[index].pid;
					break;
				case '2':
					window.location.href=url.oneYuanUrl+"&pid="+obj[index].pid;
					break;
				case '0':
					window.location.href=url.tuanUrl+"&pid="+obj[index].pid;
					break;
			}
		})
	}


	
	</script>
</body>
</html>
 