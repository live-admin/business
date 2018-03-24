<?php

class StateBehaviorTest extends TestCase
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
        $this->_connection->pdoInstance->exec(file_get_contents(dirname(__FILE__) . '/StateBehaviorTest.sql'));
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
        $model1 = StateBehaviorTestActiveRecord::model();

        $this->assertEmpty($model1->findByPk(0));
        $this->assertEmpty($model1->enabled()->findByPk(0));
        $this->assertEmpty($model1->disabled()->findByPk(0));

        $this->assertNotEmpty($model1->findByPk(1));
        $this->assertNotEmpty($model1->enabled()->findByPk(1));
        $this->assertEmpty($model1->disabled()->findByPk(1));

        $this->assertNotEmpty($model1->findByPk(2));
        $this->assertEmpty($model1->enabled()->findByPk(2));
        $this->assertNotEmpty($model1->disabled()->findByPk(2));

        $this->assertCount(5, $model1->findAll($criteria));
        $this->assertCount(3, $model1->enabled()->findAll($criteria));
        $this->assertCount(2, $model1->disabled()->findAll($criteria));

        $this->assertEquals(5, $model1->count($criteria));
        $this->assertEquals(3, $model1->enabled()->count($criteria));
        $this->assertEquals(2, $model1->disabled()->count($criteria));

    }

    public function testEnable()
    {
        $model1 = new StateBehaviorTestActiveRecord;
        $model1->title = 'enable-row-1';
        $model1->state = 0;
        $this->assertEquals(0, $model1->state);
        $this->assertTrue($model1->isEnabled);
        $this->assertFalse($model1->isDisabled);
        $model1->enable();
        $this->assertEquals(0, $model1->state);
        $this->assertTrue($model1->isEnabled);
        $this->assertFalse($model1->isDisabled);
        $model1->save();
        $id1 = $model1->id;
        $this->assertNotEmpty(StateBehaviorTestActiveRecord::model()->enabled()->findByPk($id1));
        $this->assertEmpty(StateBehaviorTestActiveRecord::model()->disabled()->findByPk($id1));

        $model2 = new StateBehaviorTestActiveRecord;
        $model2->title = 'enable-row-2';
        $model2->state = 1;
        $this->assertEquals(1, $model2->state);
        $this->assertFalse($model2->isEnabled);
        $this->assertTrue($model2->isDisabled);
        $model2->enable();
        $this->assertEquals(0, $model2->state);
        $this->assertTrue($model2->isEnabled);
        $this->assertFalse($model2->isDisabled);
        $model2->save();
        $id2 = $model2->id;
        $this->assertNotEmpty(StateBehaviorTestActiveRecord::model()->enabled()->findByPk($id2));
        $this->assertEmpty(StateBehaviorTestActiveRecord::model()->disabled()->findByPk($id2));

    }

    public function testDisable()
    {
        $model1 = new StateBehaviorTestActiveRecord;
        $model1->title = 'disable-row-1';
        $model1->state = 0;
        $this->assertEquals(0, $model1->state);
        $this->assertTrue($model1->isEnabled);
        $this->assertFalse($model1->isDisabled);
        $model1->disable();
        $this->assertEquals(1, $model1->state);
        $this->assertFalse($model1->isEnabled);
        $this->assertTrue($model1->isDisabled);
        $model1->save();
        $id1 = $model1->id;
        $this->assertEmpty(StateBehaviorTestActiveRecord::model()->enabled()->findByPk($id1));
        $this->assertNotEmpty(StateBehaviorTestActiveRecord::model()->disabled()->findByPk($id1));

        $model2 = new StateBehaviorTestActiveRecord;
        $model2->title = 'disable-row-2';
        $model2->state = 1;
        $this->assertEquals(1, $model2->state);
        $this->assertFalse($model2->isEnabled);
        $this->assertTrue($model2->isDisabled);
        $model2->disable();
        $this->assertEquals(1, $model2->state);
        $this->assertFalse($model2->isEnabled);
        $this->assertTrue($model2->isDisabled);
        $model2->save();
        $id2 = $model2->id;
        $this->assertEmpty(StateBehaviorTestActiveRecord::model()->enabled()->findByPk($id2));
        $this->assertNotEmpty(StateBehaviorTestActiveRecord::model()->disabled()->findByPk($id2));

    }
}

/**
 * @property integer $id
 * @property string $title
 * @property integer $state
 */
class StateBehaviorTestActiveRecord extends CActiveRecord
{
    /**
     * @return StateBehaviorTestActiveRecord
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
            'StateBehavior' => array(
                'class' => 'components.behaviors.StateBehavior',
                'stateFlagField' => 'state',
                'disabledFlag' => 1,
                'enabledFlag' => 0,
            ),
        );
    }
}
