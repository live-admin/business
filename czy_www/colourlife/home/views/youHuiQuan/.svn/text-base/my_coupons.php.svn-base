<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>我的优惠券</title>
		<link href="<?php echo F::getStaticsUrl('/youhuiquan/css/nts.css');?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
	</head>

	<body>
		<div class="znq">
			<div class="my_content">
				<!--没有优惠券提示-->
                <?php if(empty($detailArr)){?>
                    <div class="no_coupons" style="display: block;">
                        <div class="noc_img">
                            <img src="<?php echo F::getStaticsUrl('/youhuiquan/images/none.png');?>"/>
                        </div>
                        <p class="noc_txt">没有券</p>
                        <p class="noc_txt noc_col">去领券中心看看吧</p>
                        <div class="tosee"><a href="/youHuiQuan/getCoupons?userid=<?php echo $userid;?>"><span>去看看</span></a></div>
                    </div>
                <?php }else{?>
                <div class="header clearfix" style="display: block;"><a href="/youHuiQuan/expire?userid=<?php echo $userid;?>"><img class="left_img" src="<?php echo F::getStaticsUrl('/youhuiquan/images/alert.png');?>"/><span>您有<?php echo YouHuiQuanWeb::model()->getDalayNum($userid,1);?>张优惠券即将过期</span><img class="right_img" src="<?php echo F::getStaticsUrl('/youhuiquan/images/right.png');?>"/></a></div>
				<!--优惠券列表-->
				<div class="list_coupons" style="display: block;">
					<ul class="row_ul">
                        <?php foreach ($detailArr as $detail){?>
                            <a href='/youHuiQuan/couponDetail?userid=<?php echo $userid;?>&id=<?php echo $detail['id']?>'><li><img src="<?php echo YouHuiQuanWeb::model()->getBackgroup($detail['shop_id'],$detail['is_use'],$detail['use_end_time']);?>"/>
                                <div class="txt_li">
                                    <p class="coupons clearfix"><img src="<?php echo F::getUploadsUrl("/images/" . $detail['type_image']);?>" /><span><?php echo $detail['name']?></span></p>
                                    <p class="price">¥<span><?php echo $detail['amout']?></span></p>
                                    <p class="price"><?php if(empty($detail['man_jian'])) echo "限定商品可用";elseif(empty($detail['limit_product'])) echo "满".$detail['man_jian']."可用";else echo "限定商品可用"."&nbsp;&nbsp;满".$detail['man_jian']."可用";?></p>
                                </div>
                                <div class="right_txt">
                                    <p>使用期限</p>
                                    <p><?php echo $detail['use_start_time']?></p>
                                    <p><?php echo $detail['use_end_time']?></p>
                                    <p class="ljsy"><?php if(empty($detail['is_use'])) echo "立即使用";else echo "已使用";?></p>
                                </div>
                                
                            </li></a>
                        <?php }?>
					</ul>
				</div>
                <?php }?>
                    <div class="mar_down"></div>
				
			</div>
		</div>
        <!--<div style="position: fixed;z-index: 999;width: 100%;background: red;color:white;bottom: 0;left: 0;text-align: center;letter-spacing: 1em;font-size: 1.5em;line-height: 40px"><a href="/youHuiQuan/getCoupons?userid=<?php //echo $userid;?>" style="color: white;">领券</a></div>-->
        <div style="position: fixed;z-index: 999;width:18%;right:0;top:70%;"><a href="/youHuiQuan/getCoupons?userid=<?php echo $userid;?>"><img style="width:100%" src="<?php echo F::getStaticsUrl('/youhuiquan/images/img_20.png');?>" /></a></div>
	</body>

</html>