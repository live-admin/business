<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>感恩节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>css/layout.css" rel="stylesheet" />
        <script src="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>js/jquery-1.11.3.js"></script>
	</head>
	<body>  
		<div class="contenr">
			<div class="praise_one">
				<a href="javascript:;"><img src="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>images/button.png"></a>
				<p>(已有<span id="total"><?php echo $total;?></span>人点赞)</p>
				<a href="/NewThanksgiving/Result"><img src="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>images/button1.png"></a>
			</div>
            <div class="img_block hidden">
        		<a></a>
        		<img src="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>images/win.png">
        		<span id="coupon">礼券一张</span>
    		</div>
            <div class="img_block2 hidden">
        		<a></a>
        		<img src="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>images/win02.jpg">
   			</div>
		</div>
        <div id="mask"></div>
        <input type="hidden" id="count" value="<?php echo $luckyTodayCan;?>"/>
        <script>
		//统计点击的次数
		
			$(document).ready(function(){
				
				$(".praise_one").find("a:first").click(function(){
					var count =$("#count").val() ;
					if(count==0){
						var actid=13;
						var prize_level=2;
					  	$.ajax({
					          type: 'POST',
					          url: '/NewThanksgiving/PraiseCreate',
					          data: 'actid='+actid+'&prize_level='+prize_level,
					          dataType: 'json',
					          success: function (result) {
						          
					        	  var str='';
						          if(result.status==1){
							          var d=result.data;
							          str=d.prize_name+'优惠券，：'+d.code;
							          if(d.luckyTodayCan==1){
							        	  $("#count").val(d.luckyTodayCan) ;
								      }
								      $("#total").html(d.total);
							      }else{
								      str=result.data; 
								  }
									$("#mask").addClass("mask");
									$(".img_block").removeClass("hidden");
									$("#coupon").html(str);
									
					          }
					        });
					}else if(count>0){
						$("#mask").addClass("mask");
						$(".img_block2").removeClass("hidden");
					}
				});	
				$(".img_block2 a").click(function(){
					$(".img_block2").addClass("hidden");
					$("#mask").removeClass("mask");
				});
				$(".img_block a").click(function(){
						$(".img_block").addClass("hidden");
						$("#mask").removeClass("mask");
				});
		});
		</script>
	</body>
</html>
