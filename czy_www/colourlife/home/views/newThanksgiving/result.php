<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>感恩节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/newthanksgiving/'); ?>css/layout.css" rel="stylesheet" />
	</head>
	<body>
		<div class="end">
			 <div class="end1a">
			 	<h2>我的礼券</h2>
			 </div>
			 <div class="end_tab">
			 	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			 		<tr>
			 			<th>抽奖时间</th>
			 			<th>奖品</th>
			 			<th>领奖情况</th>
			 		</tr>
			 		
			 		<?php if (!empty($thanks)){foreach ($thanks as $k=>$v){?>
			 		<tr>
			 			<td><?php echo date("Y-m-d",$v->addtime);?></td>
			 			<td><?php echo $k+1;?>. <?php echo $v->prize_name; ?></td>
			 			<td>优惠码：<?php echo $v->code;?></td>
			 		</tr>
			 	<?php }}else{?>
			 		<tr >
			 			<td colspan="3" style="border-right:1px #fff solid !important">没中奖记录</td>
			 		</tr>
			 	<?php }?> 
			 	</table>
				 <div class="end_2a">
				 	<a href="/luckyApp/christmasShuoMingHuameida">>>深圳豪派特华美达酒店</a>
				 	<a href="/luckyApp/christmasShuoMingQuyuan">>>深圳趣园私人酒店公寓</a>
				 	<a href="/luckyApp/christmasShuoMingSanjiaozhou">>>惠州巽寮湾海岛三角洲</a>
				 	<a href="/luckyApp/christmasShuoMingLizigongguan">>>惠州丽兹公馆</a>
				 	<a href="/luckyApp/christmasShuoMingFlyvilla">>>惠州罗浮山彩别院</a>
				 	<a href="/luckyApp/christmasShuoMingHailingdao">>>阳江颐景花园彩悦皓雅度假公寓</a>
				 	<a href="/luckyApp/christmasShuoMingTaihutiancheng">>>苏州太湖天城酒店公寓</a>
				 	<a href="/luckyApp/christmasShuoMingWonderland">>>江西婺源清风仙境</a>
				 </div>
			 </div>
			 
   		
		</div>
	</body>
</html>
