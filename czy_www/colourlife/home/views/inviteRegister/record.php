<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>邀请记录</title>
		<link href="<?php echo F::getStaticsUrl('/inviteRegister/css/znq.css'); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/inviteRegister/js/jquery.min.js'); ?>"></script>
	</head>

	<body>
		<div class="znq">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_01.png'); ?>" class="lotteryimg" />
			</div>
			<p class="rule_title">我的邀请记录</p>
			<div class="rule_content">
				<p>您邀请的好友注册详情如下：</p>
				<table>
					<thead>
				          <tr>
				            <th class="xh">序号</th>
				            <th class="yqsj">邀请时间</th>
				            <th>手机号码</th>
				            <th>注册状态</th>
				          </tr>
				    </thead>
                    <tbody>
				          <?php if(!empty($records)){ ?>  
				              <?php $i=1; ?>
				              <?php foreach($records as $_v){ ?>
				              <tr>
				                <td><?php echo $i; ?></td>
				                <td><?php echo date("Y-m-d H:i:s",$_v->create_time); ?></td>
				                <td><?php echo $_v->mobile; ?></td>
				                <td>
				                      <?php if($_v->status == 1 && $_v->effective == 1){ ?>
				                             已注册
				                      <?php }else if($_v->status == 0 && (time() <= $_v->valid_time) ){ ?>
				                             注册中
				                      <?php }else if($_v->status == 0 && (time() > $_v->valid_time)){ ?>
				                             已失效
				                      <?php }else{ ?>
				                             邀请无效
				                      <?php } ?>
				              </td>
				              </tr>
				              <?php $i++; ?>
				              <?php } ?>    
				          <?php }else{ ?>
				               <tr><td colspan="4">您还没有邀请好友注册，活动期间，邀请好友成功注册彩之云APP可获得饭票及抽奖机会。赶紧去邀请好友注册吧！</td></tr>                           
				          <?php } ?>  
				        </tbody>
                    
				</table>
				<p class="instructions">注册状态说明</p>
				<p>注册中：手机号码受邀请后尚未注册成功的</p>
				<p>已注册：手机号码受邀注册成功的</p>
				<p>已失效：手机号码受邀注册已经失效，需要重新注册</p>
				<p>邀请无效：未按照活动说明注册的用户</p>
			</div>
		<a href="javascript:history.back();"><div class="return">返回</div></a>
		<p class="record">★注：彩之云享有本次活动在法律范围内的最终解释权</p>
		</div>
	</body>

</html>