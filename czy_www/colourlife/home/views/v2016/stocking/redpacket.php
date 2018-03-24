<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>2016年货盛典</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/fastclick.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/stocking/');?>js/envelopes.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/stocking/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body onload="OnLoad()">
		<div class="contaner">
			<div class="bar_top">
				<div class="bar_top_left">
					<p>剩余次数：<span>3</span></p>
					<p><span>0</span>/<span>10</span></p>
					<div class="jingdu">
						<p><span class="jingdu_plan"></span></p>
					</div>
				</div>
				<div class="bar_top_right">
					<p>15</p>
				</div>
			</div>
			<div class="hongbao_yu">
			</div>
		</div>
		
		<div class="mask hide"></div>
		<div class="Pop_qiang">
			<div class="Pop_qiang_txt">
				<p></p>
				<p></p>
			</div>
			<div class="Pop_qiang_btn_box">
				<div class="Pop_qiang_btn"></div>
			</div>
			
		</div>
		
		<div class="Pop_get hide">
			<div class="Pop_get_txt">
				<p>恭喜您获得</p>
				<p>12元优惠券1张</p>
				<p>全场满59元即可使用</p>
				<p>有效期:2016.12.16-2017.01.10</p>
			</div>
			<div class="Pop_get_box">
				<div class="Pop_sur_btn">确定</div>
				<div class="Pop_once_btn">再来一次</div>
			</div>
			
		</div>
		
		<script type="text/javascript">
		var number = <?php echo $number;?>;
		
		
			$(document).ready(function(){
				
				FastClick.attach(document.body);
				//红包雨
				var container = document.body;
				var img = new Image();
				img.src = "<?php echo F::getStaticsUrl('/activity/v2016/stocking/');?>images/red_bao.png";
				container.style.overflow = "hidden";
				var objCount = 0;
				var rainStartPostion = null;
				var rainStartPostion1 = null;
				var clickCount = 0;

				//一进来弹窗提示
				initPage();
				function initPage() {
					$(".Pop_qiang_txt>p:eq(0)").text("红包雨即将开始，");
					$(".Pop_qiang_txt>p:eq(1)").text("猛戳屏幕抢红包！");
					$(".Pop_qiang_btn").text("马上开始");
					$(".Pop_qiang").removeClass("hide");
	               	$(".mask").removeClass("hide");
	               	$(".bar_top_left>p:eq(0)>span").text(number);
	               	
	               	if (number<=0) {
	               		
	               		$(".Pop_qiang_btn").addClass("activity");
	               		$(".Pop_qiang_txt>p:eq(0)").text("今日机会已用完！");
						$(".Pop_qiang_txt>p:eq(1)").text("请明日再来！");
						$(".Pop_qiang_btn").text("去购物");
						$(".Pop_qiang").removeClass("hide");
		               	$(".mask").removeClass("hide");
	               	}
				}
				
				var i=15;
		        var countdown;
		        function timeShow() {
		            i--;
		            $(".bar_top_right>p").html(i);
					
					
		            if(i<1){
		            	
		                clearInterval(countdown);
		                endRain();
		            }
		           	
		        };
		        
		        //点击马上开始和再来一次
		        $(".Pop_qiang_btn,.Pop_once_btn").click(function() {
		        	if($(".Pop_qiang_btn").hasClass("activity")&&$(".Pop_qiang_btn").text() == "去购物"){
		        		location.href = "/Stocking";
		        	}
		        	$(".bar_top_left p:eq(1)>span:eq(0)").text("0");
		        	
		        	$(".jingdu_plan").css("width","0%");
					
					if(number <= 0){
						$(".bar_top_left>p:eq(0)>span").text("0");
						$(".Pop_qiang_btn").addClass("activity");
	               		$(".Pop_qiang_txt>p:eq(0)").text("今日机会已用完！");
						$(".Pop_qiang_txt>p:eq(1)").text("请明日再来！");
						$(".Pop_qiang_btn").text("去购物");
						$(".Pop_get").addClass("hide");
						$(".Pop_qiang").removeClass("hide");
		               	$(".mask").removeClass("hide");
					}else{
						
						$(".Pop_get").addClass("hide");
						$(".Pop_qiang").addClass("hide");
		               	$(".mask").addClass("hide");
						$('.jingdu_plan').animate({'width':'100%'},15000,function(){
							$(".jingdu_plan").css("width","0%");
						});

	              	 	$(".bar_top_left>p:eq(0)>span").text(number);
			        	i=15;
			        	countdown = setInterval(timeShow,1000);
						startRain();
						rainStartPostion = setInterval(startRain,400);
						
					}
		        });
		        
				function startRain(){
					$("body").css("overflow","hidden");
					$(".mask").removeClass("hide");
					var objCount = new Envelopes(img,container);
					objCount.fall();

					objCount.dom.addEventListener("click",function(){

						$(this).hide();
						clickCount++;
						$(".bar_top_left>p:eq(1)>span:eq(0)").text(clickCount);
						console.log(clickCount);
					});
					objCount++;
				}

				function endRain(){
					$("body").css("overflow","auto");
					
					if(number <= 0 )
						return false;
						
					number = number-1;
//					var Days = 1; //此 cookie 将被保存 1 天
//					var exp  = new Date();    //new Date("December 31, 9998");
//					exp.setTime(exp.getTime() + Days*24*60*60*1000);
//					document.cookie = cookieKey + "="+ escape (number) + ";expires=" + exp.toGMTString();
					
					$(".bar_top_left>p:eq(0)>span").text(number);
					clearInterval(rainStartPostion);

					$(".mask").addClass("hide");
					$("canvas").addClass("hide");
						
		            if(clickCount > 9){
	            	 	$.ajax({
						async:true,
						type:"POST",
						url:"/Stocking/AjaxRedpacket",
						data:"",
						dataType:'json',
						success:function(resut){
							
							if(resut.retCode == 1){  //根据后台返回的状态
								$(".Pop_get_txt>p:gt(0)").removeClass("hide");
								$(".Pop_get_txt>p:eq(0)").text("恭喜您获得");
								$(".Pop_get_txt>p:eq(1)").text(resut.data.prize_name);
								$(".Pop_get_txt>p:eq(2)").text(resut.data.desc);
								$(".Pop_get").removeClass("hide");
								$(".mask").removeClass("hide");
								if (resut.data.desc == -1) {
									$(".Pop_get_txt>p:eq(0)").text(resut.data.prize_name);
									$(".Pop_get_txt>p:gt(0)").addClass("hide");
								}
								
								
							}
							else{
								console.log("奖品抽完");
								$(".Pop_get_txt>p:eq(0)").text("谢谢参与");
								$(".Pop_get_txt>p:gt(0)").addClass("hide");
								$(".Pop_get").removeClass("hide");
								$(".mask").removeClass("hide");
							}
							
						} 
					});
		            }
		            //少于9次请求的AJAX，入库
		            else{
		            	$.ajax({
						async:true,
						type:"POST",
						url:"/Stocking/AjaxRedpacketMin",
						data:"",
						dataType:'json',
						success:function(resut){
							if(resut.retCode == 1){  //根据后台返回的状态
								$(".Pop_qiang_txt>p:eq(0)").text("速度不够快哦！");
								$(".Pop_qiang_txt>p:eq(1)").text("加油再试一次吧~");
								$(".Pop_qiang_btn").text("再来一次")
				               	$(".Pop_qiang").removeClass("hide");
				               	$(".mask").removeClass("hide");
							}
							else{
								console.log("Ajax数据插入不成功");
							}
						} 
					});
		            }
		            clickCount = 0;
				}
				$(".Pop_sur_btn").click(function(){
					location.href = "/Stocking";
//					$(".Pop_get").addClass("hide");
//					$(".Pop_qiang").addClass("hide");
//		            $(".mask").addClass("hide");
				});
			});
			
			function OnLoad(){
		   		document.documentElement.style.webkitTouchCallout = "none"; //禁止弹出菜单
				document.documentElement.style.webkitUserSelect = "none";//禁止选中
			}
			
		</script>
		
	</body>
</html>
