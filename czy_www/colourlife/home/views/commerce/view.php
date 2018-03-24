<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>平台招商申请-彩之云</title>
		<link href="<?php echo F::getStaticsUrl('/common/css/paltformshop.mobile.css'); ?>" rel="stylesheet">
	</head>
	<body>
		<div class="main">
			<div class="applyfor_interview recruit_apply">
			<a href="/commerce/league" class="goback" style="text-decoration:none;">上一页</a>
			<div class="details">
              <?php echo $model->content;?>
			</div>
			
			 <div>
                <a class="submit_button" href="/commerce/show/<?php echo $model->id;?>" style="text-decoration:none;"> &nbsp;&nbsp;&nbsp;下一页&nbsp;&nbsp;&nbsp;</a>
              </div>
							</div>
		</div>
	</body>
</html>
