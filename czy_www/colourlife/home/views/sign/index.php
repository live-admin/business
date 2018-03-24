<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>缤纷嘉年华</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/sign/'); ?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/sign/'); ?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/sign/'); ?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/sign/'); ?>css/normalize.css">
</head>
<body>
<div class="content">
    <div class="img_bg">
        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/bg1.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/bg2.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/bg3.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/bg4.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/bg5.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/bg6.jpg"/>
    </div>
    <div class="foot">
        <a href="javascript:void(0);">立即签到</a>
    </div>

<!--遮罩层-->
	<div class="mask hide"></div>
</div>
		<!--弹窗-->
		<!--签到成功-->
	<div class="Pop_suc hide">
	    <div class="Pop_know_bg_img">
	        <img src="<?php echo F::getStaticsUrl('/home/sign/'); ?>images/suc.png"/>
	    </div>
	    <div class="suc">
	        <div class="Pop_know_bg_txt_suc">
	            <p>您已签到成功！</p>
	        </div>
	        <hr style="border: none; height: 1px;width: 100%;background-color: #b2c8d4;" />
	        <div class="sur_btn" id="Pop_sur">
	            <a href="javascript:void(0)">确定</a>
	        </div>
	    </div>
	</div>


<script type="text/javascript">
    var customer_id = "<?php echo $customer_id;?>";
    
  	$(document).ready(function(){
  		
  		var addComp = {"province":"","city":"","district":"","street":"","streetNumber":""};
	
 //判断浏览器是否支持geolocation  
    if(navigator.geolocation){  
     // getCurrentPosition支持三个参数  
     // getSuccess是执行成功的回调函数  
     // getError是失败的回调函数  
     // getOptions是一个对象，用于设置getCurrentPosition的参数  
     // 后两个不是必要参数  
     var getOptions = {  
          //是否使用高精度设备，如GPS。默认是true  
          enableHighAccuracy:true,  
          //超时时间，单位毫秒，默认为0  
          timeout:5000,  
          //使用设置时间内的缓存数据，单位毫秒  
          //默认为0，即始终请求新数据  
          //如设为Infinity，则始终使用缓存数据  
          maximumAge:0  
     };  
   
     //成功回调  
     function getSuccess(position){  
      // getCurrentPosition执行成功后，会把getSuccess传一个position对象  
      // position有两个属性，coords和timeStamp  
      // timeStamp表示地理数据创建的时间？？？？？？  
      // coords是一个对象，包含了地理位置数据 
      //var point = new BMap.Point(116.331398,39.897445); 
        var geoc = new BMap.Geocoder();
        var point = new BMap.Point(position.coords.longitude,position.coords.latitude);
        geoc.getLocation(point, function(rs){
            
            if (addComp =rs.addressComponents) {
//              alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
            }
        });
     }  
     //失败回调  
     function getError(error){  
          // 执行失败的回调函数，会接受一个error对象作为参数  
          // error拥有一个code属性和三个常量属性TIMEOUT、PERMISSION_DENIED、POSITION_UNAVAILABLE  
          // 执行失败时，code属性会指向三个常量中的一个，从而指明错误原因  
        alert("请允许用户使用定位服务，并开启app定位权限"); 
        switch(error.code) { 

            case error.TIMEOUT:  
                console.log("A timeout occured! Please try again!");  
                break;  
            case error.POSITION_UNAVAILABLE:  
                console.log('We can\'t detect your location. Sorry!');  
                break;  
            case error.PERMISSION_DENIED:  
                console.log('Please allow geolocation access for this to work.');  
                break;  
            case error.UNKNOWN_ERROR:  
                console.log('An unknown error occured!');  
                break;  
        }  
     }  
  
     navigator.geolocation.getCurrentPosition(getSuccess, getError, getOptions);  
     // watchPosition方法一样可以设置三个参数  
     // 使用方法和getCurrentPosition方法一致，只是执行效果不同。  
     // getCurrentPosition只执行一次  
    }
    else{
        //不支持geolocation获取位置
        alert("您的浏览器不支持geolocation");
    }
  		
  		
		$(".foot").click(function(){
			
			var sign_location_ = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
			
            $.ajax({
            type:"POST",
            url:'/Sign/Commit',
//          data: {customer_id:customer_id,address:sign_location_},
            data: 'customer_id='+customer_id+'&address='+sign_location_,
            dataType:'json',
            success:function(data){
                if(data.status==1){
                	$(".Pop_know_bg_txt_suc p").text(data.param);
                   	$(".Pop_suc").removeClass("hide");
					$(".mask").removeClass("hide");
                }else{
                    $(".Pop_know_bg_txt_suc p").text(data.param);
                    $(".Pop_suc").removeClass("hide");
                    $(".mask").removeClass("hide");
                }
            }
            });
			
		});
		
		$(".mask,.sur_btn").click(function(){
			$(".Pop_suc").addClass("hide");
			$(".mask").addClass("hide");
		});
	});
</script>


</body>
</html>
