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
                                    <span class="title">职位列表</span>
                                </div>
                                <div class="botLine"><div class="botLine_box"></div></div>
                            </div>
                            <div class="hcConBox_conBox">
                                <div class="hc_wc_lstBox">
									<?php foreach ($jobs as $job){?>
										<a class='link' href='/hrJobs/<?php echo $job->id;?>' >
											<span class='date'><?php echo $job->pub_date;?></span> 
										 	<span class='jobName'><?php echo $job->position;?></span>
										</a>
									<?php }?>
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
                                    <span class="title">企业信息</span>
                                </div>
                                <div class="botLine"></div>
                            </div>
                            <div class="hcConBox_conBox">
                            	<!-- hcSideBar start -->
                            	<div class="hcSideBar">
                            	 <div class='hcConBox_conBox'>
							        <div class='hcSideBar'>
								        <div class='itemBox'>
								        	<span class='linkBox'>
								        	<a href='javascript:void(0)'  class='subLink'>企业名称：<?php echo $right->name?></a>
								        	</span>
								        </div>
								        <div class='itemBox'>
								        	<span class='linkBox'>
								        	<a href='javascript:void(0)'  class='subLink'>联系电话：<?php echo $right->telephone?></a>
								        	</span>
								        </div>
								        <div class='itemBox'>
								        	<span class='linkBox'>
								        	<a href='javascript:void(0)'  class='subLink'>Email：<?php echo $right->email?></a>
								        	</span>
								        </div>
								        <div class='itemBox'>
								        	<span class='linkBox'>
								        	<a href='javascript:void(0)'  class='subLink'>地址：<?php echo $right->address?></a>
								        	</span>
								        </div>
								        <div class='itemBox'>
								        	<span class='linkBox'>
								        	<a href='javascript:void(0)'  class='subLink'>邮编：<?php echo $right->postcode?></a>
								        	</span>
								        </div>
								        <div class='itemBox'>
								        	<span class='linkBox'>
								        	<a href='javascript:void(0)'  class='subLink'>联系人：<?php echo $right->contacter?></a>
								        	</span>
								        </div>
								     </div>
							     </div>
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