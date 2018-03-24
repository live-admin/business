<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>饭票</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/meetSign/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/meetSign/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/meetSign/');?>css/draw.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/meetSign/');?>css/normalize.css">
    	<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/meetSign/');?>css/new1.css">
	</head>
	<body>
		<div class="contaner">
		    <div class="contaner_txt">
		    	<img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/bg1.jpg"/>
		    	<img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/bg2.jpg"/>
		    	<img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/bg3.jpg"/>
		    	<img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/bg4.jpg"/>
		    	<img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/bg5.jpg"/>
		    	<img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/bg6.jpg"/>
		    </div>
		
		    <div class="contaner_btn">
		        <a href="javascript:void(0);">饭票领取</a>
		    </div>
		     <div class="mask hide"></div>
		</div>
	<!--遮罩层-->
   
	<!--弹窗-->
	<div class="Pop_suc hide">
	    <div class="Pop_know_bg_img">
	        <img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/suc.png"/>
	    </div>
	    <div class="suc">
	        <div class="Pop_know_bg_txt_suc">
	            <p>您已签到成功！</p>
	        </div>
	        <hr style="border: none; height: 1px;width: 100%;background-color: #b2c8d4;" />
	        <div class="sur_btn">
	            <a href="javascript:void(0)">确定</a>
	        </div>
	    </div>
	</div>
	
	<div class="loaders hide">
	 	 	<div class="loader">
		    	<div class="loader-inner ball-pulse">
			      	<div></div>
			      	<div></div>
			      	<div></div>
			    </div>
		  	</div>
	</div>


<script type="text/javascript">

	$(document).ready(function(){
		document.addEventListener('DOMContentLoaded', function () {
	  	document.querySelector('.main').className += 'loaded';
		});
	});
	
	var cust_id = <?php echo $cust_id;?>;
	var code = <?php echo $code;?>;

$(document).ready(function(){
    
        /*异步获取后台数据*/
        $(".contaner_btn").click(function(){
			$(".loaders").removeClass("hide");
        	$(".mask").removeClass("hide");

		 	$.ajax({
				async:true,
				type:"POST",
				url:"/MeetSign/Meal",
				data:{
					'cust_id':cust_id,
					'code':code
				},
				dataType:'json',
				success:function(data){
					if(data.status == 1){  //根据后台返回的状态
						$(".loaders").addClass("hide");
						$(".Pop_know_bg_txt_suc p").text(data.param);
					 	$(".Pop_suc").removeClass("hide");
					  	$(".mask").removeClass("hide");
					  
					}
					else if(data.status == 2){
						$(".loaders").addClass("hide");
						$(".Pop_know_bg_txt_suc p").text(data.param);
					 	$(".Pop_suc").removeClass("hide");
					  	$(".mask").removeClass("hide");
					  	
					}
					else{
						$(".loaders").addClass("hide");
						$(".Pop_know_bg_txt_suc p").text(data.param);
					 	$(".Pop_suc").removeClass("hide");
					  	$(".mask").removeClass("hide");
					}
				} 
			});
   	 	});
   	 	
	 	$(".mask,.sur_btn").click(function(){
 		 	$(".contaner_btn").bind("click"); 
	        $(".Pop_suc").addClass("hide");
	        $(".mask").addClass("hide");
	       
	    }); 
  });
   
</script>		
	</body>
</html>
