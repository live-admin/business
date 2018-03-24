<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form method="get">
	开始日期：<input name='start' value="<?=$start?>" maxlength="22" />&nbsp;结束日期：<input value="<?=$end?>"  name="end" maxlength="22"/>
	<input type="submit" value="查询" />
</form>
<span style="color:red"><?=$msg?></span> 如：开始2015-02-01 00:00:00 结束：2015-02-28 23:59:59
<table width="1013" height="162" border="1" summary="E评价数据">
  <caption>
    充值数据（只包括已付款跟交易成功）
  </caption>
  <tr>  
    <th scope="col">充值订单号</th>
    <th scope="col">事业部</th>
    <th scope="col">片区</th>
    <th scope="col">小区</th>
    <th scope="col">楼栋</th>
    <th scope="col">房号</th>
    <th scope="col">业主姓名</th>
    <th scope="col">业主手机</th>
    <th scope="col">充值金额</th>
    <th scope="col">赠送金额</th>
    <th scope="col">备注</th>
    <th scope="col">充值时间</th>
    <th scope="col">充值状态</th>
    <th scope="col">支付方式</th>
  </tr>
  
    <?php foreach ($data as $key => $v){?>
    <tr>
        <td><?php echo $v['os'];?></td>
        <td><?php echo $v['bn'];?></td>
        <td><?php echo $v['bn2'];?></td>
        <td><?php echo $v['cn'];?></td>
        <td><?php echo $v['bn3'];?></td>
        <td><?php echo $v['rr'];?></td>
        <td><?php echo $v['cn1'];?></td>
        <td><?php echo $v['cm'];?></td>
        <td><?php echo $v['oa']?></td>
        <td><?php echo $v['ss']?></td>
        <td><?php echo $v['sn']?></td>
        <td><?php echo $v['oc'];?></td>
        <td><?php echo $v['os1'];?></td>
        <td><?php echo $v['pn'];?></td>
    </tr>
    <?php } ?>
  </tr>
</table>
</body>
</html>
