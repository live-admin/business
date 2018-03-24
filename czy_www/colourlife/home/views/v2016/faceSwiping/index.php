<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>刷脸拼颜值首页</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>js/index.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>plugin/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>css/layout.css">
</head>
<body>
	<input style="display: none" id="vserion" />
	<header>
		<div class="banner"></div>
	</header>
	<div class="content">
		<ul class="nav">
			<li>活动规则</li>
			<li>排行榜</li>
			<li>历史记录</li>
		</ul>
		<div class="photo_wrap">
			<div class="upload">
                <a href="javascript:void(0);" class="logoBox" id="logoBox">
                	<img id="bgl" class="hide" src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/no_picture.png">
				</a>
			</div>
			<!--图片截取-->
            <div class="htmleaf-container">
				<div id="clipArea"></div>
				<div id="view"></div>
			</div>
            
            <!--按钮-->
			<div id="dpage">
				<a href="javascript:void(0);">
				    <input type="button" name="file" class="button" value="选取照片">
					<input id="file" type="file"  accept="image/*" onchange="javascript:setImagePreview();" />
				</a>
	            <a href="javascript:void(0);" class="qx">
	            	<button id="clipBtn">上传图片</button>
	            </a>
			</div>

			<div class="photo_info">
				<div class="left">
					<p>颜值:&nbsp;&nbsp;<span class="yanzhi"></span></p>
					<p>排名:&nbsp;&nbsp;<span class="rank_val"></span></p>
				</div>
				<div class="right">
					<p>兑换</p>
				</div>
			</div>
		</div>
		
		<div class="invite">
			<div class="dianzan">
				<div class="left">
					<p><img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/icon.png">呼朋唤友点赞攒颜值</p>
					<p>根据好友评论，获取更高颜值分</p>
				</div>
				<div class="right">
					<a href="javascript:void(0);">立即邀请</a>
				</div>
			</div>
			<div class="zhuce">
				<div class="left">
					<p><img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/icon.png">邀请好友注册</p>
					<p>成功邀请一位好友注册，颜值+5</p>
				</div>
				<div class="right">
					<a href="javascript:void(0);">立即邀请</a>
				</div>
			</div>	
		</div>
        
		<!--暂无评论-->
		<div class="no_discuss">
			<p><img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/pencil_icon.png">暂无评论</p>
		</div>
        
        <!--有评论-->
        <div class="discuss hide">
       		<p><img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/zan_heart.png"><span class="zan_count"></span>位好友点赞,<span class="bad_count"></span>位好友差评</p>
			<ul>
				
            </ul>
		</div>
		
	</div>
	<!--首页弹窗-->
	<div class="pop01 hide">
		<div class="pop_wrap">
			<div class="line"></div>
			<div class="detail">
				<div class="top">
					<p class="p1">
						<span></span>
						<span>兑换成功</span>
					</p>
					<p class="p2">
						<span>满100减5的彩特供抵扣券</span>
						<span>有效期2016.10.10-2016.11.04</span>
					</p>
				</div>
				<a href="javascript:void(0);" class="youhuiquan">去查看</a>
			</div>
		</div>
	</div>
	
	<div class="pop02 hide">
		<div class="pop_wrap">
			<div class="line"></div>
			<div class="detail detail02">
				<div class="top">
					<p><!--您的颜值有点低哦！--></p>
					<p><!--至少达到100分颜值，才能兑换奖品--></p>
				</div>
				<a href="javascript:void(0);">确定</a>
			</div>
		</div>
	</div>
	
	<!--初始化弹窗-->
	<div class="init_pop hide">
		<p>快来上传照片,看看你的颜值多少分</p>
		<img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/index_picture.png">
		<p>高颜值可换优惠券哦！</p>
		<a href="javascript:void(0);">立即参加</a>
	</div>
	
    <!--遮罩层-->
    <div class="mask hide"></div>
    
