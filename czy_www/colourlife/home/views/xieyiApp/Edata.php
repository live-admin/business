<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <style>

        th {

            width:4.55%;

        }
        td{
            text-align: center;
        }
        th{
            height:40px;
        }

    </style>
</head>
<body>
<form method="get">
    开始日期：<input name='start' value="<?=$start?>" maxlength="22" />&nbsp;结束日期：<input value="<?=$end?>"  name="end" maxlength="22"/>
    <input type="submit" value="查询" />
</form>
<span style="color:red"><?=$msg?></span> 如：开始2015-02-01 00:00:00 结束：2015-02-28 23:59:59
<table   border="1" summary="E评价数据">
    <caption>
        E评价数据
    </caption>
    <tr class="tr1">
        <th scope="col">
            总序号
        </th>

        <th scope="col">
            大区
        </th>
        <th scope="col">
            城市事业部
        </th>
        <th scope="col">
            有效数量
        </th>
        <th scope="col">
            项目名称
        </th>
        <th scope="col">
            房号
        </th>
        <th scope="col">
            姓名
        </th>
        <th scope="col">
            电话号码
        </th>
        <th scope="col" >
            总体评价(1)
        </th>
        <th scope="col">管理处服务(2)</th>
        <th scope="col">保安(3)</th>
        <th scope="col">设施设备(4)</th>
        <th scope="col">清洁卫生(5)</th>
        <th scope="col">绿化(6)</th>
        <th scope="col">电梯(7)</th>
        <th scope="col">意见和建议</th>
        <th scope="col">电访时间</th>
        <th scope="col">工号</th>
        <th scope="col">备注</th>


    </tr>


    <?php $i=1;  foreach ($data as $key => $v){?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $v['other']['大区'];?></td>
            <td><?php echo $v['other']['城市事业部'];?></td>
            <td><?php ?></td>
            <td><?php echo $v['other']['项目名称'];?></td>
            <td><?php echo $v['other']['房号'];?></td>
            <td><?php echo $v['other']['姓名'];?></td>
            <td><?php echo $v['other']['电话号码'];?></td>
            <td><?php echo $v['t1']?></td>
            <td><?php echo $v['t2']?></td>
            <td><?php echo $v['t3']?></td>
            <td><?php echo $v['t4'];?></td>
            <td><?php echo $v['t5'];?></td>
            <td><?php echo $v['t6'];?></td>
            <td><?php echo $v['t7'];?></td>
            <td><?php echo $v['other']['备注'];?></td>
            <td><?php echo $v['other']['电访时间'];?></td>


            <td><?php ?></td>
            <td><?php ?></td>

        </tr>
    <?php } ?>
    </tr>
</table>
</body>
</html>
