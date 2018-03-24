<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'type' => 'advanced',
    'action'=>'/index/login',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => false,
    ),
)); ?>
<div class="row">
    <?php echo $form->label($model,'username',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'username',array('class'=>"el_inText",'placeholder'=>"请填写手机号/用户名")); ?>
    <?php echo $form->error($model,'username'); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'password',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->passwordField($model,'password',array('class'=>"el_inText",'placeholder'=>"请填写密码")); ?>
    <?php echo $form->error($model,'password'); ?>
</div>
<div class="row">
    <?php echo $form->label($model,'verifyCode',array('class'=>"el_lab",'style'=>'margin-right:10px')); ?>
    <?php echo $form->textField($model,'verifyCode',array('class'=>"el_inText el_inText_ssl",'placeholder'=>"请填写验证码")); ?>
    <?php $this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,
        'imageOptions'=>array('alt'=>'点击换图','title'=>'点击换图','style'=>'cursor:pointer;','class'=>'codeImg'))); ?>
    <?php echo $form->error($model,'verifyCode'); ?>
</div>
 
<div class="btns" style="text-align:center; overflow: hidden; clear: both;">
    <a class="fpwLink" href="<?php echo F::getPassportUrl('/site/fpw') ?>" style="font-size:14px; margin-top: -2px;">忘记密码？</a>
    <input type="button" class="el_btn_orange_zxl el_btn" id="userLogin" value="登 录" style="float:right;margin-top: 30px;"/>
</div>
<?php $this->endWidget(); ?>