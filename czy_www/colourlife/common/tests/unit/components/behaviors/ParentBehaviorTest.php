<?php

class ParentBehaviorTest extends TestCase
{
    private $_connection;
    private $_testResult;
    CONST TEST_CACHE = true;

    protected function setUp()
    {
        // 测试是否有 pdo 和 pdo_sqlite 扩展
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite'))
            $this->markTestSkipped('PDO and SQLite extensions are required.');

        // 打开连接并初始化数据库
        $this->_connection = new CDbConnection('sqlite::memory:');
        $this->_connection->active = true;
        $this->_connection->pdoInstance->exec(file_get_contents(dirname(__FILE__) . '/ParentBehaviorTest.sql'));
        CActiveRecord::$db = $this->_connection;

        // 测试结果数据
        $this->_result = require(dirname(__FILE__) . '/ParentBehaviorTestResult.php');

        // 设置 cache 为文件缓存
        Yii::app()->setComponent('cache', array(
            'class' => 'CFileCache',
        ));

    }

    protected function tearDown()
    {
        // 清空 cache
        $cache = Yii::app()->getComponent('cache');
        if (!empty($cache))
            $cache->flush();
        // 关闭连接
        $this->_connection->active = false;
    }

    protected function getTestResult($name)
    {
        $this->assertNotEmpty(@$this->_result[$name], "Cannot get {$name} result");
        $this->assertInternalType('array', $this->_result[$name], "{$name} result is not array");
        return $this->_result[$name];
    }

    protected function assertParentsIds($model_name, $id, $value)
    {
        $model = CActiveRecord::model($model_name)->findBypk($id);
        if (is_null($value)) {
            $this->assertEmpty($model, "{$model_name} {$id} must empty");
        } else {
            $this->assertNotEmpty($model, "{$model_name} {$id} must not empty");
            $this->assertEquals($value, $model->getParentIds(), "{$model_name} {$id} getParentIds() failed");
        }
    }

