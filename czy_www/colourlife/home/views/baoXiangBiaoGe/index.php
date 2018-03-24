<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>3.0上线（宝箱）活动数据需求</title>
		<style>
			*{
				margin:0;
				padding:0;
			}
			body{
				font-family: "微软雅黑";
			}
			table{
				width:100%;
				border-bottom:1px #000 solid;
			}
			table caption{
				background: #BFBFBF;
			}
			table tr th{
				border-top:1px #000 solid;
				border-right:1px #000 solid;
				text-align: center;
				background: #BFBFBF;
			}
			table tr td{
				border-top:1px #000 solid;
				border-right:1px #000 solid;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<table cellpadding="0" cellspacing="0">
			<caption>3.0上线（宝箱）活动数据需求</caption>
			<tr>
				<th>活动时间</th>
				<th>注册人数</th>
				<th>活跃人数</th>
				<th>banner页面点击数</th>
				<th>领取赠送宝箱用户数</th>
				<th>抽取宝石用户</th>
				<th>红宝石</th>
				<th>蓝宝石</th>
				<th>绿宝石</th>
				<th>黄宝石</th>
				<th>紫宝石</th>
				<th>线下摇一摇次数</th>
				<th>打开宝箱用户数</th>
				<th>一元换购用户数</th>
				<th>E维修优惠劵发放数</th>
				<th>E租房优惠券发放数</th>
				<th>邮轮劵发放数</th>
                <th>彩生活特供优惠券发放数</th>
                <th>彩生活特供优惠券使用数</th>
                <th>环球精选优惠券发放数</th>
                <th>环球精选优惠券使用数</th>
			</tr>
            <?php 
                if(!empty($baoNumArr)){
                    foreach ($baoNumArr as $v){
            ?>
			<tr>
				<td><?php echo $v['day']?></td>
				<td><?php echo $v['register_num']?></td>
				<td><?php echo $v['activity_num']?></td>
				<td><?php echo $v['banner_click_num']?></td>
				<td><?php echo $v['bao_ling_user_num']?></td>
				<td><?php echo $v['bao_chou_user_num']?></td>
				<td><?php echo $v['red_num']?></td>
				<td><?php echo $v['blue_num']?></td>
				<td><?php echo $v['green_num']?></td>
				<td><?php echo $v['yellow_num']?></td>
				<td><?php echo $v['purple_num']?></td>
				<td><?php echo $v['yao_num']?></td>
				<td><?php echo $v['bao_open']?></td>
				<td><?php echo $v['one_yuan_num']?></td>
				<td><?php echo $v['eweixiu_num']?></td>
				<td><?php echo $v['ezufang_num']?></td>
				<td><?php echo $v['youlun_num']?></td>
                <td><?php echo $v['youhuiquan_num']?></td>
                <td><?php echo $v['youhuiquan_use_num']?></td>
                <td><?php echo $v['youhuiquan_num_h']?></td>
                <td><?php echo $v['youhuiquan_use_num_h']?></td>
			</tr>
            <?php }?>
            <?php }?>

			<tr style="background:#BFBFBF;">
				<td>合计</td>
				<td><?php echo $he[0]['total_1']?></td>
				<td><?php echo $he[0]['total_2']?></td>
				<td><?php echo $he[0]['total_3']?></td>
				<td><?php echo $he[0]['total_4']?></td>
				<td><?php echo $he[0]['total_5']?></td>
				<td><?php echo $he[0]['total_6']?></td>
				<td><?php echo $he[0]['total_7']?></td>
				<td><?php echo $he[0]['total_8']?></td>
				<td><?php echo $he[0]['total_9']?></td>
				<td><?php echo $he[0]['total_10']?></td>
				<td><?php echo $he[0]['total_11']?></td>
				<td><?php echo $he[0]['total_12']?></td>
				<td><?php echo $he[0]['total_13']?></td>
				<td><?php echo $he[0]['total_14']?></td>
				<td><?php echo $he[0]['total_15']?></td>
				<td><?php echo $he[0]['total_16']?></td>
                <td><?php echo $he[0]['total_17']?></td>
                <td><?php echo $he[0]['total_18']?></td>
                <td><?php echo $he[0]['total_19']?></td>
                <td><?php echo $he[0]['total_20']?></td>
			</tr>
		</table>
	</body>
</html>
