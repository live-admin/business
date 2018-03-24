<?php

Yii::import('zii.widgets.grid.CCheckBoxColumn');

class SelectCheckBoxColumn extends CCheckBoxColumn
{
    /**
     * checkbox是否显示
     */
    public $itemVisible;

    protected function renderHeaderCellContent()
    {
        if (is_string($this->visible)) {
            $this->visible = $this->evaluateExpression($this->visible);
        }
        if ($this->visible === true) {
            parent::renderHeaderCellContent();
        }
    }

    protected function renderDataCellContent($row, $data)
    {
        if ($this->visible === true) {
            if ($this->itemVisible === null || $this->evaluateExpression($this->itemVisible, array('data' => $data, 'row' => $row))) {
                parent::renderDataCellContent($row, $data);
            }
        }
    }
}