<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>新人有礼</title>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/newPepoleGift/'); ?>js/flexible.js" ></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/newPepoleGift/'); ?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/newPepoleGift/'); ?>css/layout.css" />
</head>
<body>
<div class="wrap">
	<header></header>
	<div class="content">
		<div class="top_bg">
			<p>活动时间: 7.1-7.31</p>
		</div>
		<div class="award_stander">
			<p>活动期间，新用户首次投资满足以下奖励标准，即可获得1元换购机会！</p>
			<table>
				<thead>
					<tr>
						<th>产品</th>
						<th>定投金额</th>
						<th>定投期数</th>
						<th>奖品</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>物业宝</td>
						<td>不限</td>
						<td >12期及以上</td>
						<td rowspan="3">1元换购码<br>（米、油）</td>
					</tr>
					<tr>
						<td>停车宝</td>
						<td>不限</td>
						<td>12期及以上</td>
					</tr>
					<tr>
						<td>增值宝</td>
						<td>5万及以上</td>
						<td>12期及以上</td>
					</tr>
				</tbody>
			</table>
			<p class="attend">注：1元换购码需进入彩之云平台 1元换购专区，兑换相应奖品。</p>
			<em></em>
		</div>
		
		<div class="rule">
			<div class="top_bg"></div>
			<div class="rule_wrap">
				<ul>
					<li>活动规则</li>
					<li>1、仅限活动期间内的新用户可获得1元换购码1个（米或油）；</li>
					<li>2、1元换购码请在活动结束后30天内使用，过期无效；</li>
					<li>3、本次活动仅针对首次投资的用户；</li>
					<li>4、1元换购码在活动结束后10个工作日内发放完毕；</li>
					<li>5、如提前赎回需要从客户本金中按奖品购买价扣除；</li>
					<li>6、本活动奖励可与其它活动叠加；</li>
					<li>7、活动时间：7.1-7.31</li>
				</ul>
			</div>
		</div>
	</div>
	<footer>
		<a href="javascript:mobileJump('EReduceList');">立即投资</a>
		<a href="<?php echo $one_url;?>">1元换购区</a>
	</footer>
</div>
<script>
	function mobileJump(cmd)
    {
        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
            document.location = _cmd;
        } else if (/(Android)/i.test(navigator.userAgent)) {
            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
            eval(_cmd);
        } else {

        }
    }
</script>
</body>
</html>
