<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>我的提货券</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/dazhaxie_new/js/ReFontsize.js');?>"></script>
	    <link href="<?php echo F::getStaticsUrl('/dazhaxie_new/css/layout1.css');?>" rel="stylesheet">
	    <script src="<?php echo F::getStaticsUrl('/dazhaxie_new/js/jquery-1.9.1.min.js');?>"></script>
	</head>
	<body>
		<div class="conter  conter_card1">
			<?php
                if(!empty($orderDetailArr)){
            ?>
			<div class="card_tab1">
				<!--提货卷开始-->
                <?php 
                    foreach ($orderDetailArr as $orderDetail){
                ?>
				<div class="card_tab3a">
					<!--001开始-->
               					
                
                    <div class="dv_details_zong dv_details_zong1a">
                         <a href="<?php echo $dzxHref."&order_id=".$orderDetail['order_id'];?>">
                        <div class="dv_details1a">
                            <div class="dv_details1a_top"></div>
                            <div class="dv_details1a_con">
                                <div class="dv_details1a_con_left">
                                    <p><?php echo F::msubstr($orderDetail['name'], 0, 10)."...";?></p>
                                    <p>数量：<?php echo $orderDetail['count']?>张</p>
                                    <p><?php echo DaZhaXie::model()->getOrderThqStatus($orderDetail['order_id'],$uid)?></p>
                                </div>
                                <div class="dv_details1a_con_right">
                                    <div class="dv_details1a_con_right1a">
                                            <img src="<?php echo F::getStaticsUrl('/dazhaxie/images/img_08.png');?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dv_details2a"></div>
                       </a>
                    </div>
			<!--001结束-->
				</div>
                <?php }?>
				<!--提货卷结束-->
			</div>
            <?php }else{?>
               
            <div class="no_thq">
                 <img src="<?php echo F::getStaticsUrl('/dazhaxie_new/images/thq.jpg');?>"/>
                 <p class="p_null">你还没有提货卷哦&sim;</p>
				 <p>购买提货券有很多优惠哦&sim;</p>

            </div>
            <?php }?>
			
		</div>
		
	</body>
</html>

