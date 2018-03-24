<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="彩生活">
    <meta name="description" content="彩生活">
	<title><?php echo "搜索 ".(empty($_GET['name'])?"":$_GET['name'])."  的结果页"; ?></title>
    <link href="<?php echo F::getStaticsUrl('/common/css/page.css'); ?>" rel="stylesheet">
    <link href="<?php echo F::getStaticsUrl('/common/css/common.css'); ?>" rel="stylesheet">
    <link href="<?php echo F::getStaticsUrl('/common/css/base.css'); ?>" rel="stylesheet">
    <link href="<?php echo F::getStaticsUrl('/common/css/form.css'); ?>" rel="stylesheet">
    <!--[if IE]>
    <link href="<?php echo F::getStaticsUrl('/common/css/ie.css'); ?>" rel="stylesheet">
    <![endif]-->
    <link href="<?php echo F::getStaticsUrl("/common/css/style.css"); ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
</head>
<body class="s_info_lst">
<style>
    .pagesBox {
        text-align: left;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#list").find(".community_list_div").map(function () {
            $(this).mousemove(function () {
                $(this).removeClass("fad2");
                $(this).addClass("fad1");
            });
            $(this).mouseout(function () {
                $(this).removeClass("fad1");
                $(this).addClass("fad2");
            });
        });
    });

