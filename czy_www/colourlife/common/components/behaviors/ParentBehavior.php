<?php

class ParentBehavior extends CActiveRecordBehavior
{
    /**
     * @var string The name of the table where data stored.
     * Defaults to "parent_id".
     */
    public $parentFlagField = 'parent_id';
    public $nameField = 'name';
    /**
     * @var string Cache 组件 ID
     */
    public $cacheId = 'cache';

    private $_parents, $_parentId;
    private $_hasEnableFilter, $_isEnabled;

    public function getParentCacheKey($key, $id)
    {
        $owner = $this->owner;
        return $owner->tableName() . '.' . __CLASS__ . $key . '.' . $id;
    }

    public function getParentCachedValue($key, $id)
    {
        $cache = Yii::app()->getComponent($this->cacheId);
        //var_dump($cache);die;
        if ($cache === null)
            return false;
        return $cache->get($this->getParentCacheKey($key, $id));
    }

    public function setParentCachedValue($key, $id, $value)
    {
        $cache = Yii::app()->getComponent($this->cacheId);
        if ($cache === null)
            return false;
        return $cache->set($this->getParentCacheKey($key, $id), $value, 3600 * 24); // 只缓存一天
    }

    public function deleteParentCachedValue($key, $id)
    {
        $cache = Yii::app()->getComponent($this->cacheId);
        if ($cache === null)
            return false;
        return $cache->delete($this->getParentCacheKey($key, $id));
    }

    protected function getHasEnableFilter()
    {
        if (!isset($this->_hasEnableFilter))
            $this->_hasEnableFilter = ($this->owner->asa('StateBehavior') !== null);
        return $this->_hasEnableFilter;
    }

    protected function filter($model)
    {
        $isDeleted = $model->asa('IsDeletedBehavior');
        if ($isDeleted !== null) {
            $isDeleted->filterDeleted(); // 过滤被删除的数据
        }
        if ($this->getHasEnableFilter()) {
            $model->enabled(); // 过滤被禁用的的数据
        }
        return $model;
    }

    protected function getParentIdsWithParentId($parent_id)
    {
        $ids = $this->getParentCachedValue('ParentIds', $parent_id);
        if ($ids === false) {
            $owner = $this->owner;
            // 获取上级不需要判断是否启用
            $model = $owner->findByPk($parent_id);
            if ($model !== null) {
                $ids = $model->getParentIds(); // 调用上级
                $ids[] = $parent_id;
            } else
                $ids = array();
            $this->setParentCachedValue('ParentIds', $parent_id, $ids);
        }
        return $ids;
    }

    public function getOldParentIds()
    {
        return $this->getParentIdsWithParentId($this->_parentId);
    }

    public function getParentIds()
    {
        return $this->getParentIdsWithParentId($this->owner->getAttribute($this->parentFlagField));
    }

    public function getParents()
    {
        if (isset($this->_parents)) {
            return $this->_parents;
        }
        $ids = $this->getParentIds();
        if (!empty($ids)) {
            $owner = $this->owner;
            $criteria = $owner->getDbCriteria();
            $criteria->addInCondition('id', $ids);
            $s = implode(',', $ids);
            $criteria->order = "FIND_IN_SET(id, '{$s}')";
            $this->_parents = $owner->findAll();
        } else
            $this->_parents = array();
        return $this->_parents;
    }

    public function getCanEnable()
    {
        $owner = $this->owner;
        $parent_id = $owner->getAttribute($this->parentFlagField);
        if (!empty($parent_id)) {
            $parent = $this->filter($owner)->findByPk($parent_id);
            return ($parent !== null);
        } else {
            return true;
        }
    }

    public function getCanDisable()
    {
        return $this->getCanDelete();
    }

