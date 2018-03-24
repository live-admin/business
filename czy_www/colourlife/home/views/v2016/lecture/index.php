<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>银杏院讲座</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/lecture/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/lecture/');?>css/normalize.css">
</head>
<body>
<div class="content">
    <header class="header">
        <h5>《银杏院讲堂》</h5>
        <h1>深圳入冬保健</h1>
        <hr class="hr">
        <h5>途院走进彩生活社区系列活动</h5>
    </header>
    <section class="section">
        <div class="arcticle_l">
            <h1>听清楚</h1>
            <h5>才不辜负这美景</h5>
            <p style="margin:1.0rem 0 0.15rem 0.15rem"><span>内容1：</span><span>传授专业的中医方法防治耳聋、耳背，让沟通无障碍</span></p>
        </div>
        <div class="arcticle_r" style="padding:0.55rem 0.05rem 0.05rem">
            <img src="<?php echo F::getStaticsUrl('/activity/v2016/lecture/');?>images/music.png">
        </div>
    </section>
    <section class="section_figure">
        <div class="arcticle_l">
            <h1>找准穴位</h1>
            <h5>才能让书上的按摩方法发挥作用</h5>
            <p><span>内容2：</span><span>手把手教父母找到正确的穴位，避免按错穴位适得其反</span></p>
        </div>
        <div class="arcticle_f">
            <img src="<?php echo F::getStaticsUrl('/activity/v2016/lecture/');?>images/figure.png">
        </div>
    </section>
    <article class="article_p">
        <p>我们承诺：</p>
        <p>本系列活动为专业讲座，聘请经验丰富医师，旨在提高家庭保健知识，不涉及任何产品推销</p>
    </article>
    <article class="article_con">
        <fieldset>
            <legend>讲座信息</legend>
            <ul>
                <li><span>时间：</span><span>2016年11月6日 下午 3：30 — 4：30</span></li>
                <li><span>地点：</span><span>香缇雅苑5栋1楼老年人活动中心</span></li>
                <li><span>内容：</span><span>找准按摩穴位&nbsp;中医防治耳聋耳背</span></li>
                <li><span>主讲人：</span><span>沈志勇<br/>广州中医药大学硕士毕业<br/>广州中医药大学附属医院就职<br/>擅长运用中药、针灸疗法<br/>世界中医院药联合会五官分会青年委员<br/>广东省中医耳鼻喉学会会员</span></li>
                <li><span>报名费：</span><span>59元</span></li>
            </ul>
        </fieldset>
    </article>
    <div class="button">
        点击报名
    </div>
</div>
<script>
    $(document).ready(function(){
        var ctgUrl= "<?php echo $ctgUrl;?>";
        var pid= "<?php echo $pid;?>";
        $(".button").click(function(){
            window.location.href=ctgUrl+"&pid="+pid;
        });
    });
</script>
</body>
</html>