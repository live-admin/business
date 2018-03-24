<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩之云留言墙</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/jquery-1.11.3.js" ></script>
		<script src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/flexible.js" type="text/javascript" ></script>
		<script src="<?php echo F::getStaticsUrl('/common/js/invite_reg.js'); ?>" type="text/javascript" ></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/layout.css"/>
		<style>
			@font-face {
				font-family:fontstyle3;
				src: url('<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>fonts/fontstyle3.ttf');
			}
		</style>
	</head>
	<body>
		<div class="contaner">
			<div class="contaner-bg">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg01.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg02.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg03.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg04.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg05.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg06.jpg"/>
			</div>
			<div class="contaner_top">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/title.png"/>
			</div>
			<?php if (!empty($leaves[0])){
				$obj=$leaves[0];
				$userInfo=AprilFool::model()->getUserInfo($obj->customer_id);
			?>
			<div class="contaner_message_me" id="message01">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/message.png"/>
				<div class="contaner_message_box">
					<div class="contaner_message_box_head">
						<img src="<?php if(!empty($userInfo['portrait'])){echo F::getUploadsUrl("/images/" . $userInfo['portrait']);}else {echo F::getStaticsUrl('/common/images/nopic.png');}?>"/>
					</div>
					<div class="contaner_message_box_name">
						<p><?php if (!empty($obj->customer_id)){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
					</div>
				</div>
				<p class="contaner_message_me_content">
					<?php echo $obj->content;?>
				</p>
			</div>
			<?php 
			}
			 if (!empty($leaves[1])){
				$obj=$leaves[1];
				$userInfo=AprilFool::model()->getUserInfo($obj->customer_id);
			?>		
			<div class="contaner_message_other" id="message02">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/message.png"/>
				<div class="contaner_message_box_other">
					<div class="contaner_message_box_head_other">
						<img src="<?php if(!empty($userInfo['portrait'])){echo F::getUploadsUrl("/images/" . $userInfo['portrait']);}else {echo F::getStaticsUrl('/common/images/nopic.png');}?>"/>
					</div>
					<div class="contaner_message_box_name_other">
						<p><?php if (!empty($obj->customer_id)){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
					</div>
				</div>
				<p class="contaner_message_other_content">
					<?php echo $obj->content;?>
				</p>
			</div>
			<?php 
			}
			 if (!empty($leaves[2])){
				$obj=$leaves[2];
				$userInfo=AprilFool::model()->getUserInfo($obj->customer_id);
			?>	
			<div class="contaner_message_other other_b" id="message03">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/message.png"/>
				<div class="contaner_message_box_other">
					<div class="contaner_message_box_head_other">
						<img src="<?php if(!empty($userInfo['portrait'])){echo F::getUploadsUrl("/images/" . $userInfo['portrait']);}else {echo F::getStaticsUrl('/common/images/nopic.png');}?>"/>
					</div>
					<div class="contaner_message_box_name_other">
						<p><?php if (!empty($obj->customer_id)){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
					</div>
				</div>
				<p class="contaner_message_other_content">
					<?php echo $obj->content;?>
				</p>
			</div>
			<?php 
			}
			 if (!empty($leaves[3])){
				$obj=$leaves[3];
				$userInfo=AprilFool::model()->getUserInfo($obj->customer_id);
			?>	
			<div class="contaner_message_other other_c" id="message04">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/message.png"/>
				<div class="contaner_message_box_other">
					<div class="contaner_message_box_head_other">
						<img src="<?php if(!empty($userInfo['portrait'])){echo F::getUploadsUrl("/images/" . $userInfo['portrait']);}else {echo F::getStaticsUrl('/common/images/nopic.png');}?>"/>
					</div>
					<div class="contaner_message_box_name_other">
						<p><?php if (!empty($obj->customer_id)){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
					</div>
				</div>
				<p class="contaner_message_other_content">
					<?php echo $obj->content;?>
				</p>
			</div>
			<?php 
			}
			 if (!empty($leaves[4])){
				$obj=$leaves[4];
				$userInfo=AprilFool::model()->getUserInfo($obj->customer_id);
			?>	
			
			<div class="contaner_message_other other_d" id="message05">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/message.png"/>
				<div class="contaner_message_box_other">
					<div class="contaner_message_box_head_other">
						<img src="<?php if(!empty($userInfo['portrait'])){echo F::getUploadsUrl("/images/" . $userInfo['portrait']);}else {echo F::getStaticsUrl('/common/images/nopic.png');}?>"/>
					</div>
					<div class="contaner_message_box_name_other">
						<p><?php if (!empty($obj->customer_id)){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
					</div>
				</div>
				<p class="contaner_message_other_content">
					<?php echo $obj->content;?>
				</p>
			</div>
			<?php }	?>	
			<div class="contaner_morebtn">
				<a href="<?php echo $this->createUrl('list');?>">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/more_btn.png"/>
				</a>
			</div>
			
			<div class="contaner_mess contaner_mess_index">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/mess.png"/>
			</div>
			
			<div class="contaner_price contaner_price_indexb">
			</div>
			<div class="contaner_price_input contaner_price_indexa">
					<p>参与愚人节活动赢取彩之云限量U盘</p>
			</div>
			
			<div class="start_btn start_btn_index">
				<a href="<?php echo $this->createUrl('leave');?>">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/yz.png"/>
				</a>
			</div>
			
			
			<!--活动规则-->
			<div class="rule">
				<a href="<?php echo $this->createUrl('rule');?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/rule_button.png">
				</a>
			</div>
			
			<!--邀请注册-->
			<div class="reg">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/reg.png">
				</a>
			</div>

		</div>
		<input type="hidden" value="<?php echo F::getHomeUrl(); ?>" class="h_url"/>
	</body>
</html>