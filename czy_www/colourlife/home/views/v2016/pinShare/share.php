<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>拼出最低价</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/pintuan/js/flexible.js');?>" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/pintuan/js/jquery-1.11.3.js');?>"></script>
	    <!--<script type="text/javascript" src="<?php echo F::getStaticsUrl('/pintuan/js/main_detail.js');?>"></script>-->
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/pintuan/js/share.js');?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/pintuan/css/layout.css');?>" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/pintuan/css/normalize.css');?>">
	    	<style>
	    	//浏览器指示
		        .download {
		            width: 100%;
		            height: 100%;
		            position: absolute;
		            top: 0;
		            left: 0;
		            border:1px red solid;
		        }
		        .download img{
		            width:100%;
		            height: 100%;
		            position: absolute;
		            top: -8%;
		        }
    	   </style>
    	   
	</head>
	<body style="background: #f4f4f4;">
		<div class="contanis">
			<!--头部提示-->
			<div class="tips_box">
				<div class="tips_box_txt">
					<p>每周一早上9点上新! 下单即可</p>
				</div>
				<div class="tips_box_btn">
					<div class="draw_btn">
						<a href="javascript:void(0)">参与抽奖</a>
					</div>
				</div>
				<div class="tips_box_dele">
					<img src="<?php echo F::getStaticsUrl('/pintuan/images/dele.png');?>"/>
				</div>
			</div>
			
			<!--商品-->
			<!--第一个-->
			<div class="product product_a" id="productA">
				<div class="over_time">
					<p>距结束剩余：<span id="count_down"><?php echo $productList['a']['timeLeft'];?></span></p>
				</div>
				<div class="product_box">
						<img src="<?php echo F::getStaticsUrl('/pintuan/images/').$productList['a']['img_name'].'.png';?>"/>
				</div>
				<div class="product_specific_box">
					<div class="product_specific_box_txt">
						<p><?php echo $productList['a']['name']?></p>
					</div>
					<div class="product_specific_box_spec">
						<p><span>￥<?php echo $productList['a']['price']?></span><span>起</span></p>
						<p>[已有<?php echo $productList['a']['orderNum']?>人参团]</p>
					</div>
				</div>
			</div>
			
			<!--第二个-->
			<div class="product" id="productB">
				<div class="over_time">
					<p>距结束剩余：<span id="count_down"><?php echo $productList['b']['timeLeft'];?></span></p>
				</div>
				<div class="product_box">
						<img src="<?php echo F::getStaticsUrl('/pintuan/images/').$productList['b']['img_name'].'.png';?>"/>
				</div>
				<div class="product_specific_box">
					<div class="product_specific_box_txt">
						<p><?php echo $productList['b']['name']?></p>
					</div>
					<div class="product_specific_box_spec">
						<p><span>￥<?php echo $productList['b']['price']?></span><span>起</span></p>
						<p>[已有<?php echo $productList['b']['orderNum']?>人参团]</p>
					</div>
				</div>
			</div>
			
			<!--第三个-->
			<div class="product" id="productC">
				<div class="over_time">
					<p>距结束剩余：<span id="count_down"><?php echo $productList['c']['timeLeft'];?></span></p>
				</div>
				<div class="product_box">
						<img src="<?php echo F::getStaticsUrl('/pintuan/images/').$productList['c']['img_name'].'.png';?>"/>
				</div>
				<div class="product_specific_box">
					<div class="product_specific_box_txt">
						<p><?php echo $productList['c']['name']?></p>
					</div>
					<div class="product_specific_box_spec">
						<p><span>￥<?php echo $productList['c']['price']?></span><span>起</span></p>
						<p>[已有<?php echo $productList['c']['orderNum']?>人参团]</p>
					</div>
				</div>
			</div>
			
			<!--第四个-->
			<div class="product" id="productD">
				<div class="over_time">
					<p>距结束剩余：<span id="count_down"><?php echo $productList['d']['timeLeft'];?></span></p>
				</div>
				<div class="product_box">
						<img src="<?php echo F::getStaticsUrl('/pintuan/images/').$productList['d']['img_name'].'.png';?>"/>
				</div>
				<div class="product_specific_box">
					<div class="product_specific_box_txt">
						<p><?php echo $productList['d']['name']?></p>
					</div>
					<div class="product_specific_box_spec">
						<p><span>￥<?php echo $productList['d']['price']?></span><span>起</span></p>
						<p>[已有<?php echo $productList['d']['orderNum']?>人参团]</p>
					</div>
				</div>
			</div>
			
			<!--商品结束-->
			
			<!--下载-->
			 <div class="btm_bg"></div>
			    <div class="share_footer">
			     	<div class="share_footer_box01">
			     		<img src="<?php echo F::getStaticsUrl('/pintuan/images/czy_logo.png');?>"/>
			     	</div>
			     	<div class="share_footer_box02">
			     		<a href="javascript:void(0)">
			     			立即下载
			     		</a>
			     	</div>
			     	<div class="share_footer_box03">
			     		<a href="javascript:void(0)">
		     				<img src="<?php echo F::getStaticsUrl('/pintuan/images/delet.png');?>"/>
			     		</a>
			     	</div>
		     	</div>
	     	<!--下载结束-->		
			
		<!--遮罩层开始-->
	     <div class="mask hide"></div>
    	 <!--遮罩层结束-->
	     <div class="download hide" style=" width: 100%;height: 100%;position: absolute;top: 0;left: 0;z-index: 10;">
              <img src="<?php echo F::getStaticsUrl('/pintuan/images/download_ios.png');?>"/>
         </div> 
			
		</div>
		
		<script>
			var productList = "<?php echo $productList['d']['timeLeft'];?>";
			
		</script>

	</body>
</html>
