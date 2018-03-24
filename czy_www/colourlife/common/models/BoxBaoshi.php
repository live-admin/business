<?php

/**
 * This is the model class for table "box_baoshi".
 *
 * The followings are the available columns in table 'box_baoshi':
 * @property integer $id
 * @property integer $customer_id
 * @property string $open_id
 * @property integer $baoshi_id
 * @property integer $is_use
 * @property integer $source
 * @property integer $create_time
 * @property integer $update_time
 */
class BoxBaoshi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'box_baoshi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'required'),
			array('customer_id, baoshi_id, is_use, source, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('open_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, open_id, baoshi_id, is_use, source, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'customer_id' => 'Customer',
			'open_id' => '微信open_id',
			'baoshi_id' => '宝石id
1:赤宝石;
2:橙宝石;
3:黄宝石;
4:绿宝石;
5:青宝石;
6:蓝宝石;
7:紫宝石;




',
			'is_use' => '是否使用(0:未使用;1:已使用)',
			'source' => '来源
1：在邻里发帖；
2：分享到邻里圈／微信圈；
3：缴纳物业费；
4：好友可在分享页面帮忙点亮；
5：好友可在分享页面帮忙点亮；
6：缴纳停车费；
7：邀请朋友；',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('open_id',$this->open_id,true);
		$criteria->compare('baoshi_id',$this->baoshi_id);
		$criteria->compare('is_use',$this->is_use);
		$criteria->compare('source',$this->source);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BoxBaoshi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
