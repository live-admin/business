<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>发布游记</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/ReFontsize.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>css/zyUpload.css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/jquery-1.11.3.js" ></script>
		<!-- 引用核心层插件 -->
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/zyFile.js"></script>
		<!-- 引用控制层插件 -->
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/zyUpload.js"></script>
	</head>
	<body style="position:relative;">
	<?php if (isset(Yii::app()->session[$this->user_id.'img'])&&!empty(Yii::app()->session[$this->user_id.'img'])){
		Yii::app()->session[$this->user_id.'img']=array();
	}?>
	<div class="input_block">
		<form action="/ColourTravel/ShareTravel" name="release" method="post" onsubmit="return confirm('确认发布 ？')">
			<div class="box">
				<input type="text" maxlength="20" class="biaoti" placeholder="请输入标题 （20个字以内）" value="" name="title"/>
				<input type="submit" class="sure" value="发布"/>
			</div>
			<hr style="height:1px;border:none;border-top:1px solid #BFC7CC;width:100%"/>
			<textarea rows="8" cols="45" placeholder="快来与大家分享你的旅游趣事吧(请输入至少30个字，最多140个字)" name="content" style="padding-bottom: 0.35rem"></textarea>
		</form>
	</div>
		<!-- <div class="release">
			<div class="title">
				<input type="text" class="biaoti" placeholder="请输入标题 （20个字以内）" />
				<a href="javascript:void(0)">发布</a>
			</div>
			
			<div class="pag">
				<textarea rows="10" cols="45" placeholder="快来与大家分享你的旅游趣事吧~"></textarea>
			</div>
			
			<div class="pic">
				<a href="javascript:void(0)"><img src="images/chuan.png"/></a>
			</div>
			
		</div> -->
		<input type="hidden" value="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>" class="sc_url">
		<div id="contain" class="contain"></div> 
		<script type="text/javascript">
		$(document).ready(function(){
			
			$(function(){
			// 初始化插件
			$("#contain").zyUpload({
				width            :   "100%",                 // 宽度
				height           :   "100%",                 // 宽度
				itemWidth        :   "1.5rem",                 // 文件项的宽度
				itemHeight       :   "1.5rem",                 // 文件项的高度
				url              :   "/ColourTravel/Fileupload",  // 上传文件的路径
				multiple         :   true,                    // 是否可以多个文件上传
				dragDrop         :   false,                    // 是否可以拖动上传文件
				del              :   true,                    // 是否可以删除文件
				finishDel        :   false,  				  // 是否在上传文件完成后删除预览
				/* 外部获得的回调接口 */
				onSelect: function(files, allFiles){                    // 选择文件的回调方法
					console.info("当前选择了以下文件：");
					console.info(files);
					console.info("之前没上传的文件：");
					console.info(allFiles);
				},
				onDelete: function(file, surplusFiles){                     // 删除一个文件的回调方法
					console.info("当前删除了此文件：");
					console.info(file);
					console.info("当前剩余的文件：");
					console.info(surplusFiles);
				},
				onSuccess: function(file){                    // 文件上传成功的回调方法
					console.info("此文件上传成功：");
					console.info(file);
				},
				onFailure: function(file){                    // 文件上传失败的回调方法
					console.info("此文件上传失败：");
					console.info(file);
				},
				onComplete: function(responseInfo){           // 上传完成的回调方法
					console.info("文件上传完成");
					console.info(responseInfo);
				}
				});
			});
			$(".sure").click(function(){
				var title = $(".biaoti").val();
				var content = $(".input_block textarea").val();
				var flag = $(".upload_btn_ok").hasClass("disabled");
				var contentLength = $(".input_block textarea").val().length;
				if(!title){
					alert("请输入标题！！")
					return false;
					}
				if(!content){
					alert("请输入内容！！")
					return false;
					}
				if(contentLength<30 || contentLength >140 ){
					alert("字数不能少于30或超过140！");
						return false;
					}
				if(!flag){
					alert("请先上传图片！");
					return false;
					}
				});
		});
		
		</script>
	</body>
</html>
