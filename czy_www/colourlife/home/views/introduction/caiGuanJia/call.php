<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>呼信功能使用简介</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>css/layout.css" /> 
</head>
<body style="background-color:#defaf5;">
<header><h1>呼信功能使用简介</h1></header>
<div class="produce">
	<ul class="main-item">
		<li>
			<h3>1.产品概述</h3>
			<p>“呼信”基于电话号码触发互联网应用，架起了传统通信和互联网应用之间的桥梁；用户手机在拨打电话过程中、语音通话过程中以及挂断电话后，都可以通过“呼信”一键进入互联网，在通信网和互联网双网融合的技术平台上，构建多样化、个性化的移动应用场景。</p>
		</li>
		<li>
			<h3>2.运行环境</h3>
			<p>运行环境安卓4.0以上，并支持wifi和4G网络环境。</p>
		</li>
		<li>
			<h3>3.使用功能条件</h3>
			<p>用户需允许彩管家应用拥有悬浮窗权限，及通讯录权限。</p>
		</li>
		<li>
			<h3>4.产品功能</h3>
			<ul class="child-item">
				<li>
					<p>1.当通话的时候会自动去辨别此号码是否为业主。如果是业主。</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>images/pic01.jpg" alt=""/>
					<p>则可以使用提交意见反馈，扫码开门（与彩管家内置扫码开门一样）功能。通话结此将弹出以下页面。</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>images/pic02.jpg" alt=""/>
					<p>点击意见反馈将进入以下页面。</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>images/pic03.jpg" alt=""/>
				</li>
				<li>
					<p>2.如果对方是非业主，则号码右边会出现x图标标示，页面可以变为添加业主</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>images/pic04.jpg" alt=""/>
					<p>点击“设为业主”图标，进入录入业主资料页面。</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>images/pic05.jpg" alt=""/>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiguanjiacall/');?>images/pic06.jpg" alt=""/>
					<p>点击“完成”即可完成对业主信息的录入。</p>
					<p class="red-color">*备注：录入完的业主信息将直接进入“资源系统”，请认真操作，避免录入错误数据。</p>
				</li>
			</ul>
		</li>
		<li>
			<h3>5.呼信功能使用答疑</h3>
			<ol class="question child-item">
				<li>
					<p>问：在没有开启移动网络时,呼入呼出能否显示是否为业主？</p>
  					<p>答：暂时不可以，目前产品功能需要基于移动网络或WIFI条件下实现。</p>
				</li>
				<li>
					<p>问：通话过程中能否关闭该页面。</p>
					<p>答：可以的。点击右上角的“收起”按钮，则进入普通通话页面。</p>
				</li>
				<li>
					<p>问：显示的业主信息不正确怎么办？</p>
					<p>答：可以点击“意见反馈”记录信息不匹配问题，并提交发起工单。</p>
				</li>
				<li>
					<p>问：提交的“意见反馈”工单可以在哪看到？</p>
					<p>答：发起的工单可以登录“蜜蜂协同”系统查看，默认执行人为自己，可根据内容转给相关部门处理。</p>
				</li>
				<li>
					<p>问：“添加业主”页面需要手动输入业主手机号码吗？</p>
					<p>答：不需要的，系统将抓取该号码并自动填写。在该页面输入业主姓名即可完成录入。</p>
				</li>
			</ul>
		</li>
	</ul>
</div>
<?php require_once 'cs.php'; echo '<img src="'._cnzzTrackPageView(1261301329).'" width="0" height="0"/>';?>
</body>
</html>