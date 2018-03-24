<?php
class MyPager extends CLinkPager
{
    const CSS_FIRST_PAGE = 'item';
    const CSS_LAST_PAGE = 'item';
    const CSS_PREVIOUS_PAGE = 'item';
    const CSS_NEXT_PAGE = 'item';
    const CSS_INTERNAL_PAGE = 'item';
    const CSS_HIDDEN_PAGE = 'disabled';
    const CSS_SELECTED_PAGE = 'cur';

    /**
     * @var int
     */
    public $pageSizeTpl = 999999;

    /**
     * @var string
     */
    public $cssClass = __CLASS__;

    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if ($hidden || $selected)
            $class .= ' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
        return CHtml::link($label, $this->createPageUrl($page), array('class' => $class));
    }

    public function run()
    {
        //$this->cssFile = F::getStaticsUrl('/common/css/common.css');
        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        echo $this->header;
//---------------------以下是跳转的HTML开始------------------------
        $pageUrlTemplate = $this->createPageUrl($this->pageSizeTpl);

        $jsNameSpace = __CLASS__;

        $buttons[] = <<<EOD
         <input type="text" class="el_inText {$this->cssClass}"><button class="el_btn {$this->cssClass}_button" onclick="{$jsNameSpace}.goPage(this)" page-url="{$pageUrlTemplate}" type="button" page-url="{$pageUrlTemplate}">
        <span class="txt">&gt;&gt;跳转</span></button>
EOD;
//---------------------以下是跳转的HTML结束------------------------
        echo CHtml::tag('div', $this->htmlOptions, implode("\n", $buttons));
        echo $this->footer;

//以下是跳转的JS
        $pageSizeTpl = $this->pageSizeTpl + 1;

        $js = <<<ON_JUMP
        var {$jsNameSpace} = {};
        {$jsNameSpace}.goPage = function(el){
           //var toPage = $(el).parents('.{$this->cssClass}').find(':input').val();
           var toPage = $('.{$this->cssClass}').val();
                    var er = /^[0-9]+$/;
                    if(er.test(toPage)){

                     var pageUrl = $('.{$this->cssClass}_button').attr("page-url")
                     pageUrl = pageUrl.replace('{$pageSizeTpl}',toPage);
                     window.location = pageUrl;

                    }else{
                         alert("must be a number");
                    }
        }
ON_JUMP;
        Yii::app()->clientScript->registerScript(__CLASS__, $js, CClientScript::POS_END);
    }

    public function registerClientScript()
    {
        // skip register pager.css
    }
}

