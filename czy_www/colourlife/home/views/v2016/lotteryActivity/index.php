<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>中秋节晚会抽奖</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/lotteryActivity/');?>js/src/jquery.slotmachine.js"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/lotteryActivity/');?>js/choujiang.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/lotteryActivity/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
</head>
<body style="background-color:#F6E8E7">
	<header >
		<img src="<?php echo F::getStaticsUrl('/activity/v2016/lotteryActivity/');?>images/banner.jpg">	
	</header>
	
	<div class="content">
		<p class="tips"></p>
		<div class="bg_wrap">
			
			<div class="activ_btn">
				<div class="left_btn">
					<a href="javascript:;" class="rule_btn">活动规则</a>
				</div>
				<div class="right_btn">
					<a href="javascript:;" class="share_btn">分享</a>
					<a href="javascript:;" class="record_btn">中奖记录</a>
				</div>
			</div>
			
			<div class="machine_wrap">
				<div class="laohuji" id="switch">
					<div class="l_draw_con_box01"></div>
					<div class="l_draw_con_box02 handel"></div>
				</div>
				<!--滚动图案 start-->
				<div class="content_draw">
					<div id="machine4" class="slotMachine">
						<div class="slot slot1"></div>
						<div class="slot slot2"></div>
						<div class="slot slot3"></div>
					</div>
					<div id="machine5" class="slotMachine">
						<div class="slot slot1"></div>
						<div class="slot slot2"></div>
						<div class="slot slot4"></div>
					</div>
					<div id="machine6" class="slotMachine">
						<div class="slot slot1"></div>
						<div class="slot slot2"></div>
						<div class="slot slot5"></div>
					</div>
				</div>
				<!--滚动图案 end-->
				<div class="return_order">
					<p></p>
				</div>	
			</div>
		</div>
		<div class="index_footer rule_footer"></div>
	</div>		
	
	
	<!--二维码弹窗-->
	<div class="pop01 hide">
		<div class="prize_face"></div>
		<div class="descript">
			<p class="prize_des"></p>
			<p class="prize_name"></p>
		</div>
	</div>
	
	<!--奖项码弹窗-->
	<div class="pop hide">
		<div class="prize_face"><span></span></div>
		<div class="descript">
			<p class="prize_des"></p>
			<p class="prize_name"></p>
		</div>
	</div>
	
	<!--遮罩层-->
	<div class="mask hide"></div>
		
	<script>
	var tips_static = <?php echo json_encode($status);?>;
	var msg = <?php echo json_encode($msg);?>;
	var winningList = <?php echo json_encode($winningList);?>;
	var pid = <?php echo $pid; ?>;
	
	$(".tips").text(msg);
	/*获取网页最新状态，确定是否刷新页面*/
	setInterval(_isReload,20000);
	function _isReload(){
		
		$.ajax({
				type:"POST",
				url:"/LotteryActivity/IsRefresh",
				data:"pid="+pid,
				dataType:'json',
				success:function(data){
					console.log(data);
					if(data.status==1){
						window.location.reload();
					}
				}			
			})
	}
	$(".rule_btn").click(function(){
		window.location.href="/LotteryActivity/Rule";
	})
	
	//分享
	$(".share_btn").click(function(){
		$(".pop01").removeClass("hide");
		$(".mask").removeClass("hide");
		$(".pop01 .prize_des").text("邀请小伙伴");
		$(".pop01 .prize_name").addClass("other_style").text("使用彩之云app扫码进入抽奖活动");
		$.ajax({
			type:"POST",
			url:"/LotteryActivity/ShareWeb",
			data:"",
			dataType:'json',
			success:function(data){
				if(data.status==1){
					$(".pop01 .prize_face").find("img").remove();
					$(".pop01 .prize_face").append("<img src="+data.msg+ "/>")
				}else if(data.status==0){
					console.log(data.msg);
				}
					
			}			
		})
	
	})
	
	//分享遮罩	
	$(".mask").click(function(){
		$(this).addClass("hide");
		$(".pop01").addClass("hide");
		$(".pop").addClass("hide");
	})
		
		$(".record_btn").click(function(){
			window.location.href="/LotteryActivity/WinningList";
		})
		
		
	</script>
</body>
</html>
