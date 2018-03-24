<!DOCTYPE html>
<html>


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>支付成功</title>
		<link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/nts.css'); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/js/jquery.min.js'); ?>"></script>
		<script type="text/javascript">
		var sn='<?php  echo Yii::app()->request->getParam('sn');  ?>';

//		$(function(){
//
//			$.ajax({
//			type:'POST',
//			url:'/YiNengYuan/UpdateStatus',
//			dataType:'json',
//			data:{
//				sn:sn
//			},
//			success:function(data){
//
//				if(data==0){
//
//				document.location='buy_detail_ytk?sn='+sn+'&flag='+data;
//				}
//
//				if(data==1){
//					$.ajax({
//					type:'POST',
//					url:'/YiNengYuan/UpdateStatus2',
//					dataType:'json',
//					data:{
//					sn:sn
//					}
//					});
//
//
//				}
//
//			},
//			error:function(){
//
//
//			}
//
//
//
//			});
//
//
//		});



		</script>
	</head>






	<body>

		<div class="znq" style="overflow: hidden;">
			<div class="content">
				<div class="pay_suc"><img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/right.png'); ?>"/></div>
				<p class="pay_suc_title">支付成功</p>
				<div class="pay_suc_content">
                                    <p>订单号码：<?php echo Yii::app()->request->getParam('sn');
                                        $model=OthersFees::model();
                                        $result=$model->find('sn=:sn',array(':sn'=>Yii::app()->request->getParam('sn')));
                                        ?></p>
                                        <p>订单类型：<?php if($result->model=="PowerFees"){echo "购电";}   ?></p>
					<p>充值金额：<?php echo $result->amount; ?>元</p>
					<p>红包抵扣：<?php echo $result->red_packet_pay; ?>元</p>
					<p>实付金额：<?php
                                        $model=Pay::model();
                                        $result=$model->find('id=:id',array(':id'=>$result->pay_id));
										if($result!=null)
                                        echo $result->amount;
                                        ?>元</p>
				</div>

				<a href="buy_detail_ytk?sn=<?php echo Yii::app()->request->getParam('sn');?>"><div class="comfirm_recharge">查看购电码</div></a>
			</div>
		</div>

	</body>

</html>