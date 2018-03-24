<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>四月拼团-抢购</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	     <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>js/sekill_goods_nav.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #f7f7f7;">
		<div class="top_nav">
			<ul>
				<li>
					<p>10:00</p>
					<p>已开抢</p>
				</li>
				<li>
					<p>16:00</p>
					<p>抢购进行中</p>
				</li>
				<li>
					<p>20:00</p>
					<p>即将开场</p>
				</li>
				
			</ul>
		</div>
		
		<div class="ranking_bar">
			<ul class="rich hide"><div class="clear"></div></ul>
			<ul class="drink hide"><div class="clear"></div></ul>
			<ul class="food hide"><div class="clear"></div></ul>
			<!--<ul class="fruit hide"><div class="clear"></div></ul>-->
		</div>
		
		<div class="mask hide"></div>
		<div class="Pop_qiang hide">
			<div class="Pop_qiang_txt">
				<p>亲，本场秒杀时间</p>
				<p>还未到哦，请稍后再来！</p>
			</div>
			<div class="Pop_qiang_btn_box">
				<div class="Pop_qiang_btn">确定</div>
			</div>
		</div>
		
	<script type="text/javascript">
		var _time = <?php echo json_encode($time) ?>;
		var goods=<?php echo json_encode($goods) ?>;
		var kuCunArr=<?php echo json_encode($kuCunArr) ?>;
		var url=<?php echo json_encode($url) ?>;	
		
		
		$(document).ready(function(){
			console.log(_time);
			showTime();
			
			//时间导航栏切换
			$(".top_nav li").click(function(){
				var index=$(this).index();
				$(this).addClass("choose").siblings("li").removeClass("choose");
				$(".top_nav ul li").css("color","#fff").eq(index).siblings("li").css("color","#999999");
				$(".ranking_bar ul").removeClass("hide").eq(index).siblings("ul").addClass("hide");
			});
			$(".xdebug-var-dump").hide();
			
			addGood(goods["1"],$(".rich"));
			addGood(goods["3"],$(".drink"));
			addGood(goods["4"],$(".food"));
//			addGood(goods["4"],$(".fruit"));
			
			//商品内容添加
			
			function addGood(obj,ele){

				for(var i=0;i<obj.length;i++){
					var nu=obj[i].amount-kuCunArr[obj[i].gid];
//					alert(nu);
					//判断库存少于0，马上抢变灰，不能点
					var gurl = '';
					if(obj[i].shop_type == 1){
						gurl = url.jdUrl;
					}else if (obj[i].shop_type == 2) {
						gurl = url.oneYuanUrl;
					}
					else {
						gurl = url.tuanUrl;
					}	
					
					ele.append(
								'<li>'+
									'<a href="'+gurl+'&pid='+obj[i].pid+'">'+
										'<div class="rnk_left">'+
											'<img src="'+obj[i].img_name +'"/>'+
										'</div>'+
										'<div class="rnk_right">'+
											'<p>'+obj[i].name+'</p>'+
											'<p><span>'+'¥'+'</span><span>'+obj[i].customer_price+'</span><s><span>'+'¥'+'</span><span>'+obj[i].market_price+'</span></s></p>'+
											'<div class="num_box">'+
												'<div class="site_bot_box1a">'+
												
													'<span class="span_left">'+'已抢'+'<span>'+nu+'</span>'+'件'+'</span><span><span >'+(Math.round(nu/obj[i].amount*10000)/100)+'</span>'+'%'+'</span>'+
													'<div class="clear"></div>'+
													'<p><span class="plan" style="width:'+(Math.round(nu/obj[i].amount*10000)/100)+'%'+'"></span></p>'+
												'</div>'+
												'<div class="qiang_btn">'+'马上抢'+'</div>'+
											'</div>'+
										'</div>'+
										'<div class="clear"></div>'+
									'</a>'+
								'</li>'
			 					);
						
					if (obj[i].amount == nu) {
						var index = ele.index();
						$(".ranking_bar ul").eq(index).children("li").eq(i).find(".qiang_btn").text("已抢完");
						$(".ranking_bar ul").eq(index).children("li").eq(i).find(".qiang_btn").css("background","#bfc7cc");
						$(".ranking_bar ul").eq(index).delegate("a","click",function(){
							if($(this).find(".qiang_btn").text() ==  "已抢完"){
								
								return false;
							}
						});
//						
					}	
				
				}
			}
			
			//倒计时
			function showTime(){
				
				_time *= 1000;
				var d = new Date(_time).getDate();
				var h = new Date(_time).getHours();
				var m = new Date(_time).getMinutes();
				var s = new Date(_time).getSeconds();
				console.log(typeof _time +", "+_time);
				console.log(new Date(_time));
				console.log(h,m,s);
				
				if (h<10) {
					$(".ranking_bar ul").delegate("a","click",function(){
						$(".Pop_qiang").removeClass("hide");
						$(".mask").removeClass("hide");
						return false;
					});
					
					$(".top_nav>ul>li:eq(0)>p:eq(1)").text("即将开场");
					$(".top_nav>ul>li:eq(1)>p:eq(1)").text("即将开场");
					$(".top_nav>ul>li:eq(2)>p:eq(1)").text("即将开场");
					$(".rich").removeClass("hide");
					$(".top_nav ul li:eq(0)").addClass("choose");
					$(".top_nav ul li:eq(0)").css("color","#fff");
				}else if(h>=10 && h<16){
					$(".drink,.food").delegate("a","click",function(){
						$(".Pop_qiang").removeClass("hide");
						$(".mask").removeClass("hide");
						return false;
					});
					
					$(".top_nav>ul>li:eq(0)>p:eq(1)").text("抢购进行中");
					$(".top_nav>ul>li:eq(1)>p:eq(1)").text("即将开场");
					$(".top_nav>ul>li:eq(2)>p:eq(1)").text("即将开场");
					$(".rich").removeClass("hide");
					$(".top_nav ul li:eq(0)").addClass("choose");
					$(".top_nav ul li:eq(0)").css("color","#fff");
				}
				else if(h>=16 && h<20){
					$(".food").delegate("a","click",function(){
						$(".Pop_qiang_txt>p:eq(0)").text("亲，本场秒杀时间");
						$(".Pop_qiang_txt>p:eq(1)").text("还未到哦，请稍后再来！");
						$(".Pop_qiang").removeClass("hide");
						$(".mask").removeClass("hide");
						return false;
					});
					
					$(".rich").delegate("a","click",function(){
						$(".Pop_qiang_txt>p:eq(0)").text("亲，本场秒杀已结束");
						$(".Pop_qiang_txt>p:eq(1)").text("下场秒杀请提早参加哦！");
						$(".Pop_qiang").removeClass("hide");
						$(".mask").removeClass("hide");
						return false;
					});
					
					$(".top_nav>ul>li:eq(0)>p:eq(1)").text("已开抢");
					$(".top_nav>ul>li:eq(1)>p:eq(1)").text("抢购进行中");
					$(".top_nav>ul>li:eq(2)>p:eq(1)").text("即将开场");
					$(".drink").removeClass("hide");
					$(".top_nav ul li:eq(1)").addClass("choose");
					$(".top_nav ul li:eq(1)").css("color","#fff");
				}else if(h>=20 && h<24){
					$(".drink,.rich").delegate("a","click",function(){
						$(".Pop_qiang_txt>p:eq(0)").text("亲，本场秒杀已结束");
						$(".Pop_qiang_txt>p:eq(1)").text("下场秒杀请提早参加哦！");
						$(".Pop_qiang").removeClass("hide");
						$(".mask").removeClass("hide");
						return false;
					});
					
					$(".top_nav>ul>li:eq(0)>p:eq(1)").text("已开抢");
					$(".top_nav>ul>li:eq(1)>p:eq(1)").text("已开抢");
					$(".top_nav>ul>li:eq(2)>p:eq(1)").text("抢购进行中");
					$(".food").removeClass("hide");
					$(".top_nav ul li:eq(2)").addClass("choose");
					$(".top_nav ul li:eq(2)").css("color","#fff");
				}
				
			   	if(_time>0){
					_time--;
				}
			}
			
			$(".Pop_qiang_btn").click(function(){
				$(".Pop_qiang").addClass("hide");
				$(".mask").addClass("hide");
			});
			
});
	</script>
	</body>
</html>
