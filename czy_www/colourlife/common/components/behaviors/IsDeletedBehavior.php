<?php

class IsDeletedBehavior extends CActiveRecordBehavior
{
    /**
     * @var string The name of the table where data stored.
     * Defaults to "is_deleted".
     */
    public $deletedFlagField = 'is_deleted';
    /**
     * @var mixed The value to set for deleted model.
     * Default is 1.
     */
    public $deletedFlag = 1;
    /**
     * @var mixed The value to set for normal model.
     * Default is 0.
     */
    public $normalFlag = 0;
    /**
     * @var bool If except deleted model in find results.
     * Default is false.
     */
    public $findDeleted = false;
    /**
     * @var bool The flag to disable filter deleted models in the next query.
     */
    protected $_withDeleted = false;

    public function events()
    {
        $events = array();
        if (!$this->findDeleted) {
            $events['onBeforeFind'] = 'beforeFind';
        }
        $events['onBeforeDelete'] = 'beforeDelete';
        return $events;
    }

    /**
     * Set value for trash field.
     *
     * @param mixed $value
     * @return CActiveRecord
     */
    public function setDeletedFlag($value)
    {
        $owner = $this->getOwner();
        $owner->updateByPk($owner->id, array($this->deletedFlagField => $this->deletedFlag ? $this->deletedFlag : $this->normalFlag));
        return $owner;
    }

    /**
     * Disable excepting deleted models for next search.
     *
     * @return CActiveRecord
     */
    public function withDeleted()
    {
        if (!$this->findDeleted) {
            $this->_withDeleted = true;
        }
        return $this->getOwner();
    }

    /**
     * Add condition to query for filter removed models.
     *
     * @return CActiveRecord
     * @since 1.1.4
     */
    public function filterDeleted()
    {
        $owner = $this->getOwner();
        if (!$this->_withDeleted) {
            $criteria = $owner->getDbCriteria();
            $column = $owner->getDbConnection()->quoteColumnName($owner->getTableAlias() . '.' . $this->deletedFlagField);
            if (strpos($criteria->condition, $column) === false) { // 判断是否加入
                $criteria->addCondition($column . '!=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
                $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $this->deletedFlag;
            }
        }
        $this->_withDeleted = false;
        return $owner;
    }

    /**
     * Add condition before find, for except removed models.
     *
     * @param CEvent
     */
    public function beforeFind($event)
    {
        $this->filterDeleted();
    }

    public function beforeDelete($event)
    {
        $this->setDeletedFlag($this->deletedFlag);
        $event->isValid = false;
    }

}
