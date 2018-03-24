<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'type' => 'advanced',
    'action'=>'/hrJobs/apply',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
)); ?>
<input type="hidden" name="HrApply[invite_id]" value="<?php echo $job->id;?>"  />
<div class="row">
    <?php echo $form->label($model,'name',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'name',array('class'=>"el_inText",'placeholder'=>"您的姓名")); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'telephone',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'telephone',array('class'=>"el_inText",'placeholder'=>"您的联系电话")); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'address',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'address',array('class'=>"el_inText",'placeholder'=>"您的地址")); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'verifyCode',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'verifyCode',array('class'=>"el_inText el_inText_ssl",'placeholder'=>"请填写验证码")); ?>
    <?php $this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,
        'imageOptions'=>array('alt'=>'点击换图','title'=>'点击换图','style'=>'cursor:pointer;','class'=>'codeImg'))); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'apply_reason',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textArea($model,'apply_reason',array('class'=>"el_inTextArea",'placeholder'=>"申请理由")); ?>
</div>
<div class="row">
	<label class="el_lab" style="margin-right:10px">&nbsp;</label>
    <input type="button"  id="subApply" value="提交"/>
</div>
<?php $this->endWidget(); ?>