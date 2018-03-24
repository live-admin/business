
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
                    
                </tr>
            </table>
            
            <div class="el_blank_h50 fn_clear"></div>
        </div>