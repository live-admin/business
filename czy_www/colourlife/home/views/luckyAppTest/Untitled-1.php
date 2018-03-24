<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<table width="1013" height="162" border="1" summary="E评价数据">
  <caption>
    E评价数据
  </caption>
  <tr>  
    <th scope="col">活动期数</th>
    <th scope="col">业主</th>
    <th scope="col">手机</th>
    <th scope="col">省</th>
    <th scope="col">市</th>
    <th scope="col">区</th>
    <th scope="col">小区</th>
    <th scope="col">事业部</th>
    <th scope="col">题一</th>
    <th scope="col">题二</th>
    <th scope="col">题三</th>
    <th scope="col">题四</th>
    <th scope="col">题五</th>
    <th scope="col">题六</th>
    <th scope="col">备注</th>
    <th scope="col">创建时间</th>
  </tr>
  
    <?php foreach ($data as $key => $v){?>
    <tr>
        <td><?php echo $v['other']['ca'];?></td>
        <td><?php echo $v['other']['cn'];?></td>
        <td><?php echo $v['other']['cm'];?></td>
        <td><?php echo $v['other']['rn1'];?></td>
        <td><?php echo $v['other']['rn2'];?></td>
        <td><?php echo $v['other']['rn3'];?></td>
        <td><?php echo $v['other']['cn1'];?></td>
        <td><?php echo $v['other']['bn'];?></td>
        <td><?php echo $v['t1']?></td>
        <td><?php echo $v['t2']?></td>
        <td><?php echo $v['t3']?></td>
        <td><?php echo $v['t4'];?></td>
        <td><?php echo $v['t5'];?></td>
        <td><?php echo $v['t6'];?></td>
        <td><?php echo $v['other']['en'];?></td>
        <td><?php echo $v['other']['ec'];?></td>
    </tr>
    <?php } ?>
  </tr>
</table>
</body>
</html>
