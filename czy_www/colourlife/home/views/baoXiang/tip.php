<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>首次获取宝箱弹窗</title>
		<meta content="fullscreen=yes,preventMove=no" name="ML-Config" />
		<meta content="telephone=no,email=no" name="format-detection" />
		<meta content="yes" name="apple-mobile-web-app-capable" />
		<meta content="yes" name="apple-touch-fullscreen" />
		<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
		<script src="<?php echo F::getStaticsUrl('/m/ticket/js/rem.js');?>"></script>
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/baoXiang/');?>css/layout.css">
		<script src="<?php echo F::getStaticsUrl('/m/ticket/js/jquery-1.11.3.js');?>"></script>
    </head>
	<body>
		<div class="conter">
			<div class="conter_tan">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/tan_banner.png">
				<a href="javascript:void(0)" class="cancel"></a>
                <a href="javascript:void(0)" class="lingqu"></a>
			</div>
            <div class="conter_tan1a hide">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/tan_banner02.png">
				<a href="javascript:void(0)" class="cancel"></a>
                <a href="javascript:void(0)" class="guanbi"></a>
			</div>
		</div>
        <input type="hidden" id="mobile" value="<?php echo $mobile;?>">
		<script>
			$(document).ready(function(){
                /*点击领取关闭弹出另一个关闭*/
//                $(".lingqu").click(function(){
//                    $(".conter_tan").addClass("hide");
//                    $(".conter_tan1a").removeClass("hide");
//                 });
                 /*关闭*/
                 $(".guanbi").click(function(){
                      $(".conter_tan1a").addClass("hide");
                      mobileCommand("closeBrowser");
                 });
				$(".cancel").click(function(){
					mobileCommand("closeBrowser");
				});
                $(".lingqu").click(function(){
                    var mobile=$('#mobile').val();
                    var url ="/BaoXiang/GetBaoXiang";
                    $.ajax({ 
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                $(".conter_tan").addClass("hide");
                                $(".conter_tan1a").removeClass("hide");
                            }else{
                                alert(data.msg);
                                return false;
                            } 
                        } 
                    });
                });
                
			});
            function mobileCommand(cmd)
			{
				if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
					var _cmd = "http://colourlifecommand/" + cmd;
					document.location = _cmd;
				} else if (/(Android)/i.test(navigator.userAgent)) {
					var _cmd = "jsObject." + cmd + "();";
					eval(_cmd);
				} else {

				}
			}
		</script>
	</body>
</html>
