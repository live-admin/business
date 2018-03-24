<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport"	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->title;?></title>

<link type="text/css" rel="stylesheet"	href="<?php echo F::getStaticsUrl("/home/jiafang/");?>css/css.css" />
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/jquery-1.11.1.min.js"></script>

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/common.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/base.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/commentdetail.js"></script>

<script type="text/javascript">
var tagHandleUrl = "<?php echo $this->createUrl("TagHandle"); ?>";
</script>

</head>

<body>	
	
	<div class="sign_info">
		
		<ul class="info-list">
        	<li class="list-item"><span class="title">业主姓名：</span><p class="info-content"><?php echo $comment->proprietor->name;?></p></li>
        	<li class="list-item"><span class="title">业主住址：</span><p class="info-content"><?php echo $comment->address;?></p></li>
        	<li class="list-item"><span class="title">评价时间：</span><p class="info-content"><?php echo date("Y-m-d H:i", $comment->create_time);?></p></li>
        	<li class="list-item list-item-last"><span class="title">评价内容：</span><p class="info-content"><?php echo $comment->content;?></p></li>
		</ul>
		
		<div class="sign_judge">
			<span>综合评价：</span>	
			<?php
			for($i = 0; $i <= $comment->level; $i++){
			     echo "<em></em>";
			}
			for($i = 3; $i > $comment->level; $i--){
			    echo "<em class='gray'></em>";
			}
			?>
			
			<i><?php echo $this->levelCodeToText($comment->level); ?></i>
		</div>
		<div class="clear"></div>
		<?php if($comment->attachment != null && count($comment->attachment) > 0){?>
		
		<div class="sign_photo">
			<?php foreach ($comment->attachment as $atta) {?>
			
			<img src="<?php echo $this->getAttachmentUrl($atta->attachment_url); ?>" />
	   		<?php }?>
	   		
			<div class="clear"></div>
		</div>
		<?php }?>
		
	</div>
	
	<div class="sign_add">
		<form>
			<textarea placeholder="记录你的想法吧~~" id="txtContent" <?php 
			
			echo $comment->manager_tag == 1 ? ' readonly="readonly" ' : "";
			
			 ?>><?php echo $comment->manager_tag_content; ?></textarea>			
			<input type="button" class="sub" value="<?php 
			
			echo $comment->manager_tag == 1 ? '取消标记' : "添加标记";
			
			 ?>" id="btnTagComment" data-action="<?php 
			
			echo $comment->manager_tag == 1 ? 'untag' : "tag";
			
			 ?>" data-id="<?php echo $comment->id;?>" />
		</form>	
	</div>
	
	
</body>
</html>
