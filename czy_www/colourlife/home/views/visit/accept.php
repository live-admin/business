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


        <p class="font_h4">您同意了客户经理<?php echo $model->EmployeeName;?>的拜访邀约，预约拜访时间为：<span style="color:#ff7e00;"><?php echo $model->invite_visit_time.' '.$model->invite_visit_hour;?></span>，如需要修改拜访时间，请修改。</p>
        <div class="cross_line"></div>
        <div class="time_area" style="margin-top:15px;">
            <p>
                <label>更改拜访时间:</label>
                <select name="time" id="time">
                    <option value ="<?php echo $model->invite_visit_time;?>"><?php echo date("Y年m月d日",strtotime($model->invite_visit_time));?></option>
                    <option value ="<?php echo date("Y-m-d",strtotime("$model->invite_visit_time +1 day"));?>"><?php echo date("Y年m月d日",strtotime("$model->invite_visit_time +1 day"));?></option>
                    <option value ="<?php echo date("Y-m-d",strtotime("$model->invite_visit_time +2 day"));?>"><?php echo date("Y年m月d日",strtotime("$model->invite_visit_time +2 day"));?></option>
                </select>

            </p>
            <p>
                <label>预约上门时间:</label>
                <select name="hour" id="hour">
                    <option value="10:00-12:00" <?php if($model->invite_visit_hour=="10:00-12:00") echo 'selected';?>>10:00-12:00</option>
                    <option value="15:30-17:30" <?php if($model->invite_visit_hour=="15:30-17:30") echo 'selected';?>>15:30-17:30</option>
                    <option value="18:00-20:30" <?php if($model->invite_visit_hour=="18:00-20:30") echo 'selected';?>>18:00-20:30</option>
                </select>
            </p>
        </div>
        <div class="cross_line"></div>
        <div><input type="hidden" id="mid" value="<?php echo $model->id;?>" /></div>
        <div class="btn_contairn btn_contairn_two" style="margin-top:15px;">
            <a href="/visit" class="dark_btn">返回</a>
            <a href="javascript:void(0);" id="accept" class="orange_btn">接受</a>
        </div>


    </div>

</div>
<script type="text/javascript">
    //修改
    $('#accept').click(
        function (){
            var type = 'accept';
            var mid = $('#mid').val();
            var time = $('#time').val();
            var hour = $('#hour').val();
            $.post(
                '/visit/update',
                {'type':type,'mid':mid,'time':time,'hour':hour},
                function (data){
                        location.href = '/visit';

                }
                ,'json');
        }
    );
</script>

	</body>
</html>
