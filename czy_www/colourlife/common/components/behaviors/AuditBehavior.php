<?php

/**
 * Class AuditBehavior
 */
class AuditBehavior extends CActiveRecordBehavior
{
    /**
     * @var string The name of the table where data stored.
     * Defaults to "audit".
     */
    public $auditFlagField = 'audit';
    /**
     * 虚拟状态，表示 passedFlag 和 notPassedFlag 两种已审核状态
     * @var mixed The value to set for audited model.
     * Default is 3.
     */
    public $auditedFlag = 3;
    /**
     * @var mixed The value to set for not passed model.
     * Default is 2.
     */
    public $notPassedFlag = 2;
    /**
     * @var mixed The value to set for passed model.
     * Default is 1.
     */
    public $passedFlag = 1;
    /**
     * @var mixed The value to set for pengding model.
     * Default is 0.
     */
    public $pendingFlag = 0;

    private function _getValue($value)
    {
        switch ($value) {
            case $this->notPassedFlag:
                return $this->notPassedFlag;
                break;
            case $this->passedFlag:
                return $this->passedFlag;
                break;
            default:
                return $this->pendingFlag;
                break;
        }
    }

    /**
     * Set value for field.
     *
     * @param mixed $value
     * @return CActiveRecord
     */
    public function setAuditFlag($value)
    {
        $owner = $this->getOwner();
        $owner->setAttribute($this->auditFlagField, $this->_getValue($value));
        return $owner;
    }

    /**
     * pass model
     */
    public function audit($passed = true)
    {
        $value = $passed ? $this->passedFlag : $this->notPassedFlag;
        $this->setAuditFlag($value);
    }

    /**
     * pend model
     */
    public function pend()
    {
        $this->setAuditFlag($this->pendingFlag);
    }

    /**
     * Add condition to query for filter state models.
     *
     * @return CActiveRecord
     * @since 1.1.4
     */
    public function filterAudit($value)
    {
        $owner = $this->getOwner();
        $criteria = $owner->getDbCriteria();
        $column = $owner->getDbConnection()->quoteColumnName($owner->getTableAlias() . '.' . $this->auditFlagField);
        $criteria->addCondition($column . (($value == $this->auditedFlag) ? '<>' : '=') . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
        $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $this->_getValue($value);
        return $owner;
    }

    /**
     * return passed model
     * @return CActiveRecord
     */
    public function passed()
    {
        return $this->filterAudit($this->passedFlag);
    }

    /**
     * return not passed model
     * @return CActiveRecord
     */
    public function notPassed()
    {
        return $this->filterAudit($this->notPassedFlag);
    }

    /**
     * return pending model
     * @return CActiveRecord
     */
    public function pending()
    {
        return $this->filterAudit($this->pendingFlag);
    }

    /**
     * return audited model
     * @return CActiveRecord
     */
    public function audited()
    {
        return $this->filterAudit($this->auditedFlag);
    }

    public function getIsPending()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->auditFlagField) == $this->pendingFlag;
    }

    public function getIsAudited()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->auditFlagField) != $this->pendingFlag;
    }

    public function getIsPassed()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->auditFlagField) == $this->passedFlag;
    }

    public function getIsNotPassed()
    {
        $owner = $this->getOwner();
        return $owner->getAttribute($this->auditFlagField) == $this->notPassedFlag;
    }

    public function getAuditName($html = false)
    {
        $return = '';
        $owner = $this->getOwner();
        switch ($owner->getAttribute($this->auditFlagField)) {
            case $this->pendingFlag:
                $return .= ($html) ? '<span class="label label-warning">' : '';
                $return .= '待审核';
                $return .= ($html) ? '</span>' : '';
                break;
            case $this->passedFlag:
                $return .= ($html) ? '<span class="label label-success">' : '';
                $return .= '审核通过';
                $return .= ($html) ? '</span>' : '';
                break;
            case $this->notPassedFlag:
                $return .= ($html) ? '<span class="label label-error">' : '';
                $return .= '审核不通过';
                $return .= ($html) ? '</span>' : '';
                break;
        }
        return $return;
    }

    public function getAuditNames($select = false)
    {
        $return = array();
        if ($select) {
            $return[''] = '全部';
        }
        $return[$this->pendingFlag] = '待审核';
        if ($select) {
            $return[$this->auditedFlag] = '已审核';
        }
        $return[$this->passedFlag] = '审核通过';
        $return[$this->notPassedFlag] = '审核不通过';
        return $return;
    }

}
