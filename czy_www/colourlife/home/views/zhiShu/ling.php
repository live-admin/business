<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>植树节-首页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/zhiShu/js/ReFontsize.js');?>"></script>
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/zhiShu/');?>css/layout.css">
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/zhiShu/');?>css/normalize.css">
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/zhiShu/js/jquery-1.11.3.js');?>"></script>
	</head>
	<body>
		<div class="container">
			<div class="container-img">
				<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy01.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy02.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy03.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy04.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy05.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy06.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy07.jpg"/>
                <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy08.jpg"/>
			</div>
			<div class="container-font">
				<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/sy00.png"/>
			</div>
			<div class="container-a">
				<a href="javascript:void(0)">开始种植</a>
			</div>
		</div>
        <input type="hidden" id="mobile" value="<?php echo $mobile;?>">
        <script>
        //开启宝箱btn
        $(document).ready(function(){
            $(".container-a a").click(function(){
                var mobile=$('#mobile').val();
                var url='/ZhiShu/ZhongZhi';
                $.ajax({
                    type:"POST",
                    url:url,
                    data:'mobile='+mobile,
                    dataType:'json',
                    cache:false,
                    success:function(data){
                        if(data.status==1){
                            window.location.href = "/ZhiShu/index";
                        }else{
                            alert(data.msg);
                            return false;
                        }
                    } 
                });
            });
        });
        </script>
	</body>
</html>
