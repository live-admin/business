<?php

Yii::import('bootstrap.widgets.TbButtonColumn');

class ButtonColumn extends TbButtonColumn
{
    public $htmlOptions = array('class' => 'op-column');
    public $viewButtonIcon = false;
    public $updateButtonIcon = false;
    public $deleteButtonIcon = false;

    protected function registerClientScript()
    {
        parent::registerClientScript();
        Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $this->id, <<<EOD
$('#{$this->grid->id} a.op').live('click', function() {
    var self = $(this);
    var url = self.attr('href');
    var label = self.attr('data-original-title');
    var title = self.parent().parent().children(':nth-child(2)').text();
    if(!confirm('确定要' + label + title + '吗？')) return false;
    var th=this;
    var afterDone = function() {};
    $.fn.yiiGridView.update('{$this->grid->id}', {
        type: 'POST',
        url: url,
        success: function(data) {
            $.fn.yiiGridView.update('{$this->grid->id}');
            afterDone(th, true, data);
        },
        error:function(XHR) {
            return afterDone(th, false, XHR);
        }
    });
    return false;
});
EOD
        );
    }

    /**
     *### .renderButton()
     *
     * Renders a link button.
     *
     * @param string $id the ID of the button
     * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data object associated with the row
     */
    protected function renderButton($id, $button, $row, $data)
    {
        if (isset($button['visible']) && !$this->evaluateExpression(
                $button['visible'],
                array('row' => $row, 'data' => $data)
            )
        ) {
            return;
        }

        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row))
            : '#';
        $options = isset($button['options']) ? $button['options'] : array();

        if (!isset($options['title'])) {
            $options['title'] = $label;
        }

        if (!isset($options['rel'])) {
            $options['rel'] = 'tooltip';
        }

        if (!isset($options['class']))
            $options['class'] = 'btn btn-mini';
        else
            $options['class'] .= ' btn btn-mini';

        echo CHtml::link($label, $url, $options);
    }

}