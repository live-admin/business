<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>08订单列表-收货地址</title>
		<meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/CAAnimation.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/UIPickerView.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/editAddress.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>css/style.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>css/UIPickerView.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="overflow: hidden;">
		<div class="conter conter_h">
			<div class="new_address">
				<p class="new_address_img"></p>
				<p>
					<input type="text" id="mainPicker" placeholder="请选择省市区" name="address" readonly style="border: none;" >
					<input style="width: 1.2rem;margin-left: 1.5rem;border: none;" class="hide" readonly type="text" id="townPicker" placeholder="请选择镇" name="town" >
				</p>
				<div class="clear"></div>
		   </div>
			<div class="new_address01">
				<p class="new_address_img"></p>
				<p><input type="text" placeholder="请输入详细地址" name="address01" style="border: none;"></p>
				<div class="clear"></div>
			</div>
			<div class="new_recipient">
				<p class="new_recipient_img"></p>
				<p><input type="text" placeholder="收件人" name="recipient" style="border: none;"></p>
				<div class="clear"></div>
			</div>
			<div class="new_phone">
				<p class="new_phone_img"></p>
				<p><input type="number" placeholder="联系电话" name="phone" style="border: none;" ></p>
				<div class="clear"></div>
			</div>
			<div class="new_code">
				<p class="new_code_img"></p>
				<p><input type="text" maxlength="6" placeholder="邮政编码(选填)" name="code" style="border: none;"></p>
				<div class="clear"></div>
			</div>
			<div  class="save_buttom">
				<a href="javascript:void(0)">保存</a>
			</div>
			<!-- <div class="complete">
			    <a href="javascript:void(0)">完成</a>
			</div> -->
			<div class="region-picker-wrapper visibility-control" id="mainRegion">
			    <div class="header">
				<div class="bar bar-header" style="position: relative;">
					<button class="button button-clear button-positive" id="selectYes" style="font-size: 0.3rem;color: #FE4C5D;position: absolute;right: 0.2rem;">完成</button>
				</div>
			    </div>
			    <div class="body">
					<div class="select-zone top"></div>
					<div class="select-zone middle"></div>
					<div class="select-zone bottom"></div>
					<div class="region-picker">
						<div id="provincePicker"></div>
					</div>
					<div class="region-picker">
						<div id="cityPicker"></div>
					</div>
					<div class="region-picker">
						<div id="areaPicker"></div>
					</div>
			    </div>
			</div>
			<div class="region-picker-wrapper visibility-control" id="townRegion">
			    <div class="header">
				<div class="bar bar-header" style="position: relative;">
					<button class="button button-clear button-positive" id="selectYesTown" style="font-size: 0.3rem;color: #FE4C5D;position: absolute;right: 0.2rem;">完成</button>
				</div>
			    </div>
			    <div class="body">
					<div class="select-zone top"></div>
					<div class="select-zone middle"></div>
					<div class="select-zone bottom"></div>
					<div class="region-picker">
						<div id="town_picker"></div>
					</div>
			    </div>
			</div>

			<div class="mask hide"></div>
			<div class="pop hide">
				<div class="pop_header">
					<p></p>
				</div>
				<div class="pop_con">
					<p>您的领奖信息已保存成功，请保持电话畅通</p>
				</div>
				<div class="pop_footer">
					<a href="/property/index">
						确认
					</a>
				</div>
				<!--<div class="close"></div>-->
			</div>
		</div>
		<script>
			var prize_id = <?php echo $param['prize_id'] ?>;
			var customer_id = <?php echo $param['customer_id'] ?>;
			var level_id = <?php echo $param['level_id'] ?>;
			var time = <?php echo $param['time'] ?>;
			var category_id = <?php echo $param['category_id'] ?>;
			var record_id = <?php echo $param['record_id'] ?>;
		</script>
	</body>
</html>