<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>618</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/jingdongCrazy/');?>js/main.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/jingdongCrazy/');?>css/layout.css" />
	</head>

	<body style="background: #e8eaf7;">
		<div class="contanin"> 
			<div class="contanin_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/jingdongCrazy/');?>images/1222.png"/>
			</div>
			
			<div class="contanin_a">
			</div>
		</div>
		
		<script type="text/javascript">
		var jdUrl = "<?php echo $jd_url ?>";
		var data = <?php echo $goodsList ?>;
		initPage(data,jdUrl);
		function initPage(data,jdUrl){
			for (var i = 0; i<data.length/3;i++) { 
				switch(i){
					case 0:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
					case 1:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
					case 2:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
					case 3:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
					case 4:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
					case 5:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
					case 6:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 7:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 8:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 9:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 10:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 11:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 12:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
                    case 13:{
						$(".contanin_a").append('<div class="box"></div>');
						break;
					}
//                    case 14:{
//						$(".contanin_a").append('<div class="box"></div>');
//						break;
//					}
//                    case 15:{
//						$(".contanin_a").append('<div class="box"></div>');
//						break;
//					}
//                    case 16:{
//						$(".contanin_a").append('<div class="box"></div>');
//						break;
//					}
				}
				for (var j = 0; j<3; j++) {
					
					$(".box:eq("+i+")").append(
						'<div class="box_a">'+
							'<a href="'+jdUrl+'&pid='+data[3*i+j].id+'">'+
								'<div class="img_bg">'+
								'<img src="'+data[3*i+j].img_name+'"/>'+
								'</div>'+
								'<p class="p_a">'+data[3*i+j].name+'</p>'+
								'<p class="p_b"><span>¥ </span><span>'+data[3*i+j].price+'</span></p>'+
							'</a>'+
						'</div>'
					);
				}
				
			}	
		};
		
		</script>
		
	</body>
</html>