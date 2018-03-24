<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>居家必备，每天都像住新家</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>css/layout.css">
	</head>
	<body>
		<div class="contaner">
			<div class="to_junjia"></div>
			
			<div class="to_intro">
				<div class="to_intro_one">
					<p>地板在我们脚下被“千踏万踹 时时蹂躏”你需要摇给它一些温柔的呵护</p>
					<p>那就让彩之云替你好好爱它吧</p>
				</div>
				<div class="p_line"></div>
				
				<div class="to_intro_two to_intro_two_other">
					<p>碧丽珠木地板护理蜡</p>
					<p>爱它懂它！</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/4.jpg"/>
					<ul>
						<li>
							<p>¥<span>19.90</span></p>
							<p>地板保养专家</p>
						</li>
						<li>
							<a href="javascript:void(0);" class="one">查看详情</a>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				
				
				<!--<div class="p_line"style="visibility: hidden; margin: 0.8rem 0 0rem 0;"></div>
				
				<div class="to_intro_two to_intro_two_other">
					<p style="visibility: hidden;">2 万能备胎-世家 合金平板拖把 </p>
					<p>清洁、滋润做好了，还要给地板封上一层保护膜，这时候就要就要进行打蜡， 打蜡一般1～2个月就要进行一次，一边用海绵或软布给涂蜡 一边抛光，让地板持久亮泽不暗淡。皇宇速亮地板家具防滑蜡就是不错的选择。</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/5.jpg"/>
					<ul>
						<li>
							<p>¥26.90</p>
							<p>防水去污打蜡</p>
						</li>
						<li>
							<a href="javascript:void(0);" class="two">查看详情</a>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				<div class="p_line"></div>
				
				<div class="to_intro_two to_intro_two_other">
					<p>2 历“油”弥新的保养</p>
					<p>对于皮质沙发清洁，应先用干净软布，抹去沙发表面的灰尘，切忌用水擦拭或洗涤防止皮沙发霉变， 而碧丽珠皮革护理剂 就是一款兼具清洁、光泽、保养为一体的多面实力派。它富含硅油、防水、防污、易上光，能在皮具表面形成一层持久的保护膜。皮包、皮衣、皮沙发、都可以使用，是日常护理的理想选择。</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/6.jpg"/>
					<ul>
						<li>
							<p>¥39.00</p>
							<p>清洁去污，硅油保养</p>
						</li>
						<li>
							<a href="javascript:void(0);" class="three">查看详情</a>
						</li>
					</ul>
					<div class="clear"></div>
				</div>-->
			</div>
		</div>
		<script type="text/javascript">
			var goods = <?php echo json_encode($goods);?>;
			var tuanUrl = <?php echo json_encode($tuanUrl);?>;
			$(document).ready(function(){
				$(".one").click(function(){
					location.href = tuanUrl+'&pid='+goods[0].pid;
				});
				$(".two").click(function(){
					location.href = tuanUrl+'&pid='+goods[1].pid;
				});
				$(".three").click(function(){
					location.href = tuanUrl+'&pid='+goods[2].pid;
				});
				
				$(".to_intro_two>p:eq(0)").text(goods[0].name);
				$(".to_intro_two>img").attr("src",goods[0]['imgName']);
				$(".to_intro_two li:eq(0) p span").text(goods[0].price);
				
			});
		</script>
	</body>
</html>
