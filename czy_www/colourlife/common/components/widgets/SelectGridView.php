<?php

Yii::import('bootstrap.widgets.TbGridView');

class SelectGridView extends TbGridView
{
    /**
     * 重载错误显示
     * @var string
     */
    public $ajaxUpdateError = 'function(xhr,ts,et,err){ alert(xhr.responseText); }';
    /**
     * 显示的模型对象名，如“用户”
     * @var string
     */
    public $dataName = '对象';
    /**
     * 新增footer属性
     */
    public $footer;
    /**
     * !默认禁用 ajax
     * @var bool
     */
    //public $ajaxUpdate = true;
    /**
     * 启用 HTML5 history 功能
     * @var bool
     */
    public $enableHistory = true;

    public $visible;

    /**
     * 模板
     */
    private $_fTemplate;

    /**
     * 按钮
     */
    private $_fButtons = array();

    /**
     * 多选框名称
     */
    private $_checkboxName = 'id';

    /**
     * @var array the configuration for the pager.
     * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPager')</code>.
     */
    //public $pager = array('class' => 'comcom.widgets.CTbPager');
    public $pager = array('class' => 'comcom.widgets.TbMixPager');

    public $template = '{items}<div class="row"><div class="span2">{summary}</div><div class="span8">{pager}</div></div>';

    public function init()
    {
        parent::init();
        if (is_array($this->footer) && !empty($this->footer))
            $this->initFooter();
    }

    public function initFooter()
    {
        if (isset($this->footer['template']))
            $this->_fTemplate = $this->footer['template'];
        if (isset($this->footer['checkboxName']))
            $this->_checkboxName = $this->footer['checkboxName'];
        if (isset($this->footer['buttons']))
            $this->_fButtons = $this->footer['buttons'];
        if (!empty($this->_fButtons))
            foreach ($this->_fButtons as $id => $button) {
                if (strpos($this->_fTemplate, '{' . $id . '}') === false)
                    unset($this->_fButtons[$id]);
            }
        $this->registerFooterScript();
    }

    /**
     * 是否设置Footer
     */
    public function getHasFooter()
    {
        if (!empty($this->footer))
            return true;
        return false;
    }

    /**
     * 重写GridView的footer方法
     * 只输出一个td
     */
    public function renderTableFooter()
    {
        if ($this->dataProvider->totalItemCount > 0) {
            $hasFooter = $this->getHasFooter();
            if ($hasFooter) {
                echo "<tfoot><tr>\n";
                $clos = 'colspan=' . count($this->columns);
                echo "<td {$clos}>";
                $this->renderFooterContent();
                echo "</td></tr></tfoot>\n";
            }
        }
    }

    /**
     * 显示Footer内容
     */
    public function renderFooterContent()
    {
        if (is_string($this->footer))
            echo $this->footer;
        else if (is_array($this->footer)) {
            //是否显示
            if (isset($this->footer['visible']) && !$this->evaluateExpression($this->footer['visible']))
                return;
            $tr = array();
            ob_start();
            foreach ($this->_fButtons as $id => $button) {
                $this->renderFooterButton($id, $button);
                $tr['{' . $id . '}'] = ob_get_contents();
                ob_clean();
            }
            ob_end_clean();
            echo strtr($this->_fTemplate, $tr);
        }
    }

    /**
     * 显示内容
     */
    public function renderFooterButton($id, $button)
    {
        if (!is_array($button)) {
            echo $button;
            return;
        }
        //是否显示
        if (isset($button['visible']) && !$this->evaluateExpression($button['visible']))
            return;

        //自定义的button
        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $button['url'] : '#';
        $options = isset($button['options']) ? $button['options'] : array('class' => 'btn batch batch-' . $id);
        if (!isset($options['title']))
            $options['title'] = $label;
        $options['data-method'] = isset($button['method']) ? $button['method'] : 'POST';
        if (isset($button['icon']))
            $label = "<i class='{$button['icon']}'></i> " . $label;
        //其它html
        if (isset($button['html']))
            echo $button['html'];
        echo CHtml::link($label, $url, $options);

    }

    /**
     * 注册事件
     */
    public function registerFooterScript()
    {
        $js = array();
        $function = CJavaScript::encode($this->initFooterScript());
        // 因为当前页面会被 ajax 刷新，只能使用 live
        $js[] = "jQuery('#{$this->id} .batch').live('click',{$function});";
        if ($js !== array())
            Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id, implode("\n", $js));
    }

    /**
     * 事件方法
     *                 'emptyConfirm' => '请选择要{label}的{name}！',
    'confirm' => '确定要{label}选择的{name}吗？',

     */
    private function initFooterScript()
    {
        $checkboxName = $this->_checkboxName;
        $jsContent = <<<EOT
	var ids = $("input:checkbox[name='{$checkboxName}[]']:checked").serialize();
	if (ids.length <= 0) {
		alert("请选择要" + $(this).attr('title') + "的{$this->dataName}！");
		return false;
	}
	url = $.param.querystring(url, ids);
EOT;
        if (Yii::app()->request->enableCsrfValidation) {
            $csrfTokenName = Yii::app()->request->csrfTokenName;
            $csrfToken = Yii::app()->request->csrfToken;
            $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
        } else
            $csrf = '';
        return <<<EOD
js:function() {
	var url = $(this).attr('href');
{$jsContent};
	var method = $(this).attr('data-method');
	if (method == 'GET') {
	    $(this).attr('href', url);
	    return true;
	} else if (method == 'POST') {
    	if(!confirm("确定要" + $(this).attr('title') + "选择的{$this->dataName}吗？")) return false;
    	var th=this;
        $.fn.yiiGridView.update('{$this->id}', {
            type:'POST',
            url:url,{$csrf}
            success:function(data) {
                $.fn.yiiGridView.update('{$this->id}');
            }
        });
    }
    return false;
}
EOD;
    }

}