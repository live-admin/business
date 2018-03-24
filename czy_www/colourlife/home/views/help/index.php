
	    <!-- 主要内容区域 start -->
        <div class="hc_hc_info main el_container">
        	<div class="el_blank_h20"></div>
        	
            <table class="hcTabFrame" cellpadding="0" cellspacing="0">
            	<tr>
                	<td class="hcTabFrame_lp">
                        <!-- 帮助中心列表 start -->
                        <div class="hcConBox">
                            <div class="hcConBox_titBox">
                                <div class="inner">
                                    <span class="title"> <?php echo empty($detail)?'':$detail->title;  ?></span>
                                </div>
                                <div class="botLine"><div class="botLine_box"></div></div>
                            </div>
                            <div class="hcConBox_conBox">
                                <div class="el_blankDemo_s10">

                                <?php echo empty($detail)?'':$detail->content;  ?>
                                </div><!-- demo 开发时请删除 -->
                            </div>
                        </div>
                        <!-- 帮助中心列表 end -->
                    </td>
                    <td class="hcTabFrame_mp">&nbsp;</td>
                    <td class="hcTabFrame_rp">
                        <!-- 侧栏 start -->
                        <div class="hcConBox">
                            <div class="hcConBox_titBox">
                                <div class="inner">
                                    <span class="title">帮助中心</span>
                                </div>
                                <div class="botLine"></div>
                            </div>
                            <div class="hcConBox_conBox">
                                <!-- hcSideBar start -->
                                <div class="hcSideBar">

                                <?php echo $this->createRightMenu($right,2);

                                    ?>

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
        <script>

            /*
             * 当前页相关信息
             */

            (function() {

                // 当前页面配置信息
                pageConfig = {
                    "pageItemType"		: 3,	// 当前页面所属的大类
                    "mainNavCurIndex"	: 5,	// 主栏目中当前栏目的索引值，从1开始，0表示没有当前效果
                    "subNavCurIndex"	: [<?php  echo $this->getSequence(); ?>]	// 子级当前栏目索引值，从1开始，0表示没有当前效果；数组中第1至n个分别代表2级中第？个中的...的第n级的第？个的当前栏目索引值
                }
            })();
        </script>