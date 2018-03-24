<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>彩之云客户端</title>
	<link href="<?php echo F::getStaticsUrl('/common/css/mobile.css'); ?>" rel="stylesheet">
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>  
<style>
a{text-decoration:none; color:#9c9b9b;}
.applyfor_interview,.position,.rm_list{width:95%; margin:10px auto;}
.person_info p{margin:10px 0; text-align:left;}
.person_info label{float:left;}
.info_input{width:82%; border:1px solid #b0b0b0; background:#fff; height:25px; line-height:25px;}
.goback{width:100%; display:block; padding:10px 0; background:#cecece; color:#505050; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px;}
.applyfor_interview textarea{width:81%; border:1px solid #b0b0b0; background:#fff; height:90px; margin-top:8px;}
.submitcontairn{padding-top:20px;}
.submit_button{display:block; width:100%; padding:10px 0; border:none; background:#ff7e00; color:#fff; font-size:16px; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px; cursor:pointer;}


.person_info label.sex_select{float:none; margin-right:10px;}
.person_info .span_label{font-size:16px; color:#000000;}
		</style>
	</head>
	<body>
            <div class="main">
            <div class="applyfor_interview recruit_apply">
              <a href="/commerce/view/<?php echo $model->id;?>" class="goback">上一页</a>
              <div class="person_info">
                <p class="span_label">联营申请</p>
                <p>
                  <label>性别：</label>
                  <input type="radio" name="sex" id="male" checked value="1"/><label for="male" class="sex_select">先生</label>
                  <input type="radio" name="sex" id="female" value="2"/><label for="female" class="sex_select">女士</label>
                </p>
                <p>
                  <label>姓名：</label>
                  <input type="text" name="username" class="info_input" />
                </p>
                <p>
                  <label>电话：</label>
                  <input type="text" name="tel" class="info_input" />
                </p>
                <p>
                  <label>留言：</label>
                  <textarea class="userwritedown" name="note"></textarea>
                </p>
              </div>
              <div class="submitcontairn">
                <p style="font-size:12px;" class="bottitle">您的留言会有专业人士跟进，并解答您的以前问题！</p>
                <button class="submit_button">提交申请</button>
              </div>
            </div>
		</div>
        <script>
	    $('.submit_button').click(function(){
                var username = $("[name='username']").val();
                var sex = $("[name='sex']:checked").val();
                var tel = $("[name='tel']").val();
                var note = $(".userwritedown").val();
                var type = <?php echo $model->type; ?>;
                var c_id = <?php echo $model->id; ?>;
                $.post(
                    '/commerce/applyForProcess',
                    {'username':username,'sex':sex,'tel':tel,'note':note,'type':type,'c_id':c_id},
                    function (data){
                        if(data === 1){
                            $('.bottitle').html("申请成功！");
                            $('.submit_button').hide();
                            window.setTimeout("location.href = '/commerce/category'",500);
                        }else{
                            $('.bottitle').html("申请失败！");
                        }
                    }
                ,'json');
                
            });	  
		   
        </script>
	</body>
</html>