        <!-- 主要内容区域 start -->
        <?php if($code=='404'): ?>
        <div class="s_loc_hou main el_container">
        	<div class="error_box">
            	<div class="error_con">
                	<p class="error_title_box">
                    	<span class="error_title"><?php echo CHtml::encode($message); ?></span>
                        <a href="javascript:back(-1);">
                            <i class="error_button">
                                <img src="<?php echo F::getStaticsUrl('/common/images/back.png'); ?>" />
                            </i>
                            <span class="errror_button_txt">
                                返回上一页
                            </span>
                        </a>
                    </p>
                    <div style="clear:both"></div>
                    <p>1、请检查您输入的网址是否正确。</p>
                    <p>2、有可能我们的页面正在升级或维护。</p>
                    <p>3、您可以尝试访问右边的链接。 
                        <a href="<?php echo F::getHomeUrl(); ?>" class="error_add">http://www.colourlife.com/</a>
                    </p>
                </div>
            	
            </div>
        
            
            <div class="el_blank_h50 fn_clear"></div>
        </div>
        <?php else: ?>
        <h2>Error <?php echo $code; ?></h2>

        <div class="error">
            <?php echo CHtml::encode($message); ?>
        </div>
        <?php endif; ?>
        <!-- 主要内容区域 end -->
