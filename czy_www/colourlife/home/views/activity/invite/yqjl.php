<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>邀请记录</title>
		<link href="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>css/znq.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js"></script>
	</head>

	<body>
		<div class="znq">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_01.png" class="lotteryimg" />
			</div>
			<p class="rule_title">我的邀请记录</p>
			<div class="rule_content">
				<p>您邀请的好友注册详情如下：</p>
				<table>
					<tr>
						<th class="xuhao">序号</th>
						<th>邀请时间</th>
						<th>手机号码</th>
						<th>注册状态</th>
					</tr>
                    <?php
                        if ($records):
                            foreach($records as $key => $row):
                    ?>
					<tr>
						<td><?php echo sprintf('%02d', ($key+1)); ?></td>
						<td><?php echo date('Y-m-d', $row->create_time); ?></td>
						<td><?php echo $row->mobile; ?></td>
						<td><?php echo $row->status ? '注册成功' : '注册中'; ?></td>
					</tr>
                    <?php
                            endforeach;
                        endif;
                    ?>

				</table>
				<p class="instructions">注册状态说明</p>
				<p>注册中：手机号码受邀请后尚未注册成功的</p>
				<p>已注册：手机号码受邀注册成功的</p>
				<p>已失效：手机号码受邀注册已经失效，需要重新注册</p>
				<p>注册无效：未按照活动说明注册的用户</p>
			</div>
            <a href="javascript:history.go(-1);"><div class="return">返回</div></a>
		<p class="record" style="margin-left: 5%;">★注：彩之云享有本次活动在法律范围内的最终解释权</p>
		</div>
	</body>

</html>