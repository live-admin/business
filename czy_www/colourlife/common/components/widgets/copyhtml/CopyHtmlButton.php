<?php

Yii::import('bootstrap.widgets.TbButton');

class CopyHtmlButton extends TbButton
{
    public $label = '复制 HTML 编辑内容';
    public $type = 'inverse';
    public $size = 'small';
    public $append = '将会覆盖当前内容！请确认后再使用。';
    public $tips = '确定要覆盖当前内容吗？';
    public $model, $text_name, $html_name;

    public function init()
    {
        parent::init();
        if (empty($this->model))
            throw new CException('请设置 model！');
        if (empty($this->text_name) || empty($this->html_name))
            throw new CException('请设置 text_name 和 html_name！');

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/kindeditor.copy.js');
    }

    public function run()
    {
        parent::run();
        echo ' ' . $this->append;
        $this->registerScript();
    }

    protected function registerScript()
    {
        $htmlId = CHtml::getIdByName(CHtml::resolveName($this->model, $this->html_name));
        $textId = CHtml::getIdByName(CHtml::resolveName($this->model, $this->text_name));
        $cs = Yii::app()->getClientScript();
        $cs->registerScript('CopyHtml#' . $this->id, "$('#{$this->id}').click(function(){KindEditorCopy('{$htmlId}', '{$textId}', '{$this->tips}', '{$this->tips}');});");
    }

}
