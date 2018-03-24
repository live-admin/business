<?php
/**
 * zTreeDropdown class file.
 *
 * ztree Js扩展包
 * @author jake <jake451@163.com>
 * @link http://hi.baidu.com/jake451
 */
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'BaseTree.php');
/**
 *
 * zTree下拉菜单
 * 
 * zTreeDropdown扩展包使用方法:
 * <pre>
 * echo CHtml::textField('textField');
 * echo CHtml::link('弹出', 'javascript:;', array('id'=>'open'));
 * $this->widget('path.ztree.zTreeDropdown',array(
 * 		'containerId'=>'textField',
 * 		'clickId'=>'open',
 * 		'treeNodeNameKey'=>'name',
 * 		'treeNodeKey'=>'id',
 * 		'treeNodeParentKey'=>'pId',
 * 		'onlySon'=>true,
 * 		'options'=>array(
 * 				'expandSpeed'=>"",
 * 				'showLine'=>true,
 * 			),
 *		'data'=>array(
 * 			array('id'=>1, 'pId'=>0, 'name'=>'目录1'),
 * 			array('id'=>2, 'pId'=>1, 'name'=>'目录2'),
 * 			array('id'=>3, 'pId'=>1, 'name'=>'目录3'),
 * 			array('id'=>4, 'pId'=>1, 'name'=>'目录4'),
 * 			array('id'=>5, 'pId'=>2, 'name'=>'目录5'),
 * 			array('id'=>6, 'pId'=>3, 'name'=>'目录6')
 * 		)
 * ));
 * </pre>
 *	
 *	提醒：
 *		1、clickId属性不填的话，默认与containerId相同
 *		2、width属性不填的话，背景层宽度与containerId宽度一样
 * 		
 *
 */
class zTreeDropdown extends BaseTree
{
	/**
	 * TEXT容器ID
	 * 
	 * @var string
	 */
	public $containerId;
	/**
	 * 点击弹出覆层的控件ID
	 * 默认与containerId一样
	 * @var string
	 */
	public $clickId;
	/**
	 * 更新ID的控件ID
	 * 默认与containerId一样
	 * @var unknown_type
	 */
	public $updateId;
	/**
	 * 背景高度
	 * 
	 * @var int
	 */
	public $height=300;
	
	/**
	 * 初始化(non-PHPdoc)
	 * @see zTree::init()
	 */
	public function init()
	{
		if ($this->containerId === null)
		{
			throw new CException(Yii::t('zii','containerId must be required.'));
		}
		if ($this->clickId === null)
		{
			$this->clickId	= $this->containerId;
		}
		if ($this->updateId === null)
		{
			$this->updateId	= $this->containerId;
		}
		$this->backgroundHtmlOptions['style']	.= 'display:none; position:absolute; background-color: white;border:1px solid;overflow-y:auto;overflow-x:auto;';
		parent::init();
		if ($this->options['callback']['onClick'] === null)
		{
			$this->options['callback']['onClick']= "js:function(event, treeId, treeNode) {
						alert(treeNode.name);
						if (treeNode) {
							$('#{$this->containerId}').val(treeNode.name);
							$('#{$this->updateId}').val(treeNode.id);
							$('#{$this->backgroundId}').hide();
						}
					}";
		}
	}
	
	/**
	 * 注册JS
	 * @see zTree::getRegisterScripts()
	 */
	protected function getRegisterScripts()
	{
		$js	= parent::getRegisterScripts();
		
		/** JS部分 */
		$js_ext	= '';
		if ($this->width === null)
		{
			$js_ext	= "\$('#{$this->backgroundId}').width(inputObj.width());";
		}
		$js[]="jQuery('#{$this->clickId}').live('click', function(){
					var inputObj = \$('#{$this->containerId}');
					var inputOffset = inputObj.position();
					\$('#{$this->backgroundId}').css({left:inputOffset.left + 'px', top:inputOffset.top + inputObj.outerHeight() + 'px'}).show();
					{$js_ext}
		})";
		$js[]='$("body").bind("mousedown", function(event){
				if (!(event.target.id == "'.$this->backgroundId.'" || $(event.target).parents("#'.$this->backgroundId.'").length>0))
				{
					$("#'.$this->backgroundId.'").hide();
				}
			});';
		return $js;
	}
}