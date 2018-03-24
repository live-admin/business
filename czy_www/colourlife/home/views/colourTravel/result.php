<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>花样游记-结果公布</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/ReFontsize.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>css/layout.css"/>
    <style type="text/css">
        body{
            background-color: #F6E3C7;
        }
    </style>
</head>
<body>
<div class="conter results">
    <div class="results_bg">
        <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/bg_01.jpg" />
    </div>
    <div class="results_tab">
        <div class="results_tab_titile">
            <div class="results_tab_titilebox1a">
                <p>用户名</p>
            </div>
            <div class="results_tab_titilebox2a">
                <p>游记标题</p>
            </div>
            <div class="results_tab_titilebox3a">
                <p>点赞数</p>
            </div>
        </div>

        <!--第1个开始-->

        <?php foreach($result as $v){?>
        <div class="results_tab_content">
            <div class="results_tab_contentbox1a">
                <p><?php $model=TravelShare::model();
                    echo $model->getMobile($v->customer_id)?></p>
            </div>
            <div class="results_tab_contentbox2a">
                <p><?php echo mb_substr($v->share_title,0,7,'utf-8');?>…</p>
            </div>
            <div class="results_tab_contentbox3a">
                <p><?php echo $v->share_like;?></p>
            </div>
        </div>
        <?php }?>
        <!--第1个结束-->

    </div>
</div>
</body>
</html>
