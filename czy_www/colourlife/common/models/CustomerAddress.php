<?php

/**
 * This is the model class for table "customer_address".
 *
 * The followings are the available columns in table 'customer_address':
 * @property string $id
 * @property integer $customer_id
 * @property integer $community_id
 * @property integer $build_id
 * @property string $room
 * @property integer $status
 * @property string $create_time
 */
class CustomerAddress extends CActiveRecord
{


	public $isRepeatRoom=false;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, community_id, build_id, room', 'required'),
			array('customer_id, community_id, build_id, status', 'numerical', 'integerOnly'=>true),
			array('room, create_time', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, community_id, build_id, room, status, create_time', 'safe', 'on'=>'search'),
			array('room', 'checkRoom', 'on' => 'create,update'),
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
            'region'=>array(self::BELONGS_TO,"Region","community_id"),
            'build'=>array(self::BELONGS_TO,"Build","build_id"),
            'community'=>array(self::BELONGS_TO,"Community","community_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'community_id' => 'Community',
			'build_id' => 'Build',
			'room' => 'Room',
			'status' => 'Status',
			'create_time' => 'Create Time',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('build_id',$this->build_id);
		$criteria->compare('room',$this->room,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CustomerAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //获得小区所在的地区，isself=true，包括小区
    public function getAddress($isself = true)
    {
        $community = Community::model()->findByPk($this->community_id);
        if (!empty($community)) {
            //$_regionName = Region::getMyParentRegionNames($community->region_id, true);
            $_regionName = $community->ICEGetCommunityRegionsNames();
        } else {
            $_regionName = "";
        }

        if ($isself) {
            $_regionName .=" - " . $community->name;
        }
        if(isset($this->build)){
            $_regionName .= ' - ' . $this->build->name;
        }
        if(isset($this->room)){
            $_regionName .= ' - ' . $this->room;
        }
        return $_regionName;
    }

    public function getType(){
        if(isset($this->community)){
            return $this->community->type;
        }
    }

    //获取楼栋名
    public function getBuild_name(){
        if(isset($this->build)){
            return $this->build->name;
        }
    }

    //获取小区名
    public function getCommunity_name(){
//        if(isset($this->community)){
//            return $this->community->name;
//        }
//	    接入ice
	    if(isset($this->community_id)){
		    $ICECommunity = ICECommunity::model()->FindByPk($this->community_id);
		    if (!empty($ICECommunity)) {
			    return  $ICECommunity['name'];
		    }
	    }
    }

    ///查询省市区
    public function getRegions()
    {
        $regions =array();
        if (!empty($this->community)) {
            $region = Region::model()->enabled()->findByPk($this->community->region_id);
            if (!empty($region)) {
                $regions = $region->getParents();
                $regions[] = $region;
            }
        }

        return $regions;
    }

	//去重地址
	public function checkRoom($attribute, $params)
	{
		$result = $this->find('room=:room and customer_id=:customer_id and community_id=:community_id and build_id=:build_id', array(':room' => $this->room, ':customer_id' => Yii::app()->user->id, ':community_id' => $this->community_id, ':build_id' => $this->build_id,));
		$is_self = true;
		if (!empty($result) && isset($this->id)) {
			if($result->id == $this->id){
			$is_self = false;
			}
		}
		if (!$this->hasErrors() && (!empty($result)) && $is_self) {
			//dump(111);
			$this->addError($attribute, '该地址已存在,请到地址列表中选择.');
			$this->isRepeatRoom=true;
		}
	}
}
