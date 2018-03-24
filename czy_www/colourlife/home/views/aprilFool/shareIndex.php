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
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/jquery-1.11.3.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/flexible.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/layout.css" />
		<style>
			@font-face {
				font-family: fontstyle3;
				src: url('<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>fonts/fontstyle3.ttf');
			}
			
			input::-webkit-input-placeholder {
				font-size: 0.16rem;
				color: #D4D4D4;
				text-indent: 10px;
			}
			
			input::input-placeholder {
				font-size: 0.3rem;
				color: #D4D4D4;
				text-indent: 10px;
			}
		</style>
	</head>

	<body>
		<div class="contaner">
			<div class="contaner-bg">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg01.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg02.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg03.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg04.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg05.jpg" />
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg06.jpg" />
			</div>
			<div class="contaner_top">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/title.png" />
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
				<a href="<?php echo $this->createUrl('list',array('s'=>1));?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/more_btn.png" />
				</a>
			</div>

			<div class="contaner_mess">
				<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/mess.png" />
			</div>

			<div class="contaner_price">
			</div>
			<div class="contaner_price_input">
				<p>参与愚人节活动赢取彩之云限量U盘</p>
			</div>

			<div class="contaner_input_box_message">
				<div class="contaner_input_message">
					<input type="text" placeholder="我想对你说："  maxlength="70" />
				</div>
			</div>
			
			<div class="contaner_input_box contaner_input_box_share">
				<div class="contaner_input">
					<input type="text" placeholder="输入您的手机号码参与抽奖" />
				</div>
				<div class="contaner_input_a">
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/sure_btn.png" />
					</a>
				</div>
			</div>
		</div>
		<!--活动规则-->
			<div class="rule">
				<a href="<?php echo $this->createUrl('rule',array('s'=>1));?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/rule_button.png">
				</a>
			</div>
		<div class="czy_dw">
			<div class="czy_dw_box1a">
				<p>使用彩之云APP玩游戏赢大奖</p>
			</div>
			<div class="czy_dw_box2a">
				<a href="http://mapp.colourlife.com/m.html">下载</a>
			</div>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			//提交留言
			$(".contaner_input_a").click(function(){
				var content = $(".contaner_input_message input").val();
				if(content==''||content==undefined||content==null){
					alert("内容不能为空！");
					return false;
				}
				var telphoneNum = $(".contaner_input input").val();
				if(!numberCheck(telphoneNum)){
					return false;
				}
				//提交内容
				$.ajax({
                    type: 'POST',
                    url: '/AprilFool/ShareLeave',
                    data: 's=1&content='+content+'&mobile='+telphoneNum,
                    dataType: 'json',
                    success: function (data) {
                        if(data.status==1){
                        	window.location.href = '/AprilFool/Share?s=1&n='+data.num;
                        }else if(data.status==2){
							alert(data.msg);
						}else{
							alert(data.msg);
                        }
                    }
                });
			});
			//号码校验
			function numberCheck(temp){
	        	var a=/^[1]{1}[0-9]{10}$/;

	        	if(!a.test(temp))
	            {
	            	alert("手机号码输入格式不对");
	                return false;
	            }

	            return true;
	        }
		});
		</script>
	</body>

</html>