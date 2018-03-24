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
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/getposition.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/ajaxpage.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/changecommunity.js"></script>
<script type="text/javascript">
var searchCommunityUrl = "<?php echo $this->createUrl("SearchCommunity"); ?>";
</script>
<title>物业缴费</title>
</head>

<body>
	<div id="header">
		<div class="search-bar">
			<input type="text" placeholder="请输入小区名或首字母" class="content" /><button class="btn-search" type="button"><i class="ico-search"></i></button>
		</div>
	</div>
	<div id="content">
		<div class="scroll-container">
			<ul class="community-list">
            	
			</ul>
            <div class="loading-space"></div>
		</div>
	</div>
</body>
</html>
