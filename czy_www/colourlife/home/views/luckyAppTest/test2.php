<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传图片文件</title>
</head>
<body>
	<form action="<?php echo $this->createUrl('/test/upload/');?>" method="post" enctype="multipart/form-data">
		<input type="file" name="file"/>
		<input type="hidden" name="dir" value="test"/>
		<input type="submit" value="Upload Image"/>
	</form>
</body>

</html>
