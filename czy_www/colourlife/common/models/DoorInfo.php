<?php

/**
 * This is the model class for table "door_info".
 *
 * The followings are the available columns in table 'door_info':
 * @property integer $id
 * @property string $name
 * @property string $qrcode
 * @property string $bid
 * @property string $unitid
 * @property integer $status
 * @property string $doorcode
 * @property integer $modifiedtime
 * @property integer $doortype
 * @property integer $conntype
 * @property string $version
 * @property string $factorytype
 * @property string $extra
 * @property integer $wifienable
 * @property string $wificode
 * @property string $dynamic
 * @property string $dynamicqr
 * @property string $address
 * @property string $community_uuid
 */
class DoorInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'door_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, modifiedtime, doortype, conntype, wifienable', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('qrcode, bid, unitid, doorcode, dynamic, dynamicqr', 'length', 'max'=>100),
			array('version, factorytype', 'length', 'max'=>10),
			array('extra, wificode, address, community_uuid', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, qrcode, bid, unitid, status, doorcode, modifiedtime, doortype, conntype, version, factorytype, extra, wifienable, wificode, dynamic, dynamicqr, address, community_uuid', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'qrcode' => 'Qrcode',
			'bid' => 'Bid',
			'unitid' => 'Unitid',
			'status' => 'Status',
			'doorcode' => 'Doorcode',
			'modifiedtime' => 'Modifiedtime',
			'doortype' => 'Doortype',
			'conntype' => 'Conntype',
			'version' => 'Version',
			'factorytype' => 'Factorytype',
			'extra' => 'Extra',
			'wifienable' => 'Wifienable',
			'wificode' => 'Wificode',
			'dynamic' => 'Dynamic',
			'dynamicqr' => 'Dynamicqr',
			'address' => 'Address',
			'community_uuid' => 'Community Uuid',
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
		$criteria->compare('qrcode',$this->qrcode,true);
		$criteria->compare('bid',$this->bid,true);
		$criteria->compare('unitid',$this->unitid,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('doorcode',$this->doorcode,true);
		$criteria->compare('modifiedtime',$this->modifiedtime);
		$criteria->compare('doortype',$this->doortype);
		$criteria->compare('conntype',$this->conntype);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('factorytype',$this->factorytype,true);
		$criteria->compare('extra',$this->extra,true);
		$criteria->compare('wifienable',$this->wifienable);
		$criteria->compare('wificode',$this->wificode,true);
		$criteria->compare('dynamic',$this->dynamic,true);
		$criteria->compare('dynamicqr',$this->dynamicqr,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('community_uuid',$this->community_uuid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DoorInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
