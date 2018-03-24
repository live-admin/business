<?php echo "<?php \$form=\$this->beginWidget('ActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
    'type' => 'advanced',
)); ?>\n"; 

echo "<?php if (\$model->isNewRecord) {
    \$updateLabel = '增加';
    \$updateIcon = 'check';
    \$moveButton = \$deleteButton = '';
} else {
    \$updateLabel = '保存';
    \$updateIcon = 'edit';
    if (Yii::app()->user->checkAccess('op_backend_".strtolower($this->modelClass)."_delete')) {
        \$deleteButton = \$this->widget('bootstrap.widgets.TbButton', array(
            'url' => array('delete', 'id' => \$model->id),
            'label' => '删除',
            'icon' => 'trash',
            'htmlOptions' => array(
                'class' => 'op op-delete',
            ),
        ), true);
    } else {
        \$deleteButton = '';
    }
}?>";

?>


<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
	<?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

<?php
}
?>
	<div class="form-actions">
		<?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=> \$updateLabel,
			'icon' => 'white ' . \$updateIcon,
		)); ?>\n"; ?>
		<?php echo $deleteButton; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
