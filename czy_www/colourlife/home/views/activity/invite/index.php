<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>注册邀请送饭票</title>
		<link href="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>css/znq.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js"></script>
	</head>

	<body>
		<div class="znq">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_01.png" class="lotteryimg" />
			</div>
		<div class="content">
			<img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_02.png" class="center_samll" />
			<p class="txt_h3">云海天城世家前1600名彩之云新注册用户送5元饭票</p>
            <p><span>请在”我“ 我的饭票中点击 ”收入的饭票“查看</span></p>
			<p class="beizhu">（记得填写小区信息，注册在体验区可没有礼包领哦）</p>
			<img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_03.png" class="center_samll"/>
			<div class="input_row">
				<input type="tel" placeholder="请输入电话号码" id="tel" /><span class="invite">邀请好友</span></div>
			<div class="img_row clearfix">
				<a href="/activity/inviteLog?cust_id=<?php echo $cust_id; ?>"><img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_04.png" /></a>
				<a href="/activity/inviteSuccess?cust_id=<?php echo $cust_id; ?>"><img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_05.png" /></a>
				<a href="/activity/inviteInfo?cust_id=<?php echo $cust_id; ?>"><img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/img_06.png" /></a>

			</div>
			<h3>温馨提示</h3>
			<div class="rule">
			<p>1.活动时间：2015年8月6日至2015年10月31日。</p>
			<p>2.通过“邀请好友送饭票”活动页面或“我”—“邀请好友”页面成功邀请好友视为有效参与活动。</p>
			<p>3.前800名被邀请并成功注册彩之云APP的用户，其邀请者可获赠5元饭票。</p>
			<p>4.体验区用户不能参与注册送饭票活动。</p>
			</div>
			<table>
				<tr class="rule">
					<td><p>好友如何注册彩之云：</p>
						<p>1.复制下载链接发送给TA：</p>
						<p><a href="http://dwz.cn/8YPlv">http://dwz.cn/8YPlv</a></p>
						<p>2.扫一扫，没烦恼，让TA扫码下载吧！</p>
					</td>
					<td class="table_img"><img src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/cord.png"/></td>
				</tr>
			</table>
			<p class="record">★注：彩之云享有本次活动在法律范围内的最终解释权</p>
			</div>
		</div>
			
		
		<!--弹出框-->
		<!--已经注册-->
		<div class="pop_up" style="display: none;">
			<div class="iphone_pop samsung" style="display: none;">
				<div class="close_row clearfix">
					<img class="close" src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/close.png" />
				</div>
				<p class="hint">温馨提示</p>
				
				<div class="select_type fail">
					很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。
                </div>
				<div class="know">关闭</div>
			</div>
		
		<!--手机号码格式不对-->
			<div class="iphone_pop tel_type" style="display: none;">
				<div class="close_row clearfix">
					<img class="close" src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/close.png" />
				</div>
				<p class="hint">温馨提示</p>
				
				<div class="select_type">
					您输入的手机号码格式不对</div>
				<div class="know">关闭</div>
			</div>
		
		<!--邀请成功-->
			<div class="iphone_pop invite_suc" style="display: none;">
				<div class="close_row clearfix">
					<img class="close" src="<?php echo F::getStaticsUrl('/home/activity/invite/'); ?>images/close.png" />
				</div>
				<p class="hint">温馨提示</p>
				
				<div class="select_type">
					恭喜您已成功邀请了您的好友复制下载链接发送给你的好友吧！
				<p style="color: #11a9f0;">http://dwz.cn/8YPIv</p></div>
				<div class="know">关闭</div>
			</div>
		</div>
		<script type="text/javascript">
			$(function() {
				$('.invite').click(function() {
					$('.pop_up').show();

                    var tel = $('#tel').val();
                    var reg = /^(1[3456789])[0123456789]{9}$/;
                    if ( reg.test(tel) ) {
                        $.ajax({
                            url : '/activity/invite',
                            type : 'POST',
                            data: {'mobile':tel},
                            dataType : 'JSON',
                            async : false,
                            success: function(data){
                                if ('success' == data.code) {
                                    $(".invite_suc").show();
                                }
                                else {
                                    $('.fail').text(data.code);
                                    $(".samsung").show();
                                }
                            }
                        });
                    }
                    else {
                        $(".tel_type").show();
                    }
				});

				//关闭窗口
				$('.close').click(function() {
					$('.pop_up,.pop_up>div').hide();
				});
				//关闭窗口
				$('.know').click(function() {
					$('.pop_up,.pop_up>div').hide();
				});
				//点击
				//			$('.pop_up').click(function(e) {
				//				var obj = e.srcElement || e.target;
				//				if ($(obj).is('.pop_up')) {
				//					$('.pop_up,.pop_up>div').hide();
				//				}
				//			});
			})
		</script>
		
	</body>

</html>