<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>人气召集令-我的人气值</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
      	<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>css/layout.css" />
	</head>
	<body>
		<?php //dump($renDetail);?>
		<div class="conter">
			<div class="task02">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>images/task_p02.png">
			</div>
			<div class="tab">
				<div class="tab_top">
					<div class="tab_con">时间</div>
					<div class="tab_con" style="padding-left: 10%;">获得途径</div>
					<div class="tab_con">人气值</div>
				</div>
				
				
				<div class="tab_site">
				<?php if(!empty($renDetail)){
			      foreach($renDetail as $list){
	      		?>
					<div class="tab_ban">
						<div><?php echo date("Y-m-d",$list['create_time']);?> <?php echo date("H:i:s",$list['create_time']);?></div>
						<div><?php echo Popularity::model()->getWayName($list['way'])?></div>
						<div><?php echo $list['value'];?></div>
					</div>
					<?php 
				}
				}?>
			
				</div>
				
			</div>
			<div class="tab_p">
				<p class="red01">* 彩之云对本次活动保留法律范围内的最终解释权</p>
				<p class="red01" style="text-indent: 0.25rem;">活动由彩之云提供，与设备生产商Apple Inc.公司无关</p>
			</div>
			<div class="footer"></div>
		</div>
		
		
		<script type="text/javascript">
				//var test=<?php echo json_encode($renDetail);?>;
		</script>
		
	</body>
</html>


