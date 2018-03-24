<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>满意度调查数据</title>
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
    <input type="hidden" name="access_token" value="colourlife"/>
    <input name="submit" type="submit" value="查询" /><input name="submit" type="submit" value="导入手机号" /><input name="submit" type="submit" value="导出满意度调查数据" />
</form>
<span style="color:red"><?=$msg?></span> 如：开始2015-02-01 00:00:00 结束：2015-02-28 23:59:59
<table   border="1" summary="满意度调查数据">
    <caption>
        满意度调查数据
    </caption>
    <tr class="tr1">
        <th scope="col">
            总序号
        </th>
        <th scope="col">
            用户ID
        </th>
        <th scope="col">
            姓名
        </th>
        <th scope="col">
            电话号码
        </th>
        <th scope="col">
            IP地址
        </th>
        <th scope="col">
            手机类型
        </th>
        <th scope="col">
            用户UA
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
        <th scope="col">评价时间</th>
    </tr>


    <?php $i=1;  foreach ($data as $key => $v){?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $v['customer_id'];?></td>
            <td><?php echo $v['name'];?></td>
            <td><?php echo $v['mobile'];?></td>
            <td><?php echo $v['ip'];?></td>
            <td><?php echo $v['phone_type'] == 1 ? 'android' : ($v['phone_type'] == 2 ? 'ios' : '其它');?></td>
            <td><?php echo $v['user_agent'];?></td>
            <td><?php echo $v['t1']?></td>
            <td><?php echo $v['t2']?></td>
            <td><?php echo $v['t3']?></td>
            <td><?php echo $v['t4'];?></td>
            <td><?php echo $v['t5'];?></td>
            <td><?php echo $v['t6'];?></td>
            <td><?php echo $v['t7'];?></td>
            <td><?php echo $v['note'];?></td>
            <td><?php echo date('Y-m-d H:i:s', $v['create_time']);?></td>
        </tr>
    <?php } ?>
    </tr>
</table>
</body>
</html>
