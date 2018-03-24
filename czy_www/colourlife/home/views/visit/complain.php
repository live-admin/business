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
        <p class="font_h4">为了更好监督客户经理的工作，您确定<span style="color:#ff7e00;">客户经理<?php echo $model->EmployeeName;?></span>没有进行过上门拜访，投诉请您点击投诉按钮。</p>
        <div class="cross_line"></div>

        <div><input type="hidden" id="mid" value="<?php echo $model->id;?>" /></div>

        <div class="btn_contairn btn_contairn_two">
            <a href="/visit/evaluation/<?php echo $model->id;?>" class="dark_btn">返回</a>
            <a href="javascript:void(0);" id="complain" class="orange_btn">投诉</a>
        </div>
    </div>

</div>
<script type="text/javascript">
    //修改
    $('#complain').click(
        function (){
            var type = 'complain';
            var mid = $('#mid').val();
            $.post(
                '/visit/update',
                {'type':type,'mid':mid},
                function (data){
                    location.href = '/visit';

                }
                ,'json');

        }
    );
</script>

</body>
</html>
