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
        <p class="font_h4">请您对客户经理<?php echo $model->EmployeeName;?>的拜访进行评价。</p>
        <div class="cross_line"></div>
        <div class="checkbox_area">
            <span>
              <input type="checkbox" class="chkbox" name="evaluation_id" value="1" checked="checked"/>
              <label>非常满意</label>
            </span>
            <span>
              <input type="checkbox" name="evaluation_id" value="2" class="chkbox" />
              <label>满意</label>
            </span>
            <span>
              <input type="checkbox" name="evaluation_id" value="3" class="chkbox"  />
              <label>基本满意</label>
            </span>
            <span>
              <input type="checkbox" name="evaluation_id" value="4" class="chkbox"  />
              <label>不满意</label>
            </span>

        </div>
        <div class="cross_line"></div>
        <div class="address_area" style="margin-top:15px;">
            <p style="margin-bottom:5px;">填写评价:</p>
            <textarea name="evaluation_content" id="evaluation_content"></textarea>
        </div>
        <p class="font_h4">如客服经理没有上门拜访，请选择投诉，感谢您对我们工作的支持。<a href="/visit/complain/<?php echo $model->id;?>" style="color:#e00000;">我要投诉</a></p>

        <div><input type="hidden" id="mid" value="<?php echo $model->id;?>" /></div>
        <div style="color: red;" >
            <ul class="showRet">
            </ul>
        </div>
        <div class="btn_contairn btn_contairn_two">
            <a href="/visit" class="dark_btn">返回</a>
            <a href="javascript:void(0);" id="evaluation" class="orange_btn">确定</a>
        </div>
    </div>

</div>
<script type="text/javascript">
    $('.chkbox').click(function(){
        if($(this).attr('checked')=='checked'){
            $('.chkbox').attr('checked',false);
            $(this).attr('checked',true);
        }
    });

    //修改
    $('#evaluation').click(
        function (){
            var type = 'evaluation';
            var mid = $('#mid').val();
            var eid = $("input[name='evaluation_id'][checked]").val();
            var content = $('#evaluation_content').val();
            var noError=true;
            $(".showRet li").remove();
            if(!eid){
                $(".showRet").append("<li>请选择评价</li>");
                noError=false;
            }
            if(noError){
                $.post(
                    '/visit/update',
                    {'type':type,'mid':mid,'eid':eid,'content':content},
                    function (data){
                            location.href = '/visit';

                    }
                    ,'json');
            }
        }
    );
</script>

	</body>
</html>
