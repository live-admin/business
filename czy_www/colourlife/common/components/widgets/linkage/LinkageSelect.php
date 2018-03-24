<?php

/**
 * Class LinkageSelect
 * 多级联动选择组件
 */
class LinkageSelect extends CWidget
{
	/**
	 * @var 自身 id
	 */
	public $id;
	/**
	 * @var 多级联动选择完毕后，选择值存放的 hidden 表单 id
	 */
	public $targetId;
	/**
	 * @var 初始化多级联动的数据数组
	 */
	public $data;
	/**
	 * @var 初始化多级联动的每项默认值
	 */
	public $defVal;
	/**
	 * @var 多级联动 Ajax 获取数据的 url
	 */
	public $url;

	public function run()
	{
		$assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets');
		$assetsJsUrl = $assetsUrl . '/jquery.linkagesel-min.js';
		$assetsImgUrl = $assetsUrl . '/ui-anim_basic_16x16.gif';
		Yii::app()->clientScript->registerScriptFile($assetsJsUrl, CClientScript::POS_HEAD);
		echo "<select id=\"{$this->id}\" class=\"span2 linkage\"></select>\n";
		$data = $init = '';
		if (!empty($this->data)) {

			$data .= "\n    data: " . CJavaScript::encode($this->data) . ",";
		}
		if (!empty($this->defVal)) {
			$value = CJavaScript::encode($this->defVal);
			$lastValue = array_pop($this->defVal);
			$init .= <<<EOD
linkageSel.onChange(function() {
    var id = this.getSelectedValue();
    if (id == {$lastValue})
        linkageSel.onChange(change);
});
linkageSel.changeValues({$value}, true);
EOD;
		} else {
			$init .= 'linkageSel.onChange(change);';
		}
		if (!empty($this->url)) {
			$url = CHtml::normalizeUrl($this->url);
			$data .= "\n    ajax: '{$url}',";
		}
		Yii::app()->clientScript->registerScript(__CLASS__ . '-' . $this->id, <<<EOD
(function() {
var linkageSel = new LinkageSel({{$data}
    head: '-',
    autoLink: false,
    select: '#{$this->id}',
    selClass: 'span2 linkage',
    selStyle: '',
    loaderImg: '{$assetsImgUrl}'
});
var change = function() {
    var input = $('#{$this->targetId}');
    input.val(linkageSel.getSelectedValue()).change();
};
{$init}
}());
EOD
		);
	}

}