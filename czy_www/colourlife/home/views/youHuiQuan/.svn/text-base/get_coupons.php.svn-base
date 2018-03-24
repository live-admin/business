<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>领取优惠券</title>
		<link href="<?php echo F::getStaticsUrl('/youhuiquan/css/nts.css');?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
	</head>

	<body>
		<div class="znq">
			<div class="content">
				<!--没有优惠券提示-->
                <?php if(empty($quanDetailArr)){?>
				<div class="no_coupons" style="display: block;">
					<div class="noc_img">
						<img src="<?php echo F::getStaticsUrl('/youhuiquan/images/none.png');?>"/>
					</div>
					<p class="noc_txt">暂时没有优惠券</p>
				</div>
				<!--有优惠券-->
                <?php }else{?>
				<div class="" style="display: block;">
				<!--<p class="today_recommended">今日推荐</p>-->
				<div>
					<ul class="row_ul">
                        <?php foreach ($quanDetailArr as $quanDetail){?>
                        <li onclick="getCoupons(<?=$quanDetail['id']?>)"><img src="<?php echo YouHuiQuanWeb::model()->getLingStatus($userid,$quanDetail['id'],1)?>"/>
                                <div class="txt_fourrow">
                                    <p class="coupons"><?php echo $quanDetail['name']?></p>
                                    <p class="price">¥<span><?php echo $quanDetail['amout']?></span></p>
                                    <p class="price"><?php if(empty($quanDetail['man_jian'])) echo "限定商品可用";else echo "满".$quanDetail['man_jian']."可用"?></p>
                                    <p class="price">有效期：<?php echo $quanDetail['use_start_time'] ?>—<?php echo $quanDetail['use_end_time']?></p>
                                </div>
                            </li>
                        <?php }?>
					</ul>
				</div>
				</div>
                <?php }?>
			</div>
		</div>
		<!--领券弹出框-->
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