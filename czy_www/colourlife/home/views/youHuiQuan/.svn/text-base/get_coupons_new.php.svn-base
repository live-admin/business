<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>领取优惠券</title>
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
		<div class="conter conter_Receive_coupons">
            <?php if(empty($quanDetailArr)){?>
				<div class="no_coupons" style="display: block;">
					<div class="noc_img">
						<img src="<?php echo F::getStaticsUrl('/youhuiquan_new/images/coupon_null.jpg');?>"/>
					</div>
					
                    <div class="noc_txt">
                        <p>暂时没有可以领取的优惠券哦∼</p>
                    </div>
				</div>
				<!--有优惠券-->
            <?php }else{?>
            <?php $i=0;?>
			<?php foreach ($quanDetailArr as $quanDetail){?>
			<div class="conter_Receive_coupons1a">
			</div>
			<div class="conter_Receive_coupons2a" onclick="getCoupons(<?=$quanDetail['id']?>)">
				<div class="conter_Receive_coupons2a_box1a">
					 ￥
				</div>
				<div class="conter_Receive_coupons2a_box2a">
					<?php echo $quanDetail['amout']?>
				</div>
				<div class="conter_Receive_coupons2a_box3a">
					<p><?php echo $quanDetail['name']?></p>
					<p style="font-size:0.22rem !important;"><?php if(empty($quanDetail['man_jian'])) echo "限定商品";elseif(empty($quanDetail['limit_product'])) echo "满".$quanDetail['man_jian']."可用";else echo "限定商品"."&nbsp;&nbsp;满".$quanDetail['man_jian']."可用";?></p>
				</div>
				<?php $lingStr=YouHuiQuanWeb::model()->getLingStatus($userid,$quanDetail['id'],2);?>
                <?php if($lingStr=='领取'){?>
                    <a href="javascript:void(0)" ><?php echo YouHuiQuanWeb::model()->getLingStatus($userid,$quanDetail['id'],2)?></a>
                <?php }else{?>
                    <a href="javascript:void(0)" class="already" ><?php echo YouHuiQuanWeb::model()->getLingStatus($userid,$quanDetail['id'],2)?></a>
                <?php }?>    
            </div>
            <?php }?>
            <?php }?>
		</div>
        <div class="get_suc" style="display: none;">
				<div class="get_suc_cot">
				<p>领取成功</p>
				<div class="get_suc_row clearfix"><div class="go_on_btn">继续领取</div><div class="use_btn" >使用该券</div></div>
				</div>
		</div>
        <script type="text/javascript">
				$(function(){
					//继续领取
					$('.go_on_btn').click(function(){
						$('.get_suc').hide();
                        location.reload();
					});
					
				});
                function getCoupons(id){
                    var id=id;
                    var url ="<?=F::getHomeUrl('/youHuiQuan/getYouHuiQuan')?>";
                    $.ajax({ 
                        type:"POST",
                        url:url,
                        data:"id="+id,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.success==1){
                                $('.get_suc').show();
                                //使用该券
                                $('.use_btn').click(function(){
                                        $('.get_suc').hide();
                                        window.location.href="/youHuiQuan/couponDetail?userid=<?php echo $userid;?>&id="+id;
                                });
//                              location.reload();
                            }else{
                                window.location.href="/youHuiQuan/couponDetail?userid=<?php echo $userid;?>&id="+id;
                            } 
                        } 
                    });
                }
               
			</script>
	</body>
</html>


