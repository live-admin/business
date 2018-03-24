<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>首页弹窗</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>css/normalize.css">
	</head>
	<body>
		
		<!--得奖弹窗-->
		<div class="pop">
			<div class="pop_txt">
				<p>恭喜您</p>
				<p>成功获得<?php echo (int)$result['award']; ?>彩之云饭票券。</p>
			</div>
			<div class="fp_img">
				<p><?php echo (int)$result['award']; ?>饭票券</p>
			</div>
			<p class="p_txt">有效期：<?php echo $result['valid_time']; ?></p>
			<div class="lq_btn">
				<a href="javascript:void(0);">确定</a>
			</div>
		</div>
		
	<script type="text/javascript">
		$(document).ready(function(){
			$(".lq_btn").click(function(){
				var id = <?php echo $result['id']; ?>;

				$.ajax({
					url : '/CaiZhuZhaiActivity/appReceive/'+id,
					type : 'get',
					dataType : 'JSON',
					success : function(data) {
						if (1 == data.retCode) {
							mobileCommand("closeBrowser");
						}
						else {
							alert(data.retMsg);
						}
					}
				});


			});

			function mobileCommand(cmd){
				
				if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
					var _cmd = "http://colourlifecommand/" + cmd;
					document.location = _cmd;
				} else if (/(Android)/i.test(navigator.userAgent)) {
					var _cmd = "jsObject." + cmd + "();";
					eval(_cmd);
				} else {
	
				}
			}
		});
			
	</script>
		
	</body>
	
</html>
