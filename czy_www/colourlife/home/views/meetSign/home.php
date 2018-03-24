<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>扫码领饭票</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/meetSign/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/meetSign/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/meetSign/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/meetSign/');?>css/normalize.css">
</head>
<body style="background: #eeefee;">
<div class="contaner">
    <div class="contaner_txt">
        <p>饭票在彩之云平台上使用，可用于的服务为：</p>
        <p>物管费、停车费、水、电、天然气、网络、装修、维修、租房、旅游、京东购物、大兴汽车、火车票、飞机票、酒店订房等。</p>
    </div>
    <div class="contaner_img">
        <img src="<?php echo F::getStaticsUrl('/home/meetSign/');?>images/qd_bg.png"/>
    </div>

    <div class="contaner_btn">
        <a href="javascript:void(0);">领取饭票</a>
    </div>

    <!--遮罩层-->

</div>
    <div class="mask hide"></div>

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


<script type="text/javascript">
$(document).ready(function(){
    $(".mask,.sur_btn").click(function(){
        $(".Pop_suc").addClass("hide");
        $(".mask").addClass("hide");
    }); 
        /*异步获取后台数据*/
        $(".contaner_btn").click(function(){
			 $.ajax({
				async:true,
				type:"POST",
				url:"/MeetSign/Meal",
				data:"cust_id=<?php echo $cust_id;?>",
				dataType:'json',
				success:function(data){
					if(data.status == 1){  //根据后台返回的状态
						$(".Pop_know_bg_txt_suc p").text(data.param);
					 	$(".Pop_suc").removeClass("hide");
					  	$(".mask").removeClass("hide");
					}
					else if(data.status == 2){
						$(".Pop_know_bg_txt_suc p").text(data.param);
					 	$(".Pop_suc").removeClass("hide");
					  	$(".mask").removeClass("hide");
					  	fa();
					  	
					}
					else{
						$(".Pop_know_bg_txt_suc p").text(data.param);
					 	$(".Pop_suc").removeClass("hide");
					  	$(".mask").removeClass("hide");
					}
				} 
			});
   	 	});
   	 	function fa(){
   	 		$(".sur_btn").click(function(){
   	 			window.location.href = "http://mapp.colourlife.com/m.html";
   	 		});
   	 	}
  });
   
</script>

</body>
</html>
