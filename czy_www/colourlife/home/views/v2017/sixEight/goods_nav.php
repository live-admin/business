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
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	  	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>js/swiper.min.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>js/goods_nav.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>css/swiper.min.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>css/layout.css" /> 
	</head>
	<body>
		<body>
		<section>
			<div class="goods_nva">
            <div class="swiper-container swiper-container1 swiper-container-horizontal">
                <div class="swiper-wrapper nav_list left_last">
                    <div class="swiper-slide jrtgxun">
                       <ul>
                       		<li class="current-item">全部商品</li>
                       </ul>
                    </div>
                    <div class="swiper-slide jrtgxun">
                        <ul>
                       		<li>潮流数码</li>
                       </ul>
                    </div>
                	<div class="swiper-slide jrtgxun">
	                    <ul>
                       		<li>生活电器</li>
                       </ul>
                    </div>
                    <div class="swiper-slide jrtgxun">
	                    <ul>
                       		<li>美妆个护</li>
                       </ul>
                    </div>
                    <div class="swiper-slide jrtgxun">
	                    <ul>
                       		<li>休闲零食</li>
                       </ul>
                    </div>
                    <div class="swiper-slide jrtgxun">
	                    <ul>
                       		<li>家居必备</li>
                       </ul>
                    </div>
                </div>
            </div>
        </div>
			
			
			<div class="goods-nav">
				<!-- 全部  goods-wrap-->
				<div class="all">
					<!--<a href="">
						<div class="list_box">
								<div class="list_box_left">
									<img src="images/pic_img.png"/>
								</div>
								<div class="list_box_right">
									<p>
										<span>Lily 莉莉香水</span><span>新品</span>
									</p>
									<p>Large View香氛  250ml</p>
									<p>¥100.00</p>
								</div>
						</div>
					</a>
					<a href="">
						<div class="list_box">
							<div class="list_box_left">
								<img src="images/pic_img.png"/>
							</div>
							<div class="list_box_right">
								<p>
									<span>Lily 莉莉香水</span><span>新品</span>
								</p>
								<p>Large View香氛  250ml</p>
								<p>¥100.00</p>
							</div>
						</div>
					</a>-->
				</div>
				<!-- 全部 -->
				<div class="fruit hide"></div>
				<div class="rich hide"></div>
				<div class="drink hide"></div>
				<div class="special hide"></div>
				<div class="honse hide"></div>
			</div>
		</section>

		<script>
			var goods = <?php echo json_encode($goods);?>;
			var typeAll = <?php echo json_encode($typeAll);?>;
			var fanPiaoUrl = <?php echo json_encode($fanPiaoUrl);?>;
			$(document).ready(function(){
				var swiper2 = new Swiper('.swiper-container1', {
			//      pagination: '.swiper-pagination',
			        slidesPerView: 5,
			        slidesOffsetBefore :15,
			        paginationClickable: true,
			        spaceBetween: 0,
			        freeMode: true
	   		 	});
			
			
				$(".jrtgxun").click(function(){
					var _index = $(this).index();
					
					$(this).find("li").addClass("current-item");
					$(this).siblings().find("li").removeClass("current-item");
					$(".goods-nav>div").eq(_index).removeClass("hide").siblings().addClass("hide");
				});
			});
			
		</script>
	</body>
</html>
