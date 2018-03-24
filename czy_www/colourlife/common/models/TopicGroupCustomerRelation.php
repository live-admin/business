<?php

/**
 * This is the model class for table "topic_group_customer_relation".
 *
 * The followings are the available columns in table 'topic_group_customer_relation':
 * @property string $id
 * @property integer $group_id
 * @property integer $customer_id
 * @property string $create_time
 */
class TopicGroupCustomerRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'topic_group_customer_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id,community_id, customer_id', 'required'),
			array('group_id, customer_id', 'numerical', 'integerOnly'=>true),
			array('create_time', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, group_id, community_id,customer_id, create_time', 'safe', 'on'=>'search'),
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
            "group"=>array(self::BELONGS_TO,"TopicGroup","group_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'group_id' => '分组ID',
            'community_id'=>'小区ID',
			'customer_id' => '业主ID',
			'create_time' => '创建时间',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('customer_id',$this->customer_id);
        $criteria->compare('community_id',$this->community_id);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopicGroupCustomerRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //获取圈子名字
    public function getGroupName(){
        return isset($this->group)?$this->group->title:"";
    }

    //获取圈子人数
    public function getGroupNum(){
        return isset($this->group)?$this->group->num:"0";
    }

    //获取圈子简介
    public function getGroupDesc(){
        return isset($this->group)?$this->group->desc:"";
    }

    //获取圈子图片
    public function getGroupLogo(){
        return isset($this->group)?$this->group->logo:"";
        if(isset($this->group)){
            if(!empty($this->group->logo)){
                return F::getUploadsUrl('/images/' .$this->group->logo );
            }
        }
        return  F::getStaticsUrl('/linli/images/top-2_03.png' );
    }
}
