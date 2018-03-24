
(function($){
	
	var uploadFileCount = 0,
	
	    uploadFinishCount = 0;	/*文件上传完成计数*/
		
$(function(){
	
	var 
	
	ratio = window.devicePixelRatio || 1,	
	// 缩略图大小
	thumbnailWidth = 100 ,		
	thumbnailHeight = 100 ,		
	$list = $('#fileList'),
	
	uploader = WebUploader.create({	
		auto: true,		
		swf: '/js/Uploader.swf',		
		server: getUrl()+"/image/upload?access_token="+localStorage.getItem("access_token"),		
		pick: '#filePicker',		
		// 只允许选择图片文件。
		accept: {
			title: 'Images',
			extensions: 'gif,jpg,jpeg,bmp,png',
			mimeTypes: 'image/*'
		}
	});
	
	// 当有文件添加进来的时候
	uploader.on( 'fileQueued', function( file ) {
		//相册图片后缀名为大写时，ios图片无法上传
		//将图片后缀名改为小写
		var str =file.name;
		var s =str.lastIndexOf(".");
		file.name=str.substring(0,s+1)+str.substring(s+1).toLowerCase();
		
		if(uploadFileCount >= allowUploadPicCount)
		{
			uploader.removeFile( file );
			return ;
		}
		
		uploadFileCount ++;
		isUploading = true;
		
		if(uploadFileCount == allowUploadPicCount)
		{
			$("#btnUploadFile").hide();
		}
		
		var $li = $(
				'<a href="javascript:void(0);" class="upload_pic" id="' + file.id + '" >' +
					'<img>' +
					'<div class="progress"></div>' +
				'</div>'
				),

		$img = $li.find('img');
	
	
		// $list为容器jQuery实例
		$list.append( $li );
	
		// 创建缩略图
		// 如果为非图片文件，可以不用调用此方法。
		// thumbnailWidth x thumbnailHeight 为 100 x 100
		uploader.makeThumb( file, function( error, src ) {
			if ( error ) {
				$img.replaceWith('<span>不能预览</span>');
				return;
			}
	
			$img.attr( 'src', src );
		}, thumbnailWidth, thumbnailHeight );
	});
	
	
	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage )
	{	
		$("#"+file.id).find(".progress").css("width", percentage * 100 + '%');
	});
	
	
	uploader.on( 'uploadSuccess', function( file , reponse ) {
		// alert(reponse);
		console.log(file);
		console.log(reponse);
		if(reponse.code == 0)
		{
			uploadFinishCount++;
			if(uploadFileCount == uploadFinishCount)
			{
				isUploading = false;
			}			
			
			uploadFile.push(reponse.content.image);
			
			$("#"+file.id).find(".progress").addClass("progress-success");
			$("#"+file.id).find(".progress").css("width", "100%");

		}
		else
		{
			toastMsg("上传失败：" + reponse.code);
			onUploadFail(file);
		}
	});
	
	
	// 文件上传失败，显示上传出错。
	uploader.on( 'uploadError', function( file ) {
		onUploadFail(file);
		
	});
	
	
	$("#btnUploadFile").click(function(){
		
		if(currentMonthCommentCount >= maxCommentCountOneMonth)
		{
			toastMsg("本月评价已用完");
			return;
		}
		
		if(currentDayCommentCount >= maxCommentCountOneDay)
		{
			toastMsg("今天您已经评价过了，感谢您对我们的支持! ");
			return;
		}
		
		if(uploadFileCount < allowUploadPicCount)
		{
			$("input[type=file]").click();
		}
	});
	
	var onUploadFail = function(file)
	{
		var $li = $( '#'+file.id ),
		$error = $li.find('div.error');

		// 避免重复创建
		if ( !$error.length ) {
			$error = $('<div class="error"></div>').appendTo( $li );
		}
	
		$error.text('上传失败');
		
		uploadFileCount--;
		
		if(uploadFileCount == uploadFinishCount)
		{
			isUploading = false;
		}
		
		uploader.removeFile( file );
		$li.remove();
		
		if(uploadFileCount < allowUploadPicCount)
		{
			$("#btnUploadFile").show();
		}
	}
});
	
})(jQuery);
