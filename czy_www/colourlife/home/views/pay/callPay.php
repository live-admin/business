<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>出货中...</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/m/insure/');?>js/common/jquery-1.11.3.js"></script>
</head>
<body>
    <script type="text/javascript">
        $(document).ready(function(){
            var sn = '<?php echo $sn; ?>';
            var return_url = '<?php echo F::getHomeUrl('/pay/payResult'); ?>';

            if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
                ios(sn, return_url); //苹果调用代码
            }else{
                android(sn, return_url); //android调用代码
            }

            function ios(sn, url){
                var url = "<?php echo F::getHomeUrl(); ?>*payFromHtml5*"+sn+"*"+url;
                location.href = url;
            }

            function android(sn, url){
                jsObject.payFromHtml(sn, url);
            }
        });
    </script>
</body>
</html>