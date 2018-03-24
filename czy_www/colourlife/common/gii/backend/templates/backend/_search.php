<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
)); ?>\n"; ?>
<div class="row search-div" style="display: none;">  
    <div class="span10">
        <div class="row">
<?php $i=0; foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
?>
<?php if($i%2==0){ ?>
    <div class="span5"><?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?></div>
<?php } if($i%2==1){ ?>
    <div class="span5"><?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?></div>
<?php } $i++; ?>

<?php endforeach; ?>
        <div class="span9">
            		<?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
                    			'buttonType' => 'submit',
                    			'type'=>'primary',
                    			'label'=>'搜索',
                    		)); ?>\n"; ?>
            	</div>
        </div>
    </div>
</div>
    <div>  
        <?php echo "<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>"; ?>
    </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>