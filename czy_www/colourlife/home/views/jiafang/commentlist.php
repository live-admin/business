<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport"	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->title;?></title>

<link type="text/css" rel="stylesheet"	href="<?php echo F::getStaticsUrl("/home/jiafang/");?>css/css.css" />

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/jquery-1.11.1.min.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/getPosition.js"></script>

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/common.js"></script>

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/base.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/commentlist.js"></script>

<script type="text/javascript">
var getCommentListUrl = "<?php echo $this->createUrl("GetCommentList"); ?>";
var tag = "<?php echo $tag; ?>";
var g_isEndPage = <?php echo $isEndPage; ?>;
</script>

</head>

<body>	



<div class="top-panel">
    <div class="drop-list left-down">
        <div class="drop-list-title" id="btnSelectCommunity" data-menu-list="#communityList" data-value="all">全部小区</div>
    </div>
    
    <ul class="drop-list-item-box" id="communityList" style="display:none;">
            <li data-value="all" class="selected">全部小区</li>
<?php foreach ($communitys as $community){ ?>
        	<li data-value="<?php echo $community['id']; ?>"><?php echo $community['name']; ?></li>
<?php }?>

        </ul>
    
    
    <div class="top-search-area">
        <form id="fromSearchComment">
            <input type="search"  placeholder="输入手机号或姓名" class="search-text" id="txtSearch" />	
        </form>	
    </div>
    
    <div class="drop-list right-down">
        <div class="drop-list-title" id="btnSelectTime" data-menu-list="#dateList" data-value="curr">当月</div>
    </div>
     
    <ul class="drop-list-item-box"  id="dateList">
    	<li data-value="all">全部时间</li>
        <li data-value="curr" class="selected">当月</li>
<?php foreach ($dateList as $date){ ?>
        <li data-value="<?php echo $date;?>"><?php echo $date;?></li>
<?php }?>

    </ul>
</div>



<div class="top-space"></div>



<div class="invest_area">
    
    <div class="invest_head">
        <span>评价时间</span>
        <span>业主姓名</span>
        <span>小区</span>
        <span class="end">评价</span>	
    </div>

	<div class="comment-list">    
    <?php foreach($comments as $comment) { ?>
        
    	<div class="row comment-list-item">
			<div class="col-1"><div class="time"><?php echo date("Y-m-d H:i:s",$comment['create_time']);?></div></div>
            <div class="col-1"><div class="name"><?php echo $comment['name'];?></div></div>
            <div class="col-1"><div class="address"><?php echo $comment['address'];?></div></div>
            <div class="col-1"><a href="<?php echo $this->createUrl("CommentDetail", array('id' => $comment['id'])); ?>"><div class="level"><?php echo $this->levelCodeToText($comment['level']); ?> <em>&gt;</em></div></a></div>
		</div>
	<?php }?>
        
	</div>
</div>
        
<div class="loading-space"></div>
        
<div class="bottom-space"></div>        
	<div class="invest_count">
		
		<p>共有 <span id="btnLevelTotal"><?php echo $statistics['total'];?></span> 条评价</p>
		
		<dl class="btn-comment-level" data-value="3">
			<dt>非常满意</dt>
			<dd class="red" id="btnLevel3"><?php echo $statistics['level3'];?></dd>	
		</dl>
		
		<dl class="btn-comment-level" data-value="2">
			<dt>满意</dt>
			<dd class="blue" id="btnLevel2"><?php echo $statistics['level2'];?></dd>	
		</dl>
		
		<dl class="btn-comment-level" data-value="1">
			<dt>一般</dt>
			<dd class="green" id="btnLevel1"><?php echo $statistics['level1'];?></dd>	
		</dl>
		
		<dl class="btn-comment-level" data-value="0">
			<dt>不满意</dt>
			<dd class="gray" id="btnLevel0"><?php echo $statistics['level0'];?></dd>	
		</dl>
		
	</div>
	<div class="invest_count_tip"></div>
</body>
</html>
