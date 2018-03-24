<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link type="text/css" rel="stylesheet" href="<?php echo F::getStaticsUrl("/");?>home/propertyfee/css/css.css" />
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/common.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/base.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/common_busi.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/getposition.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/ajaxpage.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/parkingfee_index.js"></script>
<script type="text/javascript">
var parkingFeesListUrl = "<?php echo $this->createUrl("parkingFeesList")?>";
var communityId = "<?php echo $community['id']?>"; 
var communityName = "<?php echo $community['name']?>";
</script>
<title>物业缴费</title>
</head>
<body>
	<div id="header">
		<div class="tips">根据您的工作岗位目前可以查看<em><?php echo $community['total']; ?></em>个小区</div>
		<div class="community-panel">
			<h3><?php echo $community['name'];?></h3>
			<a href="<?php echo $this->createUrl("ChangeCommunity"); ?>">更改小区</a>
		</div>
	</div>
	<div id="content">
		<div class="scroll-container tab-content" data-tab-index="0" id="scrollContainer1">
			<ul class="main-list">
				
			</ul>
            <div class="loading-space"></div>
		</div>
		<div class="scroll-container tab-content" data-tab-index="1" id="scrollContainer2">
			<ul class="main-list">

			</ul>
			<div class="loading-space"></div>
		</div>
	</div>
</body>
</html>
