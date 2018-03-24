<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>客服拜访-彩之云 </title>
		<link href="<?php echo F::getStaticsUrl('/common/css/visit.css'); ?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
	</head>
	<body>
<div class="main">

    <div class="invite">
        <h3>历史邀请清单</h3>
        <div class="cross_line"></div>
        <div class="invite_people_box">
            <table class="invite_people">
                <thead>
                <tr>
                    <th style="width:80px;">预约拜访时间 </th>
                    <th style="width:145px;">拜访内容 </th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
            <?php

                foreach($models as $model){
                    echo '
                    <tr>
                    <td>'.$model->invite_visit_time.'<br />'.$model->invite_visit_hour.'</td>
                    <td><p class="align_left">'.$model->visit_content.'</p></td>
                    <td><a href="/visit/view/'.$model->id.'" class="invite_it">查看</a></td>
                    </tr>
                    ';
                }

            ?>

                </tbody>
            </table>
        </div>
        <div class="btn_contairn">
            <a href="/visit" class="dark_btn">返回</a>
        </div>
    </div>

</div>

	</body>
</html>
