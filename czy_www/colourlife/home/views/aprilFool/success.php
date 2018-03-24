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
		<script src="<?php echo F::getStaticsUrl('/common/js/invite_reg.js'); ?>" type="text/javascript" ></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/layout.css" />
		<style>
			@font-face {
				font-family: fontstyle3;
				src: url('<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>fonts/fontstyle3.ttf');
			}
		</style>
		<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/');?>js/ShareSDK.js"></script>
	    <script language="javascript" type="text/javascript">
		function showShareMenuClickHandler()
	    {
	        
	        var u = navigator.userAgent, app = navigator.appVersion;
	        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	        
	         if(isAndroid){
	             try{
	                 var version = jsObject.getAppVersion();
	//                 alert(version);
	             }catch(error){
	                  //alert(version);
	                  if(version !="4.3.6.2"){
	                    alert("请到App--我--检查更新,进行更新");
	                    return false;
	                }
	             }finally {}
	            $sharesdk.open("85f512550716", true);
	        }
	        
	        if(isiOS){
	            try{
	                if(getAppVersion && typeof(getAppVersion) == "function"){
	                     getAppVersion();
	                     var vserion = document.getElementById('vserion').value;
	                    }
	                }catch(e){
	                    
	                }
	           
	            if(vserion){
	                //alert(vserion);
	                $sharesdk.open("62a86581b8f3", true); 
	            }else{  
	                alert("请到App--我--检查更新,进行更新");
	                return false;
	             }  
	            }
	        var params = {
     	            "text" : "<?php if (!empty($leaves[0]->content)){echo $leaves[0]->content;}else{echo '彩之云留言';}?>",
     	            "imageUrl" : "<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/logo.png",
     	            "url":"<?php echo F::getHomeUrl('/AprilFool/ShareIndex/'); ?>?s=1&p=<?php echo $ids;?>",
     	            "title" : "<?php if (!empty($leaves[0]->customer_id)){echo '来自'.$nickname.'的留言';}elseif(!empty($leaves[0]->mobile)){echo '来自'.substr_replace($leaves[0]->mobile,"****",3,4).'的留言';}else {?>彩之云留言<?php }?>",
     	            "titleUrl" : "<?php echo F::getHomeUrl('/AprilFool/ShareIndex/'); ?>?s=1&p=<?php echo $ids;?>",
     	            "description" : "<?php if (!empty($leaves[0]->content)){echo $leaves[0]->content;}else{echo '彩之云留言';}?>",
     	            "site" : "彩之云留言",
     	            "siteUrl" : "<?php echo F::getHomeUrl('/AprilFool/ShareIndex/'); ?>?s=1&p=<?php echo $ids;?>",
     	            "type" : $sharesdk.contentType.WebPage
     	        };
	       $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {}); 
	         
	    };
	    </script>
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
						<p><?php if (!empty($userInfo['nickname'])){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
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
						<p><?php if (!empty($userInfo['nickname'])){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
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
						<p><?php if (!empty($userInfo['nickname'])){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
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
						<p><?php if (!empty($userInfo['nickname'])){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
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
						<p><?php if (!empty($userInfo['nickname'])){echo $userInfo['nickname'];}else {echo substr_replace($obj->mobile,"****",3,4);}?></p>
					</div>
				</div>
				<p class="contaner_message_other_content">
					<?php echo $obj->content;?>
				</p>
			</div>
			<?php }	?>	
			<div class="contaner_morebtn">
				<a href="<?php echo $this->createUrl('list');?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/more_btn.png" />
				</a>
			</div>

			<div class="start_btn goto_btn">
				<a href="<?php echo $this->createUrl('nextLeave');?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/goto.png" />
				</a>
			</div>

			<div class="start_btn shark_btn">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/share.png" />
				</a>
			</div>

			<div class="start_btn draw_btn">
				<a href="<?php echo $this->createUrl('lottery');?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/draw.png" />
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

			<div class="contaner_price send">
			</div>
			<div class="contaner_price_input input_send">
				<p>你有<span class="num"><?php echo $num;?></span>次抽奖机会，分享朋友圈获得抽奖机会</p>
			</div>

		</div>
		<input type="hidden" value="<?php echo F::getHomeUrl(); ?>" class="h_url"/>
		<input style="display: none" id="vserion" />
		<script type="text/javascript">
		$(document).ready(function(){
			$(".shark_btn a").click(function(){
				showShareMenuClickHandler();
				setTimeout(shareJs,5000);
			});

			function shareJs(){
				$.ajax({
                    type: 'POST',
                    url: '/AprilFool/Log',
                    data: 'type=2',
                    dataType: 'json',
                    success: function (data) {
                        if(data.status==1){
                        	$(".num").text(data.num);
                        }
                    }
                });
			}
		});
		</script>
	</body>

</html>