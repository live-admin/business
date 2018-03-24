<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>开门有礼</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/openDoor/');?>js/swiper.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/openDoor/');?>js/swiper.min.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
	<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/openDoor/');?>css/swiper.min.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/openDoor/');?>css/layout.css" />
</head>
<body>
	<header>
		<img src="<?php echo F::getStaticsUrl('/activity/v2016/openDoor/');?>images/bg_icon.png"/>
		<div class="progress data swiper-container">
			<ul class="data_active swiper-wrapper">
				<li class="swiper-slide"><span></span><span>10.14</span></li>
				<li class="swiper-slide"><span></span><span>10.15</span></li>
				<li class="swiper-slide"><span></span><span>10.16</span></li>
				<li class="swiper-slide"><span></span><span>10.17</span></li>
				<li class="swiper-slide"><span></span><span>10.18</span></li>
				<li class="swiper-slide"><span></span><span>10.19</span></li>
				<li class="swiper-slide"><span></span><span>10.20</span></li>
				<li class="swiper-slide"><span></span><span>10.21</span></li>
				<li class="swiper-slide"><span></span><span>10.22</span></li>
				<li class="swiper-slide"><span></span><span>10.23</span></li>
				<li class="swiper-slide"><span></span><span>10.24</span></li>
				<li class="swiper-slide"><span></span><span>10.25</span></li>
				<li class="swiper-slide"><span></span><span>10.26</span></li>
				<li class="swiper-slide"><span></span><span>10.27</span></li>
				<li class="swiper-slide"><span></span><span>10.28</span></li>
				<li class="swiper-slide"><span></span><span>10.29</span></li>
				<li class="swiper-slide"><span></span><span>10.30</span></li>
				<li class="swiper-slide"><span></span><span>10.31</span></li>
				<li class="swiper-slide"><span></span><span>11.01</span></li>
				<li class="swiper-slide"><span></span><span>11.02</span></li>
				<li class="swiper-slide"><span></span><span>11.03</span></li>
				<li class="swiper-slide"><span></span><span>11.04</span></li>
				<li class="swiper-slide"><span></span><span>11.05</span></li>
				<li class="swiper-slide"><span></span><span>11.06</span></li>
				<li class="swiper-slide"><span></span><span>11.07</span></li>
				<li class="swiper-slide"><span></span><span>11.08</span></li>
			</ul>
		</div>

		<div class="total">
			<!--<span>0天</span>-->
			<div id = "canvasContainer" >
				<canvas id="progressRate"></canvas>
				<div>
					<p><span>1</span>天</p>
					<p>已累计签到</p>
				</div>
			</div>
			<!--<span>20天</span>-->
		</div>
	</header>

	<nav>
		<marquee id="list" direction="up" behavior="scroll">
			<!-- <p><span>1231231321</span>用户已累计签到<span>2</span>天</p>
			<p><span>2231231321</span>用户已累计签到<span>2</span>天</p>
			<p><span>3231231321</span>用户已累计签到<span>2</span>天</p>
			<p><span>4231231321</span>用户已累计签到<span>2</span>天</p>
			<p><span>5231231321</span>用户已累计签到<span>2</span>天</p> -->
		</marquee>
		<span>活动规则</span>
	</nav>
	<section>
		<div class="coupon">
			<div>
				<span>¥</span>
				<span>5</span>
			</div>
			<div>
				<P>第5天</P>
				<P>彩特供5元优惠券</P>
				<P>满50元可用</P>
			</div>
			<div class="clear"></div>
		</div>
		<div class="coupon">
			<div>
				<span>¥</span>
				<span>1</span>
			</div>
			<div>
				<P>第10天</P>
				<P>1彩饭票</P>
				<P>全场通用</P>
			</div>
			<div class="clear"></div>
		</div>
		<div class="coupon">
			<div>
				<span>¥</span>
				<span>20</span>
			</div>
			<div>
				<P>第15天</P>
				<P>彩特供20元优惠券</P>
				<P>满100元可用</P>
			</div>
			<div class="clear"></div>
		</div>
		<div class="coupon">
			<div>
				<span>¥</span>
				<span>2</span>
			</div>
			<div>
				<P>第20天</P>
				<P>2彩饭票</P>
				<P>全场通用</P>
			</div>
			<div class="clear"></div>
		</div>
	</section>
	<div class="rulePop hide">
		<p>扫码有礼活动规则</p>
		<p>使用彩之云扫码开门功能，成功开门即为一次签到，每个ID每天可签到一次，连续签到可获得相应奖励。</p>
		<div><p>*</p><p>活动由彩之云提供，与设备生产商Apple Inc.公司无关</p></div>
		<img src="<?php echo F::getStaticsUrl('/activity/v2016/openDoor/');?>images/cancel_btn.png" />
	</div>
	<div class="awardsPop hide">
		<p>恭喜您</p>
		<p>获得彩特供5元优惠券</p>
		<div><span>5元优惠券</span></div>
		<div>
			<button>确定</button>
		</div>
	</div>
	<div class="mask hide"></div>
	<script>
	$(document).ready(function(){
		var data = <?php echo json_encode($data) ?>;
		console.log(data);
		
		var swiperIndex = data.openDays.length > 2 ? data.openDays.length-1 : 2;
		var swiper = new Swiper('.swiper-container', {
	        paginationClickable: true,
	        slidesPerView: 5,
	        centeredSlides: true,
	        initialSlide: swiperIndex,//控制第几天居中
	        spaceBetween: 0
	    });

		initProgress(data);
		initTotal(data.totals);
		initList(data.users);

	    /*初始化进度条*/
	    function initProgress(obj){

	    	for(var i = 0; i < obj.openDays.length; i++)
	    	{
	    		if(i>0)
	    		{
	    			if(i == obj.c5){
	    				$(".progress").find("li").eq(i).find("span:eq(1)").text("5元优惠券");
	    			}
	    			else if(i == obj.c10){
	    				$(".progress").find("li").eq(i).find("span:eq(1)").text("1彩饭票");
	    			}
	    			else if(i == obj.c15){
	    				$(".progress").find("li").eq(i).find("span:eq(1)").text("20元优惠券");
	    			}
	    			else if(i == obj.c20){
	    				$(".progress").find("li").eq(i).find("span:eq(1)").text("2彩饭票");
	    			}
	    			$(".progress").find("li").eq(i-1).addClass("past");
	    		}
	    		if(obj.openDays[i] == 1)
	    		{
	    			$(".progress").find("li").eq(i).addClass("signed");
	    		}
	    		else
	    		{
	    			$(".progress").find("li").eq(i).removeClass("signed");
	    		}
	    	}
    		
			if(obj.days>=5 && obj.days < 10)
			{
				if(obj.trigger == 1){
    				$(".mask").removeClass("hide");
    				$(".awardsPop").attr("data-type","caitegong5yuan");
    				$(".awardsPop>p:eq(1)").text("获得彩特供5元优惠券");
    				$(".awardsPop>div:eq(0)>span").text("5元优惠券");
    				$(".awardsPop").removeClass("hide");
				}

				$(".progress").find("li").eq(i).find("span:eq(1)").text("5元优惠券");
				$("section>div").eq(0).removeClass("coupon");
				$("section>div").eq(0).addClass("coupon_own");
			}
			else if(obj.days>=10 && obj.days < 15)
			{
				if(obj.trigger == 1){
    				$(".mask").removeClass("hide");
    				$(".awardsPop").attr("data-type","1yuanfanpiao");
    				$(".awardsPop>p:eq(1)").text("获得1彩饭票");
    				$(".awardsPop>div:eq(0)>span").text("1彩饭票");
    				$(".awardsPop").removeClass("hide");
				}

				$(".progress").find("li").eq(i).find("span:eq(1)").text("1饭票");
				$("section>div").eq(0).removeClass("coupon");
				$("section>div").eq(1).removeClass("coupon");
				$("section>div").eq(0).addClass("coupon_own");
				$("section>div").eq(1).addClass("coupon_own");
			}
			else if(obj.days>=15 && obj.days < 20)
			{
				if(obj.trigger == 1){
    				$(".mask").removeClass("hide");
    				$(".awardsPop").attr("data-type","caitegong5yuan20");
    				$(".awardsPop>p:eq(1)").text("获得彩特供20元优惠券");
    				$(".awardsPop>div:eq(0)>span").text("20元优惠券");
    				$(".awardsPop").removeClass("hide");
				}

				$(".progress").find("li").eq(i).find("span:eq(1)").text("20元优惠券");
				$("section>div").eq(0).removeClass("coupon");
				$("section>div").eq(1).removeClass("coupon");
				$("section>div").eq(2).removeClass("coupon");
				$("section>div").eq(0).addClass("coupon_own");
				$("section>div").eq(1).addClass("coupon_own");
				$("section>div").eq(2).addClass("coupon_own");
			}
			else if(obj.days >= 20)
			{
				if(obj.trigger == 1){
					$(".mask").removeClass("hide");
    				$(".awardsPop").attr("data-type","2yuanfanpiao");
					$(".awardsPop>p:eq(1)").text("获得2彩饭票");
					$(".awardsPop>div:eq(0)>span").text("2彩饭票");
					$(".awardsPop").removeClass("hide");
				}

				$(".progress").find("li").eq(i).find("span:eq(1)").text("2饭票");
				$("section>div").eq(0).removeClass("coupon");
				$("section>div").eq(1).removeClass("coupon");
				$("section>div").eq(2).removeClass("coupon");
				$("section>div").eq(3).removeClass("coupon");
				$("section>div").eq(0).addClass("coupon_own");
				$("section>div").eq(1).addClass("coupon_own");
				$("section>div").eq(2).addClass("coupon_own");
				$("section>div").eq(3).addClass("coupon_own");
			}
			else
			{
				console.log("error");
				return false;
			}
    		

	    }
	    function initTotal(count){

			var container = document.getElementById("canvasContainer");
			$("#progressRate").attr({"width":container.clientWidth,"height":container.clientHeight});
			var pr=document.getElementById("progressRate");
			var prWidth = pr.clientWidth;
			var prHeight = pr.clientHeight;
			
			var ctx=pr.getContext("2d");
			var deg = Math.PI/20;
			var r = prHeight;
			var origin = {x:prWidth/2,y:r};
			var point1 = {x:0,y:r};

			ctx.beginPath();
			ctx.moveTo(origin.x,origin.y);           // 创建开始点
			ctx.lineTo(point1.x,point1.y); 
			ctx.arc(origin.x,origin.y,r,Math.PI,Math.PI+deg*count); // 创建弧
			ctx.lineTo(origin.x,origin.y);        // 创建垂直线
			ctx.fillStyle="#ef6b42";
			ctx.fill(); 
			$("#canvasContainer>div").find("p").eq(0).find("span").text(count);    
	    }
	    function initList(array){
	    	var phoneList = [];
	    	var dayList = [];
	    	for(var i = 0; i < array.length ; i++){
	    		phoneList[i] = (array[i].split(":")[0]).substring(0,3)+"****"+(array[i].split(":")[0]).substring(7);
	    		dayList[i] = array[i].split(":")[1];

	    		$("#list").append('<p><span>'+phoneList[i]+'</span>用户已累计签到<span>'+dayList[i]+'</span>天</p>');
	    	}

	    }
	    /*获奖弹窗关闭*/
	    $(".awardsPop button").click(function(){
	    	$.ajax({
	    		url: 'OpenDoor/OpenDoor',
	    		type: 'POST',
	    		dataType: 'json',
	    		data: 'type='+$(".awardsPop").attr("data-type"),
	    		success:function(data){
	    			if(data.status  == 0){
	    				console.log(data.message);
	    			}
	    			else if(data.status  == 1){
	    				console.log(data.message);
	    				//这里想服务器提交，然后刘强给你在改trigger的值

	    			}
	    		}
	    	});	    	
	    	$(".awardsPop").addClass("hide");
	    	$(".mask").addClass("hide");
	    });
	    /*活动规则*/
	    $("nav span").click(function(){
	    	$(".mask").removeClass("hide");
	    	$(".rulePop").removeClass("hide");
	    });
	    /*获奖弹窗关闭*/
	    $(".rulePop img").click(function(){
	    	$(".rulePop").addClass("hide");
	    	$(".mask").addClass("hide");
	    });
	});
	</script>
</body>
</html>
