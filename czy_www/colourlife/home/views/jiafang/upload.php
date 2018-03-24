<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport"	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->title;?></title>

<link type="text/css" rel="stylesheet"	href="<?php echo F::getStaticsUrl("/home/jiafang/");?>css/css.css" />
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/jquery-1.11.1.min.js"></script>

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/webuploader.js"></script>





</head>

<body>
	<div class="account_sub">
		<form enctype="multipart/form-data" action="/jiafang/UploadPortrait" method="post">
		  <p>
		    <input type="file" name="file" id="fileField">
		    
	      </p>
		  <p>&nbsp;</p>
		  <p>
		    <input type="submit" name="button" id="button" value="提交">
		  </p>
		</form>
	</div>
</body>
</html>
