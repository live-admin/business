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


        <p class="font_h4">您拒绝了客户经理<?php echo $model->EmployeeName;?>的拜访邀约，预约拜访时间为：<span style="color:#ff7e00;"><?php echo $model->invite_visit_time.' '.$model->invite_visit_hour;?></span>。</p>
        <div class="cross_line"></div>
        <p class="font_h4">请选择您的拒绝理由：</p>
        <div class="address_area">
        <select name="refuse" id="refuse">
            <option value="没时间">没时间</option>
            <option value="不想被打扰">不想被打扰</option>
            <option value="不在家">不在家</option>
            <option value="没必要">没必要</option>
        </select>
            <textarea name="refuse_content" id="refuse_content">没时间</textarea>
        </div>
        <div class="cross_line"></div>
        <div><input type="hidden" id="mid" value="<?php echo $model->id;?>" /></div>
        <div class="btn_contairn btn_contairn_two" style="margin-top:15px;">
            <a href="/visit" class="dark_btn">返回</a>
            <a href="javascript:void(0);" id="reject" class="orange_btn">拒绝</a>
        </div>


    </div>

</div>
<script type="text/javascript">
    //触发
    $("#refuse").change(
        function(){
            var refuse=$(this).val();
            $('#refuse_content').val(refuse);
    });
    //修改
    $('#reject').click(
        function (){
            var type = 'reject';
            var mid = $('#mid').val();
            var refuse = $('#refuse_content').val();
            $.post(
                '/visit/update',
                {'type':type,'mid':mid,'refuse':refuse},
                function (data){
                        location.href = '/visit';
                }
                ,'json');
        }
    );
</script>

	</body>
</html>
