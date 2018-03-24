<?php

/**
 * Class StateBehavior
 */
class StateBehavior extends CActiveRecordBehavior
{
    /**
     * @var string The name of the table where data stored.
     * Defaults to "state".
     */
    public $stateFlagField = 'state';
    /**
     * @var mixed The value to set for disabled model.
     * Default is 1.
     */
    public $disabledFlag = 1;
    /**
     * @var mixed The value to set for enabled model.
     * Default is 0.
     */
    public $enabledFlag = 0;

    /**
     * Set value for field.
     *
     * @param mixed $value
     * @return CActiveRecord
     */
    public function setStateFlag($value)
    {
        $owner = $this->getOwner();
        $owner->setAttribute($this->stateFlagField, $value == $this->disabledFlag ? $this->disabledFlag : $this->enabledFlag);
        return $owner;
    }

    /**
     * Enable model
     */
    public function enable()
    {
        $this->setStateFlag($this->enabledFlag);
    }

    /**
     * disable model
     */
    public function disable()
    {
        $this->setStateFlag($this->disabledFlag);
    }

    /**
     * Add condition to query for filter state models.
     *
     * @return CActiveRecord
     * @since 1.1.4
     */
    public function filterState($value)
    {
        $owner = $this->getOwner();
        $criteria = $owner->getDbCriteria();
        $column = $owner->getDbConnection()->quoteColumnName($owner->getTableAlias() . '.' . $this->stateFlagField);
        $criteria->addCondition($column . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
        $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = ($value == $this->disabledFlag ? $this->disabledFlag : $this->enabledFlag);
        return $owner;
    }

    /**
     * return disabled model
     * @return CActiveRecord
     */
    public function enabled()
    {
        return $this->filterState($this->enabledFlag);
    }

    /**
     * return disabled model
     * @return CActiveRecord
     */
    public function disabled()
    {
        return $this->filterState($this->disabledFlag);
    }

    public function getIsEnabled()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->stateFlagField) == $this->enabledFlag;
    }

    public function getIsDisabled()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->stateFlagField) == $this->disabledFlag;
    }

    public function getStateName($html = false)
    {
        $return = '';
        $owner = $this->getOwner();
        if ($owner->getAttribute($this->stateFlagField) == $this->disabledFlag) {
            $return .= ($html) ? '<span class="label label-error">' : '';
            $return .= '已禁用';
            $return .= ($html) ? '</span>' : '';
        } else {
            $return .= ($html) ? '<span class="label label-success">' : '';
            $return .= '已启用';
            $return .= ($html) ? '</span>' : '';
        }
        return $return;
    }

    public function getStateButton()
    {
        $owner = $this->getOwner();
        if ($owner->getAttribute($this->stateFlagField) == $this->disabledFlag) {
            return CHtml::link('已禁用', array('enable', "id" => $owner->id), array('class' => 'op op-enable btn btn-mini btn-inverse', 'rel' => 'tooltip', 'data-original-title' => '启用'));
        }
        return CHtml::link('已启用', array('disable', "id" => $owner->id), array('class' => 'op op-disable btn btn-mini btn-success', 'rel' => 'tooltip', 'data-original-title' => '禁用'));
    }

    public function getStateNames($select = false)
    {
        $return = array();
        if ($select) {
            $return[''] = '全部';
        }
        $return[$this->enabledFlag] = '启用';
        $return[$this->disabledFlag] = '禁用';
        return $return;
    }

}
