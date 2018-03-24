<?php

/**
 * This is the model class for table "liuliang_record".
 *
 * The followings are the available columns in table 'liuliang_record':
 * @property integer $id
 * @property string $ext_id
 * @property string $mobile
 * @property string $package
 * @property string $status
 * @property integer $create_time
 */
class LiuliangRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'liuliang_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('ext_id, mobile, package, status', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ext_id, mobile, package, status, create_time', 'safe', 'on'=>'search'),
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
			'ext_id' => '订单号',
			'mobile' => '手机号码',
			'package' => '流量包',
			'status' => '提交状态
(
0：成功；
000001：参数不规范；
000002：时间戳超时；
000003：账号不存在或未启用；
000004：手机号码格式不正确；
000005：签名错误；
000006：流量产品不存在；
000007：余额不足；
000015：充值失败；
000016：该IP地址与绑定IP地址不符；
000017：该订单不存在；
000099：未知错误；


)',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('ext_id',$this->ext_id,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('package',$this->package,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LiuliangRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
