<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>618-首页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	  	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>js/swiper.min.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>css/swiper.min.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>css/layout.css" /> 
	</head>
	<body style="background: #f7f7f7;">
		<div class="contaner">
			<div class="nva">
	            <div class="swiper-container swiper-container1 swiper-container-horizontal">
	                <div class="swiper-wrapper nav_list">
	                    <div class="swiper-slide nav_top">
	                       <ul>
	                       		<li>全部商品</li>
	                       </ul>
	                    </div>
	                    <div class="swiper-slide nav_top">
	                        <ul>
	                       		<li>潮流数码</li>
	                       </ul>
	                    </div>
	                	<div class="swiper-slide nav_top">
		                    <ul>
	                       		<li>生活电器</li>
	                       </ul>
	                    </div>
	                    <div class="swiper-slide nav_top">
		                    <ul>
	                       		<li>美妆个护</li>
	                       </ul>
	                    </div>
	                    <div class="swiper-slide nav_top">
		                    <ul>
	                       		<li>休闲零食</li>
	                       </ul>
	                    </div>
	                    <div class="swiper-slide nav_top">
		                    <ul>
	                       		<li>家居必备</li>
	                       </ul>
	                    </div>
	                </div>
	            </div>
	        </div>
	        
	        
	        <div class="swiper-container banner swiper-container2 swiper-container-horizontal">
	        	<div class="swiper-wrapper bannerbody">
	                <div class="swiper-slide swiper-slide-prev swiper-slide-duplicate-next">
	                    <img src="<?php echo F::getStaticsUrl('/activity/v2017/sixEight/');?>images/banner1.png">
		            </div>
        		</div>
        		
		        <!--<div class="swiper-pagination swiper-p2 swiper-pagination-bullets">
		        	<span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
		        	<span class="swiper-pagination-bullet"></span>
		        	<span class="swiper-pagination-bullet"></span>
		        </div>-->
	   		</div>
	   		
	   		<div class="hot_pro">
	   			<h5>最热抢购</h5>
	   		</div>
	   		
	   		<div class="swiper-container swiper-container3 swiper-container-horizontal swiper-container-free-mode index_lay">
                <div class="swiper-wrapper pro_list">
    			 	<!--<div class="swiper-slide">
                    	<a href="">
	        				<ul>
	        					<li><img src="images/img_icon.png"/></li>
	        					<li>全能净水器</li>
	        					<li>1999元</li>
	        				</ul>
        				</a>
        			</div>-->
	       		</div>	
			</div>
			
			<div class="chose_box">
				<ul>
					<li class="current-item">专享特惠</li>
					<li>品牌专区</li>
				</ul>
			</div>
			
			<div class="goods-list-warp">
				<!-- 全部  goods-wrap-->
				<div class="all">
					<!--<a href="#">
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
					
					<a href="#">
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
			</div>
			<div class="order_btn"></div>
			<div class="cart_btn"></div>
		</div>
		
		<script type="text/javascript">
			var goods = <?php echo json_encode($goods);?>;
			var goodsOther = <?php echo json_encode($goodsOther);?>;
			var fanPiaoUrl = <?php echo json_encode($fanUrl);?>;
			
			
			var swiper2 = new Swiper('.swiper-container1', {
		//      pagination: '.swiper-pagination',
		        slidesPerView: 5,
		        slidesOffsetBefore :15,
		        paginationClickable: true,
		        spaceBetween: 10,
		        freeMode: true
   		 	});
   		 	
	        var mySwiper1= new Swiper('.swiper-container2',{
	            pagination: '.swiper-p2',
	            autoplay:5000,
	        });
		    setInterval("mySwiper1.slideNext()", 3000);
		
			var swiper3 = new Swiper('.swiper-container3', {
		//      pagination: '.swiper-pagination',
		        slidesPerView: 'auto',
		        slidesOffsetBefore :5,
		        paginationClickable: true,
		        spaceBetween: 1,
		        freeMode: true,
		        observer:true,//修改swiper自己或子元素时，自动初始化swiper
    			observeParents:true//修改swiper的父元素时，自动初始化swiper
   		 	});
   		 	
   		 	$(".chose_box li").click(function(){
				var _index = $(this).index();
				$(this).addClass("current-item").siblings().removeClass("current-item");
				$(".goods-list-warp>div").eq(_index).removeClass("hide").siblings().addClass("hide");
			});
			
	
   		 	
		</script>
		
	</body>
</html>
