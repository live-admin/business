<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>我的提货券</title>

		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/dazhaxie/css/test.css');?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/dazhaxie/js/jquery-1.9.1.min.js');?>" type="text/javascript"></script>
	</head>

	<body>
		<div class="main">
            <?php 
                if(!empty($orderDetailArr)){
            ?>
			<ul class="my_code_ul">
                <?php 
                    foreach ($orderDetailArr as $orderDetail){
                ?>
                <a href="<?php echo $dzxHref."&order_id=".$orderDetail['order_id'];?>">
                    <li class="my_code_head">
                        <table>
                            <tr>
                                <td class="thqxq_img"><img src="<?php echo F::getStaticsUrl('/dazhaxie/images/img_08.png');?>" /></td>
                                <td class="thqxq_content"><?php echo $orderDetail['name'];?>&nbsp;&nbsp;&nbsp;&nbsp;提货券x<?php echo $orderDetail['count']?></td>
                                <td class="thqxq_state"><span class="not_exchange"><?php echo DaZhaXie::model()->getOrderThqStatus($orderDetail['order_id'],$uid)?></span></td>
                            </tr>
                            
                        </table>
                       
                    </li>
                </a>
                    <?php }?>
			</ul>
            <?php }else{?>
               
            <div class="no_thq">
                 <img src="<?php echo F::getStaticsUrl('/dazhaxie/images/thq.png');?>"/>
                 <p>您还没有提货券</p>
            </div>
            <?php }?>
		</div>
	</body>

</html>