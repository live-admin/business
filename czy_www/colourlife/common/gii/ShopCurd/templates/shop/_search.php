<?php echo "<?php \$form=\$this->beginWidget('ActiveForm',array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
    'type' => 'advanced',
)); ?>\n"; ?>

<?php $search_all = ""; 
//'<label for="Region_search_all" class="checkbox">' . $form->checkBox($model, 'search_all') . '所有</label>'; 
?>
<?php echo "<?php echo \$form->textFieldRow(\$model, 'name', array('class' => 'span2', 'append' => \$search_all)); ?>"; ?>
<div class="search-div" style="display: none;">
<?php foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
?>
	<?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

<?php endforeach; ?>
</div>

<div class="control-group ">
    <div class="controls">
        <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => '搜索',
            'icon' => 'search white',
        )); ?>"; ?>
        <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
            'label' => '更多选项',
            'icon' => 'chevron-down',
            'htmlOptions' => array(
                'class' => 'search-button',
                'data-html' => '<i class=\"icon-chevron-up\"></i> 更少选项',
            ),
        ));?>"; ?>
    </div>
</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>