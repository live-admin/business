<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>刷脸拼颜值分享页</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>js/share.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
     <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>css/layout.css" />
</head>
<body>
	<header>
		<div class="banner"></div>
	</header>
	<div class="content share_content">
		<ul class="nav">
			<li>排行榜</li>
		</ul>
		<div class="photo_wrap">
			<div class="upload">
			</div>
           	<p class="share_invite">你的好友<span></span><br>邀请你来评论~</p>
		</div>
        
		<!--暂无评论-->
		<div class="no_discuss hide">
			<p><img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/pencil_icon.png">暂无评论</p>
		</div>
        
        <!--有评论-->
		<div class="discuss">
       		<p>
       			<img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/zan_heart.png">
       			<span class="zan_count"></span>位好友点赞,<span class="bad"></span>位好友差评
       		</p>
			<ul>
				
            </ul>
		</div>
        <!--评论区-->
        
        <div class="discuss_area">
        	<form>
                <div class="discuss_wrap">
                    <div class="label">
                        <a herf="javacript:void(0);" class="left">颜值爆表 <br> +1</a>
                        <a herf="javacript:void(0);" class="right">颜值欠佳 <br>-1</a>
                    </div>
                    <p><i class="no-choose"></i>匿名</p>
                    <div class="write">
                        评论：<input type="text" name="write"  value="" class="discuss_val" placeholder="最多可输入35个字" maxlength="35">
                        <a href="javascript:void(0);">发送</a>
                    </div>
                </div>
        	</form>    
        </div>
	</div>
	<!--弹窗-->
	<div class="pop02 hide">
		<div class="pop_wrap">
			<div class="line"></div>
			<div class="detail detail02">
				<div class="top">
					<p><!--您的颜值有点低哦！--></p>
					<p><!--至少达到100分颜值，才能兑换奖品--></p>
				</div>
				<a href="javascript:void(0);"></a>
			</div>
		</div>
	</div>

    <!--遮罩层-->
    <div class="mask hide"></div>
    <script>
    	 var zan_info =<?php echo $data;?>;
    
    </script>
</body>
</html>
