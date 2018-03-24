<link href="<?php echo F::getStaticsUrl('/common/css/paltformshop.mobile.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
<style>
<!--
.row .el_lab{
	width: 20%;
	display: inline-block;
    vertical-align: top;
	text-align: right;
	line-height: 200%;
}

.row .el_inText {
    width:60%;
    vertical-align: middle;
	line-height: 200%;
}
.row .el_inTextArea {
    vertical-align: middle;
	height: 10%;
	width:60%;
}
.row #subApply {
	margin-top:10px;
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
		//登录
		function loginCheck(name,telephone,address,applyReason){
			$(".showError ul li").remove();
			$.ajax({
				type: 'post', cache: false, dataType: 'json',
				url: "http://"+window.location.host+"/hrJobsApp/applyApp",
				data:{"HrApply[name]":name,"HrApply[telephone]":telephone,"HrApply[address]":address,"HrApply[apply_reason]":applyReason,'HrApply[invite_id]':<?php echo $id?>},
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
			loginCheck(name,telephone,address,applyReason);
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

<style>
a{text-decoration:none; color:#9c9b9b;}
.main{color: #9c9b9b;font-size: 14px;}
.applyfor_interview,.position,.rm_list{width:95%; margin:10px auto;}
.person_info div{margin:10px 0; text-align:left;}
.person_info label{float:left;}
.info_input{width:75%; border:1px solid #b0b0b0; background:#fff; height:30px; line-height:30px;}
.goback{width:100%; text-align: center; display:block; padding:10px 0; background:#cecece; color:#505050; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px;}
.applyfor_interview textarea{width:98%; border:1px solid #b0b0b0; background:#fff; height:90px; margin-top:8px;}
.submitcontairn{padding-top:20px;}
.submit_button{display:block; width:100%; padding:10px 0; border:none; background:#ff7e00; color:#fff; font-size:16px; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px; cursor:pointer;}
</style>
<div class="main">
<div class="applyfor_interview">
<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'type' => 'advanced',
    'action'=>'/hrJobs/applyAp',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
)); ?>
<a href="/hrJobsApp/<?php echo $id;?>" class="goback">上一步</a>
<div class="person_info">
<input type="hidden" name="HrApply[invite_id]" value="<?php echo $id;?>"  />
<div class="row">
    <?php echo $form->label($model,'name',array('class'=>"wordlike4",'style'=>'margin-right:40px')); ?>
    <?php echo $form->textField($model,'name',array('class'=>"info_input",'placeholder'=>"您的姓名")); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'telephone',array('class'=>"wordlike4",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'telephone',array('class'=>"info_input",'placeholder'=>"您的联系电话")); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'address',array('class'=>"wordlike4",'style'=>'margin-right:40px')); ?>
    <?php echo $form->textField($model,'address',array('class'=>"info_input",'placeholder'=>"您的地址")); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'apply_reason',array('class'=>"wordlike4",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textArea($model,'apply_reason',array('class'=>"info_input",'placeholder'=>"申请理由")); ?>
</div>
<div class="submitcontairn">
	<label class="wordlike4" style="margin-right:10px">&nbsp;</label>
    <input type="button" class="submit_button" id="subApply" value="提交"/>
</div>
</div>

<?php $this->endWidget(); ?>
</div>
</div>

<div class="showError">
	<ul>
	</ul>
</div>