<!DOCTYPE html>
<html>


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="MobileOptimized" content="320"/>
    <title>商铺买电</title>
    <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/nts.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/alert.css'); ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/js/jquery.min.js'); ?>"></script>


</head>

<body>
<?php
Yii::import('common.api.StarApiNew');
$star = StarApiNew::getInstance();

?>
<div class="znq">
    <div class="my_content">
        <div class="mar_down"></div>
        <!--描述：间隔-->
        <!--优惠券列表-->
        <div class="list_electricity">
            <ul class="row_ul">

                <?php foreach ($data as $key => $value) {
                    //判断调用的是新接口还是新接口
                    $star = OthersPowerFees::model()->ConfirmInterfaceType(F::ParamFilter($value['meter']));
                    $staus = 7;//如果是调用的久接口则显示无法读取
                    $MeterBalance = "";
                    if ($value['interface_type'] == 2) {
                        $result = $star->callGetMeterBalance(F::ParamFilter($value['meter']));
                        $MeterBalance = $result->MeterBalance;
                        $resultstatus = $star->callGetMeterInsufficient(F::ParamFilter($value['meter']));
                        $staus = $resultstatus->GetMeterInsufficientResult;
                        if (empty($MeterBalance)) $MeterBalance = 0;
                    }

                    ?>
                    <li class="clearfix">
                                              
                                        <span
                                            onclick="href(<?php echo F::ParamFilter($value['id']); ?>,<?php echo F::ParamFilter($this->_userId); ?>,<?php echo F::ParamFilter($value['meter']); ?>,'<?php echo F::ParamFilter($value['customer_name']); ?>','<?php echo F::ParamFilter($value['meter_address']); ?>',<?php echo F::ParamFilter($staus); ?>,<?php if (!empty($MeterBalance)) echo $MeterBalance; else echo 0; ?>,<?php echo $value['interface_type']; ?>)">



                                     
							<div class="left_img"><img
                                    src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_01.png'); ?>"/></div>
							<div class="center_content">
                                <p>电表卡号：<span><?php echo F::ParamFilter($value['meter']); ?></span></p>

                                <p>签约人：<span><?php echo F::ParamFilter($value['customer_name']); ?></span></p>

                                <p>位置：<span><?php echo F::ParamFilter($value['meter_address']); ?></span></p>
                            </div>
                                        </span>
                                                    <span
                                                        onclick="location='buy_recharge?cust_id=<?php echo F::ParamFilter($this->_userId); ?>&meter=<?php echo F::ParamFilter($value['meter']); ?>&customer_name=<?php echo F::ParamFilter($value['customer_name']); ?>&meter_address=<?php echo F::ParamFilter($value['meter_address']); ?>&status=<?php echo $staus; ?>&MeterBalance=<?php echo $MeterBalance; ?>&interface_type=<?php echo $value['interface_type']; ?>'">
						
                                                      
                                                                  
                                                         <?php if ($MeterBalance >= 1000 && $value['interface_type'] == 2) { ?>
                                                             <div class="right_img">
                                                                 <span class="residue">剩余电量</span>
                                                                 <img
                                                                     src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_02.png'); ?>"/>

                                                                 <span
                                                                     class="residue_count"><?php echo $MeterBalance; ?>
                                                                     °</span>
                                                             </div>
                                                         <?php } ?>
                                                        <?php if ($MeterBalance >= 100 && $MeterBalance < 1000 && $value['interface_type'] == 2) { ?>
                                                            <div class="right_img">
                                                                <span class="residue">剩余电量</span>
                                                                <img
                                                                    src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_03.png'); ?>"/>
                                                                <span class="residue_count"><?php echo $MeterBalance; ?>
                                                                    °</span></div>
                                                        <?php } ?>
                                                        <?php if ($MeterBalance < 100 && $value['interface_type'] == 2) { ?>

                                                            <div class="right_img">
                                                                <span class="residue">剩余电量</span>
                                                                <img
                                                                    src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_04.png'); ?>"/>

                                                                <span class="residue_count"><?php echo $MeterBalance; ?>
                                                                    °</span></div>
                                                        <?php } ?>
                                                        <?php if ($staus == 1 || $staus == 2 || $staus == 3 || $staus == 4 || $staus == -1 || $value['interface_type'] == 1) { ?>
                                                            <?php
//                                                            echo $staus;
//                                                            echo $MeterBalance;
//                                                            echo $value['interface_type'];
                                                            ?>

                                                            <div class="right_img">
                                                                <span class="residue">剩余电量</span>
                                                                <img
                                                                    src="<?php echo F::getStaticsUrl('/home/yiNengYuan/images/img_05.png'); ?>"/>
                                                                <span class="notread">无法读取</span></div>
                                                        <?php } ?>
						
                                                    </span>

                    </li>


                <?php } ?>


            </ul>
        </div>

    </div>
</div>
<div class="bottom_btn" onclick="location='AddPower?cust_id=<?php echo F::ParamFilter($this->_userId); ?>'">添加电表</div>
</body>
<script type="text/javascript">
    //        $(function(){
    //
    //            setTimeout(function(){
    //               var a=$('.residue_count').html();
    //              a=parseInt(a).toFixed(2);
    //              alert(a);
    //              $('.residue_count').html(a+"°");
    //
    //
    //            },1000);
    //
    //          });


    function href(id, cust_id, meter, customer_name, meter_address, status, MeterBalance, interface_type) {

        location.href = 'buy_record?id=' + id + '&cust_id=' + cust_id + '&meter=' + meter + '&customer_name=' + customer_name + '&meter_address=' + meter_address + '&status=' + status + '&MeterBalance=' + MeterBalance + '&interface_type=' + interface_type;
    }


</script>

</html>