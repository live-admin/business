<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>国庆七天乐任务记录</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/task/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/task/');?>css/layout.css" />
</head>
<body class="body_bg">
	<div id="warp" class="history_wrap">
		<!--1-->
		
	</div><!--record_wrap-->
	
	<script>
		$(document).ready(function(){
			var record = <?php echo $historyData;?>;
			initPage();
			function insertNode(obj){
				$("#warp").append('<div class="history_item" id='+obj.task_id+'>'+
												'<span>'+obj.complete_date+'</span>'+
												'<div class="active_info">'+
													'<div class="circle_icon">'+
									                    '<div class="outer_color"></div>'+
									                    '<div class="inner_color"></div>'+
									               '</div>'+
												'</div>'+
											'</div>');
				for(var i = 0; i < obj.task_detail.length; i++){
					$("#"+obj.task_id).find(".circle_icon").before('<p>'+(i+1)+'.'+obj.task_detail[i]+'</p>');
				}	
			}

			function initPage(){
				var data = record.tasks;
				if(!data||data.length <= 0){
					$("#warp").removeClass("history_wrap");
					return;
				}
				else
				{					
					$("#warp").addClass("history_wrap");
					for(var i = 0 ; i < data.length ; i++){
						insertNode(data[data.length-i-1]);
					}
				}
			}
		});
	</script>
</body>
</html>
