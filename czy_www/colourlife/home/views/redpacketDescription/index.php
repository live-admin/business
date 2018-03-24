<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>红包放发说明</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl("/common/");?>js/jquery.min.js"></script>
		<link href="<?php echo F::getStaticsUrl("/redpacketDescription/");?>css/hbff.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="fbff_content">
		  <h3>饭票说明</h3>
          <dl>
            <dt>什么是饭票</dt>
            <dd>
              <p>饭票是彩之云平台专属的省钱利器，1元饭票=1元人民币。</p>
              <p>您可使用饭票缴纳物业费、停车费、手机充值 、购买商品、兑换礼品等。</p>
            </dd>
            <dt>饭票从哪来</dt>
            <dd>
              <p>您可通过注册、抽奖、消费返利、参与平台活动、使用平台应用等途径获得彩之云饭票。</p>
            </dd>
            <dt>饭票怎么用</dt>
            <dd>
              <p>彩之云E缴费、京东特供、海外直购、超市、天天团、手机充值以及幸福中国行活动商品均可使用饭票支付，嘿客商城等更多应用即将开通饭票支付，饭票理财、饭票充值功能也即将上线。</p>
            </dd>
            <dt>饭票小贴士</dt>
            <dd>
              <p>饭票不可提现，仅限在彩之云平台使用。</p>
              <p>饭票无使用期限，随时可使用。</p>
            </dd>
          </dl>
        </div>

		
		
		<script>
		  $(function(){
			   $('.fbff_content dt').click(function(){
				   $(this).next('dd').toggle();
				}) 
			  
		   })
		
		</script>
	</body>

</html>