<script>

    /*
     * 当前页相关信息
     */

    (function() {

        // 当前页面配置信息
        pageConfig = {
            "pageItemType"		: 3,	// 当前页面所属的大类
            "mainNavCurIndex"	: 2,	// 主栏目中当前栏目的索引值，从1开始，0表示没有当前效果
            "subNavCurIndex"	: [<?php echo $num; ?>]	// 子级当前栏目索引值，从1开始，0表示没有当前效果；数组中第1至n个分别代表2级中第？个中的...的第n级的第？个的当前栏目索引值
        }
    })();
</script>
<!-- 主要内容区域 start -->
<div class="hc_au main el_container">
    <div class="el_blank_h20"></div>

    <table class="hcTabFrame" cellpadding="0" cellspacing="0">
        <tr>
            <td class="hcTabFrame_lp">
                <!-- 彩之云介绍 start -->
                <div class="hcConBox">
                    <div class="hcConBox_titBox">
                        <div class="inner">
                            <span class="title"><?php echo (empty($right)?'':$right[$num-1]->name); ?></span>
                        </div>
                        <div class="botLine"><div class="botLine_box"></div></div>
                    </div>
                    <div class="hcConBox_conBox">
                        <div class="hc_wc_lstBox">
                        <?php  echo empty($model)?'':$this->createfrontView($model); ?>

                        </div>
                        <!-- 分页 start -->
                        <?php
                        $this->widget('MyPager',array(
                                'header'=>'',
                                'firstPageLabel' => '首页',
                                'lastPageLabel' => '末页',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel' => '下一页',
                                'pages' => $pages,
                                'maxButtonCount'=>13,
                                'htmlOptions'=>array('class'=>'pagesBox'),
                            )
                        );
                        ?>
                        <!-- 分页 end -->

                    </div>
                </div>
                <!-- 彩之云介绍 end -->
            </td>
            <td class="hcTabFrame_mp">&nbsp;</td>
            <td class="hcTabFrame_rp">
                <!-- 侧栏 start -->
                <div class="hcConBox">
                    <div class="hcConBox_titBox">
                        <div class="inner">
                            <span class="title">关于我们</span>
                        </div>
                        <div class="botLine"></div>
                    </div>
                    <div class="hcConBox_conBox">
                        <!-- hcSideBar start -->
                        <div class="hcSideBar">
                            <?php  echo empty($right)?'':$this->createRightMenu($right,1); ?>
                        </div>
                        <!-- hcSideBar end -->
                    </div>
                </div>
                <!-- 侧栏 end -->
            </td>
        </tr>
    </table>

    <div class="el_blank_h50 fn_clear"></div>
</div>
<!-- 主要内容区域 end -->