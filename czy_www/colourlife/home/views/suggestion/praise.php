<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>APP表扬-彩之云 </title>
		<link href="<?php echo F::getStaticsUrl('/common/css/paltformshop.mobile.css'); ?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
<style>
.info_input,.info_select{width:80%;}
</style>
	</head>
	<body>
		<div class="main">
			<div class="applyfor_interview recruit_apply">
			<div class="person_info">
			<form action="" method="post" onsubmit="return false" id="suggestionForm">
                <h3>
                    APP表扬
                </h3>
				<p>
					<label class="wordlike3">姓名：</label>
					<input name="suggestion[name]" id="name" type="text" value="<?php echo $model->name; ?>" class="info_input" />
				</p>
				<p>
					<label class="wordlike3">电话：</label>
					<input name="suggestion[tel]" id="tel" type="text" value="<?php echo $model->mobile; ?>" class="info_input" />
				</p>
				<p>
					<label class="wordlike3">描述：</label>
					<textarea style="width: 80%;" name="suggestion[content]" id="content" class="info_input"></textarea>
				</p>
                <p style="display: none;">
                    <input name="suggestion[category_id]" id="category_id" type="text" value="2" class="info_input" />
                </p>
			</div>
			<div style="color: red;" >
				<ul class="showRet">
				</ul>
			</div>
			<div class="submitcontairn">
				<button class="submit_button">提交</button>
			</div>
			</form>
		</div>
</div>
<script>
	$(document).ready(function(){
		$(".submit_button").click(function(){
			$(".showRet li").remove();
			var noError=true;
			if($("#name").val().length<1){
				$(".showRet").append("<li>姓名不符合要求</li>");
				noError=false;
			}
			if($("#tel").val().length<11){
				$(".showRet").append("<li>电话不符合要求</li>");
				noError=false;
			}
			if($("#category_id").val().length<1){
				$(".showRet").append("<li>类型不符合要求</li>");
				noError=false;
			}
			if($("#content").val().length<10){
				$(".showRet").append("<li>您的表扬不能少于10个字</li>");
				noError=false;
			}

			if(noError){
				$.ajax({
					   type: "POST",
					   url: "/suggestion/apply",
					   data:$("#suggestionForm").serialize(),
					   dataType:"json",
					   error: function(){
							  alert("请求异常");
						},
					   success: function(ret){
					     if(ret.success==1){
					    	 $(".showRet").append("<li>提交成功，谢谢您的表扬！</li>");
					    	 $(".submit_button").remove();
						 }else{
							for(var key in ret.data.errors){
								$(".showRet").append("<li>"+ret.data.errors[key]+"</li>");
							}
						}
					   }
					});
			}
		});
	});
</script>
	</body>
</html>
