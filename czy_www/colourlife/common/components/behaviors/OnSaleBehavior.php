<?php

/**
 * Class OnSaleBehavior
 */
class OnSaleBehavior extends CActiveRecordBehavior
{
    /**
     * @var string The name of the table where data stored.
     * Defaults to "state".
     */
    public $onSaleFlagField = 'is_on_sale';
    /**
     * @var mixed The value to set for is on sale model.
     * Default is 1.
     */
    public $onSaleFlag = 1;
    /**
     * @var mixed The value to set for is off sale model.
     * Default is 0.
     */
    public $offSaleFlag = 0;

    /**
     * Set value for field.
     *
     * @param mixed $value
     * @return CActiveRecord
     */
    public function setOnSaleFlag($value)
    {
        $owner = $this->getOwner();
        $owner->setAttribute($this->onSaleFlagField, $value == $this->offSaleFlag ? $this->offSaleFlag : $this->onSaleFlag);
        return $owner;
    }

    /**
     * sale model
     */
    public function setOnSale()
    {
        $this->setOnSaleFlag($this->onSaleFlag);
    }

    /**
     * not sale model
     */
    public function setOffSale()
    {
        $this->setOnSaleFlag($this->offSaleFlag);
    }

    /**
     * Add condition to query for filter on sale models.
     *
     * @return CActiveRecord
     * @since 1.1.4
     */
    public function filterOnSale($value)
    {
        $owner = $this->getOwner();
        $criteria = $owner->getDbCriteria();
        $column = $owner->getDbConnection()->quoteColumnName($owner->getTableAlias() . '.' . $this->onSaleFlagField);
        $criteria->addCondition($column . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
        $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = ($value == $this->offSaleFlag ? $this->offSaleFlag : $this->onSaleFlag);
        return $owner;
    }

    /**
     * return disabled model
     * @return CActiveRecord
     */
    public function onSale()
    {
        return $this->filterOnSale($this->onSaleFlag);
    }

    /**
     * return disabled model
     * @return CActiveRecord
     */
    public function offSale()
    {
        return $this->filterOnSale($this->offSaleFlag);
    }

    public function getIsOnSale()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->onSaleFlagField) == $this->onSaleFlag;
    }

    public function getIsOffSale()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->onSaleFlagField) == $this->offSaleFlag;
    }

    public function getOnSaleName($html = false)
    {
        $return = '';
        $owner = $this->getOwner();
        if ($owner->getAttribute($this->onSaleFlagField) == $this->offSaleFlag) {
            $return .= ($html) ? '<span class="label label-error">' : '';
            $return .= '已下架';
            $return .= ($html) ? '</span>' : '';
        } else {
            $return .= ($html) ? '<span class="label label-success">' : '';
            $return .= '已上架';
            $return .= ($html) ? '</span>' : '';
        }
        return $return;
    }

    public function getOnSaleNames($select = false)
    {
        $return = array();
        if ($select) {
            $return[''] = '全部';
        }
        $return[$this->onSaleFlag] = '上架';
        $return[$this->offSaleFlag] = '下架';
        return $return;
    }

}
