<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="MobileOptimized" content="320"/>
    <title>购买充值卡</title>
    <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/nts.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/alert.css'); ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/js/jquery.min.js'); ?>"></script>
</head>
<?php
$meter = Yii::app()->request->getParam('meter');
$interface_type = Yii::app()->request->getParam('interface_type');
$MeterBalance= Yii::app()->request->getParam('MeterBalance');
$status= Yii::app()->request->getParam('status');
?>

<body>
<div class="znq">
    <div class="content">
        <div class="mar_down"></div>
        <!--描述：间隔-->
        <?php if($interface_type == 3 ){?>
            <p class="buy_retitle">购买付款后将在后台为您进行缴纳电费。</p>
        <?php }else{?>
        <p class="buy_retitle">购买充值卡后您将获得购买凭证码，通过购买凭证码可在 电表上进行缴纳电费。</p>
        <?php }?>
        <input class="recharge_count" type="number" placeholder="输入购买金额     (不能超过5000元)"/>

        <table class="recharge_table">
            <tr>
                <td class="recharge_td">电表卡号：<span><?php echo Yii::app()->request->getParam('meter'); ?></span></td>
                <td class="recharge_txt">剩余电量</td>
            </tr>
            <tr>
                <td>签约人：<span><?php echo Yii::app()->request->getParam('customer_name'); ?></span></td>
                <td rowspan="2" class="recharge_td_right" style="background: <?php
                if ($MeterBalance >= 1000 && $interface_type == 2) {
                    echo "#119627";
                }
                if ($MeterBalance >= 100 && $MeterBalance < 1000 && $interface_type == 2) {
                    echo "#ff9600";
                }
                if ($MeterBalance < 100 && $interface_type == 2 && $status==0) {
                    echo "#bc3622";
                }
                if ($status == 1 || $status == 2 || $status == 3 || $status == 4 || $status == -1 || $interface_type == 1) {
                    echo "#999999";
                }
                ?>"><?php
                    if($interface_type==1){
                    echo "无法读取";

                    }else{
                        echo $MeterBalance.'°';
                    }

                     ?>
                </td>
            </tr>
            <tr>
                <td>位置：<span><?php echo Yii::app()->request->getParam('meter_address'); ?></span></td>
                <td></td>
            </tr>
        </table>

        <div class="comfirm_recharge">确认购买</div>

    </div>

</div>
<div class="pop_up" style="display: none;">
    <div class="iphone_pop samsung">

        <!--<p class="hint">温馨提示</p>-->

        <div class="select_type">
            很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。
        </div>
        <!--每行一个按钮-->
        <div class="row_one_btn">确定</div>
        <!--每行两个按钮-->
        <!--                            <div class="row_two_btn clearfix">
                                        <div class="add_cancel">取消</div>
                                        <div class="">确定</div>
                                    </div>-->
    </div>
</div>


<script type="text/javascript">


    var customer_id =<?php echo Yii::app()->request->getParam('cust_id'); ?>;
    var meter = '<?php echo Yii::app()->request->getParam('meter');?>';
    var customer_name = '<?php echo Yii::app()->request->getParam('customer_name');?>';
    var meter_address = '<?php echo Yii::app()->request->getParam('meter_address');?>';

    //弹窗调用
    function alert_invoke(prompt) {
        $('.select_type').html(prompt);
        $('.pop_up').show();
    }
    //关闭窗口
    $('.row_one_btn').click(function () {
        $('.pop_up').hide();
    });
    //关闭窗口
    $('.know').click(function () {
        $('.pop_up').hide();
    });


    $(function () {

        //确定购买
        $('.comfirm_recharge').click(function () {

            var amount = $('.recharge_count').val();


            if (amount > 5000) {
                alert_invoke("购买充值卡金额不能超过5000元");
                $('.recharge_count').val(5000);
            }
            else if (amount.indexOf('.') > -1) {
                alert_invoke('不能输入小数点');
            }
            else if (isNaN(amount)) {
                alert_invoke("输入的必须是数字");
            }
            else if ($.trim(amount) == "") {
                alert_invoke("购买充值卡金额不能为空");
            }
            else {

                $.ajax({
                    type: "POST",
                    url: "/YiNengYuan/CreateOrder",
                    dataType: "json",
                    data: {
                        customer_id: customer_id,
                        amount: amount,
                        meter: meter,
                        customer_name: customer_name,
                        meter_address: meter_address
                    },
                    success: function (data) {

                        if (1 == data.status) {

                            document.location = 'PayFromHtml5?sn=' + data.sn;

                        } else {

                            alert_invoke(data.msg);
                        }

                    }
                })


            }

        });
    })


</script>
</body>

</html>