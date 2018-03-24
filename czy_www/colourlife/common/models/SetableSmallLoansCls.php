<?php

/**
 * This is the model class for table "small_loans".
 *
 * The followings are the available columns in table 'small_loans':
 * @property int $id
 * @property varchar $name
 * add 20150314
 */
class SetableSmallLoansCls extends CActiveRecord
{
    public $modelName = 'v2.3应用资源类';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'setable_small_loans_cls';
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, id', 'required', 'on' => 'update,create'),
           // array('secret', 'safe', 'on' => 'update,create'),
            array('id, name', 'safe', 'on' => 'search'),
           // array('sl_key','unique','on'=>'update,create'),
            array('id,name','safe', 'on' => 'create,update'),
        );
    }

	/**
	 * @return array relational rules.
	 */
	/*public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'setableSmallLoansCls' => array(self::BELONGS_TO, 'setableSmallLoansCls', 'class_id'),
		);
	}*/
	
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '名称',
        );
    }

  
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
           // 'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
           // 'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
}
