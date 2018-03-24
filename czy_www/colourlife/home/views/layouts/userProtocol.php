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
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.tinyscrollbar.min.js'); ?>"></script>
		<script src="<?php echo F::getStaticsUrl('/common/js/often_use.js'); ?>"></script>
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
	<div class="hcMainNav">
		<div class="el_container">
			<a href="/" class="logoLink">
				<img src="<?php echo F::getStaticsUrl('/common/images/pic_blank.gif'); ?>" class="logoImg">
			</a>
		</div>
        </div>
        <div class="container" id="page">
            <?php echo $content; ?>
        </div>
    </body>
</html>