    public function getCanDelete()
    {
        $owner = $this->owner;
        $criteria = $owner->getDbCriteria();
        $column = $owner->getDbConnection()->quoteColumnName($owner->getTableAlias() . '.' . $this->parentFlagField);
        $criteria->addCondition($column . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
        $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->id;
        $count = $this->filter($owner)->count();
        return ($count == 0);
    }

    public function getCanMoveToParentId($id)
    {
        return !in_array($id, $this->getChildrenIds());
    }

    /**
     * 返回所有子项目 id 数组
     */
    public function getChildrenIds()
    {
        $owner = $this->owner;
        $ids = $this->getParentCachedValue('ChildrenIds', $owner->id);
        if ($ids === false) {
            $ids = array();
            foreach ($this->findFilterChildrenByPk($owner->id, 0) as $model) {
                $ids[] = $model->id;
                $ids = array_unique(array_merge($ids, $model->getChildrenIds())); // 调用下级
            }
            $this->setParentCachedValue('ChildrenIds', $owner->id, $ids);
        }
        return $ids;
    }

    /**
     * 返回所有子项目 id 数组
     */
    public function getChildrenIdsAndSelf()
    {
        $ids = $this->getChildrenIds();
        $ids[] = $this->owner->id;
        return $ids;
    }

    public function findFilterChildrenByPk($id, $withoutId, $compares = array())
    {
        $owner = $this->filter($this->owner);
		//var_dump($owner);die;
        $criteria = $owner->getDbCriteria();
		//var_dump($this->parentFlagField);die;
        $criteria->compare($this->parentFlagField, $id);
        if (!empty($withoutId))
            $criteria->compare('id', '<>' . $withoutId);
        foreach ($compares as $k => $v) {
            $criteria->compare($k, $v);
        }
        return $owner->findAll();
    }

    /**
     * 获取 linkagesel 所需要的多级选择项数据，和每项选择的默认值
     * $startId -1 表示直接从第一级开始取数据，否则从指定 ID 开始取数据
     * $hasSelf 数据中是否包括自身
     * $compares 其他过滤条件
     *
     * @return array
     * $data = array(
     *  '1' => array(
     *    'name' => '1标题',
     *    'cell' => array(
     *      '11' => array(
     *        'name' => '11标题',
     *        'cell' => array(
     *          '111' => array(
     *            'name' => '111标题',
     *          ),
     *          '112' => array(
     *            'name' => '112标题',
     *          ),
     *        ),
     *      ),
     *      '12' => array(
     *        'name' => '12标题',
     *      ),
     *    ),
     *  '2' => array(
     *    'name' => '2标题',
     *   ),
     * )
     * $def = array(1, 11)
     */
    public function getLinkageSelectData($startId, $hasSelf, $compares = array())
    {
        $owner = $this->owner;
        $withoutId = $hasSelf ? 0 : $owner->id;
        $data = $def = array();
        $pids = $this->getParentIds();

        if ($startId < 0 && !is_array($startId)) {
            // 首先取第一级，parent_id = 0
            foreach ($this->findFilterChildrenByPk(0, $withoutId, $compares) as $model) {
                $data[$model->id] = array(
                    'name' => $model->getAttribute($this->nameField),
                );
            }
        } else if (!is_array($startId) && $startId >= 0) {
            // 检查 startId 是否在 owner 的 parents 里面或者是自己
            if (!in_array($startId, $pids) && $owner->id != 0 && $startId != $owner->id) {
                goto end; // 非父辈，直接返回空数据
            }
            $owner = $this->filter($this->owner);
            $model = $owner->findByPk($startId);
            if (empty($model)) {
                goto end; // $startId 被禁用，直接返回空数据
            }
            $data[$model->id] = array(
                'name' => $model->getAttribute($this->nameField),
            );
            // 将前面的数据去掉，只取后面的数据
            $pids = array_slice($pids, count($model->getParentIds()));
        } else if (is_array($startId) && !empty($startId)) {
            foreach ($startId as $start_id) {
                // 检查 startId 是否在 owner 的 parents 里面或者是自己
                if (!in_array($start_id, $pids) && $owner->id != 0 && $start_id != $owner->id) {
                    goto end; // 非父辈，直接返回空数据
                }
                $owner = $this->filter($this->owner);
                $model = $owner->findByPk($start_id);
                if (empty($model)) {
                    goto end; // $startId 被禁用，直接返回空数据
                }
                $data[$model->id] = array(
                    'name' => $model->getAttribute($this->nameField),
                );
                // 将前面的数据去掉，只取后面的数据
                $pids = array_slice($pids, count($model->getParentIds()));
            }
        }

        // 依次取下一级，因为是级联数组，需要使用 & 取地址
        $d = & $data;
        foreach ($pids as $pid) {
            if (empty($d[$pid])) { // 检查上级必须存在，因为 getParents 会取出被禁用的数据
                goto end; // 直接返回数据
            }
            $d[$pid]['cell'] = array();
            $def[] = $pid; // 保存上级 id 到默认值数组
            foreach ($this->findFilterChildrenByPk($pid, $withoutId, $compares) as $model) {
                $d[$pid]['cell'][$model->id] = array(
                    'name' => $model->getAttribute($this->nameField),
                );
            }
            $d = & $d[$pid]['cell'];
        }

        if ($hasSelf && !empty($owner->id) && !empty($d[$owner->id])) {
            $d[$owner->id]['cell'] = array();
            $def[] = $owner->id; // 保存自己
            foreach ($this->findFilterChildrenByPk($owner->id, $withoutId, $compares) as $model) {
                $d[$owner->id]['cell'][$model->id] = array(
                    'name' => $model->getAttribute($this->nameField),
                );
            }
        }
        end:
        return array($data, $def);
    }

    public function afterFind($event)
    {
        $owner = $this->owner;
        $this->_parentId = $owner->getAttribute($this->parentFlagField);
        if ($this->getHasEnableFilter()) {
            $this->_isEnabled = $owner->isEnabled;
        }
    }

    public function getIsParentChanged()
    {
        return $this->_parentId != $this->owner->getAttribute($this->parentFlagField);
    }

    public function getIsEnableChanged()
    {
        return $this->getHasEnableFilter() && $this->_isEnabled != $this->owner->isEnabled;
    }

    /**
     * 删除指定的缓存
     * @param CModelEvent $event
     */
    public function afterSave($event)
    {
        $owner = $this->owner;
        if ($owner->isNewRecord) {
            $this->flushChildrenIdsOfParentsCache();
        } else if ($this->getIsParentChanged()) {
            $this->flushChildrenIdsOfOldParentsCache();
            $this->flushChildrenIdsOfParentsCache();
            $this->flushSelfParentAndChildrenIdsCache();
            $this->flushParentIdsOfChildrenCache();
        } else if ($this->getIsEnableChanged()) {
            $this->flushChildrenIdsOfParentsCache();
            $this->flushSelfParentAndChildrenIdsCache();
        }
    }

    public function beforeDelete($event)
    {
        $this->flushChildrenIdsOfParentsCache();
        $this->flushSelfParentAndChildrenIdsCache();
    }

    protected function flushChildrenIdsOfOldParentsCache()
    {
        // 清除旧上级的所有下级缓存
        foreach ($this->getOldParentIds() as $id)
            $this->deleteParentCachedValue('ChildrenIds', $id);
    }

    protected function flushChildrenIdsOfParentsCache()
    {
        // 清除新上级的所遇下级缓存
        foreach ($this->getParentIds() as $id)
            $this->deleteParentCachedValue('ChildrenIds', $id);
    }

    protected function flushSelfParentAndChildrenIdsCache()
    {
        // 清除自己的上下级缓存
        $id = $this->owner->id;
        $this->deleteParentCachedValue('ParentIds', $id);
        $this->deleteParentCachedValue('ChildrenIds', $id);
    }

    protected function flushParentIdsOfChildrenCache()
    {
        // 清除下级的上级缓存
        foreach ($this->getChildrenIds() as $id)
            $this->deleteParentCachedValue('ParentIds', $id);
    }

    //===============================以下两个方法是根据不同的户ID得到其上级所有的户ID========================//
    protected function getLevelParentIdsWithParentId($parent_id, $level)
    {
        $ids = $this->getParentCachedValue('LevelParentIds', $parent_id);
        if ($ids === false) {
            $owner = $this->owner;
            // 获取上级不需要判断是否启用
            $model = $owner->findByPk($parent_id);
            if ($model !== null) {
                $ids = $model->getLevelParentIds($level); // 调用上级
                $ids[$level] = $parent_id;
            } else
                $ids = array();
            $this->setParentCachedValue('LevelParentIds', $parent_id, $ids);
        }
        return $ids;
    }

    public function getLevelParentIds($level = 0)
    {
        $level++;
        return $this->getLevelParentIdsWithParentId($this->owner->getAttribute($this->parentFlagField), $level);
    }
    //===============================以上两个方法是根据不同的户ID得到其上级所有的户ID========================//

}
