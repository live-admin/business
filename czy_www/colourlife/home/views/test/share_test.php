<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>饭票卷-订单详情</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/m/ticket/');?>js/ReFontsize.js"></script>
	    <script src="<?php echo F::getStaticsUrl('/m/ticket/');?>js/jquery-1.11.3.js"></script>
	    <script src="<?php echo F::getStaticsUrl('/m/ticket/js/ShareSDK.js');?>"></script>
	    <link href="<?php echo F::getStaticsUrl('/m/ticket/');?>css/layout.css" rel="stylesheet">
	    <script language="javascript" type="text/javascript">

        function showShareMenuClickHandler()
        {
            var u = navigator.userAgent, app = navigator.appVersion;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

            if(isAndroid){
                try{
                    var version = jsObject.getAppVersion();
//                     alert(version);
                }catch(error){
                    //alert(version);
                    if(version !="4.3.6.2"){
                        alert("请到App--我--检查更新,进行更新");
                        return false;
                    }
                }finally {}
                $sharesdk.open("85f512550716", true);
            }

            if(isiOS){
                try{
                    if(getAppVersion && typeof(getAppVersion) == "function"){
                        getAppVersion();
                        var vserion = document.getElementById('vserion').value;
                    }
                }catch(e){

                }

                if(vserion){
                    //alert(vserion);
                    $sharesdk.open("62a86581b8f3", true);
                }else{
                    alert("请到App--我--检查更新,进行更新");
                    return false;
                }
            }

            var params = {
                "text" : "彩之云用户赶紧领取吧！",
                "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
                "url":"http://dwz.cn/8YPIv",
                "title" : "彩之云",
                "titleUrl" : "http://dwz.cn/8YPIv",
                "description" : "描述",
                "site" : "彩之云",
                "siteUrl" : "http://dwz.cn/8YPIv",
                "type" : $sharesdk.contentType.WebPage
            };
            $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {
//                alert("state = " + state + "\nshareInfo = " + shareInfo + "\nerror = " + error);
            });

        };


    </script>
	</head>
	<body>
	<input style="display: none" id="vserion" />
		<div class="mt_Volume to_Details">
			<div class="dv_details_zong dv_details_zong1a">
				<div class="dv_details1a">
					<div class="dv_details1a_top"></div>
					<div class="dv_details1a_con">
						<div class="dv_details1a_con_left">
							<p>彩之云200元饭票卷</p>
							<p>数量：1张</p>
							<p>未使用</p>
						</div>
						<div class="dv_details1a_con_right">
							<div class="mt_Volume_conter_box1a">
								<img src="images/volume_banner03.png">
									<p>￥2000</p>
							</div>
						</div>
					</div>
				</div>
				<div class="dv_details2a"></div>
				<a href="javascript:void(0)"></a>
			</div>
			<div class="to_Details1a">
				<p>饭票卷描述:</p>
				<p>1.200饭票卷相当于200元饭票</p>
				<p>2.饭票卷可赠与他人(购买成功后在饭票卷首页>>订单内进行赠送)</p>
				<p>3.饭票卷可选择退单，退单后，饭票直接充入本人账户</p>
				<p>4.饭票券无有效期</p>
				<p>5.活动结束后，未进行赠与或者退单的用户，饭票券会直接充值到本人饭票账户</p>
			</div>
			<div class="mt_Volume_footer">
				<div class="mt_Volume_footer_box1a">
					<a href="javascript:void(0)">退单</a>
				</div>
				<div class="mt_Volume_footer_box2a">
					<a href="javascript:void(0)">赠送</a>
				</div>
			</div>
	<!--遮罩层开始-->
			<!--退单取消开始-->
			<div class="img_block hide">
				<p>退单成功后，饭票将直接到账</p>
				<div class="img_block_box">
					<div class="img_block_box1a">
						<a href="javascript:void(0)">取消</a>
					</div>
					<div class="img_block_box2a">
						<a href="javascript:void(0)">确认</a>
					</div>
				</div>
        	</div>
        	<!--退单取消结束-->
        	
        	<!--赠送成功开始-->
			<div class="img_block2a hide">
				<p>赠送成功</p>
				<div class="img_block2a_box">
					<div class="img_block2a_box1a">
						<a href="javascript:void(0)">分享</a>
					</div>
				</div>
        	</div>
        	<!--赠送成功结束-->
        	
        	<!--赠送好友开始-->
			<div class="img_block1a hide">
				<div class="img_block1a_p">
					<p>赠送饭票券</p>
				</div>
				<div class="img_block1a_p1a">
					<p>Hi,你可以把这张券送给你的朋友，你的朋友会收到短信提醒。</p>
				</div>
				<input type="text" placeholder="请输入你朋友的电话号码" minlength="1" maxlength="20" class="img_block1a_p2a" />
				<input type="text" placeholder="恭喜发财，大吉大利" minlength="1" maxlength="20" class="img_block1a_p2a" value=""/>
				<div class="img_block1a_p3a">
					<a href="#">
						赠送成功
					</a>
				</div>
        	</div>
        	<!--赠送好友结束-->
        	
    <!--遮罩层结束-->
		</div>
		<div id="mask"></div>
		
		<script>
			$(document).ready(function(){
				  //退单
				$(".mt_Volume_footer_box1a a").click(function(){
					$("#mask").addClass("mask");
					$(".img_block").removeClass("hide");
				});
				//退单弹窗取消
				$(".img_block_box1a a").click(function(){
					$("#mask").removeClass("mask");
					$(".img_block").addClass("hide");
				});
				//退单弹窗确定
				$(".img_block_box2a").click(function(){
					$("#mask").removeClass("mask");
					$(".img_block").addClass("hide");
				});
				  //赠送好友
				$(".mt_Volume_footer_box2a a").click(function(){
					$("#mask").addClass("mask");
					$(".img_block1a").removeClass("hide");
				});
				//增好友弹出信息输入框确定
				$(".img_block1a_p3a a").click(function(){
					$("#mask").removeClass("mask");
					$(".img_block1a").addClass("hide");

					//校验电话号码
					var temp = $(".img_block1a input:eq(0)").val();
					var result = testNum(temp);
					$(".img_block1a input:eq(0)").val("");
					if(!result){
						return false;
					}

					//成功馈赠，弹出分享弹窗
					$("#mask").addClass("mask");
					$(".img_block2a").removeClass("hide")
				});
				//分享按钮
				$(".img_block2a_box1a").click(function(){
					showShareMenuClickHandler();
					$("#mask").removeClass("mask");
					$(".img_block2a").addClass("hide");

					//显示分享的选项
					$("#mask").addClass("mask");
					
				});

				function testNum(temp){
					var a = /^[1]{1}\d{10}$/ ;

					if(!a.test(temp)){
						alert("手机号码输入有误");
						return false;
					}
					return true;
				}

			});
		</script>
		
	</body>
</html>