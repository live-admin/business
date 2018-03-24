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
		.content .no2{margin:auto; margin-bottom:200px; margin-top:10px}
		.content .no3{font-size:22px}
		</style>
     </head>

	<body>
		<div class="znq">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_01.png'); ?>" class="lotteryimg" />
			</div>
		<div class="content">
			<h2>我的成功邀请</h2>
            <p class="no3">您的成功邀请注册详情如下：</p>
            
			
        </div>
			
			<div class="rule">
				<div class="rule-table">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
						  <th width="19%">序号</th>
						  <th width="27%">邀请时间</th>
						  <th width="27%">手机号码</th>
						  <th width="27%">注册状态</th>
						</tr>
                        <?php if(!empty($records)){ ?>
                        <?php $i=1; ?>  
                        <?php foreach($records as $_v){ ?>
                            <tr>
                              <td width="19%"><?php echo $i; ?></td>
                              <td width="27%"><?php echo date("Y-m-d",$_v['create_time']); ?></td>
                              <td width="27%"><?php echo $_v['mobile']; ?></td>
                              <td width="27%"><?php if($_v['status']==1) echo "成功注册"; else echo "未注册"; ?></td>
                            </tr>
						<?php $i++; ?>
							  <?php } ?>
                    <?php }else{ ?>  
							  <tr><td colspan="4">您还没有邀请好友注册，活动期间，邀请好友成功注册彩之云APP可获得饭票及抽奖机会。赶紧去邀请好友注册吧！</td></tr>
							<?php } ?>
						</table>
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