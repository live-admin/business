<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>即将过期</title>
		<link href="<?php echo F::getStaticsUrl('/youhuiquan/css/nts.css');?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
	</head>

	<body>
		<div class="znq">
			<div class="my_content">
                <?php if(empty($expireDetailArr)){?>
                    <!--没有优惠券提示-->
                    <div class="no_coupons" style="display:block;">
                        <div class="noc_img">
                            <img src="<?php echo F::getStaticsUrl('/youhuiquan/images/none.png');?>"/>
                        </div>
                        <p class="noc_txt">没有券</p>
                        <p class="noc_txt noc_col">去领券中心看看吧</p>
                        <div class="tosee"><a href="/youHuiQuan/getCoupons?userid=<?php echo $userid;?>"><span>去看看</span></a></div>
                    </div>
                <?php }else{?>
				<!--优惠券列表-->
				<div class="list_coupons" style="display: block;">
					<ul class="row_ul">
                        <?php foreach ($expireDetailArr as $expireDetail){?>
                            <a href='/youHuiQuan/couponDetail?userid=<?php echo $userid;?>&id=<?php echo $expireDetail['id']?>'><li><img src="<?php echo YouHuiQuanWeb::model()->getBackgroup($expireDetail['shop_id'],$expireDetail['is_use'],$expireDetail['use_end_time']);?>"/>
                                <div class="txt_li">
                                    <p class="coupons clearfix"><img src="<?php echo F::getUploadsUrl("/images/" . $expireDetail['type_image']);?>" /><span><?php echo $expireDetail['name']?></span></p>
                                    <p class="price">¥<span><?php echo $expireDetail['amout']?></span></p>
                                    <p class="price"><?php if(empty($expireDetail['man_jian'])) echo "限定商品可用";elseif(empty($expireDetail['limit_product'])) echo "满".$expireDetail['man_jian']."可用";else echo "限定商品可用"."&nbsp;&nbsp;满".$expireDetail['man_jian']."可用";?></p>
                                </div>
                                <div class="right_txt">
                                    <p>到期期限</p>
                                    <p class="txt_red"><?php echo $expireDetail['use_end_time'];?></p>
                                    <p class="ljsy">即将过期</p>
                                </div>
                            </li></a>
                        <?php }?>
					</ul>
				</div>
                <?php }?>
                <div class="mar_down"></div>
				
			</div>
		</div>

	</body>

</html>