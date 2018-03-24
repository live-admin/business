<!DOCTYPE html>
<html>


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="MobileOptimized" content="320"/>
    <title>购电记录</title>
    <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/nts.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/alert.css'); ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/js/jquery.min.js'); ?>"></script>
</head>
<?php
$status = Yii::app()->request->getParam('status');
$MeterBalance = Yii::app()->request->getParam('MeterBalance');
$interface_type = Yii::app()->request->getParam('interface_type');
$cust_id=Yii::app()->request->getParam('cust_id');
?>
<body>
<div class="znq" style="padding-bottom:40px;">
    <div class="my_content">
        <div class="mar_down"></div>
        <!--描述：间隔-->
        <!--购买记录-->
        <ul class="row_ul">
            <li class="clearfix">

                <div class="left_img"><img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_01.png'); ?>"/>
                </div>
                <div class="center_content">
                    <p>电表卡号：<span><?php echo Yii::app()->request->getParam('meter'); ?></span></p>

                    <p>签约人：<span><?php echo Yii::app()->request->getParam('customer_name'); ?></span></p>

                    <p>位置：<span><?php echo Yii::app()->request->getParam('meter_address'); ?></span></p>
                </div>
                                                 
                <span onclick="location='buy_recharge?cust_id=<?php echo Yii::app()->request->getParam('cust_id'); ?>&meter=<?php echo Yii::app()->request->getParam('meter'); ?>&customer_name=<?php echo Yii::app()->request->getParam('customer_name'); ?>&meter_address=<?php echo Yii::app()->request->getParam('meter_address'); ?>&status=<?php echo $status; ?>&MeterBalance=<?php echo $MeterBalance; ?>&interface_type=<?php echo $interface_type;  ?>'">
                         <?php if ($MeterBalance >= 1000 && $interface_type == 2) { ?>
                             <div class="right_img">
                                 <span class="residue">剩余电量</span>
                                 <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_02.png'); ?>"/>
                                 <span class="residue_count"><?php echo $MeterBalance; ?>°</span></div>
                         <?php } ?>
                        <?php if ($MeterBalance >= 100 && $MeterBalance < 1000 && $interface_type == 2) { ?>
                            <div class="right_img">
                                <span class="residue">剩余电量</span>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_03.png'); ?>"/>
                                <span class="residue_count"><?php echo $MeterBalance; ?>°</span></div>
                        <?php } ?>
                        <?php if ($MeterBalance < 100 && $interface_type == 2  && $status==0) { ?>
                            <div class="right_img">
                                <span class="residue">剩余电量</span>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_04.png'); ?>"/>
                                <span class="residue_count"><?php echo $MeterBalance; ?>°</span></div>
                        <?php } ?>

                    <!--                    新增云控电表-->
                        <?php if($MeterBalance >= 1000 && $interface_type == 3){?>
                            <div class="right_img">
                                <span class="residue">剩余电量</span>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_02.png'); ?>"/>
                                <span class="residue_count"><?php echo $MeterBalance; ?>°</span></div>
                        <?php } ?>
                        <?php if($MeterBalance >= 100 && $MeterBalance < 1000 && $interface_type == 3){?>
                            <div class="right_img">
                                <span class="residue">剩余电量</span>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_02.png'); ?>"/>
                                <span class="residue_count"><?php echo $MeterBalance; ?>°</span></div>
                        <?php } ?>
                        <?php if($MeterBalance < 100  && $interface_type == 3){?>
                            <div class="right_img">
                                <span class="residue">剩余电量</span>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_02.png'); ?>"/>
                                <span class="residue_count"><?php echo $MeterBalance; ?>°</span></div>
                        <?php } ?>
                    <!--                    新增云控电表-->

                        <?php if ($status == 1 || $status == 2 || $status == 3 || $status == 4 || $status == -1 || $interface_type == 1) { ?>
                            <div class="right_img">
                                <span class="residue">剩余电量</span>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_05.png'); ?>"/>
                                <span class="notread">无法读取</span></div>
                        <?php } ?>

                    </span>

            </li>
        </ul>

        <div class="list_record">
            <ul>
                <?php foreach ($data as $key => $value) { ?>

                    <li class="clearfix" onclick="href('<?php echo $value['sn']; ?>'); ">

                        <div class="left_img_record">
                            <?php if ($value['status'] == "待付款") { ?>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/iconfont-daifukuan.png'); ?>"/>
                            <?php } ?>

                            <?php if ($value['status'] == "已付款") { ?>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/iconfont-yifukuan.png'); ?>"/>
                            <?php } ?>

                            <?php if ($value['status'] == "交易成功") { ?>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/iconfont-chenggong.png'); ?>"/>
                            <?php } ?>

                            <?php if ($value['status'] == "交易关闭") { ?>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/iconfont-55.png'); ?>"/>
                            <?php } ?>

                            <?php if ($value['status'] == "已退款") { ?>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/ios7-arrow-right.png'); ?>"/>
                            <?php } ?>

                            <?php if ($value['status'] == "交易失败") { ?>
                                <img src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/iconfont-55.png'); ?>"/>
                            <?php } ?>


                            <p><?php echo $value['status']; ?></p>
                        </div>
                        <div class="center_img_record">
                            <p>购电日期：<span class="txt_garp"><?php echo $value['create_time']; ?></span></p>

                            <p>购电金额：<span class="txt_garp"><?php echo $value['amount']; ?></span><span class="txt_garp">元</span>
                            </p>
                        </div>
                        <div class="right_img_record"><img
                                src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/ios7-arrow-right.png'); ?>"/>
                        </div>

                    </li>


                <?php } ?>


            </ul>
        </div>

    </div>
</div>


<div class="pop_up" style="display:none;">
    <div class="iphone_pop dlt">
        <div class="close_row clearfix">
            <img class="close" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/close.png'); ?>"/>
        </div>
        <p class="hint">是否删除该电表？</p>

        <div class="select_type">
            删除充值的订单数据也将消失
        </div>

        <div class="dels_btn">

            <div class="comfirm_b" onclick="delete_power(<?php echo Yii::app()->request->getParam('id'); ?>,true,<?php echo $cust_id;  ?>)">确定
            </div>
            <div onclick="delete_power(<?php echo Yii::app()->request->getParam('id'); ?>,false)">取消</div>

        </div>
    </div>
</div>

<div class="bottom_btn" onclick="alert_invoke()">删除</div>

</body>
<script type="text/javascript">

    function href(sn) {

        location.href = 'buy_detail_ytk?sn=' + sn + '&flag=1';


    }

    //弹窗调用
    function alert_invoke() {
        $('.pop_up').show();
    }


    //关闭窗口
    $('.close').click(function () {
        $('.pop_up').hide();
    });


    function delete_power(id, confirm,cust_id) {

        if (confirm) {
            $.ajax({
                type: "POST",
                url: "/YiNengYuan/DeletePower",
                dataType: "json",
                data: {
                    id: id

                },
                success: function (data) {

                    if (1 == data.code) {
                        location.href = "Buy?cust_id="+cust_id;

                    }
                    else {

                    }
                }
            })
        } else {
            $('.pop_up').hide();
        }


    }

</script>
</html>