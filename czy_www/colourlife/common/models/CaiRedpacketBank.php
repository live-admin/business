<?php

/**
 * This is the model class for table "cai_redpacket_bank".
 *
 * The followings are the available columns in table 'cai_redpacket_bank':
 * @property integer $id
 * @property string $name
 * @property string $key
 * @property integer $state
 */
class CaiRedpacketBank extends CActiveRecord
{	

	public $modelName = '银行卡相关配置';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cai_redpacket_bank';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('is_deleted, state', 'numerical', 'integerOnly'=>true),
            array('name, key', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, key, is_deleted, state', 'safe', 'on'=>'search'),
        );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'key' => '字符',
			'is_deleted' => '删除标记',
			'state' => '状态',
		);
	}	


	public function attributes()
    {
        return array(
            'name',
            array(
                'name' => 'state',
                'type' => 'raw',
                'value' => $this->getStateName(true),
            ),
        );
    }




    /**
     * 组件挂载
     * @return array
     */
    public function behaviors()
    {
        return array(
        	'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }




	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('key',$this->key,true);
        $criteria->compare('is_deleted',$this->is_deleted);
        $criteria->compare('state',$this->state);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CaiRedpacketBank the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
