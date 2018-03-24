<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>定义input type="file" 的样式</title>
<style type="text/css">
body{ font-size:14px;}
input{ vertical-align:middle; margin:0; padding:0}
.file-box{ position:relative;width:340px}
.txt{ height:22px; border:1px solid #cdcdcd; width:180px;}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:24px; width:70px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:260px }
</style>
</head>
<body>
<div class="file-box">应用于号码异常
  <form action="<?php echo $this->createUrl('/luckyApp/load');?>" method="post" enctype="multipart/form-data">
    <input type='text' name='textfield' id='textfield' class='txt' />  
    <input type='button' class='btn' value='浏览...' />
    <input type="file" name="fileField" class="file" id="fileField" size="28" onchange="document.getElementById('textfield').value=this.value" />
    <input type="submit" name="submit" class="btn" value="上传" />
  </form>
</div>
</body>
</html>
