<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>中奖纪录</title>
		<link href="<?php echo F::getStaticsUrl('/inviteRegister/css/znq.css'); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/inviteRegister/js/jquery.min.js'); ?>"></script>
        <style>
		*{font-size:18px}
		.lotteryimg{margin-top:20px}
		.content .no1{ margin:auto; margin-top:50px; margin-bottom:20px}
		.content .no2{margin:auto; margin-bottom:200px}
		</style>
     </head>

	<body>
		<div class="znq">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_01.png'); ?>" class="lotteryimg" />
			</div>
		<div class="content">
			<h2>前三甲中奖纪录</h2>
		</div>
			
		<div class="rule">
			<div class="rule-table1">
				<table border="0" width="100%" cellpadding="0" cellspacing="1">
						<tr>
						  <th width="19%">序号</th>
						  <th width="27%">用户名</th>
						  <th width="27%">邀请人数</th>
						  <th width="27%">奖励金额</th>
						</tr>
				</table>
                <?php if(!empty($days)){?>
                <?php for ($i=1;$i<=$days;$i++){?>
                    
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <td width="100%" colspan="4"><?php echo date('Y-m-d',strtotime('-'.$i.' day'))?></td>
						</tr>
                        <?php 
                            $resultArr=Invite::model()->getCountSan(strtotime(date('Y-m-d',strtotime('-'.$i.' day'))));
                            $j=1;
                            $jianliArr=array(1=>200,2=>100,3=>50);
                            foreach ($resultArr as $result){
                        ?>
                                <tr>
                                  <td width="19%"><?php echo $j;?></td>
                                  <td width="27%"><?php $customerArr=Customer::model()->findByPk($result['customer_id']);echo $customerArr['name'];?></td>
                                  <td width="27%"><?php echo $result['mycount']; ?></td>
                                  <td width="27%"><?php echo $jianliArr[$j]; ?></td>
                                </tr>
                            <?php $j++;}?>


						</table>
                <?php }?>
                <?php }else{ ?>  
							  <!--<table><tr><td colspan="4">正在统计中，尽情期待!</td></tr></table>-->
							<?php } ?>
			</div>
		</div>
			<table>
				<tr class="rule">
					
					
				</tr>
			</table>
			<p class="record">★注：彩之云享有本次活动在法律范围内的最终解释权</p>
			</div>
		</div>

		
	</body>

</html>