    public function testGetParentIds()
    {
        foreach ($this->getTestResult('parentIds') as $k => $v) {
            $this->assertParentsIds('ParentBehaviorTestActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertParentsIds('ParentBehaviorTestWithCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('parentIds') as $k2 => $v2) {
                    $this->assertParentsIds('ParentBehaviorTestWithCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('parentIdsWithDeleted') as $k => $v) {
            $this->assertParentsIds('ParentBehaviorTestWithDeletedActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertParentsIds('ParentBehaviorTestWithDeletedAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('parentIdsWithDeleted') as $k2 => $v2) {
                    $this->assertParentsIds('ParentBehaviorTestWithDeletedAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('parentIdsWithState') as $k => $v) {
            $this->assertParentsIds('ParentBehaviorTestWithStateActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertParentsIds('ParentBehaviorTestWithStateAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('parentIdsWithState') as $k2 => $v2) {
                    $this->assertParentsIds('ParentBehaviorTestWithStateAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('parentIdsWithStateAndDeleted') as $k => $v) {
            $this->assertParentsIds('ParentBehaviorTestWithStateAndDeletedActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertParentsIds('ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('parentIdsWithStateAndDeleted') as $k2 => $v2) {
                    $this->assertParentsIds('ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }
    }

    protected function assertChildrenIds($model_name, $id, $value)
    {
        $model = CActiveRecord::model($model_name)->findBypk($id);
        if (is_null($value)) {
            $this->assertEmpty($model, "{$model_name} {$id} must empty");
        } else {
            $this->assertNotEmpty($model, "{$model_name} {$id} must not empty");
            $result1 = $model->getChildrenIds();
            sort($result1);
            $this->assertEquals($value, $result1, "{$model_name} {$id} getChildrenIds() failed");
        }
    }

    public function testGetChildrenIds()
    {
        foreach ($this->getTestResult('childrenIds') as $k => $v) {
            $this->assertChildrenIds('ParentBehaviorTestActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertChildrenIds('ParentBehaviorTestWithCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('childrenIds') as $k2 => $v2) {
                    $this->assertChildrenIds('ParentBehaviorTestWithCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('childrenIdsWithDeleted') as $k => $v) {
            $this->assertChildrenIds('ParentBehaviorTestWithDeletedActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertChildrenIds('ParentBehaviorTestWithDeletedAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('childrenIdsWithDeleted') as $k2 => $v2) {
                    $this->assertChildrenIds('ParentBehaviorTestWithDeletedAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('childrenIdsWithState') as $k => $v) {
            $this->assertChildrenIds('ParentBehaviorTestWithStateActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertChildrenIds('ParentBehaviorTestWithStateAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('childrenIdsWithState') as $k2 => $v2) {
                    $this->assertChildrenIds('ParentBehaviorTestWithStateAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('childrenIdsWithStateAndDeleted') as $k => $v) {
            $this->assertChildrenIds('ParentBehaviorTestWithStateAndDeletedActiveRecord', $k, $v, false);
            if (self::TEST_CACHE) {
                $this->assertChildrenIds('ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('childrenIdsWithStateAndDeleted') as $k2 => $v2) {
                    $this->assertChildrenIds('ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }
    }

    protected function assertLinkageSelectData($model_name, $id, $v)
    {
        if (empty($id))
            $model = new $model_name;
        else
            $model = CActiveRecord::model($model_name)->findBypk($id);
        if (is_null($v)) {
            $this->assertEmpty($model, "{$model_name} {$id} must empty");
        } else {
            $this->assertNotEmpty($model, "{$model_name} {$id} must not empty");
            foreach ($v as $start => $value) {
                $value = array(@$value[0], @$value[1]);
                $this->assertEquals($value, $model->getLinkageSelectData($start, true), "{$model_name} {$id} getLinkageSelectData({$start}) failed");
            }
        }
    }

    public function testGetLinkageSelectData()
    {
        foreach ($this->getTestResult('linkageSelectData') as $k => $v) {
            $this->assertLinkageSelectData('ParentBehaviorTestActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertLinkageSelectData('ParentBehaviorTestWithCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('linkageSelectData') as $k2 => $v2) {
                    $this->assertLinkageSelectData('ParentBehaviorTestWithCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('linkageSelectDataWithDeleted') as $k => $v) {
            $this->assertLinkageSelectData('ParentBehaviorTestWithDeletedActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertLinkageSelectData('ParentBehaviorTestWithDeletedAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('linkageSelectDataWithDeleted') as $k2 => $v2) {
                    $this->assertLinkageSelectData('ParentBehaviorTestWithDeletedAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('linkageSelectDataWithState') as $k => $v) {
            $this->assertLinkageSelectData('ParentBehaviorTestWithStateActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertLinkageSelectData('ParentBehaviorTestWithStateAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('linkageSelectDataWithState') as $k2 => $v2) {
                    $this->assertLinkageSelectData('ParentBehaviorTestWithStateAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }

        foreach ($this->getTestResult('linkageSelectDataWithStateAndDeleted') as $k => $v) {
            $this->assertLinkageSelectData('ParentBehaviorTestWithStateAndDeletedActiveRecord', $k, $v);
            if (self::TEST_CACHE) {
                $this->assertLinkageSelectData('ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord', $k, $v);
                foreach ($this->getTestResult('linkageSelectDataWithStateAndDeleted') as $k2 => $v2) {
                    $this->assertLinkageSelectData('ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord', $k2, $v2);
                }
                Yii::app()->getComponent('cache');
                if (!empty($cache))
                    $cache->flush();
            }
        }
    }

}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 */
class ParentBehaviorTestActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => NULL,
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $is_deleted
 */
class ParentBehaviorTestWithDeletedActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithDeletedActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table_with_deleted';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => NULL,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'components.behaviors.IsDeletedBehavior',
                'deletedFlagField' => 'is_deleted',
                'deletedFlag' => 1,
                'normalFlag' => 0,
                'findDeleted' => false,
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 */
class ParentBehaviorTestWithStateActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithStateActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table_with_state';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => NULL,
            ),
            'StateBehavior' => array(
                'class' => 'components.behaviors.StateBehavior',
                'stateFlagField' => 'state',
                'disabledFlag' => 1,
                'enabledFlag' => 0,
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 * @property integer $is_deleted
 */
class ParentBehaviorTestWithStateAndDeletedActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithStateAndDeletedActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table_with_state_and_deleted';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => NULL,
            ),
            'StateBehavior' => array(
                'class' => 'components.behaviors.StateBehavior',
                'stateFlagField' => 'state',
                'disabledFlag' => 1,
                'enabledFlag' => 0,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'components.behaviors.IsDeletedBehavior',
                'deletedFlagField' => 'is_deleted',
                'deletedFlag' => 1,
                'normalFlag' => 0,
                'findDeleted' => false,
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 */
class ParentBehaviorTestWithCacheActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithCacheActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => 'cache',
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $is_deleted
 */
class ParentBehaviorTestWithDeletedAndCacheActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithDeletedAndCacheActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table_with_deleted';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => 'cache',
            ),
            'IsDeletedBehavior' => array(
                'class' => 'components.behaviors.IsDeletedBehavior',
                'deletedFlagField' => 'is_deleted',
                'deletedFlag' => 1,
                'normalFlag' => 0,
                'findDeleted' => false,
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 */
class ParentBehaviorTestWithStateAndCacheActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithStateAndCacheActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table_with_state';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => 'cache',
            ),
            'StateBehavior' => array(
                'class' => 'components.behaviors.StateBehavior',
                'stateFlagField' => 'state',
                'disabledFlag' => 1,
                'enabledFlag' => 0,
            ),
        );
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 * @property integer $is_deleted
 */
class ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord extends CActiveRecord
{
    /**
     * @return ParentBehaviorTestWithStateAndDeletedAndCacheActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table_with_state_and_deleted';
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array(
                'class' => 'components.behaviors.ParentBehavior',
                'parentFlagField' => 'parent_id',
                'nameField' => 'title',
                'cacheId' => 'cache',
            ),
            'StateBehavior' => array(
                'class' => 'components.behaviors.StateBehavior',
                'stateFlagField' => 'state',
                'disabledFlag' => 1,
                'enabledFlag' => 0,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'components.behaviors.IsDeletedBehavior',
                'deletedFlagField' => 'is_deleted',
                'deletedFlag' => 1,
                'normalFlag' => 0,
                'findDeleted' => false,
            ),
        );
    }
}
