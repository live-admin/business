<style>
<!--
.left{text-align: right;}
.jobInfo td{border-bottom: 1px dotted green;line-height:25px; }
.apply{margin-top: 20px;}
.wannaApply{font-size: 16px;}
.showApply{margin-top: 10px;border: 1px solid #C1C1C1;padding:10px 0;}
.showApply .row{margin-top: 10px;}
.showApply .row .el_lab{
	width: 84px;
	display: inline-block;
    font-size: 12px;
    height: 42px;
    line-height: 42px;
    text-align: right;
    vertical-align: top;
}

.showApply .row .el_inText {
    height: 40px;
    line-height: 40px;
    padding-left: 5px;
    vertical-align: middle;
    width: 244px;
}
.showApply .row .el_inTextArea {
    height: 80px;
    padding-left: 5px;
    vertical-align: middle;
    width: 370px;
}
.showApply .row .codeImg {
   height: 40px;
	vertical-align: middle;
	border: 1px solid #C1C1C1;
}
.showApply .row #subApply {
  height: 40px;
    size: 50px;
    width: 80px;
}
.showError{padding-left: 40px;color:red;}
.showError li{list-style:disc;}
-->
</style>

<script type="text/javascript">
<!--
$(document).ready(function(){
		$(".wannaApply").click(function(){
			$(".showApply").toggle();
		});

		//登录
		function loginCheck(name,telephone,address,verifyCode,applyReason){
			$(".showError ul li").remove();
			$.ajax({
				type: 'post', cache: false, dataType: 'json',
				url: "http://"+window.location.host+"/hrJobs/apply",
				data:{"HrApply[name]":name,"HrApply[telephone]":telephone,"HrApply[address]":address,"HrApply[verifyCode]":verifyCode,"HrApply[apply_reason]":applyReason,'HrApply[invite_id]':<?php echo $job->id?>},
				async: true,
				success: function (data) {
					if (data!= null && data!= "") {
					//处理
						if(data['success']==1)
						{
							addInfo(['提交成功，请等候我们的审核！']);
							$("#subApply").remove();
						}
						else
						{
							//SimulationAlert(data['ok']);
							$(".codeImg").click();
							var errors=data.data.errors;
							addInfo(errors);
						}
						return;
					} else {
						alert('提交失败，请重试！');
					}
				},
				error: function (result) {
					
				}
			});
		}
		
		$("#subApply").click(function() {
			var name = $("#HrApply_name").attr("value");
			var telephone = $("#HrApply_telephone").attr("value");
			var address = $("#HrApply_address").attr("value");
			var applyReason = $("#HrApply_apply_reason").attr("value");
			
			
			var verifyCode = $("#HrApply_verifyCode").attr("value");
			if( name == '')
			{
				addInfo(['用户名不能为空！']);
				return false;
			}
			else if( telephone == '')
			{
				addInfo(['联系电话不能为空！']);
				return false;
			}else if( address == '')
			{
				addInfo(['地址不能为空！']);
				return false;
			}
			else if(verifyCode == '')
			{
				addInfo(['验证码不能为空！']);
				return false;
			}else if( applyReason == '')
			{
				addInfo(['申请理由不能为空！']);
				return false;
			}

			var isMobile=/^(?:13\d|15\d)\d{5}(\d{3}|\*{3})$/;   
			var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;

			if(!isMobile.test(telephone) && !isPhone.test(isPhone)){
				addInfo(['联系电话格式错误！']);
				return false;
			}
			loginCheck(name,telephone,address,verifyCode,applyReason);
		});

		function addInfo(infos){
			$(".showError ul li").remove();
			for(var i=0;i<infos.length;i++){
				$(".showError ul").append("<li>"+infos[i]+"</li>");
			}
		}
		
});
//-->
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
                                    <span class="title">
                                    	<a href="/hrJobs/index">职位列表</a> --&gt; <?php echo $job->position; ?>
                                    </span>
                                </div>
                                <div class="botLine"><div class="botLine_box"></div></div>
                            </div>
                            <div class="hcConBox_conBox">
                                <div class="hc_wc_infoBox">
                          			<?php if(!empty($job)){?>
                          			<table class='jobInfo'>
                          				<tr>
                          					<td class="left">职位：</td><td><?php echo $job->position;?></td>
                          				</tr>
                          				<tr>
                          					<td class="left">部门：</td><td><?php echo $job->department;?></td>
                          				</tr>
                          				<tr>
                          					<td  class="left">发布时间：</td><td><?php echo $job->pub_date;?></td>
                          				</tr>
                          				<tr>
                          					<td class="left">有效时间：</td><td><?php echo $job->start_date;?> 至 <?php echo $job->end_date;?></td>
                          				</tr>
                          				<tr>
                          					<td class="left">工作地点：</td><td><?php echo $job->work_place;?></td>
                          				</tr>
                          				<tr>
                          					<td class="left">招聘人数：</td><td><?php echo $job->need_person;?></td>
                          				</tr>
                          				<tr>
                          					<td class="left">薪资：</td><td><?php echo $job->pay;?></td>
                          				</tr>
                          				
                          				<tr>
                          					<td class="left">工作职责：</td><td><?php echo $job->work_content;?></td>
                          				</tr>
                          				<tr>
                          					<td class="left">任职要求：</td><td><?php echo $job->work_need;?></td>
                          				</tr>
                          				<?php if(!empty($job->remark)){?>
                          					<tr>
                          						<td class="left">备注：</td><td><?php echo $job->remark;?></td>
                          					</tr>
                          				<?php }?>
                          			</table>
	                          		<?php }?>
                        		</div>
        <!-- 申请表单 start-->
        	<div class="apply">
        		<a href="javascript:void(0)" class="wannaApply">我要申请</a>
		        <div class="showApply" style="display:none;">
    
		       <!-- 申请框 start -->
		       	<?php $this->renderPartial('_apply',array('model'=>$model,"job"=>$job)); ?>
                <!-- 申请框 end -->
                <div class="showError">
                	<ul>
                	</ul>
                </div>
		        </div>
	        </div>
        <!-- 申请表单 end-->
        
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
        
        
        