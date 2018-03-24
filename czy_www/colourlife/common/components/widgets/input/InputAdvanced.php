<?php

Yii::import('bootstrap.widgets.input.TbInputHorizontal');

class InputAdvanced extends TbInputHorizontal
{

    protected function getLabel()
    {
        if (isset($this->labelOptions['class']))
            $this->labelOptions['class'] .= ' control-label';
        else
            $this->labelOptions['class'] = 'control-label';

        if ($this->label !== false && !in_array($this->type, array('checkbox', 'radio')) && $this->hasModel())
            return $this->form->label($this->model, $this->attribute, $this->labelOptions);
        else if ($this->label !== null)
            return $this->label;
        else
            return '';
    }

    /**
     * Returns the prepend element for the input.
     * @return string the element
     */
    protected function getPrepend()
    {
        if ($this->hasAddOn()) {
            $htmlOptions = $this->prependOptions;

            if (isset($htmlOptions['class']))
                $htmlOptions['class'] .= ' add-on';
            else
                $htmlOptions['class'] = 'add-on';

            ob_start();
            echo '<div class="' . $this->getAddonCssClass() . '">';
            if (isset($this->prependText)) {
                if (isset($htmlOptions['span']) && $htmlOptions['span'] == false) {
                    echo $this->prependText;
                } else {
                    echo CHtml::tag('span', $htmlOptions, $this->prependText);
                }
            }

            return ob_get_clean();
        } else
            return '';
    }

    /**
     * Returns the append element for the input.
     * @return string the element
     */
    protected function getAppend()
    {
        if ($this->hasAddOn()) {
            $htmlOptions = $this->appendOptions;

            if (isset($htmlOptions['class']))
                $htmlOptions['class'] .= ' add-on';
            else
                $htmlOptions['class'] = 'add-on';

            ob_start();
            if (isset($this->appendText)) {
                if (isset($htmlOptions['span']) && $htmlOptions['span'] == false) {
                    echo $this->appendText;
                } else {
                    echo CHtml::tag('span', $htmlOptions, $this->appendText);
                }
            }

            echo '</div>';
            return ob_get_clean();
        } else
            return '';
    }

    /**
     * Renders a text field.
     * @return string the rendered content
     */
    protected function textField()
    {
        echo $this->getLabel();
        echo '<div class="controls">';
        echo $this->getPrepend();
        if (isset($this->htmlOptions['useName']) && $this->htmlOptions['useName'] == true) {
            $this->attribute = str_replace('_id', '', $this->attribute);
            $this->attribute .= 'Name';
        }
        echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
        echo $this->getAppend();
        echo $this->getError() . $this->getHint();
        echo '</div>';
    }

}