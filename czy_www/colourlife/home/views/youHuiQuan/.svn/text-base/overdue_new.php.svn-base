<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>即将过期</title>
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
		<div class="conter conter_overdue">
            <?php if(empty($expireDetailArr)){?>
                    <!--没有优惠券提示-->
                    <div class="no_coupons" style="display:block;">
                        <div class="noc_img">
                            <img src="<?php echo F::getStaticsUrl('/youhuiquan_new/images/coupon_null.jpg');?>"/>
                        </div>
                        <div class="noc_txt1a">
                            <p class="noc_txt2a">你还没有优惠卷哦∼</p>
                            <p class="noc_txt3a">领取优惠卷能享受更多的优惠哦！亲∼</p>
                        </div>
                        <div class="tosee"><a href="/youHuiQuan/getCoupons?userid=<?php echo $userid;?>"><span>去看看</span></a></div>
                    </div>
            <?php }else{?>
                    <?php foreach ($expireDetailArr as $expireDetail){?>
                        <div class="coupon">
                            <div class="coupon1a">
                                    <a href="/youHuiQuan/couponDetail?userid=<?php echo $userid;?>&id=<?php echo $expireDetail['id']?>">
                                        <div class="preferential_price1a_box1a">
                                               ￥
                                        </div>
                                        <div class="preferential_price1a_box2a">
                                              <?php echo $expireDetail['amout']?>
                                        </div>
                                        <div class="preferential_price1a_box3a"></div>
                                        <div class="preferential_price1a_box4a">
                                            <p>京东特供全场通用卷</p>
                                            <p><?php if(empty($expireDetail['man_jian'])) echo "限定商品";elseif(empty($expireDetail['limit_product'])) echo "满".$expireDetail['man_jian']."可用";else echo "限定商品"."&nbsp;&nbsp;满".$expireDetail['man_jian']."可用";?></p>
                                            <p class="p2"><?php echo $expireDetail['use_start_time'];?>至<?php echo $expireDetail['use_end_time'];?></p>
                                        </div>
                                    </a>
                                </div>
                        </div>
                    <?php }?>
            <?php }?>
			
		</div>
	</body>
</html>

