<?php

class IsDeletedBehaviorTest extends TestCase
{
    private $_connection;

    protected function setUp()
    {
        // 测试是否有 pdo 和 pdo_sqlite 扩展
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite'))
            $this->markTestSkipped('PDO and SQLite extensions are required.');

        // 打开连接并初始化数据库
        $this->_connection = new CDbConnection('sqlite::memory:');
        $this->_connection->active = true;
        $this->_connection->pdoInstance->exec(file_get_contents(dirname(__FILE__) . '/IsDeletedBehaviorTest.sql'));
        CActiveRecord::$db = $this->_connection;
    }

    protected function tearDown()
    {
        // 关闭连接
        $this->_connection->active = false;
    }

    public function testFind()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', '<6');

        // 测试 findDeleted = false
        $model1 = IsDeletedBehaviorTestWithoutDeletedActiveRecord::model();

        $this->assertEmpty($model1->findByPk(0));
        $this->assertEmpty($model1->withDeleted()->findByPk(0));
        $this->assertEmpty($model1->filterDeleted()->findByPk(0));

        $this->assertNotEmpty($model1->findByPk(1));
        $this->assertNotEmpty($model1->withDeleted()->findByPk(1));
        $this->assertNotEmpty($model1->filterDeleted()->findByPk(1));

        $this->assertEmpty($model1->findByPk(2));
        $this->assertNotEmpty($model1->withDeleted()->findByPk(2));
        $this->assertEmpty($model1->filterDeleted()->findByPk(2));

        $this->assertCount(3, $model1->findAll($criteria));
        $this->assertCount(5, $model1->withDeleted()->findAll($criteria));
        $this->assertCount(3, $model1->filterDeleted()->findAll($criteria));

        $this->assertEquals(5, $model1->count($criteria));
        //$this->assertEquals(5, $model1->withDeleted()->count($criteria));
        $this->assertEquals(3, $model1->filterDeleted()->count($criteria));

        // 测试 findDeleted = true
        $model2 = IsDeletedBehaviorTestWithDeletedActiveRecord::model();

        $this->assertEmpty($model2->findByPk(0));
        $this->assertEmpty($model2->filterDeleted()->findByPk(0));
        $this->assertEmpty($model2->withDeleted()->findByPk(0));

        $this->assertNotEmpty($model2->findByPk(1));
        $this->assertNotEmpty($model2->filterDeleted()->findByPk(1));
        $this->assertNotEmpty($model2->withDeleted()->findByPk(1));

        $this->assertNotEmpty($model2->findByPk(2));
        $this->assertEmpty($model2->filterDeleted()->findByPk(2));
        $this->assertNotEmpty($model2->withDeleted()->findByPk(2));

        $this->assertCount(5, $model2->findAll($criteria));
        $this->assertCount(3, $model2->filterDeleted()->findAll($criteria));
        $this->assertCount(5, $model2->withDeleted()->findAll($criteria));

        $this->assertEquals(5, $model2->count($criteria));
        $this->assertEquals(3, $model2->filterDeleted()->count($criteria));
        //$this->assertEquals(5, $model2->withDeleted()->count($criteria));

    }

    public function testDelete()
    {
        $model1 = new IsDeletedBehaviorTestWithoutDeletedActiveRecord;
        $model1->title = 'delete-row-1';
        $model1->save();
        $id1 = $model1->id;
        $this->assertNotEmpty(IsDeletedBehaviorTestWithoutDeletedActiveRecord::model()->findByPk($id1));
        $model1->delete();
        $this->assertEmpty(IsDeletedBehaviorTestWithoutDeletedActiveRecord::model()->findByPk($id1));
        $this->assertNotEmpty(IsDeletedBehaviorTestWithoutDeletedActiveRecord::model()->withDeleted()->findByPk($id1));

        $model2 = new IsDeletedBehaviorTestWithDeletedActiveRecord;
        $model2->title = 'delete-row-2';
        $model2->save();
        $id2 = $model2->id;
        $this->assertNotEmpty(IsDeletedBehaviorTestWithDeletedActiveRecord::model()->findByPk($id2));
        $model2->delete();
        $this->assertNotEmpty(IsDeletedBehaviorTestWithDeletedActiveRecord::model()->findByPk($id2));
        $this->assertEmpty(IsDeletedBehaviorTestWithDeletedActiveRecord::model()->filterDeleted()->findByPk($id2));

    }

}

/**
 * @property integer $id
 * @property string $title
 * @property integer $is_deleted
 */
class IsDeletedBehaviorTestWithoutDeletedActiveRecord extends CActiveRecord
{
    /**
     * @return IsDeletedBehaviorTestActiveRecord
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
            'isDeletedBehavior' => array(
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
 * @property string $title
 * @property integer $is_deleted
 */
class IsDeletedBehaviorTestWithDeletedActiveRecord extends CActiveRecord
{
    /**
     * @return IsDeletedBehaviorTestActiveRecord
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
            'IsDeletedBehavior' => array(
                'class' => 'components.behaviors.IsDeletedBehavior',
                'deletedFlagField' => 'is_deleted',
                'deletedFlag' => 1,
                'normalFlag' => 0,
                'findDeleted' => true,
            ),
        );
    }
}

