<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>荔枝</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/layout.css">
	</head>
	<body style="background: #f2f3f4;">
		<div class="contaner">
			<div class="cf_banner">
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/five_br.png"/>
			</div>
			<div class="cf_btn">
				<ul>
					<li>领取免单券</li>
					<li>领取优惠券</li>
				</ul>
			</div>
			
			<div class="cf_rule">
				<p>活动规则：</p>
				<p>1、 活动期间，1个用户仅限参与1次；</p>
				<p>2、 活动期间，彩富用户购买增值宝12期产品，并且订单金额≧150000元，即可获赠免单券一张，如果订单金额≧50000，即可获赠30元优惠券；</p>
				<p>3、 用户获得免单券、优惠券后，可直接在购买页面使用，仅限购买活动商品；</p>
				<p>4、 同笔订单只可以获赠一次奖励；</p>
				<p>5、 除增值宝外其他产品不在奖励范围；</p>
			</div>
		</div>
		<div class="mask hide"></div>
		<div class="cf_pop hide">
			<div class="cf_pop_txt">
				<p>恭喜您成功领取</p>
				<p>【免单券】</p>
				<p>请在购买页面直接使用</p>
			</div>
			<div class="cf_pop_btn">
				<ul>
					<li>取消</li>
					<li>确定</li>
				</ul>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				$(".cf_btn ul li").click(function(){
					var index = $(this).index();
					console.log(index);
					switch (index){
						case 0:
							$.ajax({
								async:true,
								type:"POST",
								url:"/LiZhiJie/LingQuYou",
								data:"buType=1",
								dataType:'json',
								success:function(data){
									if(data.status == 1){  //根据后台返回的状态
										if (data.qid == 1) {
											$(".cf_pop_txt p:eq(0)").text("恭喜您成功领取");
											$(".cf_pop_txt p:eq(1)").text("【免单券】");
											$(".cf_pop_txt p:eq(2)").text("请在购买页面直接使用");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 2) {
											$(".cf_pop_txt p:eq(0)").text("恭喜您成功领取");
											$(".cf_pop_txt p:eq(1)").text("【30元荔枝优惠券】");
											$(".cf_pop_txt p:eq(2)").text("请在购买页面直接使用");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 3) {
											$(".cf_pop_txt p:eq(0)").text("很抱歉！");
											$(".cf_pop_txt p:eq(1)").text("你还不能领取这张券呢~");
											$(".cf_pop_txt p:eq(2)").text("");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 4) {
											$(".cf_pop_txt p:eq(0)").text("您已成功领取过此券~");
											$(".cf_pop_txt p:eq(1)").text("");
											$(".cf_pop_txt p:eq(2)").text("");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}
										else if (data.qid == 5) {
											$(".cf_pop_txt p:eq(0)").text("很抱歉！");
											$(".cf_pop_txt p:eq(1)").text("你还不能领取这张券呢~");
											$(".cf_pop_txt p:eq(2)").text("");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}
									}
									else{
										alert(data.meg);
									}
								} 
							});
							break;
						case 1:	
							$.ajax({
								async:true,
								type:"POST",
								url:"/LiZhiJie/LingQuYou",
								data:"buType=2",
								dataType:'json',
								success:function(data){
									if(data.status == 1){  //根据后台返回的状态
										if (data.qid == 1) {
											$(".cf_pop_txt p:eq(0)").text("恭喜您成功领取");
											$(".cf_pop_txt p:eq(1)").text("【免单券】");
											$(".cf_pop_txt p:eq(2)").text("请在购买页面直接使用");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 2) {
											$(".cf_pop_txt p:eq(0)").text("恭喜您成功领取");
											$(".cf_pop_txt p:eq(1)").text("【30元荔枝优惠券】");
											$(".cf_pop_txt p:eq(2)").text("请在购买页面直接使用");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 3) {
											$(".cf_pop_txt p:eq(0)").text("很抱歉！");
											$(".cf_pop_txt p:eq(1)").text("你还不能领取这张券呢~");
											$(".cf_pop_txt p:eq(2)").text("");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 4) {
											$(".cf_pop_txt p:eq(0)").text("您已成功领取过此券~");
											$(".cf_pop_txt p:eq(1)").text("");
											$(".cf_pop_txt p:eq(2)").text("");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}else if (data.qid == 5) {
											$(".cf_pop_txt p:eq(0)").text("很抱歉！");
											$(".cf_pop_txt p:eq(1)").text("你还不能领取这张券呢~");
											$(".cf_pop_txt p:eq(2)").text("");
											$(".cf_pop").removeClass("hide");
											$(".mask").removeClass("hide");
										}
									}
									else{
										alert(data.meg);
									}
								} 
							});
							break;
					}
				});
				
				$(".mask,.cf_pop_btn ul li").click(function(){
					$(".cf_pop").addClass("hide");
					$(".mask").addClass("hide");
				});
			});
		</script>
	</body>
</html>