<script>

	var data =<?php echo $data;?>;
	var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/img_pro.png";
	var url="<?php echo F::getHomeUrl('/faceSwiping/Share?reUrl='.$surl.'&share=ShareWeb&sl_key=693&isWx=1'); ?>"
	//分享     start  *****************************************************
	
	//新版分享功能参数
	var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    
    if (isAndroid) {
		var param={
	    		"platform": [
	                 "ShareTypeWeixiSession",
	                 "ShareTypeWeixiTimeline",
	                 "ShareTypeQQ",
	                 "ShareTypeSinaWeibo",
	                 "NeighborShare"
	             ],
	             "title": "刷脸拼颜值",
	             "url": url,
	             "image": imgUrl,
	             "content": "赶紧参加活动瓜分丰厚大礼",
	         };
    }
    else if (isiOS) {
    	var param={
    		"platform": [
                 "ShareTypeWeixiSession",
                 "ShareTypeWeixiTimeline",
//	                 "ShareTypeQQ",
//	                 "ShareTypeSinaWeibo"
            	 "NeighborShare"


             ],
             "title": "刷脸拼颜值",
             "url": url,
             "image": imgUrl,
             "content": "赶紧参加活动瓜分丰厚大礼",
         };
    }
	
   	//旧版分享功能参数
	var params = {
            "text" : "赶紧参加活动瓜分丰厚大礼",
            "imageUrl" : imgUrl,
            "url":url,
            "title": "刷脸拼颜值",
            "titleUrl" : url,
            "description" : "描述",
            "site" : "彩之云",
            "siteUrl" : url,
            "type" : $sharesdk.contentType.WebPage
        };
			
//分享end	*****************************************************	
</script>

