<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
  <meta name="format-detection" content="telephone=no" />
  <meta name="MobileOptimized" content="320" />
  <title>彩生活住宅</title>
  <link href="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>css/layout.css" rel="stylesheet" type="text/css">
  <script src="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>js/jquery-1.11.3.js"></script>
</head>
<body>
 <div class="container">
  <!--头部开始-->
 
	<header class="header">
		<div class="header_logo">
			<!--<a href="#" class="fl header_icon icon_back"></a>-->
		</div>
		<div class="header_box">
			<h1 class="page_title">彩生活住宅证书</h1>
		</div>
		<!--
		<div class="header_category">
			<a href="#"></a>
			<div class="search_list"></div>
			
		</div>
		-->
	</header>
	<!--头部结束-->
	<?php foreach ($model as $v){	?>
	<div class="line"></div>
	<!--房主信息开始-->
     <div class="housing_information">
			<div class="housing_information1a">
				<div class="housing_information1a_left">
					<img src="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>images/logo.png">
				</div>
				<div class="housing_information1a_right">
					房主信息
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="housing_information2a">
				<table class="table" width="100%" height="" border="0" cellpadding="0" cellspacing="0">
				      <tbody>
				        <tr>
				          <td width="100px"  class="housing_information3a">姓名：</td>
				          <td width="" class="housing_information4a"><?php echo $this->nameFormat($v->owner);?></td>

				        </tr>
				        <tr>
				          <td width="100px" scope="row" class="housing_information3a">身份证号：</td>
				          <td width="" class="housing_information4a"><?php echo $this->idNumberFormat($v->id_number);?></td>
				        </tr>
				         <tr>
				          <td width="100px"  class="housing_information3a">手机号：</td>
				          <td width="" class="housing_information4a"><?php echo $v->mobile;?></td>
				        </tr>
				      </tbody>
             </table>
			</div>
		</div>
	<!--房主信息结束-->
		    <div class="line"></div>
	    <!--饭票信息开始-->
		<div class="housing_information ticket_information_top">
			<div class="housing_information5a">
				<div class="housing_information1a_left">
					<img src="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>images/logo.png">
				</div>
				<div class="housing_information1a_right">
					饭票信息
				</div>
				<div class="clear"></div>
			</div>
		    <div class="ticket_information">
		    	<table class="table" width="100%" height="" border="0" cellpadding="0" cellspacing="0">
				      <tbody>
				        <tr>
				          <td width="120" scope="row" class="ticket_information1a">应返饭票总额：</td>
				          <td width="" class="ticket_information2a"><span><?php echo $v->total_tickets;?></span>元</td>
						</tr>
				        <tr>
				          <td width="120" scope="row" class="ticket_information1a">每月返还饭票：</td>
				          <td width="" class="ticket_information2a"><span><?php echo $v->month_tickets;?></span>元</td>
				        </tr>

				      </tbody>
            	 </table>
		    </div>
		</div>
		<!--饭票信息结束-->
	    <div class="line"></div>
	    <!--房源信息开始-->
	    <div class="housing_information">
			<div class="housing_information5a">
				<div class="housing_information1a_left">
					<img src="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>images/logo.png">
				</div>
				<div class="housing_information1a_right">
					房源信息
				</div>
				<div class="clear"></div>
			</div>
			<div class="housing_information6a">
				<p><?php echo $v->address;?></p>
			</div>
			<div class="housing_information7a">
			   <div class="housing_information8a">
			   	     <img src="<?php echo F::getUploadsUrl("/images/" . $v->house_pic_url); ?>">
			   </div>
			   <div class="housing_information9a">
			   <?php if($v->is_buy=='Y'){?>
				    <img src="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>images/purchased.png">
				<?php }?>
			   </div>
			   <div class="clear"></div>
			</div>
			
		</div>

		<div class="housing_information10a">
		    <table class="table" width="100%" height="" border="0" cellpadding="0" cellspacing="0">
				      <tbody>
				        <tr>
				          <td  class="housing_information11a">户型：</td>
				          <td class="housing_information12a"><?php echo $v->huxing;?></td>
				           <td class="housing_information11a">建筑面积：</td>
				           <td class="housing_information12a"><?php echo $v->total_area;?>㎡</td>
				         
				        </tr>
				         <tr>
				          <td  class="housing_information11a">总价：</td>
				          <td class="housing_information12a"><?php echo $v->total_price;?>万</td>
				           <td class="housing_information11a">规划用途：</td>
				           <td class="housing_information12a"><?php echo $v->useful;?></td>
				          
				        </tr>
				        <tr>
				          <td class="housing_information11a">使用面积：</td>
				          <td  class="housing_information12a"><?php echo $v->use_area;?>㎡</td>
				          <td class="housing_information11a">产权年限：</td>
				          <td  class="housing_information12a"><?php echo $v->effective_age;?>年</td>
				        </tr>
				      </tbody>
             </table>
		</div>
	    <!--房源信息结束-->
		<?php } if(!empty($v->ad_pic_url)){?>
		
		<div class="line line_l"></div>
		<!--广告图片-->
		<div id ="off_btn" class="advertisement_picture">
		 	<img src="<?php echo F::getUploadsUrl("/images/" . $v->ad_pic_url);?>" alt="" style="width:100%">
			<a class="off">
				<img src="<?php echo F::getStaticsUrl('/home/caiZhuZhai/'); ?>images/off.png" >
			</a>
		
		</div>
		<!--广告图片结束-->
		<?php }?>
</div>
	<!--低部结束-->

<script>
		$("#off_btn").click(function(){
			//alert(1);
			$("#off_btn").removeClass("advertisement_picture");
			$("#off_btn").addClass("advertisement_picture_hidden");
			//$("#off_btn").addClass("advertisement_picture_test");
		});
</script>
</div>
</body>
</html>