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
		  <script rel="script" src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>js/jquery-1.11.3.js"></script>
		  <title>抽奖-中奖榜</title>
		  <link href="<?php echo F::getStaticsUrl('/home/lottery/'); ?>css/layout.css" rel="stylesheet" type="text/css">
		  <link href="<?php echo F::getStaticsUrl('/home/lottery/'); ?>css/media.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="conter">
		    <div class="banner_p">
		    <!--抽奖内容开始-->
		    	<div class="center">
		    		<div class="luck_draw_list">
		    			
		    			<div class="luck_draw_list_top">
		    				<img src="<?php echo F::getStaticsUrl('/home/lottery/'); ?>images/header_banner.jpg" width="100%">
		    				<a href="#">中奖榜</a>
		    			</div>
		    			<div class="luck_draw1a_top">
		    			<?php 
		    			if (!empty($prize)){
		    				foreach ($prize as $val){
								$mem=$val->getWinningMem($val->id);
								if (empty($mem)){
									continue;
								}
		    			?>
			    			<div class="luck_draw_list1a">
			    				<p class="p1"><?php echo $val->grade_name;?>：<?php echo $val->prize_name;?></p>
			    				<div class="luck_draw_list1a1a">
			    					<img src="<?php echo F::getUploadsUrl("/images/" .$val->prize_pic_url); ?>" width="75%">
			    				</div>
			    				<p class="p2">中奖名单</p>
			    				<div class="luck_draw_list3a_top">
			    				<?php foreach ($mem as $v){ 
			    					if ($v['name']==$username){
			    				?>
			    				<p class="highlight">
			    				<?php 
				    				if ($v['type']==2){
										echo $v['name'];?>
										<span><?php echo '('.$v['mobile'].')';?></span>
									<?php
									}else{
										echo $v['username'];?>
										<span><?php echo '('.$v['name'].')';?></span>
										<?php
									}
								?>
			    				</p>
			    				<?php }else{?>
			    					<p>
		    						<?php 
					    				if ($v['type']==2){
											echo $v['name'].'('.$v['mobile'].')';
										}else{
											echo $v['username'].'('.$v['name'].')';
										}
									?>
			    					</p>
			    				<?php }
			    				}?>	
			    				</div>
			    			</div>
			    			<br>
			    			<?php
							}
		    			}
		    			?>
			    		</div>
		    			
		    		
		    		</div>
		    		
		    	</div>
		    <!--抽奖内容结束-->
		    </div>
		</div>
		<script type="text/javascript">
			$(document).ready(function{
				$(".highlight")
				});
		</script>
	</body>
</html>

