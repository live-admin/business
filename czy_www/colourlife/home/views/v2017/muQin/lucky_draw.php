<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>抢福袋</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>js/each.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>css/layout.css">
	</head>
	<body style="background: #f7f7f7;">
		<div class="contaner">
			<div class="fudai_pic">
				<div class="change"></div>
				<div class="qiang_btn">拆福袋</div>
			</div>
			
			<div class="btn">中奖记录</div>
			
			<div class="contaner_rule">
				<h4>活动规则</h4>
				<div class="contaner_p">
					<p>1.活动时间：2017年5月11日－2017年5月21日;</p>
					<p>2.每个ID每天拥有3次参与机会；</p>
					<p>3.游戏中所得奖励（优惠券），仅限本次活动中使用（除超值特惠区外）；</p>
					<p>4.下单完成后，若用户申请退款，只返还减去奖励以后的金额；</p>
					<p>5.如因填写地址不完整导致下单失败，则只退还实付金额</p>
					<p>6.若因系统问题造成优惠券延迟到账，请联系客服人员；</p>
					<p>7.活动期间若出现作弊行为，则取消该帐号活动资格，情节严重者将给予封号处理。</p>
					<p>本活动最终解释权归彩之云所有。</p>
				</div>
				<div class="line"></div>
			</div>
		</div>
		
		<div class="mask hide"></div>
		
		<div class="pop_prize hide">
			<div class="quan_pic"></div>
			<div class="quan_name">
				<p>恭喜您获得</p>
				<p>3元优惠券1张</p>
			</div>
			<div class="quan_detail">
				<p>除超值特惠全场满3元即可使用</p>
				<p>有效期2017.05.11-2017.05.21</p>
			</div>
			<div class="sure_btn">确定</div>
			<div class="close"></div>
		</div>
		
		<div class="pop_no hide">
			<div class="no_quan_pic"></div>
			<div class="no_quan_name">
				<p>很遗憾，</p>
				<p>您没有抽中呢~</p>
			</div>
			<div class="sure_btn">确定</div>
			<div class="close"></div>
		</div>
		
		<script type="text/javascript">
			var leftChance =<?php echo json_encode($leftChance);?>;
		</script>
		
	</body>
</html>
