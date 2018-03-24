<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>
?>

<?php echo "<?php \$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', 
    'stacked'=>false, 
    'items'=>array(
        array('label'=>'所有', 'url'=>array('index')),
        array('label'=>'增加', 'url'=>array('create')),
        array('label'=>'查看', 'url'=>'#','active'=>true),
        array('label'=>'编辑', 'url'=>array('update','id'=>\$model->{$this->tableSchema->primaryKey})),
        array('label'=>'删除', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>\$model->{$this->tableSchema->primaryKey}),'confirm'=>'你确定要删除这条数据？')),
    ),
)); ?>\n";?>
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
	echo "\t\t'".$column->name."',\n";
?>
	),
)); ?>