<!--<script src="js/jquery-1.11.3.js" type="text/javascript"></script>-->
<script>window.jQuery || document.write('<script src="js/jquery-1.11.3.js"><\/script>')</script>
<script src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>plugin/iscroll-zoom.js"></script>
<script src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>plugin/hammer.js"></script>
<script src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>plugin/jquery.photoClip.js"></script>
<script>
var obUrl = ''
$("#clipArea").photoClip({
	width:200,
	height:200,
	file: "#file", //拍照按钮
	view: "#view",
	ok: "#clipBtn",
	loadStart: function() {
		console.log("照片读取中");
	},
	loadComplete: function() {
		console.log("照片读取完成");
	},
	clipFinish: function(dataURL) {

		$.ajax({ 
				type:"POST",
				dataType: 'json',
				url:'/FaceSwiping/UploadPic',
				data:"file="+encodeURIComponent(dataURL),
				success:function(result){
					if(result.status == 1){
//						$(".upload a").append('<img id="bgl" src="'+ data.picUrl +'">');
						$("#logoBox").empty();
						$('#logoBox').append('<img src="' + imgsource + '" align="absmiddle" style=" width:100%;">');
						$(".htmleaf-container").hide();
						$("#dpage").removeClass("show");
						$(".dianzan .right").removeClass("disable");
						console.log("上传成功！");
					}else{
						console.log("上传成功！");
					}
				}
				
		})	
		
	}
});
</script>
<script>
$(function(){
$("#logoBox,#s_dpage").click(function(){
	$(".htmleaf-container").fadeIn(300);
	$("#dpage").addClass("show");
})

});
</script>
<script type="text/javascript">
$(function(){
	jQuery.divselect = function(divselectid,inputselectid) {
		var inputselect = $(inputselectid);
		$(divselectid+" small").click(function(){
			$("#divselect ul").toggle();
			$(".mask").removeClass("hide");
		});
		$(divselectid+" ul li a").click(function(){
			var txt = $(this).text();
			$(divselectid+" small").html(txt);
			var value = $(this).attr("selectid");
			inputselect.val(value);
			$(divselectid+" ul").hide();
			$(".mask").addClass("hide");
			$("#divselect small").css("color","#333")
		});
	};
	$.divselect("#divselect","#inputselect");
});
</script>
<script type="text/javascript">
$(function(){
	jQuery.divselectx = function(divselectxid,inputselectxid) {
		var inputselectx = $(inputselectxid);
		$(divselectxid+" small").click(function(){
			$("#divselectx ul").toggle();
			$(".mask").removeClass("hide");
		});
		$(divselectxid+" ul li a").click(function(){
			var txt = $(this).text();
			$(divselectxid+" small").html(txt);
			var value = $(this).attr("selectidx");
			inputselectx.val(value);
			$(divselectxid+" ul").hide();
			$(".mask").addClass("hide");
			$("#divselectx small").css("color","#333")
		});
	};
	$.divselectx("#divselectx","#inputselectx");
});
</script>
<script type="text/javascript">
$(function(){
	jQuery.divselecty = function(divselectyid,inputselectyid) {
		var inputselecty = $(inputselectyid);
		$(divselectyid+" small").click(function(){
			$("#divselecty ul").toggle();
			$(".mask").removeClass("hide");
		});
		$(divselectyid+" ul li a").click(function(){
			var txt = $(this).text();
			$(divselectyid+" small").html(txt);
			var value = $(this).attr("selectyid");
			inputselecty.val(value);
			$(divselectyid+" ul").hide();
			$(".mask").addClass("hide");
			$("#divselecty small").css("color","#333")
		});
	};
	$.divselecty("#divselecty","#inputselecty");
});
</script>
<script type="text/javascript">
$(function(){
   $(".mask").click(function(){
	   $(".mask").addClass("hide");
	   $(".all").addClass("hide");
   })
	$(".right input").blur(function () {
		if ($.trim($(this).val()) == '') {
			$(this).addClass("place").html();
		}
		else {
			$(this).removeClass("place");
		}
	})
});
</script>
<script>
$("#file0").change(function(){
	var objUrl = getObjectURL(this.files[0]) ;
	 obUrl = objUrl;
	console.log("objUrl = "+objUrl) ;
	if (objUrl) {
		$("#img0").attr("src", objUrl).show();
	}
	else{
		$("#img0").hide();
	}
}) ;
function qd(){
   var objUrl = getObjectURL(this.files[0]) ;
   obUrl = objUrl;
   console.log("objUrl = "+objUrl) ;
   if (objUrl) {
	   $("#img0").attr("src", objUrl).show();
   }
   else{
	   $("#img0").hide();
   }
}
function getObjectURL(file) {
	var url = null ;
	if (window.createObjectURL!=undefined) { // basic
		url = window.createObjectURL(file) ;
	} else if (window.URL!=undefined) { // mozilla(firefox)
		url = window.URL.createObjectURL(file) ;
	} else if (window.webkitURL!=undefined) { // webkit or chrome
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url ;
}
</script>
<script type="text/javascript">
function setImagePreview() {
	var preview, img_txt, localImag, file_head = document.getElementById("file_head"),
			picture = file_head.value;
	if (!picture.match(/.jpg|.gif|.png|.bmp|.jpeg/i)) return alert("您上传的图片格式不正确，请重新选择！"),
			!1;
	if (preview = document.getElementById("preview"), file_head.files && file_head.files[0]) preview.style.display = "block",
			preview.style.width = "100px",
			preview.style.height = "100px",
			preview.src = window.navigator.userAgent.indexOf("Chrome") >= 1 || window.navigator.userAgent.indexOf("Safari") >= 1 ? window.webkitURL.createObjectURL(file_head.files[0]) : window.URL.createObjectURL(file_head.files[0]);
	else {
		file_head.select(),
				file_head.blur(),
				img_txt = document.selection.createRange().text,
				localImag = document.getElementById("localImag"),
				localImag.style.width = "100px",
				localImag.style.height = "100px";
		try {
			localImag.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)",
					localImag.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = img_txt
		} catch(f) {
			return alert("您上传的图片格式不正确，请重新选择！"),
					!1
		}
		preview.style.display = "none",
				document.selection.empty()
	}
	return document.getElementById("DivUp").style.display = "block",
			!0
}
</script>

</body>
</html>
