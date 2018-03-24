<?php

/**
 * Class LinkageSelect
 * 多级联动选择组件
 */
class ICELinkageSelect extends CWidget
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
		//$assetsJsUrl = $assetsUrl . '/jquery.linkagesel-min.js';
		$assetsImgUrl = $assetsUrl . '/ui-anim_basic_16x16.gif';
		//$assetsCssUrl = $assetsUrl . '/css/common.css';
		$assetsJsCmmUrl = $assetsUrl . '/js/comm.js';
		$assetsJsUrl = $assetsUrl . '/js/linkagesel-min.js';
		//$assetsImgUrl = $assetsUrl . '/images/ui-anim_basic_16x16.gif';
		//Yii::app()->clientScript->registerScriptFile($assetsCssUrl, CClientScript::POS_HEAD);
		Yii::app()->clientScript->registerScriptFile($assetsJsCmmUrl, CClientScript::POS_HEAD);
		Yii::app()->clientScript->registerScriptFile($assetsJsUrl, CClientScript::POS_HEAD);


		$data = $init = '';
		$resourceVar = sprintf(
			'linkageSel%s%s',
			ucfirst(strtolower(trim($this->id))),
			uniqid()
		);

		if (!empty($this->data)) {
			$data .= "\n    data: " . CJavaScript::encode($this->data) . ",";
		}

		if (!empty($this->defVal)) {
			$value = CJavaScript::encode($this->defVal);
			$lastValue = array_pop($this->defVal);
			$init .= <<<EOD
{$resourceVar}.onChange(function() {
    var id = this.getSelectedValue();
    if (id == {$lastValue})
        {$resourceVar}.onChange(change);
});
{$resourceVar}.changeValues({$value}, true);
EOD;
		} else {
			$init .= $resourceVar . '.onChange(change);';
		}

		if (!empty($this->url)) {
			$url = CHtml::normalizeUrl($this->url);
			$data .= "\n    ajax: '{$url}',";
		}

		if ($this->id == 'region') {
			$init .= <<<EOD
var regionSelect = $('#region');

var hiddenInput = regionSelect.prev();
var hiddenInputName = hiddenInput.attr('name');
var searchModelName = hiddenInputName.substring(0, hiddenInputName.indexOf('['));
//var hiddenInputFullID = hiddenInput.attr('id');
//var hiddenInputID = hiddenInputFullID.substring(searchModelName.length + 1);
hiddenInput.after('<input name="' + searchModelName + '[province_id]" id="' + searchModelName + '_province_id" type="hidden">'
+ '<input name="' + searchModelName + '[city_id]" id="' + searchModelName + '_city_id" type="hidden">'
+ '<input name="' + searchModelName + '[district_id]" id="' + searchModelName + '_district_id" type="hidden">');

$('div.search-form button[type="submit"]').on('click', function (e) {
	$('#' + searchModelName + '_province_id').val(regionSelect.val() || '');

	var regionSelects = regionSelect.nextAll('select');

	switch (regionSelects.length) {
		case 0:
			break;
		case 1:
			$('#' + searchModelName + '_city_id').val(regionSelects.eq(0).val() || '');
			break;
		case 2:
			$('#' + searchModelName + '_city_id').val(regionSelects.eq(0).val() || '');
			$('#' + searchModelName + '_district_id').val(regionSelects.eq(1).val() || '');
			break;
	}
});
EOD;
		}

		echo "<select id=\"{$this->id}\" class=\"span2 linkage\"></select>\n";

		Yii::app()->clientScript->registerScript(__CLASS__ . '-' . $this->id, <<<EOD
(function() {
var {$resourceVar} = new LinkageSel({{$data}
    head: '-',
    autoLink: false,
    select: '#{$this->id}',
    selClass: 'span2 linkage',
    selStyle: '',
    loaderImg: '{$assetsImgUrl}'
});
var change = function() {
    var input = $('#{$this->targetId}');
    input.val({$resourceVar}.getSelectedValue()).change();
};
{$init}
}());
EOD
		);
	}

}