</script>
<!-- 工具栏 start -->
<div class="topBanner_box">
    <div class="toolbar el_container">

        <!-- left part start -->
        <div class="fn_left">
            <a class="favorite el_btn" title="添加收藏">
                <i class="icon"></i>
                <span class="txt">添加收藏</span>
            </a>
            <a class="phoneToCL el_btn" title="手机彩之云" href="<?php echo FootItems::getAppLink(); ?>" target="_blank">
                <i class="icon"></i>
                <span class="txt">手机彩之云</span>
            </a>
            <a class="ttWeibo shareBox el_btn" href="<?php echo FootItems::getTqqLink(); ?>" target="_blank">
                <i class="icon"></i>
            </a>
            <a class="sinaWeibo shareBox el_btn" href="<?php echo FootItems::getWeiboLink(); ?>" target="_blank">
                <i class="icon"></i>
            </a>
            <a class="weixin shareBox el_btn">
                <i class="icon"></i>
                    <span class="shareBox_inner">
                        <strong class="shareBox_box">
                            <img src="<?php echo F::getStaticsUrl('/common/images/pic_blank.gif'); ?>" class="codeImg">
                            <span class="txt">关注微信公众账号，获取更多优惠。</span>
                        </strong>
                    </span>
            </a>
        </div>
        <!-- left part end -->

        <!-- right part start -->
        <div class="fn_right">
            <?php if (Yii::app()->user->isGuest) { ?>
                <a href="<?php echo F::getPassportUrl('/site/login'); ?>"
                   class="optLink el_btn">[登录]</a><!-- title="登录" -->
                <a href="<?php echo F::getPassportUrl('/site/reg'); ?>" class="optLink el_btn" class="免费注册">[免费注册]</a>
            <?php } else { ?>
                <a href="<?php echo F::getCyzUrl(); ?>"
                   class="optLink el_btn"><?php echo Yii::app()->user->username; ?></a>
                <a href="<?php echo F::getCyzUrl('/order'); ?>" class="optLink el_btn" title="我的订单">我的订单</a>
                <a href="<?php echo F::getPassportUrl('/site/logout'); ?>" class="optLink el_btn" title="退出">退出</a>
            <?php } ?>
            <span href="#" class="optMore el_btn">
                    <span class="optMore_item">
                        <span class="txt" title="帮助">帮助</span>
                        <i class="icon"></i>
                    </span>
                    <span class="optMore_box">
                        <div class="optMore_box_inner">
                            <?php $data = FootItems::getHelpItems();
                            for ($i = 0; $i < count($data); $i++) { ?>
                                <?php if ($i == 0) { ?>
                                    <a href="<?php echo F::getHomeUrl($data[$i]['url']); ?>"
                                       class="optMore_link optMore_link_first">   <?php echo $data[$i]['title']; ?></a>
                                <?php } elseif ($i == (count($data) - 1)) { ?>
                                    <a href="<?php echo F::getHomeUrl($data[$i]['url']); ?>"
                                       class="optMore_link optMore_link_last"><?php echo $data[$i]['title']; ?></a>
                                <?php } else { ?>
                                    <a href="<?php echo F::getHomeUrl($data[$i]['url']); ?>"
                                       class="optMore_link"><?php echo $data[$i]['title']; ?></a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </span>
                </span>
        </div>
        <!-- right part end -->

        <div class="fn_clear"></div>
    </div>
</div>
<!-- 工具栏 end -->

<form action="" method="get">
    <div class="www">

        <div class="search">
        <a href="<?php echo F::getCommunityUrl(); ?>">
            <img src="<?php echo F::getStaticsUrl("/common/images/fcaishenghuo.gif"); ?>" width="160" height="52"
                 class="fpic"/>
		</a>
            <form action="/community" method="get">
                <input type="text" placeholder="请输入小区名" value="<?php echo empty($searchValue) ? "" : $searchValue ?>"
                       class="ftxt" name="name"/>

                <input name="" type="submit" value="  " class="fbut"/>
            </form>
        </div>

        <div class="fad" id="list">
            <?php if (!empty($communityList)) { ?>
                <?php foreach ($communityList as $community) { ?>
                    <div class="fad2  community_list_div"  <?php echo $community->id ?>>
                        <p class="title4">
                            <a target="_blank" href="<?php echo F::getFrontendUrl(strtolower($community->domain)); ?>">
                                <?php echo $community->name; ?>
                            </a>
                        </p>

                        <p class="fp1">所在地域：
                            <?php echo implode('-', F::getRegion($community->region_id)); ?>
                        </p>
                        <?php $goods = $model->getSaleGoods($community->id); ?>
                        <?php if (!empty($goods)) ?>
                        <p class="fp1">
                            <?php if (!empty($goods)) { ?>
                                天天特价：
                                <label class="fprice">
                                    <?php echo $goods[0]["name"] ?>
                                    ￥<?php echo $goods[0]["cheap_price"] ?>

                                    <?php echo empty($goods[0]["unit"]) ? "" : " / " . $goods[0]["unit"]; ?>

                                </label>
                            <?php } ?>
                        </p>

                        <p class="fp2">
                            <?php echo empty($goods[0]["description_html"]) ? "" : $goods[0]["description_html"]; ?>
                        </p>
                        <?php if (!empty($goods)) { ?>
                            <p class="fp1">

                                有效期：<?php echo date('Y-m-d', $goods[0]["start_cheap_time"]) ?>
                                至
                                <?php echo date('Y-m-d', $goods[0]["end_cheap_time"]) ?>
                            </p>
                            <input type="button" class="fbut1" value="立即订购"/>
                        <?php } ?>
                        <p class="fp3">
                            <a href="<?php echo F::getFrontendUrl(strtolower($community->domain)); ?>">
                                <?php echo F::getFrontendUrl(strtolower($community->domain)); ?>
                            </a>
                        </p>
                    </div>
                <?php } ?>
            <?php } else {
                echo "没有找到数据.";
            } ?>
            <!-- 热门 -->
            <!--
      	 <div class="fzone">
         	<div class="hot">
   	     <img src="<?php echo F::getStaticsUrl("/common/images/hot.gif");?>" width="36" height="15"  class="fpic1"/> 
         	<p class="title">&nbsp;热门小区</p>
         	</div>
            <div class="zone1">
            	<p class="title5">碧水龙庭</p>
                <p class="fp4">所在地域：广东省-深圳市-龙华新区</p>
            </div>
            <div class="zone1">
            	<p class="title5">碧水龙庭</p>
                <p class="fp4">所在地域：广东省-深圳市-龙华新区</p>
            </div>
            <div class="zone1">
            	<p class="title5">碧水龙庭</p>
                <p class="fp4">所在地域：广东省-深圳市-龙华新区</p>
            </div>
         
      </div> 
         -->
            <!-- /热门  -->


            <div class="clear"></div>
            <div class="fyema">
                <?php
                $this->widget('MyPager', array(
                        'header' => '',
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '末页',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel' => '下一页',
                        'pages' => $pages,
                        'maxButtonCount' => 5,
                        'htmlOptions' => array('class' => 'pagesBox'),
                    )
                );
                ?>

            </div>
            <div class="search1">
                <form action="/community" method="get">
                    <input name="" type="text" name="name" value="<?php echo empty($searchValue) ? "" : $searchValue ?>"
                           placeholder="请输入小区名" class="ftxt1"/>
                    <input name="" type="submint" value="  " class="fbut2"/>
                </form>
            </div>
        </div>
    </div>
</form>
<!-- 服务快链区域 start -->
<div class="serverBar">
    <div class="serverBar_box el_container">

        <!-- borderBox start -->
                <span class="borderBox">
                    <!-- item start -->
                    <?php

                    foreach (FootItems::getItems() as $arr) {
                        ?>
                        <span class="item">
                              <strong class="title"><?php echo $arr['title']; ?></strong>
                                   <span class="box">
                                <?php foreach ($arr['items'] as $a) { ?>
                                    <a href="<?php echo F::getHomeUrl($a['url']); ?>" class="link"
                                       title="<?php echo $a['name']; ?>"><?php echo $a['name']; ?></a>
                                    <em class="el_blank"></em>
                                <?php } ?>
                               </span>
                    </span>
                    <?php } ?>

                    <!-- item end -->
                </span>
        <!-- borderBox end -->

        <!-- exBox start -->
                <span class="exBox">
                    <!-- item start -->
                    <span class="item">
                        <strong class="title">联系我们</strong>
                        <span class="box">
                            <a href="mailto:Service@colourlife.com" class="link"
                               title="彩之云简介">Service@colourlife.com</a>
                            <em class="el_blank"></em>
                            <a class="txt">客服:4008-893-893</a>
                            <em class="el_blank"></em>
                            <a class="txt">业务：0755-999999999</a>
                        </span>
                    </span>
                    <!-- item end -->
                </span>
        <!-- exBox start -->
    </div>
</div>
<!-- 服务快链区域 end -->
<!-- 公司链接区域 start -->
<div class="companyBar el_container">
    <p class="quicklinkBox">
        <?php $data = FootItems::getBottom();
        for ($i = 0; $i < count($data); $i++) { ?>
            <a href="<?php echo F::getHomeUrl($data[$i]['url']); ?>" class="link"
               title="<?php echo $data[$i]['title']; ?>"><?php echo $data[$i]['title']; ?></a>
            <?php if ($i < (count($data) - 1)) { ?>
                |
            <?php } ?>
        <?php } ?>
    </p>

    <p class="copyrightBox">CopyRigth ©2013-2014 Colour Life All Rights Reserved</p>
</div>
<!-- 公司链接区域 end -->
</body>
</html>

