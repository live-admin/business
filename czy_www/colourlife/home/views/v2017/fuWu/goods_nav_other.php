<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>让工作日不再手忙脚乱</title>
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
			<div class="to_img"></div>
			
			<div class="to_intro">
				<div class="to_intro_one">
					<p>新女性的得力之选</p>
					<p>彩生活社区的女神业主是稳妥妥的新时代女性</p>
					<p>就是传说中的：上得了厅堂，下得了厨房，写得了代码，查得出异常，杀得了木马， 翻得了围墙，开得起好车，买得起新房，斗得过二奶，打得过流氓...猴赛雷，最主要的是工作干的漂亮,家里净的亮堂。要说工作忙的昏天暗地，怎么打扫家里呢？那就要得力于以下几种神器了。</p>
				</div>
				
				<div class="p_line"></div>
				<div class="to_intro_two">
					<p>1 颜值才华并存的好伴侣-科沃斯地宝魔镜S扫地机器人</p>
					<p>它管家力Max，却又温柔如兔。你指尖轻触app即开启智能扫拖模式，拥有越野式驱动轮 抓地不打滑轻松过门槛；真空吸口将轻飘毛发收入囊中，自主渗水使拖布适度均匀，清扫更彻底，定时预约，每天清扫不怕忘，自动返回充电。一室清洁让你更期待回家。</p>
					<img src=""/>
					<ul>
						<li>
							<p>¥<span>1099.00</span></p>
							<p>高性价比的清洁单品</p>
						</li>
						<li>
							<a href="javascript:void(0);" class="one">查看详情</a>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				
				<div class="p_line"></div>
				<div class="to_intro_two">
					<p>2 万能备胎-世家 合金平板拖把 </p>
					<p>它是家里的除尘多面手，你是要对付地板或者浴室、清扫玻璃窗或者天花板、还是低矮处、死角里 又或者用它清扫爱车 都是最好的选择。铝合金的材质更有质感、波浪形的双排夹扣更牢固、高强度加厚杆身可承重40kg、双重伸缩锁适用全家人的高度。绝对是一个暖男。</p>
					<img src=""/>
					<ul>
						<li>
							<p>¥<span>1099.00</span></p>
							<p>除尘多面手</p>
						</li>
						<li>
							<a href="javascript:void(0);" class="two">查看详情</a>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				
				<div class="p_line"></div>
				<div class="to_intro_two">
					<p>3 蓝颜知己-小米净化器2</p>
					<p>如果说清扫家居是“物质”的干净，那么空气净化就是“精神”的洗涤，那你就需要一个蓝颜知己来与你同呼吸共命运，小米净化器全新空气增压系统、高效净化滤芯360°进风3层净化，10分钟即可让房间空气焕然一新，让家成为你舒适健康的生活空间。</p>
					<img src=""/>
					<ul>
						<li>
							<p>¥<span>1099.00</span></p>
							<p>净化空气远离PM2.5</p>
						</li>
						<li>
							<a href="javascript:void(0);" class="three">查看详情</a>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
				
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
				
				$(".to_intro_two:eq(0)>p:eq(0)").text(goods[0].name);
				$(".to_intro_two:eq(0)>img").attr("src",goods[0]['imgName']);
				$(".to_intro_two:eq(0) li:eq(0) p span").text(goods[0].price);
				
				$(".to_intro_two:eq(1)>p:eq(0)").text(goods[1].name);
				$(".to_intro_two:eq(1)>img").attr("src",goods[1]['imgName']);
				$(".to_intro_two:eq(1) li:eq(0) p span").text(goods[1].price);
				
				$(".to_intro_two:eq(2)>p:eq(0)").text(goods[2].name);
				$(".to_intro_two:eq(2)>img").attr("src",goods[2]['imgName']);
				$(".to_intro_two:eq(2) li:eq(0) p span").text(goods[2].price);
			});
		</script>
		
	</body>
</html>
