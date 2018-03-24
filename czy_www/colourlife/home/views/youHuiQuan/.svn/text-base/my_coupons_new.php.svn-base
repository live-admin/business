<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>我的优惠券</title>
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
		<div class="conter preferential">
			<div class="card_tab1">
                
				<!--优惠卷开始-->
				<div class="card_tab2a">
                    <?php if(empty($detailArr)){?>
                    <div class="no_coupons" style="display: block;">
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
					<div class="preferential_price">
                        <p><a href="/youHuiQuan/expire?userid=<?php echo $userid;?>">您有<?php echo YouHuiQuanWeb::model()->getDalayNum($userid,1);?>张优惠卷即将过期</a></p>
					</div>
                    <?php $i=0;?>
                    <?php $j=0;?>
                    <?php foreach ($detailArr as $detail){?>
                    <?php $now= time();$end_time_int=strtotime($detail['use_end_time'])+24*60*60;?>
                    <?php if($detail['is_use']==0 && $now<$end_time_int){?>
					<div class="preferential_price1a">
						<a href='/youHuiQuan/couponDetail?userid=<?php echo $userid;?>&id=<?php echo $detail['id']?>'>
							<div class="preferential_price1a_box1a">
							       ￥
							</div>
							<div class="preferential_price1a_box2a">
							      <?php echo $detail['amout']?>
							</div>
							<div class="preferential_price1a_box3a"></div>
							<div class="preferential_price1a_box4a">
								<p><?php echo $detail['name']?></p>
								<p><?php if(empty($detail['man_jian'])) echo "限定商品";elseif(empty($detail['limit_product'])) echo "满".$detail['man_jian']."可用";else echo "限定商品"."&nbsp;&nbsp;满".$detail['man_jian']."可用";?></p>
								<p class="p2"><?php echo $detail['use_start_time']?>至<?php echo $detail['use_end_time']?></p>
							</div>
						</a>
				    </div>
                    <?php }?>
				    <?php if($detail['is_use']==0 && $now>$end_time_int){?>
                    
                    <?php if($i==0){?>
				    <div class="preferential_price2a">
				    	<p>以下是过期的优惠卷</p>
				    </div>
                    <?php $i++;}?>
				    <div class="preferential_price1a1">
						<a href="javascript:void(0)">
							<div class="preferential_price1a_box1a">
							      ￥
							</div>
							<div class="preferential_price1a_box2a">
							      <?php echo $detail['amout']?>
							</div>
							<div class="preferential_price1a_box3a"></div>
							<div class="preferential_price1a_box4a">
								<p><?php echo $detail['name']?></p>
								<p><?php if(empty($detail['man_jian'])) echo "限定商品";elseif(empty($detail['limit_product'])) echo "满".$detail['man_jian']."可用";else echo "限定商品"."&nbsp;&nbsp;满".$detail['man_jian']."可用";?></p>
								<p class="p1"><?php echo $detail['use_start_time']?>至<?php echo $detail['use_end_time']?></p>
							</div>
						</a>
				    </div>
				    <?php }?>
                    <?php if($detail['is_use']==1){?>
                    <?php if($j==0){?>
				     <div class="preferential_price2a">
				    	<p>以下是已使用的优惠卷</p>
				    </div>
                    <?php $j++;}?>
				    <div class="preferential_price1a1">
						<a href="javascript:void(0)">
							<div class="preferential_price1a_box1a">
							      ￥
							</div>
							<div class="preferential_price1a_box2a">
							      <?php echo $detail['amout']?>
							</div>
							<div class="preferential_price1a_box3a"></div>
							<div class="preferential_price1a_box4a">
								<p><?php echo $detail['name']?></p>
								<p><?php if(empty($detail['man_jian'])) echo "限定商品";elseif(empty($detail['limit_product'])) echo "满".$detail['man_jian']."可用";else echo "限定商品"."&nbsp;&nbsp;满".$detail['man_jian']."可用";?></p>
								<p class="p1"><?php echo $detail['use_start_time']?>至<?php echo $detail['use_end_time']?></p>
							</div>
						</a>
				    </div>
                    <?php }?>
                    <?php }?>
				    
				    <div class="preferential_price3a">
				    	<a href="/youHuiQuan/getCoupons?userid=<?php echo $userid;?>"><img src="<?php echo F::getStaticsUrl('/youhuiquan_new/images/receive.jpg');?>"></a>
				    </div>
                    <?php }?>
				   
				</div>
                
				<!--优惠卷结束-->
				
				
			</div>
			
		</div>
		
	</body>
</html>