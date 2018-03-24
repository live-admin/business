<script
	src="<?php echo F::getStaticsUrl('/common/js/jquery.ui.widget.js'); ?>"
	type="text/javascript"></script>
<script
	src="<?php echo F::getStaticsUrl('/common/js/jquery.iframe-transport.js'); ?>"
	type="text/javascript"></script>
<script
	src="<?php echo F::getStaticsUrl('/common/js/jquery.fileupload.js'); ?>"
	type="text/javascript"></script>
<style>
<!--
.complaint_photo {
	width: 400px;

	overflow: hidden;
	clear: both;
	_zoom: 1;
	display: block;
	-webkit-margin-before: 1em;
	-webkit-margin-after: 1em;
	-webkit-margin-start: 0px;
	-webkit-margin-end: 0px;
}

#fileupload {
	align-items: baseline;
	color: inherit;
	text-align: start;
	
}
#complaint_photo dt{
	width: 121px;
	height: 121px;
	cursor: pointer;
	position: relative;
}
-->
</style>
<script type="text/javascript">
 function exitPic(obj){
     $(obj).parent().remove();
     
  }
	$(function(){
		 
	     $('#fileupload').fileupload({
		   
	        dataType: 'json',  
	        url: '/site/ajaxUploads?PHPSESSID=<?php echo session_id();?>&YII_CSRF_TOKEN = <?php echo Yii::app()->request->csrfToken ; ?>',
	        success: function (json) {
		        if(json.success){
			        var html = "<dd>";
				        html+="<img src='"+json.filename+"'  />";
				        html+="<span class='del_it' onclick='exitPic(this)'></span>";
				        html+="<input type='hidden' name='upFile[]' value='"+json.id+"' />";
				        html+="</dd>";
				     $("#complaint_photo").append(html);
		        }
	        }
	    });  
	     
	});
 </script>

<dl class="complaint_photo" id="complaint_photo">
	<dt style="cursor: pointer;">
		<img src="<?php echo F::getStaticsUrl("/common/images/addimg.jpg");?>"
			alt="添加图片" /> <input id="fileupload" type="file" name="qqfile"
			style="position: absolute; height: 100%;right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;">
	</dt>

</dl>
