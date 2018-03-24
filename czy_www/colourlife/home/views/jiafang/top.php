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

</head>

<body>	
	
	<div class="comment_rank">
		
		<h2>月度评价TOP20</h2>		
        
		<div class="rank_head">
			<span class="s1">姓名</span>
			<span class="s2">事业部</span>
			<span class="s2">总评价数</span>
			<span class="s1 end">头衔</span>	
			<div class="clear"></div>
		</div>
<?php foreach($topList as $top) {?>
		<div class="rank_li">
			<span class="r1"><?php echo $top['mgr_name'] == "" ? "无名氏" : $top['mgr_name'];?></span>
			<span class="r2"><?php echo $this->getParentBranchName($top['branch_id'], 2);?></span>
			<span class="r3"><?php echo $top['score'];?></span>
			<span class="r4"><?php echo $this->levelScoreToTitle($top['score']) ; ?></span>
			<div class="clear"></div>
		</div>
<?php }?>

	
			
	</div>
	
	<div class="rank_tip">
		
		<dl>
			<dt>当月我的全国排名<span><?php echo $myRank; ?></span>(<?php echo $this->levelScoreToTitle($myScore);?>)</dt>	
			<dd>距离上一头衔 还差<span><?php echo $upNextLevelScore; ?></span>个评价数</dd>
		</dl>
		
		<div class="tip_mof">
			<a href="<?php echo $this->createUrl("CommentList", array("action"=>"tag"));?>" class="bj">我标记的评价</a>	
			<a href="<?php echo $this->createUrl("CommentList", array("action"=>"all"));?>" class="sy">查看所有评价</a>
		</div>
			
	</div>
	<div class="rank_tip_zw"></div>
	
</body>
</html>
