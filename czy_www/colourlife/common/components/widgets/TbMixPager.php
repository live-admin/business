<?php

Yii::import('bootstrap.widgets.TbPager');
class TbMixPager extends TbPager
{

    public $alignment = TbPager::ALIGNMENT_RIGHT;

    public function run()
    {
        $this->maxButtonCount = 10;
        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        echo $this->header;
        echo CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons));
        echo $this->footer;
        echo $this->controller->widget('comcom.widgets.TbJumpPager',
            array('pages' => $this->pages,
                'ajaxUpdate' => true,
                'cssClass' => 'pull-right'),
            true);
    }
}
