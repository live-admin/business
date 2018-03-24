<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="keywords" content="彩生活">
        <meta name="description" content="彩生活">
        <link href="<?php echo F::getStaticsUrl('/common/css/page.css'); ?>" rel="stylesheet">
        <link href="<?php echo F::getStaticsUrl('/common/css/common.css'); ?>" rel="stylesheet">
        <link href="<?php echo F::getStaticsUrl('/common/css/base.css'); ?>" rel="stylesheet">
        <!--[if IE]>
        <link href="<?php echo F::getStaticsUrl('/common/css/ie.css'); ?>" rel="stylesheet">
        <![endif]-->
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
        <script src="<?php echo F::getStaticsUrl('/common/js/colourlife.common.js'); ?>"></script>
        <script src="<?php echo F::getStaticsUrl('/common/js/colourlife.page.js'); ?>"></script>
        <!--[if IE 6]>   
        <script src="<?php echo F::getStaticsUrl('/common/js/DD_belatedPNG.js'); ?>" ></script>   
        <script src="<?php echo F::getStaticsUrl('/common/js/DD_belatedPNG.use.js'); ?>" ></script>   
        <![endif]-->
        <script>
		
			/*
			 * 当前页相关信息
			 */
			 
        	(function() {
				
				// 当前页面配置信息
				pageConfig = {
					"pageItemType"		: 3,	// 当前页面所属的大类
					"mainNavCurIndex"	: 5,	// 主栏目中当前栏目的索引值，从1开始，0表示没有当前效果
					"subNavCurIndex"	: [3,3]	// 子级当前栏目索引值，从1开始，0表示没有当前效果；数组中第1至n个分别代表2级中第？个中的...的第n级的第？个的当前栏目索引值
				}
			})();
        </script>
	</head>
    
    <body>
    	<!-- 帮助中心头部 start -->
        <div class="hcMainNav">
        	<div class="el_container">
                <a href="/" class="logoLink">
                    <img src="<?php echo F::getStaticsUrl('/common/images/pic_blank.gif'); ?>" class="logoImg">
                </a>
                <div class="umMainNav_main">
                    <a class="item" href="/" title="彩之云">
                        <span class="item_cl box">
                            <i class="icon"></i>
                        </span>
                        <span class="title">彩之云</span>
                    </a>
                    <span class="sepLine"></span>
                    <a class="item" href="<?php echo  F::getHomeUrl("/about") ?>" title="关于我们">
                        <span class="item_au box">
                            <i class="icon"></i>
                        </span>
                        <span class="title">关于我们</span>
                    </a>
                    <span class="sepLine"></span>
                    <a class="item" href="/jobs" title="工作机会">
                        <span class="item_wc box">
                            <i class="icon"></i>
                        </span>
                        <span class="title">工作机会</span>
                    </a>
                    <span class="sepLine"></span>
                    <a class="item" href="/advertising" title="广告机会">
                        <span class="item_as box">
                            <i class="icon"></i>
                        </span>
                        <span class="title">广告机会</span>
                    </a>
                    <span class="sepLine"></span>
                    <a class="item" href="/help" title="帮助中心">
                        <span class="item_hc box">
                            <i class="icon"></i>
                        </span>
                        <span class="title">帮助中心</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- 帮助中心头部 end -->
        
        <div class="container" id="page">
            <?php echo $content; ?>
        </div><!-- page -->
        
        <!-- 公司链接区域 start -->
        <div class="companyBar_s1 companyBar el_container">
            <p class="copyrightBox">CopyRight All 彩之云 2013-2020</p>
            <p class="quicklinkBox">
                <span class="txt">网络文化经营许可证：文网文[2010]040号</span>
                |
                <span class="txt">增值电信业务经营许可证：粤B2-20080224-1</span>
                |
                <span class="txt">信息网络传播视听节目许可证：1109364号</span>
            </p>
        </div>
        <!-- 公司链接区域 end -->
		<div style="display:none">
            <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000022871'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000022871%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
        </div>
    </body>
</html>
