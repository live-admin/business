<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>优惠券详情</title>
		<link href="<?php echo F::getStaticsUrl('/youhuiquan/css/nts.css');?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
	</head>

	<body>
		<div class="znq">
			<div class="my_content">
				<div class="header_row clearfix">
                                    <div class="header_left" style="border: 1px red solid;width: 60px;height: 60px;text-align: center;-webkit-border-radius: 30px;margin-left: 1%">
                                        <label style="position: relative;top: 20px;color:red"><span style="font-size: 1em"><?php echo $couponDetail['amout']?></span></label></div>
					<div class="header_content" style="margin-left:2%">
						<p class="header_title" ><?php echo $couponDetail['name']?></p>
						<p><span><?php echo $couponDetail['use_start_time']?>至<?php echo $couponDetail['use_end_time']?></span></p>
					</div>
					<div class="header_right"><img src="<?php echo F::getStaticsUrl('/youhuiquan/images/img_07.png');?>" /></div>
				</div>

				<!--适用商品-->
				<div class="goods_content clearfix">
                    <div class="goods_title clearfix"><span class="row_txt_left"><?php if(!empty($couponDetail['limit_product'])) echo "适用商品";else echo "推荐商品";?></span><span class="row_txt_right">换一换</span></div>
					<div class="goods_list_content">
                        <?php 
                            if(!empty($productDetail)){
                                foreach ($productDetail as $product){
                        ?>
						<div class="goods_list">
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
                                
                            ?>" style="color:#000000;">
							<div class="goods_img"><img src="<?php 
                                             $arr=explode(':', $product['good_image']);
                                             if(count($arr)>1){
                                                 echo $product['good_image'];
                                             }else{
                                                 echo F::getUploadsUrl("/images/" . $product['good_image']);
                                             }
                                             
                                           ?>" /></div>
                                <div class="goods_describe"><?php echo F::msubstr($product['name'], 0, 13)."...";?></div>
							<p class="goods_price"><?php echo $product['customer_price']?></p>
						</a>
                        </div>
                            <?php 
                                }
                                }
                            ?>
					</div>
				</div>

				<!--温馨提示-->
				<div class="prompt clearfix">
					<p>温馨提示</p>
					<p><?php echo $couponDetail['dec']?></p>
				</div>
                <?php 
                    $title=YouHuiQuanWeb::model()->getLingStatus($userid,$couponDetail['id'],2);
                    if($title=="领取优惠券" || $title=="已领取"){
                ?>
                        <div class="get_coupons_btn"><?php echo $title;?></div>
                    <?php }else{?>
                        <div class="get_coupons_btn" style="background: #716D6D;"><?php echo $title;?></div>
                    <?php }?>
			</div>
    <script type="text/javascript">
        $(function() {

            getshops();
            $('.share_btn').click(function() {
                $('.pop_up').show();
                $(".iphone_pop").show();
            })
            $('.cencal').click(function() {
                $('.pop_up,.pop_up>div').hide();
            })
            var start = 6;
            var count = $('.goods_list').length;
            if(count<=6){
                $('.row_txt_right').text('');
            }
            function getshops() {
                for (var i = 0; i < 6; i++) {
                    $('.goods_list').eq(i).show();

                                   }

            }
            $('.row_txt_right').click(function() {
                var end = start + 6;
                if (count < start) {
                    for (var i = 0; i < count; i++) {
                        $('.goods_list').eq(i).hide();
                    }
                    getshops();
                    start = 6;
                    return;
                }
                if (start >= 6 && count >= start) {
                    for (var i = 0; i < start; i++) {
                        $('.goods_list').eq(i).hide();
                    }
                }
                if (count >= start) {
                    for (var i = start; i < end; i++) {
                        $('.goods_list').eq(i).show();
                    }
                }
                start = end;
            })
        })
    </script>
	</body>
</html>