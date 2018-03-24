<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta name="MobileOptimized" content="240">
    <title>支付测试</title>  
</head>
<body>
    <select id="sn" style="width: 250px">
<?php 
$order = ThirdFees::model()->findAll('status!=:status1 and status!=:status99', array(':status1'=>1, 'status99'=>99));
if ($order){
    foreach($order as $k=>$v){
        echo '<option value="' .$v->sn. '">' .$v->sn.'</option>';
    }
}
?>
</select> 
    
    
<input type="button" value="去支付" onclick="test()">
<script>
function test(){
       var sn = document.getElementById('sn').value;
       if (!sn) {alert('sn is null'); return;}
    //如：彩之云单号为：2030002150421134304090，回调地址（支付成功或失败后会调用商家传过来的地址用来显示给用户）：http://www.xxxx.com/pay/return
   // if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
       ios(sn, 'http://www.5ker.com:6888/XieyiApp/ok')//苹果调用代码
    //} else {
     //  android('90020000150513122505698', 'http://www.xxxx.com/pay/return');//android调用代码
    //}
}


//（2）	JS调用ＩＯＳ代码
/*
 * 格式：*payFromHtml5*彩之云订单号*回调状态地址
 * payFromHtml5,ios会自动识别
 * @param sn彩之云订单号
 * @param url回调地址
 * @return 
 * 2015.5.6
 */
function ios(sn, url){
    var url= "*payFromHtml5*" + sn + "*" + url;
    location.href=url;
    //或用iframe来访问：
    //<iframe src=url></iframe>
}
</script>

</body>
</html>
