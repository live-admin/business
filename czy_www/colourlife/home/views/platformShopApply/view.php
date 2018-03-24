<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>平台招商申请-彩之云</title>
		<link href="<?php echo F::getStaticsUrl('/common/css/mobile.css'); ?>" rel="stylesheet">
	</head>
	<body>
		<div class="main">
			<div class="applyfor_interview recruit_apply">

			<div>
              <?php echo $model->description;?>
			</div>

            <div class="btn_contairn btn_contairn_two">
                <a href="/platformShopApply" class="dark_btn">上一步</a>
                <a href="/platformShopApply/showApply?cate_no=<?php echo $model->no;?>" class="dark_btn" style="float:right;">下一步</a>
            </div>
							</div>
		</div>
	</body>
</html>
