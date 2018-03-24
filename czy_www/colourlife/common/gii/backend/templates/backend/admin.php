<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php \n ";
echo " \$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>

Yii::app()->clientScript->registerScript('search', <<<EOD
$('.search-button').click(function() {
	$('.search-div').toggle();
	var html = $(this).html();
	$(this).html($(this).attr('data-html'));
	$(this).attr('data-html', html);
	return false;
});
$('.search-form form').submit(function() {
	$.fn.yiiGridView.update('region-grid', {
		data: $(this).serialize()
	});
	return false;
});
EOD
);
<?php echo "?>";?>

<?php echo "<?php /* \$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs',
    'stacked' => false,
    'items' => \$this->getSubMenu($model),
));*/ ?>";
?>


<div class="search-form" style="">
<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php"; ?> $this->widget('SelectGridView',array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'dataName' => $model->modelName,
	'footer' => array(
        'template' => '{enable} {disable} {delete}',
        'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_batch")',
        'buttons' => array(
            'enable' => array(
                'label' => '批量启用',
                'url' => CHtml::normalizeUrl(array('enable')),
                'icon' => 'icon-ok-sign',
                'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
            ),
            'disable' => array(
                'label' => '批量禁用',
                'url' => CHtml::normalizeUrl(array('disable')),
                'icon' => 'icon-remove-sign',
                'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
            ),
            'delete' => array(
                'label' => '批量删除',
                'url' => CHtml::normalizeUrl(array('delete')),
                'icon' => 'icon-trash',
                'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_delete")',
            ),
        ),
    ),
	'columns'=>array(
        array(
            'selectableRows' => 2,
            'class' => 'SelectCheckBoxColumn',
			'checkBoxHtmlOptions' => array('name' => 'id[]'),
            'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_batch")',
        ),
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
            'header' => '操作',
            'class' => 'ButtonColumn',
            'htmlOptions' => array(
                'width' => 90,
            ),
            'template' => '{admin} {view} {update} {enable} {disable} {trash}',
            'buttons' => array(
                'view' => array(
                    'label' => '查看',
                ),
                'admin' => array(
                    'label' => '管理',
                    'icon' => 'icon-list',
                    'url' => 'CHtml::normalizeUrl(array("/<?php echo strtolower($this->modelClass); ?>/index", "id" => $data->id))',
                ),
                'update' => array(
                    'label' => '编辑',
                    'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
                ),
                'enable' => array(
                    'label' => '启用',
                    'icon' => 'icon-ok-sign',
                    'url' => 'CHtml::normalizeUrl(array("/<?php echo strtolower($this->modelClass); ?>/enable", "id" => $data->id))',
                    'visible' => '$data->isDisabled && Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
                    'options' => array('class' => 'op op-enable'),
                ),
                'disable' => array(
                    'label' => '禁用',
                    'icon' => 'icon-remove-sign',
                    'url' => 'CHtml::normalizeUrl(array("/<?php echo strtolower($this->modelClass); ?>/disable", "id" => $data->id))',
                    'visible' => '$data->isEnabled && Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
                    'options' => array('class' => 'op op-disable'),
                ),
                'trash' => array(
                    // 不使用 zii 自带的 delete 是为了避免自带生成 js 产生的问题
                    'label' => '删除',
                    'icon' => 'icon-trash',
                    'url' => 'CHtml::normalizeUrl(array("/<?php echo strtolower($this->modelClass); ?>/delete", "id" => $data->id))',
                    'visible' => 'Yii::app()->user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_delete")',
                    'options' => array('class' => 'op op-delete'),
                ),
            ),
        ),
	),
)); ?>

