<?php

class IpBehaviorTest extends TestCase
{
    private $_connection;

    protected function setUp()
    {
        // pdo and pdo_sqlite extensions are obligatory
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite'))
            $this->markTestSkipped('PDO and SQLite extensions are required.');

        // open connection and create testing tables
        $this->_connection = new CDbConnection('sqlite::memory:');
        $this->_connection->active = true;
        $this->_connection->pdoInstance->exec(file_get_contents(dirname(__FILE__) . '/IpBehaviorTest.sql'));
        CActiveRecord::$db = $this->_connection;
    }

    protected function tearDown()
    {
        // close connection
        $this->_connection->active = false;
    }

    public function testCreateAttribute()
    {
        // behavior changes created_at after inserting
        $model1 = new IpBehaviorTestActiveRecord;
        $model1->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => 'created_at',
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $model1->title = 'testing-row-1';
        $this->assertEquals('', $model1->created_at);
        $model1->save();
        $this->assertEquals('127.0.0.1', $model1->created_at);

        // behavior changes created_at after inserting
        $model2 = new IpBehaviorTestActiveRecord;
        $model2->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => 'created_at',
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $model2->title = 'testing-row-2';
        $model2->created_at = 'cl';
        $this->assertEquals('cl', $model2->created_at);
        $model2->save();
        $this->assertEquals('127.0.0.1', $model2->created_at);

        // behavior does not changes created_at after inserting
        $model3 = new IpBehaviorTestActiveRecord;
        $model3->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $model3->title = 'testing-row-3';
        $model3->created_at = 'cl.cl';
        $this->assertEquals('cl.cl', $model3->created_at);
        $model3->save();
        $this->assertEquals('cl.cl', $model3->created_at);

        // behavior changes created_at after inserting
        $model4 = new IpBehaviorTestActiveRecord;
        $model4->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => 'created_at',
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $model4->title = 'testing-row-4';
        $this->assertEquals('', $model4->created_at);
        $model4->save();
        $this->assertEquals('127.0.0.1', $model4->created_at);

        // behavior changes created_at after inserting
        $model5 = new IpBehaviorTestActiveRecord;
        $model5->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => 'created_at',
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $model5->title = 'testing-row-5';
        $model5->created_at = 'cl.cl.cl';
        $this->assertEquals('cl.cl.cl', $model5->created_at);
        $model5->save();
        $this->assertEquals('127.0.0.1', $model5->created_at);

        // behavior does not changes created_at after inserting
        $model6 = new IpBehaviorTestActiveRecord;
        $model6->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $model6->title = 'testing-row-6';
        $model6->created_at = 'cl.cl.cl.cl';
        $this->assertEquals('cl.cl.cl.cl', $model6->created_at);
        $model6->save();
        $this->assertEquals('cl.cl.cl.cl', $model6->created_at);
    }

    public function testUpdateAttribute()
    {
        // behavior changes updated_at after updating
        $model1 = IpBehaviorTestActiveRecord::model()->findByPk(1);
        $model1->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => 'updated_at',
            'setUpdateOnCreate' => false,
        ));
        $this->assertEquals('192.168.1.1', $model1->updated_at);
        $model1->save();
        $this->assertEquals('127.0.0.1', $model1->updated_at);

        // behavior changes updated_at after updating
        $model2 = IpBehaviorTestActiveRecord::model()->findByPk(2);
        $model2->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => 'updated_at',
            'setUpdateOnCreate' => true,
        ));
        $this->assertEquals('192.168.1.2', $model2->updated_at);
        $model2->save();
        $this->assertEquals('127.0.0.1', $model2->updated_at);

        // behavior does not changes updated_at after updating
        $model3 = IpBehaviorTestActiveRecord::model()->findByPk(3);
        $model3->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $this->assertEquals('192.168.1.3', $model3->updated_at);
        $model3->save();
        $this->assertEquals('192.168.1.3', $model3->updated_at);

        // behavior changes updated_at after updating
        $model4 = IpBehaviorTestActiveRecord::model()->findByPk(4);
        $model4->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => 'updated_at',
            'setUpdateOnCreate' => false,
        ));
        $this->assertEquals('192.168.1.4', $model4->updated_at);
        $model4->save();
        $this->assertEquals('127.0.0.1', $model4->updated_at);

        // behavior changes updated_at after updating
        $model5 = IpBehaviorTestActiveRecord::model()->findByPk(5);
        $model5->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => 'updated_at',
            'setUpdateOnCreate' => true,
        ));
        $this->assertEquals('192.168.1.5', $model5->updated_at);
        $model5->save();
        $this->assertEquals('127.0.0.1', $model5->updated_at);

        // behavior does not changes updated_at after updating
        $model6 = IpBehaviorTestActiveRecord::model()->findByPk(6);
        $model6->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => null,
            'setUpdateOnCreate' => false,
        ));
        $this->assertEquals('192.168.1.6', $model6->updated_at);
        $model6->save();
        $this->assertEquals('192.168.1.6', $model6->updated_at);

        // behavior does not changes updated_at after inserting
        $model7 = new IpBehaviorTestActiveRecord;
        $model7->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => 'updated_at',
            'setUpdateOnCreate' => false,
        ));
        $model7->title = 'testing-row-7';
        $model7->updated_at = 'cl.cl';
        $this->assertEquals('cl.cl', $model7->updated_at);
        $model7->save();
        $this->assertEquals('cl.cl', $model7->updated_at);

        // behavior changes updated_at after inserting
        $model8 = new IpBehaviorTestActiveRecord;
        $model8->attachBehavior('IpBehavior', array(
            'class' => 'components.behaviors.IpBehavior',
            'createAttribute' => null,
            'updateAttribute' => 'updated_at',
            'setUpdateOnCreate' => true,
        ));
        $model8->title = 'testing-row-8';
        $model8->updated_at = 'cl.cl.cl';
        $this->assertEquals('cl.cl.cl', $model8->updated_at);
        $model8->save();
        $this->assertEquals('127.0.0.1', $model8->updated_at);
    }
}

/**
 * @property integer $id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 */
class IpBehaviorTestActiveRecord extends CActiveRecord
{
    /**
     * @return IpBehaviorTestActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'table';
    }
}