<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Create',
);\n";
?>
?>

<?php echo "<?php \$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', 
    'stacked'=>false, 
    'items'=>array(
        array('label'=>'所有', 'url'=>array('index')),
        array('label'=>'增加', 'url'=>'#', 'active'=>true),
    ),
)); ?>\n";?>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
