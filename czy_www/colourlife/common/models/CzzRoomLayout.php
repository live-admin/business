<?php

/**
 * This is the model class for table "czz_room_layout".
 *
 * The followings are the available columns in table 'czz_room_layout':
 * @property string $id
 * @property string $room_layout
 * @property double $floor_area
 * @property string $orientation
 * @property string $layout_pic_url
 * @property string $building_id
 */
class CzzRoomLayout extends CActiveRecord
{
    public $temp_pic_url;
    public $temp_pic_path;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_room_layout';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('room_layout, floor_area, orientation, building_id', 'required'),
			array('floor_area', 'numerical'),
			array('room_layout, orientation', 'length', 'max'=>32),
			array('layout_pic_url', 'length', 'max'=>400),
			array('building_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, room_layout, floor_area, orientation, layout_pic_url, building_id', 'safe', 'on'=>'search'),
		    
		    array("temp_pic_url, temp_pic_path", "safe"),
		    
		    array('layout_pic_url', 'picCheck'),
		);
	}
	
	public function picCheck()
	{
	    if(empty($this->layout_pic_url) && empty($this->temp_pic_path))
	    {
	        $this->addError("pic_url", "房源图片不能为空");
	    }
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		    'building' => array(self::BELONGS_TO, 'CzzBuilding', 'building_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'room_layout' => '户型',
			'floor_area' => '面积',
			'orientation' => '朝向',
			'layout_pic_url' => '户型图',
			'building_id' => '楼盘ID',
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
		$criteria->compare('room_layout',$this->room_layout,true);
		$criteria->compare('floor_area',$this->floor_area);
		$criteria->compare('orientation',$this->orientation,true);
		$criteria->compare('layout_pic_url',$this->layout_pic_url,true);
		$criteria->compare('building_id',$this->building_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getLayoutPicUrl()
	{
	    return Yii::app()->imageFile->getUrl($this->layout_pic_url, '/common/images/nopic-map.jpg');
	}
	
	/**
	 * @return bool
	 */
	protected function beforeSave()
	{
	    if (!empty($this->temp_pic_path)) {
	        $this->layout_pic_url = Yii::app()->ajaxUploadImage->moveSave($this->temp_pic_path, $this->layout_pic_url);
	    }
	
	    return parent::beforeSave();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzRoomLayout the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
