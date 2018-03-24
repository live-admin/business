<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>优惠卷详情</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/youhuiquan_new/js/ReFontsize.js');?>"></script>
	    <link href="<?php echo F::getStaticsUrl('/youhuiquan_new/css/layout.css');?>" rel="stylesheet">
        <script src="<?php echo F::getStaticsUrl('/youhuiquan_new/js/jquery-1.11.3.js');?>"></script>
	</head>
	<body>
		<div class="conter conter_coupon">
			<div class="coupon">
                
				<div class="coupon1a">
						<a href="javascript:void(0)">
							<div class="preferential_price1a_box1a">
							       ￥
							</div>
							<div class="preferential_price1a_box2a">
							      <?php echo $couponDetail['amout']?>
							</div>
							<div class="preferential_price1a_box3a"></div>
							<div class="preferential_price1a_box4a">
								<p><?php echo $couponDetail['name']?></p>
								<p><?php if(empty($couponDetail['man_jian'])) echo "限定商品";elseif(empty($couponDetail['limit_product'])) echo "满".$couponDetail['man_jian']."可用";else echo "限定商品"."&nbsp;&nbsp;满".$couponDetail['man_jian']."可用";?></p>
								<p class="p2"><?php echo $couponDetail['use_start_time']?>至<?php echo $couponDetail['use_end_time']?></p>
							</div>
						</a>
				    </div>
				<div class="coupon2a">
					<p><?php if(!empty($couponDetail['limit_product'])) echo "适用商品";else echo "推荐商品";?></p>
                    <a href="javascript:void(0)" class="huan" >换一换</a>
				</div>
                <?php 
                    if(!empty($productDetail)){
                        foreach ($productDetail as $product){
                ?>
                <div class="coupon3a" style="display: none">
					<ul class="coupon4a" style="margin-top: 0;">
						<li class="coupon5a">
							<a href="<?php 
                                if($product['shop_id']==Item::JD_SELL_ID){
                                    echo $jdHref."&pid=".$product['id'];
                                }
                                if($product['shop_id']==Item::HUANQIU_JINGXUAN){
                                    echo $hqHref."&pid=".$product['id'];
                                }
                                if($product['shop_id']==Item::DA_ZHA_XIE){
                                    echo $dzxHref."&pid=".$product['id'];
                                }
                                if($product['shop_id']==Item::CAI_TE_GONG || $product['shop_id']==5011){
//                                    $cheaplogArr=CheapLog::model()->find('goods_id=:goods_id',array(':goods_id'=>$product['id']));
                                    $cheaplogArr = CheapLog::model()->find(array(
                                            'order' => 'id DESC',
                                            'condition' => 'goods_id=:goods_id',
                                            'params' => array(':goods_id'=>$product['id']),
                                          ));
                                    echo $tgHref."&pid=".$cheaplogArr['id'];
                                }
                                
                            ?>">
								<img src="<?php 
                                             $arr=explode(':', $product['good_image']);
                                             if(count($arr)>1){
                                                 echo $product['good_image'];
                                             }else{
                                                 echo F::getUploadsUrl("/images/" . $product['good_image']);
                                             }
                                             
                                           ?>" />
								<div class="coupon3a_right">
									<p><?php echo F::msubstr($product['name'], 0, 25)."...";?></p>
									<p>￥<?php echo $product['customer_price']?></p>
								</div>
							</a>
						</li>
					
						
					</ul>
				 </div>
                <?php 
                    }
                    }
                ?>

				 <div class="coupon_Prompt">
				 	<p>温馨提示：</p>
				 	<p><?php echo $couponDetail['dec']?></p>
				 </div>
			</div>
			
		</div>
        <script type="text/javascript">
        $(function() {

            getshops();
//            $('.share_btn').click(function() {
//                $('.pop_up').show();
//                $(".iphone_pop").show();
//            })
//            $('.cencal').click(function() {
//                $('.pop_up,.pop_up>div').hide();
//            })
            var start = 3;
            var count = $('.coupon3a').children().length;
            if(count<=3){
                $('.huan').text('');
            }
            
            function getshops() {
                for (var i = 0; i < 3; i++)
                {
                    $('.coupon3a').eq(i).show();

                 }
            }
            
            $('.huan').click(function() {
                var end = start + 3;
                if (count <= start) {
                    for (var i = 0; i < count; i++) {
                        $('.coupon3a').eq(i).hide();
                    }
                    getshops();
                    start = 3;
                    return;
                }
                if (start >= 3 && count >= start) {
                    for (var i = 0; i < start; i++) {
                        $('.coupon3a').eq(i).hide();
                    }
                }
                if (count >= start) {
                    for (var i = start; i < end; i++) {
                        $('.coupon3a').eq(i).show();
                    }
                }
                start = end;
            });
        })
    </script>
	</body>
</html>

