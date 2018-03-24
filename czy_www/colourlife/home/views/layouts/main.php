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
        <style type="text/css">
        /*弹出框*/
/*.opacity{width:100%; height:100%; position:absolute; left:0; top:0; z-index:1001;}
.alertcontairn{width:500px; border:2px solid #ff7e00; text-align:left; position:relative; background:#fff; font-size:14px;}
.alertcontairn a.close {width:11px;height:12px;display:inline-block;background:url(<?php echo F::getStaticsUrl('/common/images/pic_elements.png');?>) no-repeat 0 -2949px;float:right;margin:10px 10px 0 0;}
.alertcontairn h3{padding:10px; border-bottom:1px solid #f0f0f0; font-size:18px;}
.alertcontairn_content{padding:20px;}
.textinfo{width:85%; margin:0 auto; text-align:left; padding:25px 0;}
.textinfo p{line-height:200%;}
.textredcolor{color:#ff7e00;}
.pop_btn{padding:15px 0; text-align:center; border-top:1px solid #f0f0f0;}
.pop_btn a{border:1px solid #eeeded; text-decoration:none; display:inline-block; margin:0 15px; width:100px; height:30px; line-height:30px; background:#eeeded;}
.pop_btn a span{display:inline-block; padding:5px 0; background:#009726; width:100%; border-radius:8px; -webkit-border-radius:8px; -moz-border-radius:8px;}
.pop_btn a.closeOpacity{border:1px dashed #8e8e8e;}
.pop_btn a.closeOpacity span{background:#8e8e8e;}
.alertcontairn .yesorno{display:inline-block; width:112px; height:28px; background-position:-140px -40px; line-height:28px; color:#000; margin:15px 20px;}
.pop_btn a.gotopay{border:1px solid #ff7e00; background:#ff7e00; color:#fff;}
.warm_tt{margin-bottom:20px; font-size:18px; font-weight:lighter; color:#000;}
.warm_bottm{margin:30px 0 0; text-align:right;}*/
       </style>
	</head>
    <!--弹出框-->
<!-- <div class="opacity" style="position:fixed; background:url(<?php echo F::getStaticsUrl('/common/images/blackopacity.png')?>) repeat;">
  <div class="alertcontairn" style="width:700px;">
     <a href="javascript:void(0)" class="close">&nbsp;</a>
     <div class="alertcontairn_content">
       <div class="textinfo">
         <h4 class="warm_tt">各位亲爱的业主：</h4>
         <p style="text-indent:24px;">
           为了给您带来更好的服务与体验，彩之云计划于2015/1/25（周日）晚上10点——2015/1/26（周一）上午8点对彩之云服务器进行升级，届时彩之云APP与彩之云网站将不能使用，给您的生活带来不便，敬请谅解！我们将以更优质的服务回报您的支持，谢谢~
         </p>
         <p class="warm_bottm">彩生活服务集团有限公司<br/>2015/1/22</p>
       </div>
     </div>
  </div>

</div> -->
<!--弹出框-->
    <body>
    	
        
        <div class="container" id="page">
            <?php echo $content; ?>
        </div><!-- page -->

	<!-- 底部 end -->
        <div class="homeBotBox">
            <span class="homeBotBox_txt">
              
            </span>
            <p/>
            <a target="_blank" style="color: #fff" href="http://www.miitbeian.gov.cn">粤ICP备13038151号</a>
        </div>
        <!-- 底部 end -->
        
       
    </body>
    <script type="text/javascript">
    //弹出框居中函数
    //  function center(obj) {
    //   var screenWidth = $(window).width();
    //   var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
    //   var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
    //   var objLeft = (screenWidth - obj.width())/2 ;
    //   var objTop = (screenHeight - obj.height())/2 + scrolltop;
    //   obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
    //   //浏览器窗口大小改变时
    //   $(window).resize(function() {
    //   screenWidth = $(window).width();
    //   screenHeight = $(window).height();
    //   scrolltop = $(document).scrollTop();
    //   objLeft = (screenWidth - obj.width())/2 ;
    //   objTop = (screenHeight - obj.height())/2 + scrolltop;
    //   obj.css({left: objLeft + 'px', top: objTop + 'px'});
    //   });
     
    // } 
    
    // function alertPop(){
    //    $('.opacity').show();
    //    center($(".alertcontairn"));
    // }
    // $('.close').click(function(){
    //    $('.opacity').hide();
    // })
    
    // window.onload=alertPop;
    </script>
</html>
