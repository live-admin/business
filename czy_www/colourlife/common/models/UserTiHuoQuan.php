<?php

/**
 * This is the model class for table "user_ti_huo_quan".
 *
 * The followings are the available columns in table 'user_ti_huo_quan':
 * @property integer $id
 * @property string $thq_code
 * @property string $mobile
 * @property integer $is_use
 * @property integer $num
 * @property integer $order_id
 * @property integer $create_time
 */
class UserTiHuoQuan extends CActiveRecord
{
    public $modelName = '提货券';
    public $type_arr=array(1=>'大闸蟹');
    public $use_arr=array(1=>'未使用',2=>'已使用',3=>'已赠送',4=>'已过期');
//    public $sn;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_ti_huo_quan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_use, num, order_id, create_time', 'numerical', 'integerOnly'=>true),
			array('thq_code, mobile', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, thq_code, mobile, is_use, num, order_id, create_time', 'safe', 'on'=>'search'),
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
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'type'=>'类型',
			'thq_code' => '提货券编码',
			'mobile' => '用户手机号码',
			'is_use' => '使用状态',
			'num' => '数量',
			'order_id' => '订单号',
			'create_time' => '获取时间',
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
		$criteria->compare('thq_code',$this->thq_code,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('is_use',$this->is_use);
		$criteria->compare('num',$this->num);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserTiHuoQuan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    //提货券类型
    public function getTypeName()
    {
        return $this->type_arr[$this->type];
    }
    //提货券状态
    public function getIsUse()
    {
        return $this->use_arr[$this->is_use];
    }
    //
    public function getSn(){
        return $this->order->sn;
    }
}
