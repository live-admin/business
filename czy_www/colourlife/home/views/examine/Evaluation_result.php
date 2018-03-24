<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>评价完成</title>

		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl("/home/XieyiApp/css/evaluation.css"); ?>" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl("/home/XieyiApp/js/jquery.min.js"); ?>"></script>
	</head>

	<body>
		<div class="evaluation_content">
			<div class="e_img"><img src="<?php echo F::getStaticsUrl("/home/XieyiApp/img/img_01.png"); ?>" /></div>
			<p class="center_style">评价成功</p>
                        <span class="timeout">3</span>s后结束本次评价</p>
		
			<p class="e_content gray_txt">感谢您对小区的物业管理和服务做出实事求是、客观公平的评价， 我们会根据您的意见和建议不断改进、完善小区管理，为您提供更 加满意的服务。
			</p>
			
			<div id="tiaoyong" class="submit_btn e_up">完成
			<p class="center_style gray_txt" style="letter-spacing: 1px;font-size: 1em;">©2015 深圳市彩生活慈善基金会</p>
			</div>

		</div>
	</body>
        
 	<script type="text/javascript">
			var x=0; 
			$(function() {
				var x=$('.timeout').html(); 
				function countSecond() {
					$('.timeout').html(x--); 
				}
				var a=setInterval(function(){ 
					if(x>0){
						countSecond();
					}else{
						clearInterval(a);
						//结束的动作
                                           closeBrowser();
					}
				}, 1000);
			})
		</script>       
        
<script type="text/javascript">

$("#tiaoyong").click(function(){

	closeBrowser();
});

function closeBrowser(){
	mobileCommand("closeBrowser");
}
function mobileCommand(cmd)
{
	if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
		var _cmd = "http://colourlifecommand/" + cmd;
		document.location = _cmd;
    } else if (/(Android)/i.test(navigator.userAgent)) {
        var _cmd = "jsObject." + cmd + "();";
        eval(_cmd);
    } else {
        
    };
}

</script>
</html>
